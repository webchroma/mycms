<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_edit.php","",PAGENAME));
$l = $thispg = $thumb = null;
if(isset($_POST["submit"]) && isset($_POST["id_PROJ"]))
{
	$errMSG=check_empty_fields($_POST,$arrTextes);
	if($errMSG=="")
	{
		settype($_POST["id_PROJ"],"INT");
		try
		{
			$objConn = MySQL::getIstance();
			$objConn->stopCOMMIT();
			foreach($arrL AS $value)
			{	
				$strSQL = "INSERT INTO ".PREFIX."_project_text (id_PROJ,lan,name,target,result,fund,short_text,texto,web_title,meta_key,meta_des) 
							VALUES (".$_POST["id_PROJ"].",'$value','".$objConn->prepMysql($_POST[$value."_name"])."','".$objConn->prepMysql($_POST[$value."_target"])."','".$objConn->prepMysql($_POST[$value."_result"])."','".$objConn->prepMysql($_POST[$value."_fund"])."','".$objConn->prepMysql($_POST[$value."_shorttxt"])."','".$objConn->prepMysql($_POST[$value."_txt"])."','".$objConn->prepMysql($_POST[$value."_web_title"])."','".$objConn->prepMysql($_POST[$value."_meta_key"])."','".$objConn->prepMysql($_POST[$value."_meta_des"])."')
				  			ON DUPLICATE KEY UPDATE name='".$objConn->prepMysql($_POST[$value."_name"])."',target='".$objConn->prepMysql($_POST[$value."_target"])."',result='".$objConn->prepMysql($_POST[$value."_result"])."',fund='".$objConn->prepMysql($_POST[$value."_fund"])."',short_text='".$objConn->prepMysql($_POST[$value."_shorttxt"])."',texto='".$objConn->prepMysql($_POST[$value."_txt"])."',web_title='".$objConn->prepMysql($_POST[$value."_web_title"])."',meta_key='".$objConn->prepMysql($_POST[$value."_meta_key"])."',meta_des='".$objConn->prepMysql($_POST[$value."_meta_des"])."'";
				$objConn->i_query($strSQL);
			}				
			trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST[$cmslan."_name"].") ".$arrTextes["tracking"]["modify"]."");
			$errMSG = $arrTextes["forms"]["ismodified"];
			$objConn->doCOMMIT(true);
			$bolKO = false;
		}

		catch(Exception $e)
		{
			$objConn->doCOMMIT(false);
			$errMSG = captcha($e);
		}
	}
}
else
{
	if(isset($_GET["id_PROJ"]))
	{
		settype($_GET["id_PROJ"],"INT");
		try
		{
			$objConn = MySQL::getIstance();
			if(isset($_GET["akt"]) && $_GET["akt"]=="glthumb") // aktion from media page _ insert thumbnail information 
			{
				$strSQL = "UPDATE ".PREFIX."_project SET thumb='".$_GET["thmb"]."' WHERE id_PROJ=".$_GET["id_PROJ"]."";
				$objConn->i_query($strSQL);
			}
            if(isset($_GET["akt"]) && $_GET["akt"]=="remthumb") // aktion from media page _ remove thumbnail information 
			{
				$strSQL = "UPDATE ".PREFIX."_project SET thumb='' WHERE id_PROJ=".$_GET["id_PROJ"]."";
				$objConn->i_query($strSQL);
			}
		
			$strSQL = "SELECT lan.lan, lan.name AS lname,lan.notext AS lnotext,meta.meta_key, meta.meta_des,
					(SELECT gln.name FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gname,
					(SELECT gln.target FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gtarget,
					(SELECT gln.result FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gresult,
					(SELECT gln.fund FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gfund,
					(SELECT gln.short_text FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gshorttext,
					(SELECT gln.texto FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS gtexto,
					(SELECT gln.web_title FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS web_title,
					(SELECT gln.meta_key FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS meta_key,
					(SELECT gln.meta_des FROM ".PREFIX."_project_text AS gln WHERE gln.lan=lan.lan AND gln.id_PROJ=".$_GET["id_PROJ"].") AS meta_des,
                    (SELECT thumb FROM ".PREFIX."_project WHERE id_PROJ=".$_GET["id_PROJ"].") AS thumb
					FROM ".PREFIX."_languages AS lan
					LEFT JOIN ".PREFIX."_preferences_metatext AS meta ON lan.lan=meta.lan";
			
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				while($gall = $rs->fetchObject())
				{
					$arrLanName[$gall->lan]=$gall->lname;
                    $_POST["thumb"] = $gall->thumb;
					$gall->gname!="" ? $_POST[$gall->lan."_name"] = $gall->gname : $_POST[$gall->lan."_name"] = "";//$gall->lnotext;
					$gall->gtarget!="" ? $_POST[$gall->lan."_target"] = $gall->gtarget : $_POST[$gall->lan."_target"] = "";//$gall->lnotext;
					$gall->gresult!="" ? $_POST[$gall->lan."_result"] = $gall->gresult : $_POST[$gall->lan."_result"] = "";//$gall->lnotext;
					$gall->gfund!="" ? $_POST[$gall->lan."_fund"] = $gall->gfund : $_POST[$gall->lan."_fund"] = "";//$gall->lnotext;
					$gall->gshorttext!="" ? $_POST[$gall->lan."_shorttxt"] = $gall->gshorttext : $_POST[$gall->lan."_shorttxt"] = "";//$gall->lnotext;
					$gall->gtexto!="" ? $_POST[$gall->lan."_txt"] = $gall->gtexto : $_POST[$gall->lan."_txt"] = "";//$gall->lnotext;
					/**/
					$gall->web_title!="" ? $_POST[$gall->lan."_web_title"] = $gall->web_title : $_POST[$gall->lan."_web_title"] = "";
					$gall->meta_key!="" ? $_POST[$gall->lan."_meta_key"] = $gall->meta_key : $_POST[$gall->lan."_meta_key"] = "";
					$gall->meta_des!="" ? $_POST[$gall->lan."_meta_des"] = $gall->meta_des : $_POST[$gall->lan."_meta_des"] = "";
				}
				$_SESSION["lannames"] = $arrLanName;
			}
			else
			{
				header("location:".THISMAINPAGENAME."_neu.php");
			}
		}
		
		catch(Exception $e)
		{
			$errMSG = captcha($e);
		}
		
	}
	else
	{
		header("location:".THISMAINPAGENAME.".php");
	}
}
include_once("inc/page-head.inc.php");
?>
<body class="portfolio">
	<div id="submenu">
		<?php
		echo "<a href=\"".THISMAINPAGENAME.".php\">".strtoupper(THISMAINPAGENAME)." ".$arrTextes["admin"]["admin"]."</a> / ".str_replace("#name","<em>".strtoupper("".$_POST[$cmslan."_name"]."")."</em>",$arrTextes["aktions"]["edit"])."";
		?>
	</div>
	<div id="mainpage">
		<?php
		if(isset($errMSG))
		{
			$bolKO ? $class=" errLabel" : $class="okLabel";
			echo "<div id=\"errBox\" class=\"$class\">$errMSG</div>";
		}
		?>
		<div class="form_two_columns">
			<h1><?=$arrTextes["forms"]["titlemessage"]?></h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="ngall" id="ngall">
				<input type="hidden" name="id_PROJ" id="id_PROJ" value="<?php if(isset($_REQUEST["id_PROJ"])) echo $_REQUEST["id_PROJ"]?>">
                <div id="accordion">
				<?php
				//$strJSValidation = null;
				$l = null;
				foreach($_POST as $key=>$value) 
				{
					if($key!="submit" && $key!="id_PROJ" && $key!="ongoing" && $key!="thumb")
					{
						$arrV=explode("_",$key);
						if($l!=$arrV[0])
						{
							$l=$arrV[0];
                            echo "<h3>".$_SESSION["lannames"][$l]."</h3>";
							echo "<div>";
							echo "<ul class=\"form\">";
							/*NAME*/
							echo "<li>".$arrTextes["forms"]["title"]."<br />";
							isset($_POST["".$l."_name"]) ? $val = $_POST["".$l."_name"] : $val = "";
							echo "<input name=\"".$l."_name\" type=\"text\" id=\"".$l."_name\" size=\"200\" maxlength=\"255\" value=\"$val\" />";
							echo "</li>";
							/*SHORT TEXT*/
							echo "<li>".$arrTextes["forms"]["short-txt"]."<br />";
							isset($_POST["".$l."_shorttxt"]) ? $val = $_POST["".$l."_shorttxt"] : $val = "";
							echo "<textarea name=\"".$l."_shorttxt\" cols=\"250\" rows=\"10\" class=\"mceEditor_reduced\" id=\"".$l."_shorttxt\">$val</textarea>";
							echo "</li>";
							/*TEXT*/
							echo "<li>".$arrTextes["forms"]["txt"]."<br />";
							isset($_POST["".$l."_txt"]) ? $val = $_POST["".$l."_txt"] : $val = "";
							echo "<textarea name=\"".$l."_txt\" cols=\"47\" rows=\"25\" class=\"mceEditor\" id=\"".$l."_txt\">$val</textarea>";
							echo "</li>";
							/*TARGET*/
							echo "<li>".$arrTextes["forms"]["target"]."<br />";
							isset($_POST["".$l."_target"]) ? $val = $_POST["".$l."_target"] : $val = "";
							echo "<textarea name=\"".$l."_target\" cols=\"56\" rows=\"15\" class=\"mceEditor_reduced\" id=\"".$l."_target\">$val</textarea>";
							echo "</li>";
							/*RESULT*/
							echo "<li>".$arrTextes["forms"]["result"]."<br />";
							isset($_POST["".$l."_result"]) ? $val = $_POST["".$l."_result"] : $val = "";
							echo "<textarea name=\"".$l."_result\" cols=\"56\" rows=\"15\" class=\"mceEditor_reduced\" id=\"".$l."_result\">$val</textarea>";
							echo "</li>";
							/*FUND*/
							echo "<li>".$arrTextes["forms"]["fund"]."<br />";
							isset($_POST["".$l."_fund"]) ? $val = $_POST["".$l."_fund"] : $val = "";
							echo "<textarea name=\"".$l."_fund\" cols=\"56\" rows=\"10\" class=\"mceEditor_reduced\" id=\"".$l."_fund\">$val</textarea>";
							echo "</li>";
							echo "</ul>";
							echo "<ul class=\"form\">";
							echo "<li style=\"border:none;\"><strong>".$arrTextes["forms"]["meta_info_title"]."</strong></li>";
							echo "<li>".$arrTextes["prefs"]["web_title"]."<br />";
							isset($_POST["".$l."_web_title"]) ? $val = $_POST["".$l."_web_title"] : $val = "";
							echo "<input name=\"".$l."_web_title\" type=\"text\" id=\"".$l."_web_title\" size=\"50\" maxlength=\"255\" value=\"$val\" />";
							echo "</li>";
							echo "<li>".$arrTextes["prefs"]["meta_key"]."<br />";
							isset($_POST["".$l."_meta_key"]) ? $val = $_POST["".$l."_meta_key"] : $val = "";
							echo "<textarea name=\"".$l."_meta_key\" cols=\"50\" rows=\"5\" id=\"".$l."_meta_key\">$val</textarea>";
							echo "</li>";
							echo "<li>".$arrTextes["prefs"]["meta_des"]."<br />";
							isset($_POST["".$l."_meta_des"]) ? $val = $_POST["".$l."_meta_des"] : $val = "";
							echo "<textarea name=\"".$l."_meta_des\" cols=\"50\" rows=\"5\" id=\"".$l."_meta_des\">$val</textarea>";
							echo "</li>";
							echo "</ul>";
							echo "</div>";
						}
					}
				}
				?>
                </div>
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
					<li>&nbsp;</li>
					<li class="centerbold noborder">
						<input type="submit" class="ui-button ui-widget subbtn" name="submit" id="submit" value="<?=$arrTextes["forms"]["modify"]?>" />
					</li>
                    <li class="centerbold"><?php echo $arrTextes["data"]["doformfirst"]?></li>
                    <li class="sectiontitle ui-state-default ui-corner-all">
						<h3>PROJECT COVER FOTO</h3>
					</li>
                    <li>
                        <?php
                         $strSQL = "SELECT med.id_MED, med.tipo,med.url
								  FROM ".PREFIX."_media AS med
								  WHERE med.url='".$_POST["thumb"]."'";
						$rs = $objConn->rs_query($strSQL);

						if ($rs->count() > 0)
						{
							$querystring = $_SERVER["PHP_SELF"]."?id_PROJ=".$_REQUEST["id_PROJ"]."&name=".$_POST[$cmslan."_name"]."&id_ZONA=".$_REQUEST["id_ZONA"];
							while($img = $rs->fetchObject())
							{
								echo "<div class=\"imgsing\">";
								switch($img->tipo)
								{
									case "img":
										$flname=IMAGES_AS_URL."/".$img->url;
										$flname_small = getFileNameFormat($flname,THUMB);
										$flname = getFileNameFormat($flname,LARGE);
										echo "<a href=\"$flname\" target=\"_blank\" class=\"img\"><img src=\"$flname_small\" class=\"img\" /></a>";
										break;
									case "vid":
										echo "VIDEO: $img->url";
										break;
									case "vimeo":
										echo "VIMEO: <a href=\"https://vimeo.com/$img->url\" target=\"_blank\">$img->url</a>";
										break;
									case "data":
										echo "<a href=\"".DATA_AS_URL."$img->url\" target=\"_blank\">$img->url</a>";
										break;
									default:
										break;
								}
								echo "<br class=\"break\"/>";
								$msg=str_replace("#name",$img->url,$arrTextes["gallery"]["thumb"]);
                                $img->url==$_POST["thumb"] ? $icon = "on" : $icon="off";
                                $isH="thumb";
				                //echo "<a href=\"$querystring&id_MED=$img->id_MED&isThumb=1\" class=\"imghref_ispag\" id=\"$img->id_MED,$isH,".$_REQUEST["id_PROJ"].",id_PROj,project\"><i class=\"fa fa-home $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
                                echo "<a href=\"".$_SERVER['PHP_SELF']."?id_PROJ=".$_GET["id_PROJ"]."&akt=remthumb&pg=$thispg&thmb=$img->url\" class=\"aktlink\" id=\"glthumb,".$_GET["id_PROJ"].",$img->url\"><i class=\"fa fa-home $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
								echo "</div>";
							}
							echo "<br class=\"break\"/>";
							echo "</div>";
						}   
                        ?>
                    </li>
					<li class="sectiontitle ui-state-default ui-corner-all">
						<h3>LINKED MEDIA</h3>
					</li>
					<li>
						<?php
						$strSQL = "SELECT med.id_MED, med.tipo, med.url
								  FROM ".PREFIX."_media AS med
								  JOIN ".PREFIX."_project_media AS medn ON medn.id_MED=med.id_MED WHERE medn.id_PROJ='".$_REQUEST["id_PROJ"]."'
								  ORDER BY med.id_MED DESC";
						$rs = $objConn->rs_query($strSQL);

						if ($rs->count() > 0)
						{
							$querystring = $_SERVER["PHP_SELF"]."?id_PROJ=".$_REQUEST["id_PROJ"]."&name=".$_POST[$cmslan."_name"]."&id_ZONA=".$_REQUEST["id_ZONA"];
							while($img = $rs->fetchObject())
							{
								echo "<div class=\"imgsing\">";
								switch($img->tipo)
								{
									case "img":
										$flname=IMAGES_AS_URL."/".$img->url;
										$flname_small = getFileNameFormat($flname,THUMB);
										$flname = getFileNameFormat($flname,LARGE);
										echo "<a href=\"$flname\" target=\"_blank\" class=\"img\"><img src=\"$flname_small\" class=\"img\" /></a>";
										break;
									case "vid":
										echo "VIDEO: $img->url";
										break;
									case "vimeo":
										echo "VIMEO: <a href=\"https://vimeo.com/$img->url\" target=\"_blank\">$img->url</a>";
										break;
									case "data":
										echo "<a href=\"".DATA_AS_URL."$img->url\" target=\"_blank\">$img->url</a>";
										break;
									default:
										break;
								}
								echo "<br class=\"break\"/>";
								$isH=1;
								$icon = "fa-minus-circle";
								$strMsg = $arrTextes["gallery"]["delseiten"];
								$msg=$arrTextes["gallery"]["seiten"];
								echo "<a href=\"$querystring&id_MED=$img->id_MED&isH=$isH\" class=\"imghref_ispag\" id=\"$img->id_MED,$isH,".$_REQUEST["id_PROJ"].",id_PROj,project\"><i class=\"fa $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
								echo "</div>";
							}
							echo "<br class=\"break\"/>";
							echo "</div>";
						}
						?>
					</li>
				</ul>
			</form>
			<?php
			$id_REF = "id_PROJ";
			$ref_tbl_name = "project";
			if(!isset($_REQUEST["id_ZONA"]))
				$_REQUEST["id_ZONA"]=null;
			include("inc/media_seitenleiste.inc.php");
			?>
			<div id="invi"></div>
		</div>
	</div>
</body>
</html>