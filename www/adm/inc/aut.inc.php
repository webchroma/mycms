<?php
session_start();
if(!isset($_SESSION["loga"]))
{
	include_once("logO.inc.php");
	exit;	
}
// set language for user
$cmslan = $_SESSION["usr"]["lan"];
include_once("fx.inc.php");
include_once("inc/tracking.inc.php");
?>