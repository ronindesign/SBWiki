<?php
class SbUpdate extends SbCoreBase
{
	//--- Constructor ---//

	protected function __construct(Parser $parser)
	{
		parent::__construct($parser, NULL, NULL);
	}

	//--- Public Methods ---//

	public static function Hook(Parser $parser, $param1= '0.0.0', $param2= 'update')
	{
		$obj = new SbUpdate($parser);

		return $obj->Execute($param1, $param2);
	}

	//--- Private Methods ---//

	private function CheckVersion($major, $minor, $release, $rollback)
	{
		$majorCore = NULL;
		$minorCore = NULL;
		$releaseCore = NULL;

		$result = $this->ExtractVersion(SbCore::Version, $majorCore, $minorCore, $releaseCore);
		if(isset($result))
		{
			if($rollback)
			{
				if($major == $majorCore) 
					if($minor == $minorCore)
						if($minor == $minorCore)
							return FALSE;

				return TRUE;
			}

			if($major > $majorCore) 
				return TRUE;

			if($major == $majorCore)
			{
				if($minor > $minorCore)
					return TRUE;

				if($minor == $minorCore)
					if($release > $releaseCore)
						return TRUE;
			}
		}
		return FALSE;
	}
	
	private function CheckUser()
	{
		global $wgUser;

		$name = $wgUser->getName();

		if(in_array($name, SbCore::Config('UpdateUsers'), TRUE))
			return NULL;
			
		return $this->Error('Your user "'. $name. '" is not permitted to execute updates');
	}

	private function Cleanup($destination)
	{
		$result = '';

		$dir = opendir($destination);
		while(FALSE !== ($file = readdir($dir)))
		{
			$name = $destination. '/'. $file;

			if(is_dir($name))
			{
				if($file != '.')
					if($file != '..')
						$result.= $this->Cleanup($name);
			}
			else if(is_file($name))
			{
				if(!@unlink($name))
					$result.= $this->Error('Cannot remove file "'. $name. '"');
			}
		}
		closedir($dir);
		if(!@rmdir($destination))
			$result.= $this->Error('Cannot remove directory "'. $destination. '"');

		return $result;
	}

	private function Download($source, $destination)
	{
		$cache = @file_get_contents($source);
		if($cache === FALSE)
			return $this->Error('Cannot download file from "'. $source. '"');

		return (@file_put_contents($destination, $cache, LOCK_EX)===FALSE) ? $this->Error('Cannot save downaloded file to "'. $destination. '"') : NULL;
	}

	private function Execute($version, $mode)
	{
		global $IP;

		set_time_limit(0);

		$rc = $this->CheckUser();
		if(isset($rc))
			return $rc;
		
		$major = NULL;
		$minor = NULL;
		$release = NULL;
		$version = $this->ExtractVersion($version, $major, $minor, $release);
		if(!isset($version))
			return $this->Error('Invalid version.');

		$result = 'Update log for requested version '. $version. ':<ul>';
		if($this->CheckVersion($major, $minor, $release, $mode=='rollback'))
		{
			$host = SbCore::Config('UpdateSite');
			$dir = SbCore::Config('UpdateDir');
			$mask = SbCore::Config('UpdateMask');
			$file = sprintf($mask, $major, $minor, $release);
			
			$result.= '<li>Downloading new version from "'. $host. '" ...</li>';
			
			if(ini_get('allow_url_fopen') != 1)
				$result.= $this->Error('Download is disabled in PHP configuration (allow_url_fopen != 1)');
			else
			{
				$source = $host. $dir. $file;
				$destination = $IP. '/extensions/'. $file;

				$rc = $this->Download($source, $destination);
				if(isset($rc))
					$result.= $rc;
				else
				{
					$dir = SbCore::Config('RootLocal');

					$result.= '<li>Cleaning up directory "'. $dir. '" ...</li>';
					$result.= $this->Cleanup($dir);
					
					$result.= '<li>Extracting zip file "'. $destination. '" ...</li>';
				
					$source = $destination;
					$destination = $dir;

					$rc = $this->ExtractZip($source, $destination);
					if(isset($rc))
						$result.= $rc;
					else
						$result.= '<li>Update to version "'. $version. '" was successful.</li>';
				}
			}
		}
		else
			$result.= '<li>Your Wiki is already up to date with version "' . SbCore::Version. '".</li>';

		$result.= '</ul>';

		return $result;
	}

	private function ExtractVersion($version, &$major, &$minor, &$release)
	{
		$split = explode('.', $version);

		$count = count($split);
		if($count <= 0)
			return NULL;

		$major = intval($split[0]);
		if($major <= 0)
			return NULL;

		$minor = $count>=2 ? intval($split[1]) : 0;
		if($minor < 0)
			return NULL;

		$release = $count>=3 ? intval($split[2]) : 0;
		if($release < 0)
			return NULL;

		return $major. '.'. $minor. '.'. $release;
	}

	private function ExtractZip($source, $destination)
	{
		$result = NULL;

		$zip = new ZipArchive;
		if($zip->open($source) === TRUE)
		{
			if($zip->extractTo($destination) !== TRUE)
				$result = $this->Error('Cannot extract zip file to "'. $destination. '"');

			$zip->close();
		}
		else
			$result = $this->Error('Cannot open zip file "'. $source. '"');

		return $result;
	}
}
?>