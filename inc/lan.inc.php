<?php
if(!isset($objConn)) 
	$objConn = MySQL::getIstance();

/* if no language passed, use default from the database or from config file */
if(isset($_GET["l"]))
	$cmslan = $_GET["l"];
else
{
	// if no language is given, check default language
	$strSQL = "SELECT web_lingua FROM ".PREFIX."_preferences";
	$rs = $objConn->rs_query($strSQL);
	if($rs->count() == 0)
		$cmslan = CMSLAN;
	else
	{
		$rowL = $rs->fetchAssocArray();
		$cmslan = $rowL["web_lingua"];
	}
}

if(file_exists(INCPATH."/inc/languages/$cmslan.inc.php"))
	include_once("languages/$cmslan.inc.php");
else
	include_once("languages/en.inc.php");

/* umlaute _ to solve problems with strtoupper when umlaute remains small */

function getUm($str)
{
	$str = str_replace("ä","&Auml;",$str);
	$str = str_replace("ö","&Ouml;",$str);
	$str = str_replace("ü","&Uuml;",$str);
	return $str;
}

/* filename friendly umlaute and specialsigns -> ö = oe || é -> e */
$arrSigns["ä"] = "ae";
$arrSigns["ö"] = "oe";
$arrSigns["ü"] = "ue";
$arrSigns["Ä"] = "ae";
$arrSigns["Ö"] = "oe";
$arrSigns["Ü"] = "ue";
$arrSigns["ß"] = "ss";
$arrSigns["à"] = "a";
$arrSigns["é"] = "e";
$arrSigns["è"] = "e";
$arrSigns["À"] = "a";
$arrSigns["É"] = "e";
$arrSigns["È"] = "e";
$arrSigns["ì"] = "i";
$arrSigns["Ì"] = "i";
$arrSigns["ù"] = "u";
$arrSigns["Ù"] = "u";

function doUrlUm($str)
{
	global $arrSigns;
	foreach($arrSigns AS $key=>$value)
	{
		if(strstr($str,$key)) $str = str_replace($key,$value,$str);
	}
	return $str;
}
?>