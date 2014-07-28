<?php
class SbYouTube extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL)
	{
		$obj = new SbYouTube($parser);

		return $obj->Render($param1);
	}

	//--- Private Methods ---//

	private function Render($id)
	{
		if(isset($id))
		{
			$result= '<iframe width="560" height="315" src="http://www.youtube.com/embed/';
			$result.= htmlspecialchars($id);
			$result.= '" frameborder="0" allowfullscreen></iframe>';
		}
		else
			$result = $this->Error('ID is not set');

		return array($result, 'noparse' => true, 'isHTML' => true);
    }
}
?>
