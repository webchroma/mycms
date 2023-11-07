<?php
include_once("inc/header.inc.php");
include_once("mysql.class.inc.php");
/*
$_POST["id_MED"] = 5;
$_POST["isH"] = 0;
$_POST["id_PAG"] = 34;
*/
if(isset($_POST["id_MED"]) && isset($_POST["isH"]) && isset($_POST["id"]))
{
	if($_POST["id_MED"]!=0 && $_POST["id"]!=0)
	{
		settype($_POST["id_MED"],"INT");
		settype($_POST["id"],"INT");
		settype($_POST["isH"],"INT");
		if($_POST["isH"]==1)
		{
			$strSQL = "DELETE FROM ".PREFIX."_".$_POST["tbl_name"]."_media WHERE id_MED=".$_POST["id_MED"]."";
		}
		else
		{
			$strSQL = "INSERT INTO ".PREFIX."_".$_POST["tbl_name"]."_media (".$_POST["idName"].",id_MED) VALUES (".$_POST["id"].",".$_POST["id_MED"].")";
		}
		try
		{
			$objConn = MySQL::getIstance();
			if($objConn->i_query($strSQL))
			{
				$arrResp[0] = "OK";
			}
			else
			{
				$arrResp[0] = "KO";
			}
		}

		catch(Exception $e)
		{
			$arrResp[0] = "KO";
		}
	}
	else
	{
		$arrResp[0] = "EMPTY";
	}
}
else
{
	$arrResp[0] = "EMPTY";
}
echo json_encode($arrResp);
?>