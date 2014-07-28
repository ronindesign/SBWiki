<?php
class SbMessage extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser, PPFrame $frame, array $args)
	{
		parent::__construct($parser, $frame, $this->FetchSkin($args));

		SbCore::RegisterCSS($parser, 'SbMessage');
	}

	//--- Public Methods ---//

	public static function Hook($input, array $args, Parser $parser, PPFrame $frame)
	{
		$obj = new SbMessage($parser, $frame, $args);

		return $obj->Render($input, $args);
	}

	//--- Private Methods ---//

	private function Render($input, array $args)
	{
		$icon = NULL;

		if(array_key_exists('msg', $args))
		{
			$skin = $this->GetSkin();

			switch($args['msg'])
			{
				case 'construct':
					$icon = $skin->IconMsgConstruct();
					$input = '<b>This article is under construction.</b><br/>Please do not change it until this message disappears. Thanks !';
					break;

				case 'outdated':
					$icon = $skin->IconMsgOutdated();
					$input = '<b>This article seems to be outdated.</b><br/>Please help to correct outdated content to improve the quality of the Wiki. Thanks !<br/>[[Category:Outdated]]';
					break;

				case 'stub':
					$icon = $skin->IconMsgStub();
					$input = '<b>This article seems to be stub.</b><br/>Please help to expand content to improve the quality of the Wiki. Thanks !<br/>[[Category:Stub]]';
					break;
			}
		}

		$result = '<table class="sbMsg0">';
		$result.= '<tr><td>';
		
		if(array_key_exists('icon', $args))
			$icon = htmlentities(SbCore::ParseUri($args['icon']));

		if(isset($icon))
			$result.= '<img src="'. $icon. '" alt=""/></br>';
		
		$result.= '</td><td class="sbMsg1">';
		$result.= $this->ResolveTag($input);
		
		if(array_key_exists('user', $args))
			$result.= $this->ResolveTag('----<i>{{sbUser:'. $args['user']. '}}</i>');

		$result.= '</td></tr></table>';

		return array($result);
	}
}
?>