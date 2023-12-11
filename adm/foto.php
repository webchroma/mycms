<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(!defined("THISMAINPAGENAME")) define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
include_once("inc/page-head.inc.php");
?>
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
		$strWHERE = " WHERE med.tipo='img'";
		include("inc/media.inc.php");
		?>
	</div>
</body>
</html>