<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(isset($_POST["params"]))
{
	$params = explode("#",$_POST["params"]);
	if($params[1]==0) // delete reference
		$strSQL = "DELETE FROM ".PREFIX."_pages_gallery WHERE id_GALL=".(int)$params[0];
	else // insert reference
		$strSQL = "INSERT INTO ".PREFIX."_pages_gallery (id_PAG,id_GALL) VALUES (".(int)$params[1].",".(int)$params[0].")
				   ON DUPLICATE KEY UPDATE id_GALL=".(int)$params[0].", id_PAG=".(int)$params[1];
	include_once("fx.inc.php");
	include_once("mysql.class.inc.php");
	if(!isset($objConn)) $objConn = MySQL::getIstance();
	if(!$objConn->i_query($strSQL))
		echo "THERE WAS A PROBLEM";
	else
		echo "REFERENCE WAS SETTED";
}
else
	echo "THERE WAS A PROBLEM";
?>