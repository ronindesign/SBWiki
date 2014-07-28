<?php
class SbAbilityOverview extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $params1= '', $params2= 'co')
	{
		$obj = new SbAbilityOverview($parser);

		return $obj->Render($params1, $params2);
	}

	//--- Private Methods ---//

	private function Render($id, $mode)
	{
		$vCurrent = FALSE;
		$vObsolete = FALSE;

		for($index=0; $index<strlen($mode); $index++)
			switch($mode[$index])
			{
				case 'c': $vCurrent = TRUE; break;
				case 'o': $vObsolete = TRUE; break;
				default: return $this->Error('Invalid mode "'. $mode[$index]. '"');
			}

		$result = '<sbTable><h>Ability</h><h align="center">Hotkey</h><h>Description</h><h align="right">Cost</h><h align="right">Energy</h><h align="right">Cast time</h><h align="right">Effect time</h><h align="right">Cooldown time</h>';

		$items = SbCoreReader::Fetch('SbAbility');
		$keys = array_keys($items);
		foreach($keys as $key)
		{
			if($id != '')
				if(substr($key, 0, 3) != $id)
					continue;

			$item = $items[$key];
			
			if(array_key_exists('patch', $item))
			{
				if(array_key_exists('to', $item['patch']))
					$visible = $vObsolete;
				else
					$visible = $vCurrent;
			}
			else
				$visible = $vCurrent;

			if(!$visible) 
				continue;

			$result.= '<c>{{sbAbility:'. $key. '}}</c>';

			if(array_key_exists('hotkey', $item))
				$result.= '<c>'. $item['hotkey']['text']. '</c>';
			else
				$result.= '<c/>';

			if(array_key_exists('description', $item))
				$result.= '<c>'. $item['description']['text']. '</c>';
			else
				$result.= '<c/>';

			if(array_key_exists('cost', $item))
				$result.= '<c>'. intval($item['cost']['text']). '</c>';
			else
				$result.= '<c/>';

			if(array_key_exists('energy', $item))
			{
				$data = $item['energy'];
				$result.= '<c>'. intval($data['text']);
				if(array_key_exists('drain', $data))
					$result.= ' (drain '. intval($data['drain']). '/s)';
				$result.= '</c>';
			}
			else
			{
				if(array_key_exists('type', $item))
				{
					if($item['type']['text']=='passive')
						$result.= '<c><i>passive</i></c>';
					else
						$result.= '<c/>';
				}
				else
					$result.= '<c/>';
			}

			if(array_key_exists('time', $item))
			{
				$data = $item['time'];

				if(array_key_exists('cast', $data))
					$result.= '<c>'. $this->RenderTime(intval($data['cast'])). '</c>)';
				else
					$result.= '<c/>';

				if(array_key_exists('effect', $data))
					$result.= '<c>'. $this->RenderTime(intval($data['effect'])). '</c>)';
				else
					$result.= '<c/>';

				if(array_key_exists('cooldown', $data))
					$result.= '<c>'. $this->RenderTime(intval($data['cooldown'])). '</c>';
				else
					$result.= '<c/>';
			}
			else
				$result.= '<c/><c/><c/>';
		}

		$result.= '</sbTable>';

		return $this->ResolveTag($result);
	}
}

?>
