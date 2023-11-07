<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_neu.php","",PAGENAME));
try{
	$objConn = MySQL::getIstance();
}
catch(Exception $e)
{
	$errMSG = captcha($e);
}
if(isset($_POST["submit"]))
{
	$errMSG=check_empty_fields($_POST,$arrTextes);
	if($errMSG=="")
	{
		$strSQL = "INSERT INTO ".PREFIX."_gallery (aktiv,symlink) VALUES (0,'".$objConn->prepMysql(strtolower($_POST["symlink"]))."')";
		try
		{
			$objConn->stopCOMMIT();

			if($objConn->i_query($strSQL))
			{
				$gallID = $objConn->getInsertID();
				
				foreach($arrL AS $value)
				{
					$strSQL = "INSERT INTO ".PREFIX."_gallery_text (id_GALL,lan,name,texto,web_title,meta_key,meta_des) VALUES ($gallID,'$value','".$objConn->prepMysql($_POST[$value."_name"])."','".$objConn->prepMysql($_POST[$value."_txt"])."','".$objConn->prepMysql($_POST[$value."_web_title"])."','".$objConn->prepMysql($_POST[$value."_meta_key"])."','".$objConn->prepMysql($_POST[$value."_meta_des"])."')";
					$objConn->i_query($strSQL);
				}				
				trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST[$cmslan."_name"].") ".$arrTextes["tracking"]["insert"]."");
				$objConn->doCOMMIT(true);
				header("location:".THISMAINPAGENAME."_edit.php?id_GALL=$gallID");
			}
			else
			{
				$objConn->doCOMMIT(false);
				$errMSG = $arrTextes["errors"]["insert"];
			}
		}
		catch(Exception $e)
		{
			$objConn->doCOMMIT(false);
			$errMSG = captcha($e);
		}
	}
}
include_once("inc/page-head.inc.php");
?>
<body>
	<div id="submenu">
		<?php
		echo "<a href=\"".THISMAINPAGENAME.".php\">".strtoupper(THISMAINPAGENAME)." ".$arrTextes["admin"]["admin"]."</a> / ".$arrTextes["admin"]["new"]." ".strtoupper(THISMAINPAGENAME);
		?>
	</div>
	<div id="mainpage">
		<div id="errBox"<?php if(isset($errMSG)) {echo " class=\"errLabel\">".$errMSG.""; }else{ echo ">";}?></div>
		<div class="form_two_columns">
			<h1><?=$arrTextes["forms"]["titlemessage"]?></h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="nnews" id="nnews">
				<ul class="form">
					<li>
						<?=$arrTextes["pages"]["symlink"]?>
						<br />
						<input type="text" name="symlink" id="symlink" value="" size="10" maxlength="10" />
						<br />
						<?=$arrTextes["pages"]["symlink_txt"]?>
					</li>
				</ul>
				<?php
				include_once("inc/text_fields.inc.php");
				?>
				<br class="break" />
				<p><?=$arrTextes["prefs"]["meta_explanation"]?><br /><strong><?=$arrTextes["forms"]["meta_info_title_extra"]?></strong></p>
				<br />
				
				<p><?=$arrTextes["prefs"]["meta_explanation_title"]?></p>
				<br />
				<p><?=$arrTextes["prefs"]["meta_explanation_keys"]?></p>
				<br />
				<p><?=$arrTextes["prefs"]["meta_explanation_des"]?></p>
				<br />
				<ul class="form">
					<li class="centerbold noborder">
						<input type="submit" class="ui-button ui-widget subbtn" name="submit" id="submit" value="<?=$arrTextes["forms"]["modify"]?>" />
					</li>
				</ul>
			</form>

			<div id="invi"></div>
		</div>
	</div>
</body>
</html>