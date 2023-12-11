<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_delete.php","",PAGENAME));
if(!isset($_REQUEST["id_ZONA"]))
	$_REQUEST["id_ZONA"] = null;
if(!isset($_REQUEST["id_PAG"]) && $_REQUEST["id_PAG"]=="")
{
	header("location:".THISMAINPAGENAME."_neu.php?id_ZONA=".$_REQUEST["id_ZONA"]);
}

if(isset($_POST["submit"]) && isset($_POST["id_PAG"]))
{
	$objConn = MySQL::getIstance();
	$strSQL = "DELETE FROM ".PREFIX."_pages WHERE id_PAG=".$_POST["id_PAG"]." OR parent_id=".$_POST["id_PAG"]."";
	$objConn->i_query($strSQL);
	trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST["name"].") ".$arrTextes["tracking"]["delete"]."");
	header("location:".THISMAINPAGENAME.".php?id_ZONA=".$_REQUEST["id_ZONA"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=WEBTITLE?></title>
<link href="css/adm.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="css/ie7.css"></link>
<![endif]-->
</head>

<body>
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php?id_ZONA=<?=$_REQUEST["id_ZONA"]?>"><?=$arrTextes["pages"]["title"]?></a></div>
	<div id="mainpage">
		<div class="form_one_column">
			<h1>Die Seite (<?=strtoupper("".$_REQUEST["name"]."")?>) wirklich löschen?<br />Alle unterseite werden gelöscht</h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="nnews" id="nnews">
				<input type="hidden" name="id_ZONA" value="<?=$_REQUEST["id_ZONA"]?>" />
				<input type="hidden" name="id_PAG" value="<?=$_REQUEST["id_PAG"]?>" />
				<input type="hidden" name="name" value="<?=$_REQUEST["name"]?>" />
				<ul>
					<li class="separator">&nbsp;</li>
					<li><input type="submit" class="submit" name="submit" id="submit" value="<?=$arrTextes["help"]["delete"]?>" /></li>
				</ul>
			</form>
		</div>
	</div>
</body>
</html>