<?php
/* get preferences */
$strSQL = "SELECT prf.*,
			(SELECT web_title FROM ".PREFIX."_preferences_metatext WHERE lan='$cmslan') AS web_title,
 			(SELECT meta_key FROM ".PREFIX."_preferences_metatext WHERE lan='$cmslan') AS meta_key,
			(SELECT meta_des FROM ".PREFIX."_preferences_metatext WHERE lan='$cmslan') AS meta_des
			FROM ".PREFIX."_preferences AS prf";
try
{
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$row = $rs->fetchAssocArray();
		foreach($row AS $key=>$value)
			define(strtoupper($key),$value,true);
	}
}
catch(Exception $e)
{
	echo captcha($e);
}

// META TAGS _ first set defaults
$web_title = WEB_TITLE;
$meta_des = META_DES;
$meta_key = META_KEY;

/* get fixed section-pages IDs AND symlinks */
$strSQL = "SELECT id_PAG, symlink, home, blog FROM ".PREFIX."_pages";
try
{
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		while($row = $rs->fetchObject())
		{
			if($row->home)
			{
				define("HOME_ID",$row->id_PAG);
				define("HOME_SYMLINK",$row->symlink);
			}
			if($row->blog)
			{
				define("NWS_ID",$row->id_PAG);
				define("NWS_SYMLINK",$row->symlink);
			}
			if($row->symlink=="impressum")
			{
				define("IMP_ID",$row->id_PAG);
				define("IMP_SYMLINK",$row->symlink);
			}
		}	
	}
}
catch(Exception $e)
{
	echo captcha($e);
}

// website is stopped
if(WEB_STOP)
	include_once("inc/page_module/webstop.inc.php");
	
// website too old -> IE < 7
if(isset($_GET["ie"]))
	include_once("inc/noie.inc.php");

// check fo section
isset($_REQUEST["s"]) ? $s = $_REQUEST["s"] : $s = null; // no section given
// check for pages
isset($_REQUEST["p"]) ? $p = $_REQUEST["p"] : $p = $s; // homepage
isset($_REQUEST["pp"]) ? $pp = $_REQUEST["pp"] : $pp = null;
isset($_REQUEST["ppp"]) ? $ppp = $_REQUEST["ppp"] : $ppp = null;
isset($_REQUEST["med"]) ? $med = $_REQUEST["med"] : $med = null;

// "<br><br><br><br><br><br><br><br><br><br><br><br>s:".$p."/p:".$p."/pp:".$pp."/ppp:".$ppp."/med:".$med."";

if(!FLATLINK)
{
    settype($s,"INT");
	settype($p,"INT");
	settype($pp,"INT");
	settype($ppp,"INT");
	$pageurl = $_SERVER["QUERY_STRING"];
}
else
	if(isset($_SERVER["REDIRECT_URL"]))
		$pageurl = $_SERVER["REDIRECT_URL"];
	else
		$pageurl = "";

if((START_FIX_START)||$p!=null) // if homepage is from pages || we have a page => show
{
    // homepage
	if($s==null)
		include_once("readdb_pages.inc.php");
    // is a page or gallery
	if($s=="page"||$s=="nws")
		include_once("readdb_pages.inc.php");
	if($s=="project")
		include_once("readdb_section.inc.php");
	// is a section or project
}
else // home page is a gallery or portfolio
{
	if(START_RAND_MED)
		$strSQL = "SELECT md.* FROM ".PREFIX."_media AS md WHERE tipo='img' ORDER BY rand()";
	elseif(START_RAND_GAL)
	{
		$arr_start_id_GALL=explode("#",START_ID_GALL);
		$strSQL = "SELECT gln.name, gln.texto, med.id_MED, med.url, med.tipo,
					(SELECT name FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='$cmslan') AS medname
					FROM ".PREFIX."_gallery_text AS gln
					LEFT JOIN ".PREFIX."_gallery AS db ON gln.id_GALL=db.id_GALL
					LEFT JOIN ".PREFIX."_gallery_media AS glm ON gln.id_GALL=glm.id_GALL
					LEFT JOIN ".PREFIX."_media AS med ON med.id_MED=glm.id_MED
					WHERE db.id_GALL=".$arr_start_id_GALL[0]." AND gln.lan='$cmslan' ORDER BY rand()";
	}
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$i=0;
		while($row = $rs->fetchObject())
		{
			$arridMED[$i] = $row->id_MED;
			$arrIMGS[$row->id_MED]["url"] = $row->url;
			$arrIMGS[$row->id_MED]["type"] = $row->tipo;
			$arrIMGS[$row->id_MED]["caption"] = $row->medname;
			$i++;
		}
	}
}
?>