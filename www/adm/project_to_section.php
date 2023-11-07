<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(isset($_POST["params"]))
{
	$params = explode("#",$_POST["params"]);
	if($_POST["a"]=="del") // delete reference
		$strSQL = "DELETE FROM ".PREFIX."_project_section WHERE id_PROJ=".(int)$params[0]." AND id_SEC=".(int)$params[1];
	else // insert reference
		$strSQL = "INSERT INTO ".PREFIX."_project_section (id_PROJ,id_SEC) VALUES (".(int)$params[0].",".(int)$params[1].")
				   ON DUPLICATE KEY UPDATE id_SEC=".(int)$params[1].", id_PROJ=".(int)$params[0];
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