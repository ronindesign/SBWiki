<?php
class SbCoreSkin
{
	//--- Constructor ---//

	public function __construct($ids)
	{
		$index = count(self::$stack)-1;

		if($index >= 0)
			$this->Copy(self::$stack[$index]);
		else
			$this->Copy(self::$skins['gray']);

		if(isset($ids))
		{
			if(is_string($ids))
				$ids = explode(' ', $ids);
		
			foreach($ids as $id)
				$this->Copy(self::Fetch($id));
		}

		self::$stack[]= $this->items;
	}

	//--- Destructor ---//

	public function __destruct()
	{
		array_pop(self::$stack);
	}

	//--- Public Methods ---//

	public function BoxColorClass()
	{
		return $this->items['Class']. 'Box';
	}

	public function FrameColorClass()
	{
		return $this->items['Class']. 'Frm';
	}

	public function IconEnergy()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconEnergy']);
	}

	public function IconMagnify()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconMagnify']);
	}

	public function IconMinerals()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconMinerals']);
	}

	public function IconMsgConstruct()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconMsgConstruct']);
	}

	public function IconMsgOutdated()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconMsgOutdated']);
	}

	public function IconMsgStub()
	{
		return SbCore::ParseUri('skn:'. $this->items['IconMsgStub']);
	}

	//--- Private Methods ---//

	private function Copy(array $items)
	{
		foreach($items as $key => $value)
			$this->items[$key] = $value;
	}

	private static function Fetch($id)
	{
		$id = strtolower($id);

		if(array_key_exists($id, self::$skins))
			return self::$skins[$id];

		return NULL;
	}

	//--- Private Fields ---//

	private $items = array();

	private static $stack = array();

	private static $skins = array(
		'gray' => array(
			'Class' => 'sbCx',
			'IconEnergy' => 'energy.png',
			'IconMagnify' => 'magnify.png',
			'IconMinerals' => 'minerals.png',
			'IconMsgConstruct' => 'msg_construct.png',
			'IconMsgOutdated' => 'msg_outdated.png',
			'IconMsgStub' => 'msg_stub.png'
		),
		'blue' => array(
			'Class' => 'sbCb',
			'IconMagnify' => 'magnify_b.png'
		),
		'green' => array(
			'Class' => 'sbCg',
			'IconMagnify' => 'magnify_g.png'
		),
		'red' => array(
			'Class' => 'sbCr',
			'IconMagnify' => 'magnify_r.png'
		)
	);
}
?>
