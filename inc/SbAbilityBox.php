<?php
class SbAbilityBox extends SbUnitBox
{
	//--- Constructors ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $args);
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbAbilityBox($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Private Methods ---//

	private function ParseXml(DOMDocument $doc)
	{
		$data = array();

		$nodes = $doc->documentElement->childNodes;
		foreach($nodes as $node)
		{
			if($node->nodeType == XML_ELEMENT_NODE)
				switch($node->tagName)
				{
					case 'cost': 
						$data['cost'] = array('text' => $node->textContent); 
						break;

					case 'description': 
						$data['description'] = array('text' => SbCoreReader::GetInnerHtml($node)); 
						break;

					case 'energy':
						$item = array();
						if(isset($node->textContent)) $item['text'] = $node->textContent;
						if($node->hasAttribute('drain')) $item['drain'] = $node->getAttribute('drain');
						if(count($item)>0) $data['energy'] = $item;
						break;

					case 'hotkey':
						$data['hotkey'] = array('text' => $node->textContent);
						break;

					case 'patch':
						$item = array();
						if($node->hasAttribute('from')) $item['from'] = $node->getAttribute('from');
						if($node->hasAttribute('to')) $item['to'] = $node->getAttribute('to');
						if(count($item)>0) $data['patch'] = $item;
						break;

					case 'ship':
						$data['ship'] = array('text' => $node->textContent); 
						break;

					case 'time':
						$item = array();
						if($node->hasAttribute('cast')) $item['cast'] = $node->getAttribute('cast');
						if($node->hasAttribute('cooldown')) $item['cooldown'] = $node->getAttribute('cooldown');
						if($node->hasAttribute('effect')) $item['effect'] = $node->getAttribute('effect');
						if(count($item)>0) $data['energy'] = $item;
						break;

					case 'type':
						$data['type'] = array('text' => $node->textContent);
						break;
				}
		}
	}

	private function Render($input, array $args)
	{
		$result = '<div class="sbUB0">';

		if(array_key_exists('dock', $args))
		{
			switch($args['dock'])
			{
				case 'left': $result = '<div class="sbUB1">'; break;
				case 'right': break;
			}
		}

		$this->RenderHead($result, $dock);

		$result.='<table class="sbUB3"><colgroup><col><col><col></colgroup>';

		if(array_key_exists('id', $args))
		{
			$id = $args['id'];
			$data = SbCoreReader::Fetch('SbAbility');
			$data = $data[$id];
			
			if(!isset($data))
				return array($this->Error('Invalid id "'. $id. '"'));

			$this->AppendTitle($result, $data['name']['text']);
			$this->AppendImage($result, 'abi:'. $id. '.png');
		}
		else
		{
			if(array_key_exists('title', $args))
				$this->AppendTitle($result, $args['title']);

			if(array_key_exists('image', $args))
				$this->AppendImage($result, 'abi:'. $args['image']. '.png');

			$doc = new DOMDocument;
			$doc->loadXML('<data>'. $input. '</data>');

			$data = $this->ParseXml($doc);
		}

		$this->RenderData($result, $data);

		$result.='</table>';

		$this->RenderFoot($result);

		$result.= '</div>';

		return array($result);
	}

	private function RenderData(&$result, array $data)
	{
		$this->AppendTitle($result, 'General');

		if(array_key_exists('ship', $data))
			$this->AppendS21($result, 'Ship', '{{sbShip:'. htmlentities($data['ship']['text']). '}}');

		if(array_key_exists('type', $data))
			$this->AppendS21($result, 'Type', $data['type']['text']);

		if(array_key_exists('hotkey', $data))
			$this->AppendS21($result, 'Hotkey', $data['hotkey']['text']);

		if(array_key_exists('cost', $data))
		{
			$this->AppendTitle($result, 'Purchase');

			$this->AppendS21($result, 'Cost', intval($data['cost']['text']));
		}

		if(array_key_exists('energy', $data))
		{
			$item = $data['energy'];
			$this->AppendTitle($result, 'Energy');

			$this->AppendS21($result, 'Activation', intval($item['text']));

			if(array_key_exists('drain', $item))
				$this->AppendS21($result, 'Drain', intval($item['drain']). ' /s');
		}

		if(array_key_exists('time', $data))
		{
			$item = $data['time'];
			$this->AppendTitle($result, 'Timings');

			if(array_key_exists('cast', $item))
				$this->AppendS21($result, 'Cast time', $this->RenderTime(intval($item['cast'])));

			if(array_key_exists('effect', $item))
				$this->AppendS21($result, 'Duration time', $this->RenderTime(intval($item['effect'])));

			if(array_key_exists('cooldown', $item))
				$this->AppendS21($result, 'Cooldown time', $this->RenderTime(intval($item['cooldown'])));
		}

		if(array_key_exists('patch', $data))
		{
			$item = $data['patch'];
			$this->AppendTitle($result, 'Patch');

			if(array_key_exists('from', $item))
				$this->AppendS21($result, 'Introduced', '{{sbPatch:'. htmlspecialchars($item['from']). '}}');

			if(array_key_exists('to', $item))
				$this->AppendS21($result, 'Removed', '{{sbPatch:'. htmlspecialchars($item['to']). '}}');
		}
	}
}
?>
