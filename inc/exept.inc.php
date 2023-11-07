<?php
function captcha($e)
{
	global $arrTextes;
	if($GLOBALS['debug'])
	{
		return $e->getMessage();
	}
	else
	{
		$errMSG = $arrTextes["errors"]["general"];
		return $errMSG;
	}
}
?>