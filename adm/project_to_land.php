<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(isset($_POST["params"]))
{
	$params = explode("#",$_POST["params"]);
	if($params[1]==0) // delete reference
		$strSQL = "DELETE FROM ".PREFIX."_project_land WHERE id_PROJ=".(int)$params[0];
	else // insert reference
		$strSQL = "INSERT INTO ".PREFIX."_project_land (id_PROJ,id_LAND) VALUES (".(int)$params[0].",".(int)$params[1].")
				   ON DUPLICATE KEY UPDATE id_LAND=".(int)$params[1].", id_PROJ=".(int)$params[0];
    echo $strSQL;
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