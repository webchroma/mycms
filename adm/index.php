<?php
session_start();
include_once("inc/header.inc.php");

if(isset($_POST["login"]))
{
	if($_POST["usr"]!="" && $_POST["pwd"]!="")
	{
		include_once("fx.inc.php");
		if(AlfaNum($_POST["usr"]) && AlfaNum($_POST["pwd"]))
		{
			include_once("conf.inc.php");
			include_once("mysql.class.inc.php");
			$objConn = MySQL::getIstance();
			$objConn->prepMysql($_POST["usr"]);
			$objConn->prepMysql($_POST["pwd"]);
			$strSQL = "SELECT adm.id_USR, CONCAT(adm.nname,' ',adm.vname) AS name, admzu.id_ZONA, adm.lan
				   FROM ".PREFIX."_admin AS adm LEFT JOIN ".PREFIX."_admin_usr_zone AS admzu ON adm.id_USR=admzu.id_USR
				   WHERE adm.usr='".$_POST["usr"]."' AND adm.pwd='".$_POST["pwd"]."' AND adm.aktiv";
			try
			{
				$rs = $objConn->rs_query($strSQL);	
				if ($rs->count() > 0)
				{
					$i=0;
					while($user = $rs->fetchObject())
					{
						if($i==0)
						{
							$_SESSION["usr"]["id_USR"] = $user->id_USR;
							$_SESSION["usr"]["name"] = $user->name;
							$_SESSION["usr"]["lan"] = $user->lan;
						}
						if(!is_null($user->id_ZONA))
							$_SESSION["usr"]["zona"][$i] = $user->id_ZONA;
						$i++;
					}
					$_SESSION["loga"] = true;
					include_once("inc/tracking.inc.php");
					trackUSR($_SESSION["usr"]["id_USR"],"LOGIN");
					header("location:admin.php");
				}
				else
				{
					$strMSG = $arrTextes["login"]["nouser"];
				}
			}
			
			catch(Exception $e)
			{
				$strMSG = captcha($e);
			}
		}
		else
		{
			$strMSG = $arrTextes["login"]["alpha"];
		}
	}
	else
	{
		$strMSG = $arrTextes["login"]["empty"];
	}
}
?>
<!DOCTYPE html>
<html>
<head lang="<?=$cmslan?>">
<meta charset="UTF-8" />
<meta name="robots" content="no-index,no-follow" />
<meta name="googlebot" content="no-index,no-follow" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title><?=WEBTITLE?></title>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css" type="text/css" />
<link href="css/adm.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#usr").focus();
	 });
</script>
</head>
<body>
	<div style="position: absolute; left: 50%; top: 150px; margin-left: -180px; width: 360px; height: 300px;">
		<div style="width:100%;text-align:right; border-bottom:solid 1px #06F;">
		LOGIN
		</div>
		<br />
		<form action="index.php" method="post" name="frmlogin" id="frmlogin">
			<input name="usr" type="text" id="usr" size="15" maxlength="100" style="line-height:2;height:20px" /> <?=$arrTextes["login"]["formuser"]?>
			<br />
			<br />
			<input name="pwd" type="password" id="pwd" size="15" maxlength="150" style="line-height:2;;height:20px" /> <?=$arrTextes["login"]["formpassword"]?>
			<br />
			<br />
			<input type="submit" name="login" id="login" value="<?=$arrTextes["login"]["formenter"]?>" class="btn" style="float:right;clear:both;" />
			<br />
		</form>
		<?php
		if(isset($strMSG)) echo "<div id=\"errmsg\" style=\"width:100%; border-top:solid 1px #000;color:#F00;margin-top:25px;padding-top:5px;\">".strtoupper($strMSG)."</div>";
		?>
	</div>
</body>
</html>