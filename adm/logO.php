<?php
session_start();
if(!isset($_SESSION["loga"]))
{
	header("location: index.php");
	exit;
}
include_once("inc/header.inc.php");
include_once("mysql.class.inc.php");
include_once("fx.inc.php");
include_once("inc/tracking.inc.php");
trackUSR($_SESSION["usr"]["id_USR"],"LOGOUT");
$_SESSION["loga"] = "";
$_SESSION["usr"] = "";
session_destroy();
header("location: index.php");
?>