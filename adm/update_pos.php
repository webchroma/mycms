<?php 
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(isset($_POST["action"])&&$_POST["action"]=="updatePos")
{
	include_once("fx.inc.php");
	include_once("mysql.class.inc.php");
	if(!isset($objConn)) $objConn = MySQL::getIstance();
	$i = 1;
	$strMSG = "<div class=\"okLabel\" style=\"width:240px\">".$arrTextes["data"]["pos_update"]."</div>";
	foreach ($_POST["arrPOS"] as $val) 
	{
		$strSQL = "UPDATE ".PREFIX."_media SET pos=$i WHERE id_MED=".$val;
        if(!$objConn->i_query($strSQL))
			$strMSG = "<div class=\"errLabel\" style=\"width:280px\">".$arrTextes["data"]["pos_no_update"]."</div>";
		$i++;	
	}
	trackUSR($_SESSION["usr"]["id_USR"],"PORTFOLIO (".$_POST["gl"].") ".$arrTextes["data"]["pos_update"]."");
	echo $strMSG;
}
?>