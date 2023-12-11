<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
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
<script type="text/javascript">
	$(document).ready(function()
	{
		$("#jsena").addClass("gruen");
		$("#jsena").html("<strong><?=$arrTextes["browser"]["active"]?></strong>");
	});
</script>
</head>

<body>
	<div id="mainpage">
		<div class="form_one_column" style="margin-top:50px; width:600px">
			<ul class="form">
				<li><?=$arrTextes["browser"]["info"]?></li>
				<li><strong>BROWSER</strong><br />
					Safari > 4 (osx)<br />
					Chrome (osx - windows)<br />
					Firefox > 2 (osx - windows)<br />
					Internet Explorer > 7 (windows)
				</li>
				<li></li>
				<li><strong>JAVASCRIPT</strong><br />
					<div id="jsena" class="red"><strong><?=$arrTextes["browser"]["notactive"]?></strong></div>
				</li>
			</ul>
				
		</div>
	</div>
</body>
</html>