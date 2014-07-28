<?php
class SbImgBox extends SbCoreBase
{
	//--- Constructors ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));

		SbCore::RegisterCSS($parser, 'SbImgBox');
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbImgBox($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Private Methods ---//

	private function Render($input, array $args)
	{
		if(array_key_exists('thumb', $args))
		{
			$image = htmlentities(SbCore::ParseUri($args['thumb']));

			$link = NULL;
			if(array_key_exists('image', $args))
				$link = htmlentities(SbCore::ParseUri($args['image']));

			$class = 'sbImgBox0';
			if(array_key_exists('align', $args))
			{
				$align = $args['align'];
				switch($align)
				{
					case 'left':
						$class = 'sbImgBox1';
						break;

					case 'right':
						break;

					default:
						return array($this->Error('Invalid align "'. $align. '"'));
				}
			}

			$height = 320;
			if(array_key_exists('height', $args))
				$height = htmlentities($args['height']);
				
			$width = 200;
			if(array_key_exists('width', $args))
				$width = htmlentities($args['width']);

			if(!isset($input))
				$input = '';

			$skin = $this->GetSkin();

			$result = '<div class="'. $class. '">';
			$result.= '<div class="sbImgBox2 '. $skin->FrameColorClass(). '" style="width:'. ($width+2). 'px">';			
			$result.= '<img src="'. $image. '" style="height:'. $height. 'px;width:'. $width.'px" alt=""/>';
			if(isset($link) || $input != '')
			{
				$result.= '<div class="sbImgBox3">';
				if(isset($link))
				{
					$result.= '<div class="sbImgBox4">';
					$result.= '<a href="'. $link. '">';
					$result.= '<img src="'. $skin->IconMagnify(). '" alt=""/>';
					$result.= '</a>';
					$result.= '</div>';
				}
				$result.= $this->ResolveTag($input);
				$result.= '</div>';
			}
			$result.='</div></div>';
		}
		else
			$result = $this->Error('No thumb');

		return array($result);
	}
}
?>
