<?php
class SbIcon extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, $this->FetchSkin());
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= NULL)
	{
		$obj = new SbIcon($parser);

		return $obj->Render($param1);
	}

	//--- Private Methods ---//

	private function Render($id)
	{
		$result = '';
		if(isset($id))
		{
			$skin = $this->GetSkin();
			switch($id)
			{
				case 'energy': $result = $this->RenderIcon($skin->IconEnergy(), NULL, "Energy"); break;
				case 'minerals': $result = $this->RenderIcon($skin->IconMinerals(), NULL, "Minerals"); break;
			}
		}

		return $this->InsertStrip($result);
	}
}
?>
