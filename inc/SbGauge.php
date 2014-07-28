<?php
class SbGauge extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbGauge($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Private Methods ---//

	private function Render($input, array $args)
	{
		$skin = $this->GetSkin();

		if(array_key_exists('val', $args))
			$val = doubleval($args['val']);
		else
			return array($this->Error('Attribute "val" is not set'));

		$max = $val;
		if(array_key_exists('max', $args))
		{
			$max = doubleval($args['max']);
			if($max < $val)
				$max = $val;
		}

		$min = 0;
		if(array_key_exists('min', $args))
		{
			$min = doubleval($args['min']);
			if($min > $val)
				$min = $val;
		}

		$width = 32;
		if(array_key_exists('width', $args))
		{
			$width = intval($args['width']);
			if($width < 1)
				return array('');
		}

		$length = intval((($val - $min) / ($max - $min)) * $width);

		$result = '<span class="'. $skin->BoxColorClass();
		$result.= '" style="';
		
		if(array_key_exists('height', $args))
		{
			$height = intval($args['height']);
			if($height > 0)
				$result.= 'height:'. $height. 'px;';
		}

		$result.= 'margin-right:'. ($width - $length). 'px;padding-right:'. $length. 'px"></span>';
		
		$title = isset($input) ? $this->ResolveTag($input) : '';
		if($title == '')
			$title = $this->ResolveTag($args['val']);
		
		if(trim($title) != '')
			$result.= '&nbsp;'. $title;

		return array($result);
	}
}
?>