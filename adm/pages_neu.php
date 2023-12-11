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

$stridZONA = "";
$id_ZONA = null;

if(isset($_REQUEST["id_ZONA"]))
	$stridZONA = "id_ZONA=".$_REQUEST["id_ZONA"]."";
else
	$_REQUEST["id_ZONA"] = null;

if(isset($_POST["submit"]))
{
	$errMSG=check_empty_fields($_POST,$arrTextes);
	if($errMSG=="")
	{
		isset($_REQUEST["id_ZONA"]) ? settype($_REQUEST["id_ZONA"],"INT") : $_REQUEST["id_ZONA"] = 0;
		settype($_POST["parent_id"],"INT");
		$_POST["parent_id"]==0 ? $open=1 : $open=0;
		$strSQL = "INSERT INTO ".PREFIX."_pages (pos,parent_id,open,symlink) 
				   VALUES ((SELECT IFNULL(MAX(pg.pos)+1,1) FROM ".PREFIX."_pages AS pg LEFT JOIN ".PREFIX."_struktur_str_page AS strpg ON pg.id_PAG=strpg.id_PAG WHERE parent_id=".$_POST["parent_id"]." AND strpg.id_ZONA=".$_REQUEST["id_ZONA"]."),".$_POST["parent_id"].",$open,'".$objConn->prepMysql(strtolower($_POST["symlink"]))."')";
		try
		{
			$objConn->stopCOMMIT();

			if($objConn->i_query($strSQL))
			{
				$pagID = $objConn->getInsertID();
				
				foreach($arrL AS $value)
				{
					$strSQL = "INSERT INTO ".PREFIX."_pages_text (id_PAG,lan,name,texto,web_title,meta_key,meta_des) 
							   VALUES ($pagID,'$value','".$objConn->prepMysql($_POST[$value."_name"])."','".$objConn->prepMysql($_POST[$value."_txt"])."',
							'".$objConn->prepMysql($_POST[$value."_web_title"])."','".$objConn->prepMysql($_POST[$value."_meta_key"])."','".$objConn->prepMysql($_POST[$value."_meta_des"])."')";
					$objConn->i_query($strSQL);
				}
				if(!is_null($_REQUEST["id_ZONA"]))
				{
					settype($_REQUEST["id_ZONA"],"INT");
					$strSQL = "INSERT INTO ".PREFIX."_struktur_str_page (id_ZONA,id_PAG) VALUES(".$_REQUEST["id_ZONA"].",$pagID)";
					$objConn->i_query($strSQL);
				}			
				trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST[$cmslan."_name"].") ".$arrTextes["tracking"]["insert"]."");
				$objConn->doCOMMIT(true);
				header("location:".THISMAINPAGENAME.".php?id_ZONA=".$_REQUEST["id_ZONA"]);
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
				<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST["parent_id"]?>">
				<input type="hidden" name="id_ZONA" id="id_ZONA" value="<?=$_REQUEST["id_ZONA"]?>">
				<ul class="form">
					<li>
						<?=$arrTextes["pages"]["symlink"]?>
						<br />
						<input type="text" name="symlink" id="symlink" value="" size="25" maxlength="50" />
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
		</div>
	</div>
</body>
</html>