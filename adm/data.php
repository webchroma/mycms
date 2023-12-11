<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(!defined("THISMAINPAGENAME")) define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
include_once("inc/page-head.inc.php");
?>
</head>
<body>
	<div id="submenu"><?=$arrTextes["media"]["title_data"]?></div>
	<div id="mainpage">
		<?php
		$isFOTO = false;
		$isVIDEO = false;
		$isEXTVID = false;
		$isDATA = false;
		$isFOLD = true;
		$strWHERE = " WHERE med.tipo='data'";
		include("inc/media.inc.php");
		?>
	</div>
</body>
</html>