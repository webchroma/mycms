<?php
include_once("header.inc.php");
include_once("mysql.class.inc.php");
//$_POST["tm"] = "1273096800";
if(isset($_POST["tm"]))
{
	$strSQL = "SELECT 'kalender' AS typ, calinfo.name 
				FROM ".PREFIX."_calendar AS cal LEFT JOIN ".PREFIX."_calendar_info AS calinfo ON cal.id_CAL=calinfo.id_CAL 
				WHERE cal.tm=".$_POST["tm"]." AND calinfo.lan='$cmslan'
				UNION
				SELECT nwspara.zona AS typ, nws.name 
				FROM ".PREFIX."_calendar AS cal LEFT JOIN ".PREFIX."_calendar_nws AS calnws ON cal.id_CAL=calnws.id_CAL 
				LEFT JOIN ".PREFIX."_news_text AS nws ON calnws.id_NWS=nws.id_NWS 
				LEFT JOIN ".PREFIX."_news AS nwspara ON nws.id_NWS=nwspara.id_NWS 
				WHERE cal.tm=".$_POST["tm"]." AND nws.lan='$cmslan'";
	//echo $strSQL;
	try
	{
		$objConn = MySQL::getIstance();
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			foreach($rs AS $row)
			{
				$arrResp[$row->typ][] = $row->name;
			}
		}
		else
		{
			$arrResp[0] = "EMPTY";
		}
	}

	catch(Exception $e)
	{
		$arrResp[0] = "KO";
		if($debug)
			$arrResp[0] = captcha($e);
	}
	echo json_encode($arrResp);
}