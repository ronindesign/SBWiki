<?php
class SbCore
{
	//--- Constructor ---//

	private function __construct($root)
	{
		global $wgExtensionCredits;
		global $wgScriptPath;

		$wgExtensionCredits['parserhook'][] = array(
			'path' => __FILE__,
			'name' => 'SbWiki',
			'author' =>'Markus J. Wolfger ([[User:MaxiTB|MaxiTB]])', 
			'url' => 'http://wiki.learnstarbattle.com/index.php?title=User:MaxiTB/SbWiki/xml',
			'description' => 'Official StarBattle Wiki extension (custom tags and parser functions).',
			'version'  => self::Version
		); 

		$this->config['EnableFncAbility'] = TRUE;
		$this->config['EnableFncAbilityOverview'] = TRUE;
		$this->config['EnableFncClan'] = TRUE;
		$this->config['EnableFncFlag'] = TRUE;
		$this->config['EnableFncIcon'] = TRUE;
		$this->config['EnableFncPatch'] = TRUE;
		$this->config['EnableFncShip'] = TRUE;
		$this->config['EnableFncTournament'] = TRUE;
		$this->config['EnableFncUpdate'] = TRUE;
		$this->config['EnableFncUser'] = TRUE;
		$this->config['EnableFncYouTube'] = TRUE;
		
		$this->config['EnableTagAbilityBox'] = TRUE;
		$this->config['EnableTagBox'] = TRUE;
		$this->config['EnableTagGauge'] = TRUE;
		$this->config['EnableTagImgBox'] = TRUE;
		$this->config['EnableTagMessage'] = TRUE;
		$this->config['EnableTagTable'] = TRUE;
		$this->config['EnableTagUnitBox'] = TRUE;
		$this->config['EnableTagSkin'] = TRUE;

		$split = explode('/', dirname(__FILE__));
		$name = $split[count($split)-2];
		$uri = $wgScriptPath. '/extensions/'. $name;

		$this->config['Name'] = $name;
		$this->config['RootLocal'] = $root;
		$this->config['RootRemote'] = $uri;
		$this->config['RootXml'] = 'User:MaxiTB/SbWiki/xml';

		$this->config['UpdateDir'] = '/';
		$this->config['UpdateMask'] = 'SbWiki_%d.%d.%d.zip';
		$this->config['UpdateSite'] = 'http://starbattle.maxisoft.org';
		$this->config['UpdateUsers'] = array('MaxiTB');

		$this->config['WikiLogo'] = 'img:loadscreen.jpg';

		$this->config['AllowWikiXml'] = TRUE;

		$this->DoInitialize($name);
	}

	//--- Public Methods ---//

	public static function Config($key, $value= NULL)
	{
		$config = self::$current->config;

		if(isset($value))
			$config[$key] = $value;

		return $config[$key];
	}

	public static function HookInitialize(Parser &$parser)
	{
		return self::$current->DoHookInitialize($parser);
	}

	public static function HookMagic(&$magic)
	{
		return self::$current->DoHookMagic($magic);
	}

	public static function Initialize($rootDirectory)
	{
		if(!isset(self::$current))
			self::$current = new SbCore($rootDirectory);

		return self::$current;
	}

	public static function ParseUri($uri)
	{
		if(isset($uri))
			if(strlen($uri)>3)
			{
				$root = self::$current->config['RootRemote'];

				switch(substr($uri,0,4))
				{
					case 'abi:': $uri = $root. '/img/abs/'. substr($uri, 4); break;
					case 'cln:': $uri = $root. '/img/clan/'. substr($uri, 4); break;
					case 'flg:': $uri = $root. '/img/flags/'. substr($uri, 4); break;
					case 'img:': $uri = $root. '/img/misc/'. substr($uri, 4); break;
					case 'shp:': $uri = $root. '/img/ships/'. substr($uri, 4); break;					
					case 'skn:': $uri = $root. '/img/skin/'. substr($uri, 4); break;
					case 'trn:': $uri = $root. '/img/tourns/'. substr($uri, 4); break;					
					case 'usr:': $uri = $root. '/img/user/'. substr($uri, 4); break;
				}
			}

		return $uri;
	}

	public static function RegisterCSS(Parser $parser, $name)
	{
		self::$current->DoRegisterCSS($parser, $name);
	}

	public static function RegisterJS(Parser $parser, $name)
	{
		self::$current->DoRegisterJS($parser, $name);
	}

	//--- Public Fields ---//

	const Version = '1.3.8';

	//--- Private Methods ---//	
	
	private function DoHookInitialize(Parser &$parser)
	{
		global $wgLogo;
		
		foreach($this->GetFncList() as $name)
			$parser->setFunctionHook('sb'. $name, 'Sb'. $name. '::Hook', SFH_NO_HASH);

		foreach($this->GetTagList() as $name)
			$parser->SetHook('sb'. $name, 'Sb'. $name. '::Hook');

		$logo = $this->config['WikiLogo'];
		if(isset($logo))
			$wgLogo = self::ParseUri($logo);

		return TRUE;
	}

	private function DoHookMagic(&$magic)
	{
		foreach($this->GetFncList() as $name)
			$magic['sb'. $name] = array(0, 'sb'. $name);

		return TRUE;
	}

	private function DoInitialize($name)
	{
		global $wgAutoloadClasses;
		global $wgHooks;
		global $wgResourceModules;

		$dir = $this->config['RootLocal'];
		$inc = $dir. '/inc/';

		foreach($this->GetClsList() as $class)
		{
			$wgAutoloadClasses[$class] = $inc . $class . '.php';

			$module = array(
				'group'=> 'ext.SbWiki',
				'localBasePath' => $dir,
				'remoteExtPath' => $name
			);
			
			$file = 'mod/'. $class. '.css';
			if(file_exists($dir. '/'. $file))
			{
				$module['styles'] = $file;
				$this->css[$class] = TRUE;
			}

			$file = 'mod/'. $class. '.js';
			if(file_exists($dir. '/'. $file))
			{
				$module['scripts'] = $file;
				$this->js[$class] = TRUE;
			}

			if(count($module) > 3)
				$wgResourceModules['ext.SbWiki.'. $class] = $module;
		}

		$wgHooks['ParserFirstCallInit'][] = 'SbCore::HookInitialize';
		$wgHooks['LanguageGetMagic'][] = 'SbCore::HookMagic';
	}
	
	public function DoRegisterCSS(Parser $parser, $name)
	{
		if(array_key_exists($name, $this->css))
		{
			if($this->css[$name])
			{
				$output = $parser->getOutput();
				$output->addModuleStyles('ext.SbWiki.'. $name);
				$this->css[$name] = FALSE;
			}
		}
	}

	public function DoRegisterJS(Parser $parser, $name)
	{
		if(array_key_exists($name, $this->js))
			if($this->js[$name])
			{
				$output = $parser->getOutput();
				$output->addModuleScripts('ext.SbWiki.'. $name);
				$this->js[$name] = FALSE;
			}
	}

	private function GetClsList()
	{
		$result = array('SbCoreBase', 'SbCoreReader', 'SbCoreSkin');

		foreach($this->config as $key => $value)
		{
			if(strpos($key, 'EnableFnc') === 0)
				$result[]= 'Sb'. substr($key, 9);

			if(strpos($key, 'EnableTag') === 0)
				$result[]= 'Sb'. substr($key, 9);
		}

		return $result;
	}
	
	private function GetFncList()
	{
		$result = array();

		foreach($this->config as $key => $value)
			if($value)
				if(strpos($key, 'EnableFnc') === 0)
					$result[]= substr($key, 9);

		return $result;
	}

	private function GetTagList()
	{
		$result = array();

		foreach($this->config as $key => $value)
			if($value)
				if(strpos($key, 'EnableTag') === 0)
					$result[]= substr($key, 9);

		return $result;
	}

	//--- Private Fields ---//

	private $config = array();
	private $css = array();
	private $js = array();

	private static $current;
}

?>
