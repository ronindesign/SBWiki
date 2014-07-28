<?php
class SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser, $frame, $skin)
	{
		$this->frame = $frame;
		$this->parser = $parser;
		$this->skin = $skin;

		SbCore::RegisterCSS($parser, 'SbCoreSkin');
	}

	//--- Protected Methods ---//

	protected function Error($message)
	{
		return '<pre class="sb0">!ERR['. get_class($this). ']: '. $message. '!</pre>';
	}
	
	protected function FetchSkin(array $args = NULL)
	{
		if(isset($args))
			if(array_key_exists('skin', $args))
				return new SbCoreSkin($args['skin']);

		return new SbCoreSkin(NULL);
	}

	protected function GetFrame()
	{
		return $this->frame;
	}

	protected function GetParser()
	{
		return $this->parser;
	}

	protected function GetSkin()
	{
		return $this->skin;
	}
	
	protected function InsertStrip($text)
	{
		$parser = $this->parser;

		return $parser->insertStripItem($text, $parser->mStripState);
	}

	protected function RenderIcon($icon, $class, $title)
	{
		$result = '<img src="'. htmlspecialchars($icon). '" alt="" class="sb1';
			
		if(isset($class))
			$result.= ' '. htmlspecialchars($class);
			
		$result.= '"';
			
		if(isset($title))
			$result.= ' title="'. htmlspecialchars($title). '"';
			
		$result.= '/>';

		return $result;
	}

	protected function RenderLink($text, $link, $icon, $class, $title)
	{
		$result = isset($icon) ? $this->RenderIcon($icon, $class, $title) : '';

		if(isset($text))
		{
			if($result != '')
				$result.= '&nbsp;';

			$result.= isset($link) ? $this->ResolveTag('[['. $link. '|'. $text. ']]') : htmlspecialchars($text);
		}

		return $this->InsertStrip($result);		
	}

	protected function RenderTime($time)
	{
		if($time >= 60)
		{
			$result= intval($time/60). ' m ';
			if($time%60 > 0) 
				$result.= intval($time%60). ' s';
		}
		else
			$result= $time. ' s';

		return $result;
	}

	protected function ResolveTag($text)
	{
		$parser = $this->parser;
		$frame = $this->frame;

		return $parser->recursiveTagParse($text, $frame);
	}

	//--- Protected Fields ---//

	private $frame;
	private $parser;
	private $skin;
}
?>
