<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
if(!defined("THISMAINPAGENAME")) define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
$objConn = MySQL::getIstance();
$l = null;
if(isset($_POST["change"]))
{
	$doIns = true;
	$strSQL = "UPDATE ".PREFIX."_preferences SET";
	foreach($_POST as $key=>$value) 
	{
		if($key!="change")
		{
			if($value=="" && $key!="start_id_GALL" && (strrpos($key, "meta")===false) && (strrpos($key, "lan")===false) && (strrpos($key, "webtitle")===false))
			{
				$doIns = false;
				$errMSG = $arrTextes["forms"]["allfields"];
			}
			else
			{
				if($key=="start_rand_gal" && $value)
				{
					if($_POST["start_id_GALL"]=="")
					{
						$doIns = false;
						$errMSG = $arrTextes["prefs"]["chs_gal"];
					}
				}
				if($key!="start_id_GALL" && $key!="media_thb_ref" && (strrpos($key, "web")===false) && (strrpos($key, "meta")===false) && (strrpos($key, "lan")===false))
				{
					
					//settype($value,"INT");
					$strSQL .= " $key=$value,";
				}	
				else
				{
					if($key=="start_id_GALL" && $value=="")
						$value = "";
					$objConn->prepMysql($_POST[$key]);
					if((strrpos($key, "lan")===false) && (strrpos($key, "meta-")===false))
						$strSQL .= " $key='$value',";
				}
				
				// check meta keywords and description
				
				if(strrpos($key, "meta-")!==false)
				{
					// get languages
					$arrV=explode("_",$key);
					if($l!=$arrV[0])
					{
						$arrL[]=$arrV[0];
						$l=$arrV[0];
					}
				}
			}
		}
	}
	if($doIns)
	{
		$objConn->stopCOMMIT();
		
		// insert preferences
		$strSQL = rtrim($strSQL,',');
		if(!isset($_POST["start_id_GALL"]))
			$_POST["start_id_GALL"] = "";
		if(!$_POST["start_rand_gal"])
			$strSQL = str_replace("id_GALL='".$_POST["start_id_GALL"]."'","id_GALL=''",$strSQL);
		$objConn->i_query($strSQL);
		// setting meta keywords and description
		foreach($arrL AS $value)
		{
			$strSQL = "INSERT INTO ".PREFIX."_preferences_metatext (lan,web_title,meta_key,meta_des) 
						VALUES ('$value','".$objConn->prepMysql($_POST[$value."_meta-webtitle"])."','".$objConn->prepMysql($_POST[$value."_meta-key"])."','".$objConn->prepMysql($_POST[$value."_meta-des"])."')
			  			ON DUPLICATE KEY UPDATE web_title='".$objConn->prepMysql($_POST[$value."_meta-webtitle"])."',
						meta_key='".$objConn->prepMysql($_POST[$value."_meta-key"])."',
						meta_des='".$objConn->prepMysql($_POST[$value."_meta-des"])."'";
			$objConn->i_query($strSQL);
		}
		
		// updating default language informations
		foreach($arrL AS $value)
		{
			if($_POST[$value."_lan-name"]!="" && $_POST[$value."_lan-notext"]!="")
			{
				$strSQL = "UPDATE ".PREFIX."_languages 
							SET name='".$objConn->prepMysql($_POST[$value."_lan-name"])."', notext='".$objConn->prepMysql($_POST[$value."_lan-notext"])."'
							WHERE lan='$value'";
				$objConn->i_query($strSQL);
			}
		}
		
		// insert new language
		
		if($_POST["new_lan-lan"]!="" && $_POST["new_lan-name"]!="" && $_POST["new_lan-notext"]!="")
		{
			$nLan = $objConn->prepMysql($_POST["new_lan-lan"]);
			$nName = strtoupper($objConn->prepMysql($_POST["new_lan-name"]));
			$nTxt = $objConn->prepMysql($_POST["new_lan-notext"]);
			$strSQL = "INSERT INTO ".PREFIX."_languages (lan,name,notext,aktiv) VALUES ('$nLan','$nName','$nTxt',0)";
			$objConn->i_query($strSQL);
			
			// update pages_text and preference_metatext tables with new language
			
			$strSQL = "INSERT INTO ".PREFIX."_pages_text (id_PAG,lan,name,texto) (SELECT id_PAG, '$nLan' AS lan, '$nTxt' AS name, '$nTxt' AS texto FROM ".PREFIX."_pages_text WHERE lan='$cmslan')";
			$objConn->i_query($strSQL);
		}
		
		$objConn->doCOMMIT(true);
		$errMSG = $arrTextes["forms"]["ismodified"];
		$bolKO = false;
		
	}
}

if(isset($_GET["lan"]) && isset($_GET["akt"]))
{
	if($_GET["akt"]=="dea")
	{
		if(isset($_GET["isA"]))
		{
			$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
			$strSQL = "UPDATE ".PREFIX."_languages SET aktiv=".$_GET["isA"]." WHERE lan='".$_GET["lan"]."'";
			$objConn->i_query($strSQL);
			trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$arrTextes["prefs"]["lans"].") ".$arrTextes["tracking"]["newstatus"]."");
		}
	}
	elseif($_GET["akt"]=="loes")
	{
		$strSQL = "DELETE FROM ".PREFIX."_languages WHERE lan='".$_GET["lan"]."'";
		$objConn->i_query($strSQL);
		trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$arrTextes["prefs"]["lans"].") ".$arrTextes["tracking"]["delete"]."");
	}
}

// get main preferences
$strSQL = "SELECT prf.*,gln.name AS gal_name,gln.id_GALL AS galID FROM ".PREFIX."_preferences AS prf
			LEFT JOIN ".PREFIX."_gallery_text AS gln ON prf.start_id_GALL=gln.id_GALL";
$rs = $objConn->rs_query($strSQL);
if ($rs->count() > 0)
{
	$row = $rs->fetchAssocArray();
	foreach($row AS $key=>$value)
		$_POST[$key] = $value;
}

// get language specific preferences
$strSQL = "SELECT lan.name AS lanname, lan.lan, lan.notext, lan.aktiv, meta.web_title,meta.meta_key, meta.meta_des
			FROM ".PREFIX."_languages AS lan LEFT JOIN ".PREFIX."_preferences_metatext AS meta ON lan.lan=meta.lan";
$rsLT = $objConn->rs_query($strSQL);
$rsLM = $objConn->rs_query($strSQL);
$rsLL = $objConn->rs_query($strSQL);
include_once("inc/page-head.inc.php");
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.aktlink').click(function(){
			var d = this.id.explode(",");
			url = this;
			if(d[0]=="dea")
			{
				if(d[3]==1)
				{
					msg = "<?=$arrTextes["aktions"]["deaktiv"]?>";
				}
				else
				{
					msg = "<?=$arrTextes["aktions"]["aktiv"]?>";
				}
			}
			else
			{
				msg = "<?=$arrTextes["aktions"]["pagedelete"]?>";
			}
			$.openDialog(url,msg,400,"<?=$arrTextes["messages"]["confirm"]?>","<?=$arrTextes["aktions"]["yes"]?>","<?=$arrTextes["aktions"]["no"]?>");
			return false;
		});
	});
</script>
</head>
<body>
	<div id="submenu"><?=$arrTextes["preference"]["title"]?></div>
	<div id="mainpage">
		<?php
		if(isset($errMSG))
		{
			$bolKO ? $class=" errLabel" : $class="okLabel";
			echo "<div id=\"errBox\" class=\"$class\">$errMSG</div>";
		}
		?>
		<div class="block">
			<div id="dialog"></div>
			<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["web_stop"]?>:&nbsp;
						<?php
						$_POST["web_stop"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="web_stop" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["web_stop"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="web_stop" value="0" <?=$chk?>/>
						&nbsp;&nbsp;<?=$arrTextes["prefs"]["web_stop_txt"]?>
					</li>
					<li>
						<?=$arrTextes["prefs"]["meta_explanation_title"]?>:
						<ul>
							<?php
							if($rsLT->count()>0)
								while($row = $rsLT->fetchObject())
								{
									$row->web_title != "" ? $val = $row->web_title : $val = "";
									echo "<li>$row->lanname: <input type=\"text\" name=\"".$row->lan."_meta-webtitle\" size=\"100\" maxlenght=\"255\" value=\"$val\" /></li>";
								}
							unset($rsLT);
							?>
						</ul>
					</li>
				</ul>
				<br />
				<strong><?=$arrTextes["prefs"]["meta"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["meta_copy"]?>:&nbsp;<input type="text" name="meta_copy" size="50" maxlenght="255" value="<?=$_POST["meta_copy"]?>" />
					</li>
					<li>
						<?=$arrTextes["prefs"]["meta_mail"]?>:&nbsp;<input type="text" name="meta_mail" size="50" maxlenght="255" value="<?=$_POST["meta_mail"]?>" /> (<?=$arrTextes["prefs"]["meta_mail_exp"]?>)
					</li>
					<li>
						<?=$arrTextes["prefs"]["meta_key-des"]?>:<br />
						<?php
						if ($rsLM->count() > 0)
						{
							while($row = $rsLM->fetchObject())
							{
								echo "<div class=\"left\">";
								echo "<ul>";
								echo "<li><strong>".strtoupper($row->lanname)."</strong></li>";
								echo "<li>".$arrTextes["prefs"]["meta_key"]."<br />";
								$row->meta_key != "" ? $val = $row->meta_key : $val = "";
								echo "<textarea name=\"".$row->lan."_meta-key\" cols=\"40\" rows=\"5\" id=\"".$row->lan."_meta-key\">$val</textarea>";
								echo "</li>";
								echo "<li>".$arrTextes["prefs"]["meta_des"]."<br />";
								$row->meta_des != "" ? $val = $row->meta_des : $val = "";
								echo "<textarea name=\"".$row->lan."_meta-des\" cols=\"40\" rows=\"5\" id=\"".$row->lan."_meta-des\">$val</textarea>";
								echo "</li>";
								echo "</ul>";
								echo "</div>";
								$arrLan[$row->lan]["lan"] = $row->lan;
								$arrLan[$row->lan]["name"] = $row->lanname;
								$arrLan[$row->lan]["notext"] = $row->notext;
							}
							echo "<br class=\"break\" />";
							echo "<p>".$arrTextes["prefs"]["meta_explanation"]."</p><br />";
							echo "<p>".$arrTextes["prefs"]["meta_explanation_des"]."</p><br />";
							echo "<p>".$arrTextes["prefs"]["meta_explanation_keys"]."</p><br />";
						}
						unset($rsLM);
						?>
					</li>
				</ul>
				<br />
				<strong><?=$arrTextes["prefs"]["lans"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["landes"]?>
					</li>
					<?php
					if($rsLL->count()>0)
					{
						while($row = $rsLL->fetchObject())
						{
							echo "<li>";
							echo "<span style=\"display:inline-block;width:25px;\">$row->lan</span>";
							echo "<input type=\"text\" name=\"".$row->lan."_lan-name\" size=\"10\" value=\"".strtoupper($row->lanname)."\" maxlength=\"50\" />&nbsp;";
							echo "<input type=\"text\" name=\"".$row->lan."_lan-notext\" size=\"50\" value=\"$row->notext\" maxlength=\"255\" />&nbsp;";
							if($row->aktiv)
							{
								$ico = "page_enabled.png";
								$msg=$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["dodeaktiv"]);
							}
							else
							{
								$ico = "page_disabled.png";
								$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["doaktiv"]);
							}
							echo "<a href=\"".THISMAINPAGENAME.".php?lan=$row->lan&akt=dea&isA=$row->aktiv\" class=\"aktlink\" id=\"dea,$row->lan,$row->lanname,$row->aktiv\"><img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" /></a>&nbsp;";
							$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["dodelete"]);
							echo "<a href=\"".THISMAINPAGENAME.".php?lan=$row->lan&akt=loes\" class=\"aktlink\" id=\"loes,$row->lan,$row->lanname\"><img src=\"imago/page_delete.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\" /></a>";
							echo "</li>";
						}
						echo "<li>MAIN LANGUAGE&nbsp:&nbsp;";
						echo "<select name=\"web_lingua\">";
						$rsLL->rewind();
						while($row = $rsLL->fetchObject())
						{
							$_POST["web_lingua"]==$row->lan?$sel=" selected=\"selected\"":$sel="";
							echo "<option value=\"$row->lan\"$sel>$row->lanname</option>";
						}
						echo "</select>";
						echo "</li>";
					}
					else
					{
						echo "<li>";
						echo "<span style=\"display:inline-block;width:25px;\">$row->lan</span>";
						echo "<input type=\"text\" name=\"".$row->lan."_lan-name\" size=\"10\" value=\"".strtoupper($row->lanname)."\" maxlength=\"50\" />&nbsp;";
						echo "<input type=\"text\" name=\"".$row->lan."_lan-notext\" size=\"50\" value=\"$row->notext\" maxlength=\"255\" />&nbsp;";
						if($row->aktiv)
						{
							$ico = "page_enabled.png";
							$msg=$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["dodeaktiv"]);
						}
						else
						{
							$ico = "page_disabled.png";
							$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["doaktiv"]);
						}
						echo "<a href=\"".THISMAINPAGENAME.".php?lan=$row->lan&akt=dea&isA=$row->aktiv\" class=\"aktlink\" id=\"dea,$row->lan,$row->lanname,$row->aktiv\"><img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" /></a>&nbsp;";
						$msg=str_replace("#name",$row->lanname,$arrTextes["aktions"]["dodelete"]);
						echo "<a href=\"".THISMAINPAGENAME.".php?lan=$row->lan&akt=loes\" class=\"aktlink\" id=\"loes,$row->lan,$row->lanname\"><img src=\"imago/page_delete.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\" /></a>";
						echo "</li>";
					}
					echo "<li>".$arrTextes["prefs"]["newlan"]."<br /><br />";
					echo "<input type=\"text\" name=\"new_lan-lan\" size=\"2\" value=\"\" maxlength=\"2\" />&nbsp;";
					echo "<input type=\"text\" name=\"new_lan-name\" size=\"10\" value=\"\" maxlength=\"50\" />&nbsp;";
					echo "<input type=\"text\" name=\"new_lan-notext\" size=\"50\" value=\"\" maxlength=\"255\" />";
					echo "</li>";
					unset($rsLL);
					?>
				</ul>
				<br />
				<strong><?=$arrTextes["prefs"]["start"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["start_from_pages"]?>:&nbsp;
						<?php
						$_POST["start_fix_start"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="start_fix_start" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["start_fix_start"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="start_fix_start" value="0" <?=$chk?>/>
					</li>
					<li>
						<?=$arrTextes["prefs"]["start_rnd_media"]?>:&nbsp;
						<?php
						$_POST["start_rand_med"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="start_rand_med" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["start_rand_med"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="start_rand_med" value="0" <?=$chk?>/>
					</li>
					<li>
						<?=$arrTextes["prefs"]["start_rnd_gall"]?>:&nbsp;
						<?php
						$_POST["start_rand_gal"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="start_rand_gal" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["start_rand_gal"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="start_rand_gal" value="0" <?=$chk?>/>
						<?php
						if($_POST["gal_name"]!="")
							echo "&nbsp;&nbsp;".$arrTextes["prefs"]["chsen_gal"].": ".strtoupper($_POST["gal_name"]);
						?>
					</li>
					<li class="gall_chs">
						<?=$arrTextes["prefs"]["chs_gal"]?>
						<br />
						<?php
						$strSQL = "SELECT gl.id_GALL AS rowID, gl.symlink, gln.name, gl.aktiv, (SELECT COUNT(med.id_MED) FROM ".PREFIX."_media AS med JOIN ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED WHERE glm.id_GALL=rowID) AS tM
								   FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL
								   WHERE gln.lan='".$cmslan."' ORDER by gl.pos";
						$rs = $objConn->rs_query($strSQL);
						if ($rs->count() > 0)
						{
							echo "<select name=\"start_id_GALL\">";
							echo "<option value=\"\"></option>";
							while($row = $rs->fetchObject())
							{
								if($row->tM>0)
								{
									$row->rowID==$_POST["galID"]?$sel = " selected=\"selected\"":$sel="";
									echo "<option value=\"$row->rowID#$row->symlink\"$sel>$row->name ($row->tM ".$arrTextes["prefs"]["gall_tot_media"].")</option>";
								}
								else
									echo "<option value=\"$row->rowID#$row->symlink\" disabled=\"disabled\">$row->name (".$arrTextes["prefs"]["gall_no_media"].")</option>";
							}
							echo "</select>";
						}
						else
							echo "<br />&nbsp;&nbsp;<span class=\"red\">".$arrTextes["prefs"]["no_gal"]."</span>";
						?>
					</li>
					<li class="gall_chs">
						<?=$arrTextes["prefs"]["chs_gal_link"]?>
						<?php
						$_POST["start_id_GALL_link"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="start_id_GALL_link" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["start_id_GALL_link"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="start_id_GALL_link" value="0" <?=$chk?>/>
					</li>
				</ul>
				<br />
				<strong><?=$arrTextes["prefs"]["menu"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["menu_home_logo"]?>:&nbsp;
						<?php
						$_POST["menu_home_logo"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="menu_home_logo" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["menu_home_logo"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="menu_home_logo" value="0" <?=$chk?>/>
					</li>
					<li>
						<?=$arrTextes["prefs"]["menu_home"]?>:&nbsp;
						<?php
						$_POST["menu_home"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="menu_home" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["menu_home"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="menu_home" value="0" <?=$chk?>/>
						<br />(<?=$arrTextes["prefs"]["menu_home_txt"]?>)
					</li>
					<li>
						<?=$arrTextes["prefs"]["menu_home_fix"]?>:&nbsp;
						<?php
						$_POST["menu_home_fix"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="menu_home_fix" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["menu_home_fix"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="menu_home_fix" value="0" <?=$chk?>/>
						<br />(<?=$arrTextes["prefs"]["menu_home_fix_txt"]?>)
					</li>
					<li>
						<?=$arrTextes["prefs"]["menu_portfolio"]?>:&nbsp;
						<?php
						$_POST["menu_portfolio"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="menu_portfolio" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["menu_portfolio"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="menu_portfolio" value="0" <?=$chk?>/>
						<br />(<?=$arrTextes["prefs"]["menu_portfolio_txt"]?>)
					</li>
				</ul>
				<br />
				<strong><?=$arrTextes["prefs"]["portfolio"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["portfolio_thmbs"]?>:&nbsp;
						<?php
						$_POST["portfolio_thmbs"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="portfolio_thmbs" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["portfolio_thmbs"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="portfolio_thmbs" value="0" <?=$chk?>/>
						&nbsp;&nbsp;(<?=$arrTextes["prefs"]["portfolio_thmbs_txt"]?>)
					</li>
				</ul>
				<br />
				
				<strong><?=$arrTextes["prefs"]["media"]?></strong>
				<br /><br />
				<ul class="boxes">
					<li>
						<?=$arrTextes["prefs"]["resize"]?>:&nbsp;
						<?php
						$_POST["media_RESIZEIMG"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["yes"]?>&nbsp;<input type="radio" name="media_RESIZEIMG" value="1" <?=$chk?>/>&nbsp;&nbsp;
						<?php
						!$_POST["media_RESIZEIMG"]?$chk="checked=\"checked\" ":$chk="";
						?>
						<?=$arrTextes["aktions"]["no"]?>&nbsp;<input type="radio" name="media_RESIZEIMG" value="0" <?=$chk?>/>
						&nbsp;&nbsp;(<?=$arrTextes["prefs"]["resize_txt"]?>)
					</li>
					<li>MAX <?=$arrTextes["prefs"]["h"]?>:&nbsp;<input type="text" name="media_IMG_MAX_H" size="4" value="<?=$_POST["media_IMG_MAX_H"]?>" />&nbsp;px</li>
					<li>MAX <?=$arrTextes["prefs"]["w"]?>:&nbsp;<input type="text" name="media_IMG_MAX_W" size="4" value="<?=$_POST["media_IMG_MAX_W"]?>" />&nbsp;px</li>
					<li>
						<?=$arrTextes["prefs"]["thb_ref"]?>:
						<select name="media_thb_ref">
							<?php
							$_POST["thb_ref"]=="h"?$sel=" selected=\"selected\"":$sel="";
							?>
							<option value="h"<?=$sel?>><?=$arrTextes["prefs"]["h"]?></option>
							<?php
							$_POST["thb_ref"]=="w"?$sel=" selected=\"selected\"":$sel="";
							?>
							<option value="w"<?=$sel?>><?=$arrTextes["prefs"]["w"]?></option>
						</select>
					</li>
					<li>THUMBNAILS <?=$arrTextes["prefs"]["h"]?>:&nbsp;<input type="text" name="media_IMG_THUMB_H" size="4" value="<?=$_POST["media_IMG_THUMB_H"]?>" />&nbsp;px</li>
					<li>THUMBNAILS <?=$arrTextes["prefs"]["w"]?>:&nbsp;<input type="text" name="media_IMG_THUMB_W" size="4" value="<?=$_POST["media_IMG_THUMB_W"]?>" />&nbsp;px</li>
				</ul>
				<br /><br />
				<input type="submit" name="change" value="<?=$arrTextes["forms"]["modify"]?>" />
			</form>
		</div>
	</div>
</body>
</html>