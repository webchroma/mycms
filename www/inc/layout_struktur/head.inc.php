<?php
if ($web_title == "")
  $web_title = WEB_TITLE;
?>
<!DOCTYPE html>
<html lang="<?= $cmslan ?>">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="referrer" content="no-referrer" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="index,follow" />
  <meta name="googlebot" content="index,follow" />
  <meta name="geo.region" content="IT-MI" />
  <meta name="geo.placename" content="Milan" />
  <meta name="revisit-after" content="30 days" />
  <meta name="DC.title" content="<?= $web_title ?>" />
  <meta name="description" content="<?php if (isset($meta_des)) echo $meta_des ?>" />
  <meta name="keywords" content="<?php if (isset($meta_key)) echo $meta_key ?>" />
  <title><?= $web_title ?></title>
  <link rel="stylesheet" href="/css/scripts/normalize.3.0.3.css" type="text/css" />
  <link rel="stylesheet" href="/css/scripts/video-js.4.12.15.min.css" />
  <link rel="stylesheet" href="/css/scripts/owl.carousel.1.3.3.min.css" />
  <link rel="stylesheet" href="/css/scripts/owl.theme.1.3.3.min.css" />
  <link rel="stylesheet" href="/css/scripts/jquery.fancybox.3.2.0.min.css" />
  <link rel="stylesheet" href="/css/fonts.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" />
  <!--[if lt IE 9]>
      <script src="/js/scripts/html5shiv.3.7.2.min.js"></script>
      <script src="/js/scripts/respond.1.4.2.min.js"></script>
    <![endif]-->
</head>
<?php
$s == "" ? $cls = "start" : $cls = $s;
?>

<body class="<?= $cls ?>">
  <div id="wrapper">