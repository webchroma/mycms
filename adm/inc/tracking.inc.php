<?php
function trackUSR($id,$location)
{
	$objConn = MySQL::getIstance();
	$location = $objConn->prepMysql($location);
	$strSQL = "INSERT INTO ".PREFIX."_admin_tracking (id_USR,info) VALUES ($id,'".$location."')";
	$objConn->i_query($strSQL);
}
?>