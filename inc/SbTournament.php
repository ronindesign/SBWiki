<?php
class SbTournament extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL, $param2= 'ilt')
	{
		$obj = new SbTournament($parser);

		return $obj->Render($param1, $param2);
	}

	//--- Private Methods ---//

	private function Render($name, $mode)
	{
		$vCode = FALSE;
		$vLink = FALSE;
		$vIcon = FALSE;
		$vText = FALSE;

		for($index=0; $index<strlen($mode); $index++)
			switch($mode[$index])
			{
				case 'c':
					$vCode = TRUE;
					break;

				case 'i': 
					$vIcon = TRUE; 
					break;

				case 'l': 
					$vLink = TRUE; 
					break;

				case 't': 
					$vText = TRUE; 
					break;

				default: 
					return $this->Error('Invalid mode "'. $mode[$index]. '"');
			}

		if($vLink)
		{
			if(!$vText && !$vCode)
				$vText = TRUE;
		}

		if(!$vIcon && !$vText && !$vCode)
			return $this->Error('Mode "'. $mode. '" results in invisibility');

		if(!isset($name))
			return $this->Error('No ID');
		
		$items = SbCoreReader::Fetch('SbTournament');

		if(!array_key_exists($name, $items))
			return $this->Error('Invalid ID "'. $name. '"');

		$item = $items[$name];
			
		if($vCode)
			$text = $item['code']['text'];
		else
			$text = $vText ? $item['name']['text'] : NULL;

		$link = $vLink ? 'Tournament:'. $item['link']['text'] : NULL;
		if(array_key_exists('icon', $item))
			$icon = $vIcon ? SbCore::ParseUri('trn:'. $item['icon']['text']. '.png') : NULL;
		else
			$icon = NULL;
		$title = $item['name']['text'];

		return $this->RenderLink($text, $link, $icon, NULL, $title);
	}
}
?>
