<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
$_SESSION["news_zona"] = "";
if(!isset($_GET["zona"]))
{
	echo $arrTextes["errors"]["nopage"];
	exit;
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript" src="js/adm.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		var d = $("li:first-child").attr("id").explode("_");
		$.openSec(d[1]);
		$("li:last-child").addClass("submenulast");
	});
</script>
</head>

<body>
	<div id="submenu_tabs">
		<?php
		settype($_GET["zona"],"INT");
		$strSQL = "SELECT name FROM ".PREFIX."_admin_zone WHERE zona=".$_GET["zona"]." AND subzona!=0 AND aktiv ORDER BY subzona";
		$objConn = MySQL::getIstance();
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			echo "<ul class=\"submenu\">";
			while($zona = $rs->fetchObject())
			{
				$sname = str_replace(" ","",doUrlUm($zona->name));
				echo "<li id=\"li_$sname\" class=\"mainmenu\"><a class=\"amenu\" id=\"a_$sname\" href=\"".strtolower($sname).".php\" target=\"subframe\">".$zona->name."</a></li>";
			}
			echo "</ul>";
		}
		?>
	</div>
	<div id="subframecontainer">
		<iframe id="subframe" name="subframe" class="mainframe" frameborder="0"></iframe>
	</div>
</body>
</html>