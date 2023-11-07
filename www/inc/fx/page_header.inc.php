<?php
/* for debugging. set $debug true to display complete error messages */
global $debug;
$debug = true;

if (!$debug)
	error_reporting(0);
else
	error_reporting(E_ALL);

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

$objConn = MySQL::getIstance();
include_once("fx.inc.php");
include_once("lan.inc.php");
include_once("exept.inc.php");

// get last update on database
$strSQL = "SELECT max(datum) AS dt FROM ".PREFIX."_admin_tracking";
$rs = $objConn->rs_query($strSQL);
if ($rs->count() > 0)
{
	$row = $rs->fetchAssocArray();
	define("LASTUPD",$row["dt"]);
}
// start serving the page / Headers
header("Cache-Control: public, max-age=2592000");
header("Expires: ".gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
if(!$debug)
	header("Last-Modified: " . gmdate('D, d M Y H:i:s \G\M\T',strtotime(LASTUPD))); // get last timestamp from DB
if(isset($_GET["rss"])&&$_GET["rss"])
	header("Content-Type: application/xml; charset=utf-8");
else
	header("Content-type: text/html; charset=utf-8");
