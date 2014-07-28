<?php
class SbPatch extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL)
	{
		$obj = new SbPatch($parser);

		return $obj->Render($param1, $param2);
	}

	//--- Private Methods ---//

	private function Render($version)
	{
		if(isset($version))
		{
			$version = htmlentities($version);

			$result = '[[Patch Notes#v ';
			$result.= $version;
			$result.= '|';
			$result.= $version;
			$result.= ']]';
		}
		else
			$result ='[[Patch Notes]]';

		$result = $this->ResolveTag($result);

		return $this->InsertStrip($result);
    }
}
?>
