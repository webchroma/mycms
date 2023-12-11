<?php
header("Cache-Control: no-store"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
header("Content-type: text/html; charset=utf-8");

/* for debugging. set $debug true to display complete error messages */
global $debug;
$debug = true;

if(!$debug)
{
	error_reporting(0);
}
else
{
	error_reporting(E_ALL);
}

global $localMachine;
if($_SERVER["HTTP_HOST"]=="localhost")
{
	define("INCPATH","/Users/piero/_DATA/_WEB-WORK/GRT/v.2/web");
    $localMachine = true;
}
else
{
	define("INCPATH","/www/htdocs/***/grtitalia");
    $localMachine = false;
}
set_include_path(get_include_path().PATH_SEPARATOR.INCPATH."/inc");
include_once("conf.inc.php");
include_once("mysql.class.inc.php");
include_once("lan.inc.php");
include_once("exept.inc.php");
