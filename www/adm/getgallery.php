<?php
include_once("inc/header.inc.php");
include_once("mysql.class.inc.php");
/*
$_GET["get"] = "galleries";

	$_GET["get"] = "images";
	$_GET["id"] = 1;
*/
if(isset($_GET["get"]))
{
	$doArray = true;
	if($_GET["get"]=="galleries")
	{
		$strSQL = "SELECT gl.id_GALL AS id, gln.name AS title, gln.name AS desco
				   FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL
				   WHERE gln.lan='".$cmslan."'";
	}
	elseif($_GET["get"]=="images")
	{
		settype($_GET["id"],"INT");
		$_GET["id"] == 0 ? $strWH = " WHERE tipo='img'" : $strWH = " WHERE id_GALL=".$_GET["id"]." AND tipo='img'";
		$strSQL = "SELECT med.id_MED AS id, '' AS title, CONCAT('".IMAGES_AS_URL."',med.url) AS thumb,
				   IFNULL((SELECT medn.name FROM ".PREFIX."_media_caption AS medn WHERE medn.id_MED=med.id_MED AND medn.lan='$cmslan'),'') AS desco
				  FROM ".PREFIX."_media AS med
				  LEFT JOIN ".PREFIX."_gallery_media AS medgal ON medgal.id_MED=med.id_MED$strWH
				  ORDER BY med.id_MED DESC";
	}
	elseif($_GET["get"]=="image")
	{
		$doArray = false;
		settype($_GET["id"],"INT");
		$strSQL = "SELECT med.id_MED AS id, '' AS title, CONCAT('".IMAGES_AS_URL."',med.url) AS thumb,
				  (SELECT medn.name FROM ".PREFIX."_media_caption AS medn WHERE medn.id_MED=med.id_MED AND medn.lan='$cmslan') AS desco
				  FROM ".PREFIX."_media AS med WHERE med.id_MED=".$_GET["id"]."";
	}
	elseif($_GET["get"]=="imagesrc")
	{
		$doArray = false;
		settype($_GET["id"],"INT");
		$strSQL = "SELECT CONCAT('".IMAGES_AS_URL."',med.url) AS src FROM ".PREFIX."_media AS med WHERE med.id_MED=".$_GET["id"]."";
	}
	try
	{
		$objConn = MySQL::getIstance();
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 1)
		{
			while($obj = $rs->fetchObject())
			{
				$arrResp[] = $obj;
			}
		}
		elseif ($rs->count() > 0)
		{
			if($doArray)
			{
				$arrResp[] = $rs->fetchObject();
			}
			else
			{
				$arrResp = $rs->fetchObject();	
			}
		}
		else
		{
			$arrResp[0] = "EMPTY";
		}
	}

	catch(Exception $e)
	{
		$arrResp[0] = "KO";
	}
}
echo json_encode($arrResp);
?>