<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_edit.php","",PAGENAME));
$l = null;
if(isset($_POST["submit"]) && isset($_POST["id_NWS"]))
{
	$doIns = true;
	foreach($_POST as $key=>$value) 
	{
		if($key!="submit" && $key!="datum" && $key!="id_NWS")
		{
			if($value=="")
			{
				$doIns = false;
				$errMSG = $arrTextes["forms"]["allfields"];
			}
			else
			{
				// get total languages
				$arrV=explode("_",$key);
				if($l!=$arrV[0])
				{
					$arrL[]=$arrV[0];
					$l=$arrV[0];
				}
			}
		}
	}
	if($doIns)
	{
		settype($_POST["id_NWS"],"INT");
		try
		{
			$objConn = MySQL::getIstance();
			$objConn->stopCOMMIT();
			if(isset($_POST["datum"]))
			{
				$strSQL = "UPDATE ".PREFIX."_news SET datum=CURRENT_TIMESTAMP WHERE id_NWS=".$_POST["id_NWS"];
				$objConn->i_query($strSQL);
			}
			foreach($arrL AS $value)
			{
				$strSQL = "UPDATE ".PREFIX."_news_text 
						   SET name='".$objConn->prepMysql($_POST[$value."_name"])."',texto='".$objConn->prepMysql($_POST[$value."_txt"])."' 
						   WHERE id_NWS=".$_POST["id_NWS"]." AND lan='$value'";
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
	if(isset($_GET["id_NWS"]))
	{
		try
		{
            $objConn = MySQL::getIstance();
            settype($_GET["id_NWS"],"INT");
            if(isset($_GET["akt"]) && $_GET["akt"]=="glthumb") // aktion from media page _ insert thumbnail information 
            {
                $strSQL = "UPDATE ".PREFIX."_news SET thumb='".$_GET["thmb"]."' WHERE id_NWS=".$_GET["id_NWS"]."";
                $objConn->i_query($strSQL);
            }
            if(isset($_GET["akt"]) && $_GET["akt"]=="remthumb") // aktion from media page _ remove thumbnail information
            {
                $strSQL = "UPDATE ".PREFIX."_news SET thumb='' WHERE id_NWS=".$_GET["id_NWS"]."";
                $objConn->i_query($strSQL);
            }
            
            $strSQL = "SELECT nws.thumb, nwst.lan, lan.name AS lname, nwst.name, nwst.texto 
            FROM ".PREFIX."_news AS nws 
            LEFT JOIN ".PREFIX."_news_text AS nwst ON nws.id_NWS=nwst.id_NWS
            JOIN ".PREFIX."_languages AS lan ON nwst.lan=lan.lan 
            WHERE nwst.id_NWS=".$_GET["id_NWS"]."";
			
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				while($nws = $rs->fetchObject())
				{
					$arrLanName[$nws->lan]=$nws->lname;
                    $_POST["thumb"] = $nws->thumb;
					$_POST[$nws->lan."_name"] = $nws->name;
					$_POST[$nws->lan."_txt"] = $nws->texto;
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
<body>
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php"><?=$arrTextes["news"]["title"]?></a> / <? echo str_replace("#name","<em>".strtoupper("".$_POST[$cmslan."_name"]."")."</em>",$arrTextes["aktions"]["edit"])?></div>
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
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="nnews" id="nnews">
				<input type="hidden" name="id_NWS" id="id_NWS" value="<?php if(isset($_REQUEST["id_NWS"])) echo $_REQUEST["id_NWS"]?>">
				<div id="accordion">
                <?php
				//$strJSValidation = null;
				foreach($_POST as $key=>$value) 
				{
					if($key!="submit" && $key!="datum" && $key!="id_NWS" && $key!="thumb")
					{
						$arrV=explode("_",$key);
						if($l!=$arrV[0])
						{
							$l=$arrV[0];
							echo "<h3>".$_SESSION["lannames"][$l]."</h3>";
							echo "<div>";
							echo "<ul class=\"form\">";
							echo "<li>".$arrTextes["forms"]["title"]."<br />";
							isset($_POST["".$l."_name"]) ? $val = $_POST["".$l."_name"] : $val = "";
							echo "<input name=\"".$l."_name\" type=\"text\" id=\"".$l."_name\" size=\"50\" maxlength=\"255\" value=\"$val\" />";
							echo "</li>";
							echo "<li>".$arrTextes["forms"]["txt"]."<br />";
							isset($_POST["".$l."_txt"]) ? $val = $_POST["".$l."_txt"] : $val = "";
							echo "<textarea name=\"".$l."_txt\" cols=\"47\" rows=\"25\" class=\"mceEditor\" id=\"".$l."_txt\">$val</textarea>";
							echo "</li>";
							echo "</ul>";
							echo "</div>";
						}
					}
				}
				//$strJSValidation = substr_replace($strJSValidation ,"",-1);
				?>
                </div>
				<br class="break" />
                <ul class="form">
                    <li><?=$arrTextes["news"]["update"]?><br />
						<input name="datum" type="checkbox" id="datum" value="true" />
					</li>
					<li>&nbsp;</li>
					<li class="centerbold noborder">
						<input type="submit" class="ui-button ui-widget subbtn" name="submit" id="submit" value="<?=$arrTextes["forms"]["modify"]?>" />
					</li>
                    <li class="centerbold"><?php echo $arrTextes["data"]["doformfirst"]?></li>
                    <li class="sectiontitle ui-state-default ui-corner-all">
						<h3>PAGE COVER FOTO</h3>
					</li>
                    <li>
                        <?php
                         $strSQL = "SELECT med.id_MED, med.tipo,med.url
								  FROM ".PREFIX."_media AS med
								  WHERE med.url='".$_POST["thumb"]."'";
						$rs = $objConn->rs_query($strSQL);

						if ($rs->count() > 0)
						{
							$querystring = $_SERVER["PHP_SELF"]."?id_PAG=".$_REQUEST["id_PAG"]."&name=".$_POST[$cmslan."_name"]."&id_ZONA=".$_REQUEST["id_ZONA"];
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
                                echo "<a href=\"".$_SERVER['PHP_SELF']."?id_PAG=".$_GET["id_PAG"]."&akt=remthumb&pg=$thispg&thmb=$img->url\" class=\"aktlink\" id=\"glthumb,".$_GET["id_PAG"].",$img->url\"><i class=\"fa fa-home $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
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
								  JOIN ".PREFIX."_news_media AS medn ON medn.id_MED=med.id_MED WHERE medn.id_NWS='".$_REQUEST["id_NWS"]."'
								  ORDER BY med.id_MED DESC";
						$rs = $objConn->rs_query($strSQL);

						if ($rs->count() > 0)
						{
							$querystring = $_SERVER["PHP_SELF"]."?id_PAG=".$_REQUEST["id_PAG"]."&name=".$_POST[$cmslan."_name"]."&id_ZONA=".$_REQUEST["id_ZONA"];
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
								echo "<a href=\"$querystring&id_MED=$img->id_MED&isH=$isH\" class=\"imghref_ispag\" id=\"$img->id_MED,$isH,".$_REQUEST["id_PAG"].",id_PAG,pages\"><i class=\"fa $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
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
			/*
            if(!isset($isFOTO))
				$isFOTO = true;
			if(!isset($isVIDEO))
				$isVIDEO = true;
			if(!isset($isEXTVID))
				$isEXTVID = true;
			if(!isset($isDATA))
				$isDATA = true;
			if(!isset($isFOLD))
				$isFOLD = false;
			$doSort = true;
			include("inc/media.inc.php");
            */
            $id_REF = "id_NWS";
			$ref_tbl_name = "news";
			include("inc/media_seitenleiste.inc.php");
			?>
			<br class="break" />
		</div>
	</div>
</body>
</html>