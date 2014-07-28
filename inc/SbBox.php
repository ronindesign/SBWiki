<?php
class SbBox extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));

		SbCore::RegisterCSS($parser, 'SbBox');
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbBox($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Protected Methods ---//

	protected function RenderHead(&$result, $fullsize= TRUE)
	{
		$skin = $this->GetSkin();

		$result.= '<table class="'. ($fullsize ? 'sbBox0' : 'sbBox5'). '">';
		$result.= '<tr><td class="sbBox1 '. $skin->FrameColorClass(). '">';
	}

	protected function RenderSplitterH(&$result)
	{
		$skin = $this->GetSkin();

		$result.= '</td><td></td><td class="sbBox2 '. $skin->FrameColorClass(). '">';
	}

	protected function RenderSplitterV(&$result)
	{
		$skin = $this->GetSkin();

		$result.= '</td></tr><tr><td></td><td></td><td></td></tr><tr><td class="sbBox2 '. $skin->FrameColorClass(). '">';
	}

	protected function RenderTitle(&$result, $title)
	{
		$skin = $this->GetSkin();

		$result.= '<table class="sbBox3">';
		$result.= '<tr><td class="sbBox4 '. $skin->BoxColorClass(). '">';
		$result.= $this->ResolveTag($title);
		$result.= '</td></tr></table>';
	}

	protected function RenderFoot(&$result)
	{
		$result.= '</td></tr></table>';
	}

	//--- Private Methods ---//

	private function Render($input, array $args)
	{
		$vHeader = FALSE;
		$vSplitterH = FALSE;
		$vSplitterV = FALSE;
		$vFooter = FALSE;		

		if(array_key_exists('layout', $args))
		{
			$layout = explode(' ', $args['layout']);
			foreach($layout as $arg)
				switch($arg)
				{
					case 'foot': 
						$vFooter = TRUE; 
						break;

					case 'head': 
						$vHeader = TRUE; 
						break;

					case 'hsplit': 
						$vSplitterH = TRUE; 
						break;

					case 'vsplit': 
						$vSplitterV = TRUE; 
						break;

					default: 
						return array($this->Error('Invalid mode "'. $arg. '"'));
				}
		}
		else
		{
			$vHeader = TRUE;
			$vFooter = TRUE;		
		}
		
		$result = '';

		if($vHeader)
			$this->RenderHead($result);

		if(array_key_exists('title', $args))
			$this->RenderTitle($result, $args['title']);

		$result.= $this->ResolveTag($input);
		
		if($vSplitterH)
			$this->RenderSplitterH($result);
			
		if($vSplitterV)
			$this->RenderSplitterV($result);
			
		if($vFooter)
			$this->RenderFoot($result);

		return array($result);
	}
}
?>
