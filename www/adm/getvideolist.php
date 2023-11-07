<?php
include_once("inc/header.inc.php");
include_once("mysql.class.inc.php");

$strSQL = "SELECT med.url AS vid,
		  (SELECT medn.name FROM ".PREFIX."_media_caption AS medn WHERE medn.id_MED=med.id_MED AND medn.lan='$cmslan') AS title
		  FROM ".PREFIX."_media AS med
		  WHERE med.tipo='vid'
		  ORDER BY med.id_MED DESC";
try
{
	$objConn = MySQL::getIstance();
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$strJS = "var tinyMCEMediaList = new Array(";
		while($obj = $rs->fetchObject())
		{
			$obj->title=="" ? $title = $obj->vid: $title = $obj->title;
			$strJS .= '["'.$title.'", "/swf/player.swf?flv='.VIDEOS_AS_URL.$obj->vid.'"],';
		}
		$strJS = rtrim($strJS, ',').');';
		echo $strJS;
	}
}

catch(Exception $e)
{
	
}
?>