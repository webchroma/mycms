<?php
if($web_title=="")
	$web_title = WEB_TITLE;
	// <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
?>
<!DOCTYPE html>
<html>
<head lang="<?=$cmslan?>">
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="index,follow" />
<meta name="geo.region" content="DE-BE" />
<meta name="geo.placename" content="Berlin" />
<meta name="revisit-after" content="30 days" />
<meta name="DC.title" content="<?=$web_title?>" /> 
<meta name="description" content="<?php if(isset($meta_des)) echo $meta_des?>" />
<meta name="keywords" content="<?php if(isset($meta_key)) echo $meta_key?>" />
<title><?=$web_title?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/4.12.15/video-js.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.0/jquery.fancybox.min.css" />
<link rel="stylesheet" href="/css/fonts.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" />
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<?php
$s==""?$cls="start":$cls=$s;
?>
<body class="<?=$cls?>">
	<div id="wrapper">