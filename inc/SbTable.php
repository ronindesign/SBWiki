<?php
class SbTable extends SbCoreBase
{
	//--- Constructors ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));

		SbCore::RegisterCSS($parser, 'SbTable');
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbTable($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Private Methods ---//

	private function Render($input, array $args)
	{
		$skin = $this->GetSkin();

		$result= '<table class="'. $skin->FrameColorClass() .' sbTbl0 ">';

		$doc = new DOMDocument;
		$doc->loadXML('<data>'. $input. '</data>');

		$cols = 0;
		if(array_key_exists('cols', $args))
			$cols = intval($args['cols']);

		$heads = $doc->getElementsByTagName('h');
		if($heads->length > 0)
		{
			$align = array();
			$cols = $heads->length;

			$result.= '<tr>';
			foreach($heads as $head)
			{
				if($head->hasAttribute('align'))
				{
					switch($head->getAttribute('align'))
					{
						case 'center': $align[]= 'sbTbl3'; break;
						case 'right': $align[]= 'sbTbl4'; break;
						default: $align[]= 'sbTbl1'; break;
					}
				}
				else
					$align[]= 'sbTbl1';

				$result.= '<th class="'. $skin->BoxColorClass(). ' sbTbl2">';
				$result.= $this->ResolveTag(SbCoreReader::GetInnerHtml($head));
				$result.= '</th>';
			}
			$result.= '</tr>';
		}
		else
			$align = NULL;

		$cells = $doc->getElementsByTagName('c');
		if($cells->length > 0)
		{
			if($cols == 0)
				$cols = $cells->length;

			if(!isset($align))
			{
				$align = array();
				for($index=0; $index<$cols; $index++)
					$align[]= 'sbTbl1';
			}

			$result.= '<tr>';
			$index = 0;
			foreach($cells as $cell)
			{
				if($index >= $cols)
				{
					$result.= '</tr><tr>';
					$index = 0;
				}

				$result.= '<td class="'. $skin->FrameColorClass() .' '. $align[$index]. '">';
				$result.= $this->ResolveTag(SbCoreReader::GetInnerHtml($cell));
				$result.= '</td>';

				$index++;
			}
			$result.= '</tr>';
		}

		$result.='</table>';

		return array($result);
	}
}
?>
