<?php
class SbUnitBox extends SbBox
{
	//--- Constructors ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $args);

		SbCore::RegisterCSS($parser, 'SbUnitBox');
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbUnitBox($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Protected Methods ---//

	protected function AppendImage(&$result, $image)
	{
		$result.= '<tr><td class="sbUB5" colspan="3"><img src="';
		$result.= htmlentities(SbCore::ParseUri($image));
		$result.= '" alt=""/></td></tr>';
	}
	
	protected function AppendTitle(&$result, $title)
	{
		$output = '';

		$this->RenderTitle($output, $title);
		$this->AppendS3($result, $output);
	}

	/* <td><td2>*/
	protected function AppendS12(&$result, $key, $value)
	{
		$skin = $this->GetSkin();

		$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2">';
		$result.= $this->ResolveTag($key);
		$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4" colspan="2">';
		$result.= $this->ResolveTag($value);
		$result.= '</td></tr>';
	}

	/* <td2><td>*/
	protected function AppendS21(&$result, $key, $value)
	{
		$skin = $this->GetSkin();

		$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2" colspan="2">';
		$result.= $this->ResolveTag($key);
		$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4">';
		$result.= $this->ResolveTag($value);
		$result.= '</td></tr>';
	}

	/* <td><td><td> */
	protected function AppendS111(&$result, $key, $value, $sub)
	{
		$skin = $this->GetSkin();


		$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2">';
		$result.= $this->ResolveTag($key);
		$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB2">';
		$result.= $this->ResolveTag($value);
		$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4">';
		$result.= $this->ResolveTag($sub);
		$result.= '</td></tr>';
	}

	/* <td3> */
	protected function AppendS3(&$result, $key)
	{
		$skin = $this->GetSkin();

		$result.= '<tr><td class="sbUB5" colspan="3">';
		$result.= $this->ResolveTag($key);
		$result.= '</td></tr>';
	}

	/* <td><td2>*/
	protected function AppendM12(&$result, $key, array $value)
	{
		$count = count($value);

		if($count > 1)
		{
			$skin = $this->GetSkin();

			for($index=0; $index < $count; $index++)
			{
				$curVal = $value[$index];

				if($index == 0)
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2" rowspan="'. $count. '">';
					$result.= $this->ResolveTag($key);
					$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4" colspan="2">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td></tr>';
				}
				else
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB4" colspan="2">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td></tr>';
				}
			}
		}
		else
			$this->AppendS12($result, $key, $value[0]);
	}

	/* <td2><td>*/
	protected function AppendM21(&$result, $key, array $value)
	{
		$count = count($value);

		if($count > 1)
		{
			$skin = $this->GetSkin();

			for($index=0; $index < $count; $index++)
			{
				$curVal = $value[$index];

				if($index == 0)
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2" colspan="2" rowspan="'. $count. '">';
					$result.= $this->ResolveTag($key);
					$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td></tr>';
				}
				else
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB4">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td></tr>';
				}
			}
		}
		else
			$this->AppendS21($result, $key, $value[0]);
	}

	/* <td><td><td> */
	protected function AppendM111(&$result, $key, array $value, array $sub)
	{
		$count = count($value);

		if($count > 1)
		{
			$skin = $this->GetSkin();

			for($index=0; $index < $count; $index++)
			{
				$curVal = $value[$index];
				$curSub = $sub[$index];

				if($index == 0)
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2" rowspan="'. $count. '">';
					$result.= $this->ResolveTag($key);
					$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB2">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4">';
					$result.= $this->ResolveTag($curSub);
					$result.= '</td></tr>';
				}
				else
				{
					$result.= '<tr><td class="'. $skin->FrameColorClass(). ' sbUB2">';
					$result.= $this->ResolveTag($curVal);
					$result.= '</td><td class="'. $skin->FrameColorClass(). ' sbUB4">';
					$result.= $this->ResolveTag($curSub);
					$result.= '</td></tr>';
				}
			}
		}
		else
			$this->AppendS111($result, $key, $value[0], $sub[0]);
	}

	//--- Private Methods ---//

	private function ParseData(&$result, array $data)
	{
		$items = array();
		
		/* General */

		if(array_key_exists('class', $data)) $items['class'] = explode(',', $data['class']['text']);
		if(array_key_exists('type', $data)) $items['type'] = $data['type']['text'];
		if(array_key_exists('race', $data)) $items['race'] = $data['race']['text'];
		if(count($items)>0)
		{
			$this->RenderGeneral($result, $items);
			$items = array();
		}
		
		/* Base */

		if(array_key_exists('energy', $data)) $items['energy'] = intval($data['energy']['text']);
		if(array_key_exists('hull', $data))
		{
			$src = $data['hull'];
			$item = array('hp'=> intval($src['text']));
			if(array_key_exists('armor', $src))
				$item['armor'] = intval($src['armor']);
			$items['hull'] = $item;
		}
		if(array_key_exists('shield', $data))
		{
			$src = $data['shield'];
			$item = array('hp'=> intval($src['text']));
			if(array_key_exists('armor', $src))
				$item['armor'] = intval($src['armor']);
			$items['shield'] = $item;
		}
		if(array_key_exists('speed', $data)) $items['speed'] = doubleval($data['speed']['text']);
		if(count($items)>0)
		{
			$this->RenderBase($result, $items);
			$items = array();
		}

		/* Primary Weapon */

		if(array_key_exists('primary', $data))
		{
			$src = $data['primary'];
			$items['name'] = $src['text'];
			$items['amount'] = array_key_exists('amount', $src) ? intval($src['amount']) : 1;
			$items['damage'] = array_key_exists('damage', $src) ? intval($src['damage']) : 0;
			$items['range'] = array_key_exists('range', $src) ? intval($src['range']) : 0;
			$items['speed'] = array_key_exists('speed', $src) ? doubleval($src['speed']) : 1;			

			$this->RenderWeapon($result, $items, 'Primary Armament');
			$items = array();
		}

		/* Secondary Weapon */

		if(array_key_exists('secondary', $data))
		{
			$src = $data['secondary'];
			$items['name'] = $src['text'];
			$items['amount'] = array_key_exists('amount', $src) ? intval($src['amount']) : 1;
			$items['damage'] = array_key_exists('damage', $src) ? intval($src['damage']) : 0;
			$items['range'] = array_key_exists('range', $src) ? intval($src['range']) : 0;
			$items['speed'] = array_key_exists('speed', $src) ? doubleval($src['speed']) : 1;			

			$this->RenderWeapon($result, $items, 'Secondary Armament');
			$items = array();
		}

		/* Fighters */

		if(array_key_exists('fighter', $data))
		{
			$src = $data['fighter'];
			$items['name'] = $src['text'];
			$items['amount'] = array_key_exists('amount', $src) ? intval($src['amount']) : 10;
			$items['damage'] = array_key_exists('damage', $src) ? intval($src['damage']) : 0;

			$this->RenderFighters($result, $items);
			$items = array();
		}

		/* Abilities */

		if(array_key_exists('active', $data)) $items['active'] = explode(',', $data['active']['text']);
		if(array_key_exists('passive', $data)) $items['passive'] = explode(',', $data['passive']['text']);
		if(count($items)>0)
			$this->RenderAbilities($result, $items);
	}

	private function ParseXml(&$result, DOMDocument $doc)
	{
		$general = array();
		$base = array();
		$primary = array();
		$secondary = array();
		$fighters = array();
		$abilities = array();

		$nodes = $doc->documentElement->childNodes;
		foreach($nodes as $node)
		{
			if($node->nodeType == XML_ELEMENT_NODE)
				switch($node->tagName)
				{
					case 'active': $abilities['active'] = explode(',', $node->textContent); break;
					case 'class': $general['class'] = explode(',', SbCoreReader::GetInnerHtml($node)); break;						
					
					case 'energy': $base['energy'] = intval($node->textContent); break;
					
					case 'fighter':
						$fighters['name'] = SbCoreReader::GetInnerHtml($node);
						$fighters['amount'] = $node->hasAttribute('amount') ? intval($node->getAttribute('amount')) : 10;
						$fighters['damage'] = $node->hasAttribute('damage') ? intval($node->getAttribute('damage')) : 0;
						break;

					case 'hull': 
						$item = array('hp'=>intval($node->textContent));
						if($node->hasAttribute('armor'))
							$item['armor'] = intval($node->getAttribute('armor'));
						$base['hull'] = $item;
						break;
					
					case 'primary':
						$primary['name'] = SbCoreReader::GetInnerHtml($node);
						$primary['amount'] = $node->hasAttribute('amount') ? intval($node->getAttribute('amount')) : 1;
						$primary['damage'] = $node->hasAttribute('damage') ? intval($node->getAttribute('damage')) : 0;
						$primary['range'] = $node->hasAttribute('range') ? intval($node->getAttribute('range')) : 0;
						$primary['speed'] = $node->hasAttribute('speed') ? doubleval($node->getAttribute('speed')) : 1;
						break;

					case 'secondary':
						$secondary['name'] = SbCoreReader::GetInnerHtml($node);
						$secondary['amount'] = $node->hasAttribute('amount') ? intval($node->getAttribute('amount')) : 1;
						$secondary['damage'] = $node->hasAttribute('damage') ? intval($node->getAttribute('damage')) : 0;
						$secondary['range'] = $node->hasAttribute('range') ? intval($node->getAttribute('range')) : 0;
						$secondary['speed'] = $node->hasAttribute('speed') ? doubleval($node->getAttribute('speed')) : 1;
						break;

					case 'shield':
						$item = array('hp'=>intval($node->textContent));
						if($node->hasAttribute('armor'))
							$item['armor'] = intval($node->getAttribute('armor'));
						$base['shield'] = $item;
						break;

					case 'speed': $base['speed'] = doubleval($node->textContent); break;
					case 'type': $general['type'] = SbCoreReader::GetInnerHtml($node); break;
					case 'passive': $abilities['passive'] = explode(',', $node->textContent); break;					
					case 'race': $general['race']= SbCoreReader::GetInnerHtml($node); break;
				}
		}

		if(count($general)>0)
			$this->RenderGeneral($result, $general);

		if(count($base)>0)
			$this->RenderBase($result, $base);

		if(count($primary)>0)
			$this->RenderWeapon($result, $primary, 'Primary Armament');

		if(count($secondary)>0)
			$this->RenderWeapon($result, $secondary, 'Secondary Armament');

		if(count($fighters)>0)
			$this->RenderFighters($result, $fighters);

		if(count($abilities)>0)
			$this->RenderAbilities($result, $abilities);
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
			$data = SbCoreReader::Fetch('SbShip');
			$data = $data[$id];
			
			if(!isset($data))
				return array($this->Error('Invalid id "'. $id. '"'));

			$this->AppendTitle($result, $data['name']['text']);

			if(array_key_exists('image', $data))
				$this->AppendImage($result, $data['image']['text']);

			$this->ParseData($result, $data);
		}
		else
		{
			if(array_key_exists('title', $args))
				$this->AppendTitle($result, $args['title']);

			if(array_key_exists('image', $args))
				$this->AppendImage($result, $args['image']);

			$doc = new DOMDocument;
			$doc->loadXML('<data>'. $input. '</data>');

			$this->ParseXml($result, $doc);
		}

		$result.='</table>';

		$this->RenderFoot($result);

		$result.= '</div>';

		return array($result);
	}

	private function RenderAbilities(&$result, array $items)
	{
		$this->AppendTitle($result, 'Abilities');

		if(array_key_exists('active', $items))
		{
			$item = $items['active'];
			for($index=0; $index<count($item); $index++)
				$item[$index] = '{{sbAbility:'. $item[$index]. '}}';

			$this->AppendM12($result, 'Active', $item);
		}

		if(array_key_exists('passive', $items))
		{
			$item = $items['passive'];
			for($index=0; $index<count($item); $index++)
				$item[$index] = '{{sbAbility:'. $item[$index]. '}}';

			$this->AppendM12($result, 'Passive', $item);
		}
	}

	private function RenderBase(&$result, array $items)
	{
		$this->AppendTitle($result, 'Base Stats');

		if(array_key_exists('hull', $items))
		{
			$item = $items['hull'];
			$values = array( $item['hp'], $item['armor']);
			$this->AppendM111($result, 'Hull', array('Points', 'Armor'), $values);
		}

		if(array_key_exists('shield', $items))
		{
			$item = $items['shield'];
			$values = array( $item['hp'], $item['armor']);
			$this->AppendM111($result, 'Shield', array('Points', 'Armor'), $values);
		}

		if(array_key_exists('speed', $items))
			$this->AppendS21($result, 'Speed', sprintf('%0.2f', $items['speed']));

		if(array_key_exists('energy', $items))
			$this->AppendS21($result, 'Energy', $items['energy']);
	}

	private function RenderFighters(&$result, array $items)
	{
		$this->AppendTitle($result, 'On-board fighters');

		$keys = array('Damage', 'Amount');
		$values = array($items['damage'], $items['amount']);

		$this->AppendM111($result, $items['name'], $keys, $values);
	}

	private function RenderGeneral(&$result, array $items)
	{
		$this->AppendTitle($result, 'General');
		
		if(array_key_exists('race', $items))
			$this->AppendS21($result, 'Race', $items['race']);

		if(array_key_exists('type', $items))
			$this->AppendS21($result, 'Type', $items['type']);
		else
			$this->AppendS21($result, 'Type', '[[Capital Ship]]');

		if(array_key_exists('class', $items))
			$this->AppendM21($result, 'Class', $items['class']);
	}

	private function RenderWeapon(&$result, array $items, $title)
	{
		$this->AppendTitle($result, $title);

		$amount = $items['amount'];
		$damage = $items['damage'];
		$speed = $items['speed'];
		$dps = $damage * $amount / $speed;

		if($amount != 1)
		{
			$keys = array('Damage', 'Amount', 'Speed', 'DPS', 'Range');
			$values = array($damage, $amount, sprintf('%0.2f', $speed), sprintf('%0.2f', $dps), $items['range']);
		}
		else
		{
			$keys = array('Damage', 'Speed', 'DPS', 'Range');
			$values = array($damage, sprintf('%0.2f', $speed), sprintf('%0.2f', $dps), $items['range']);
		}

		$this->AppendM111($result, $items['name'], $keys, $values);
	}
}
?>
