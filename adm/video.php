<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(!defined("THISMAINPAGENAME")) define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
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
<link href="js/jquery/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/uploadify.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script type="text/javascript" src="js/jquery/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="js/jquery/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript" src="js/adm.js"></script>
</head>
<body>
	<div id="submenu"><?=$arrTextes["media"]["title"]?></div>
	<div id="mainpage">
		<?php
		$isFOTO = false;
		$isVIDEO = false;
		$isEXTVID = false;
		$isDATA = false;
		$isFOLD = false;
		$strWHERE = " WHERE med.tipo='vid'";
		include("inc/media.inc.php");
		?>
	</div>
</body>
</html>