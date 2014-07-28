<?php

//--- Configuration overrides in LocalSettings.php ---//

/*
SbCore::Config('EnableFncAbility', TRUE|FALSE);
SbCore::Config('EnableFncClan', TRUE|FALSE);
SbCore::Config('EnableFncFlag', TRUE|FALSE);
SbCore::Config('EnableFncIcon', TRUE|FALSE);
SbCore::Config('EnableFncPatch', TRUE|FALSE);
SbCore::Config('EnableFncShip', TRUE|FALSE);
SbCore::Config('EnableFncTournament', TRUE|FALSE);
SbCore::Config('EnableFncUpdate', TRUE|FALSE);
SbCore::Config('EnableFncUser', TRUE|FALSE);
SbCore::Config('EnableFncYouTube', TRUE|FALSE);

SbCore::Config('EnableTagAbilityBox', TRUE|FALSE);
SbCore::Config('EnableTagBox', TRUE|FALSE);
SbCore::Config('EnableTagGauge', TRUE|FALSE);
SbCore::Config('EnableTagImgBox', TRUE|FALSE);
SbCore::Config('EnableTagMessage', TRUE|FALSE);
SbCore::Config('EnableTagSkin', TRUE|FALSE);
SbCore::Config('EnableTagTable', TRUE|FALSE);
SbCore::Config('EnableTagUnitBox', TRUE|FALSE);

SbCore::Config('Name', 'SbWiki');
SbCore::Config('RootLocal', $IP. '/extensions/SbWiki');
SbCore::Config('RootRemote', $wgScriptPath. '/extensions/SbWiki');
SbCore::Config('RootXml', 'User:MaxiTB/SbWiki/xml');

SbCore::Config('UpdateDir', '/');
SbCore::Config('UpdateMask', 'SbWiki_%d.%d.%d.zip');
SbCore::Config('UpdateSite', 'http://starbattle.maxisoft.org');
SbCore::Config('UpdateUsers', array('MaxiTB'));

SbCore::Config('AllowWikiXml', TRUE|FALSE);
*/

//--- Initialize ---//

if(defined('MEDIAWIKI')) 
{
	$egSbWikiRoot = dirname( __FILE__ );

	$wgAutoloadClasses['SbCore'] = $egSbWikiRoot. '/inc/SbCore.php';
	SbCore::Initialize($egSbWikiRoot);
}

?>
