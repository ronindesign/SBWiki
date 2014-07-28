<?php
class SbShip extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL, $param2= 'ilt')
	{
		$obj = new SbShip($parser);

		return $obj->Render($param1, $param2);
	}

	//--- Private Methods ---//

	private function Render($name, $mode)
	{
		$vLink = FALSE;
		$vIcon = FALSE;
		$vText = FALSE;

		for($index=0;$index<strlen($mode);$index++)
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
		
		$ships = SbCoreReader::Fetch('SbShip');

		if(!array_key_exists($name, $ships))
			return $this->Error('Invalid name "'. $name. '"');

		$item = $ships[$name];
			
		$text = $vText ? $item['name']['text'] : NULL;
		$link = $vLink ? $item['link']['text'] : NULL;
		$icon = $vIcon ? SbCore::ParseUri('shp:'. $name. '.png') : NULL;
		$title = $item['name']['text'];

		return $this->RenderLink($text, $link, $icon, NULL, $title);
	}
}
?>
