<?php
class SbClan extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL, $param2= 'ilt')
	{
		$obj = new SbClan($parser);

		return $obj->Render($param1, $param2);
	}

	//--- Private Methods ---//

	private function Render($name, $mode)
	{
		$vLink = FALSE;
		$vIcon = FALSE;
		$vText = FALSE;

		for($index=0; $index<strlen($mode); $index++)
			switch($mode[$index])
			{
				case 'i': $vIcon = TRUE; break;
				case 'l': $vLink = TRUE; break;
				case 't': $vText = TRUE; break;
				default: return $this->Error('Invalid mode "'. $mode[$index]. '"');
			}

		if($vLink)
			$vText = TRUE;

		if(!$vIcon && !$vText)
			return $this->Error('Mode "'. $mode. '" results in invisibility');

		if(!isset($name))
			return $this->Error('No name');
		
		$items = SbCoreReader::Fetch('SbClan');

		if(array_key_exists($name, $items))
		{
			$item = $items[$name];
			
			$text = $vText ? $item['name']['text'] : NULL;
			$link = $vLink ? 'Clan:'. $item['link']['text'] : NULL;
			$icon = $vIcon ? (array_key_exists('icon', $item) ? SbCore::ParseUri('cln:'. $item['icon']['text']. '.png') : NULL) : NULL;
			$title = $item['name']['text'];

			return $this->RenderLink($text, $link, $icon, NULL, $title);
		}
		
		if(!$vText)
			return $this->Error('Mode "'. $mode. '" results in invisibility');

		$result = $vLink ? $this->ResolveTag('[[Clan:'. $name. '|'. $name. ']]') : $name;
		
		return $this->InsertStrip($result);
	}
}

?>