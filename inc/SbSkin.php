<?php
class SbSkin extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbSkin($parser, $frame, $args);
		
		return $obj->Render($input);
	}

	//--- Private Methods ---//

	private function Render($input)
	{
		$result = isset($input) ? $this->ResolveTag($input) : '';

		return array($result);
	}
}
?>
