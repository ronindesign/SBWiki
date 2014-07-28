<?php
class SbCoreReader
{
	//--- Public Methods ---//

	public static function GetInnerHtml($element)
	{
		$result = ''; 
		
		$children = $element->childNodes; 
		foreach ($children as $child) 
		{ 
			$doc = new DOMDocument(); 
			$doc->appendChild($doc->importNode($child, true)); 
			
			$xml = $doc->saveXML();
			$xml = trim(substr($xml, strpos($xml, '>')+1));
			
			$result.= $xml;
		} 
		
		return $result;
 	}

	public static function Fetch($name)
	{
		if(array_key_exists($name, self::$cache))
			return self::$cache[$name];

		if(SbCore::Config('AllowWikiXml'))
		{
			$title = Title::newFromText(SbCore::Config('RootXml'). '/'. $name);
			if($title->exists())
			{
				$page = WikiPage::factory($title);
				if($page->exists())
				{
					$xml = $page->getRawText();

					$doc = new DOMDocument();
					$doc->loadXml($xml);

					$result = self::ReadXml($doc->documentElement);

					self::$cache[$name] = $result;
		
					return $result;
				}
			}
		}

		$name = SbCore::Config('RootLocal'). '/xml/'. $name. '.xml';

		if(!file_exists($name))
			die('ERR: SbWiki installation is missing the file "'. $name. '" !');

		$doc = new DOMDocument();
		$doc->load($name);
			
		$result = self::ReadXml($doc->documentElement);

		self::$cache[$name] = $result;
		
		return $result;
	}
	
	//--- Private Methods ---//

	private static function ReadXml(DOMElement $root)
	{
		$result = array();

		foreach($root->childNodes as $record)
		{
			if($record->nodeType != XML_ELEMENT_NODE)
				continue;
			
			$data = array();
			$key = NULL;

			foreach($record->childNodes as $field)
			{
				if($field->nodeType != XML_ELEMENT_NODE)
					continue;
			
				$name = $field->tagName;
				if($name == 'id')
					$key = $field->textContent;
				else
				{
					$item = array('text'=> self::GetInnerHtml($field));

					foreach($field->attributes as $attrib)
						$item[$attrib->name] = $attrib->value;

					$data[$name] = $item;
				}
			}

			if(isset($key))
				$result[$key] = $data;
		}

		return $result;
	}

	//--- Private Fields ---//

	private static $cache = array();
}
?>
