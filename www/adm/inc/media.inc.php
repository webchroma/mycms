<?php
// part of a gallery, news or pages?
$strSQLJOIN = "";
$strid = null;
$id_TBL_NAME = "null";
$id_TBL = "null";
$strRef = 0;
$strRefType = "null";

// allow sort of images

if(!isset($doSort))
	$doSort = false;

$objConn = MySQL::getIstance();
if(MEDIAPARAM)
	getMEDIAPARAM();
else
	include_once("mediaparam.inc.php");
if(isset($_REQUEST["id_GALL"]))
{
	if(!is_null($_REQUEST["id_GALL"]))
	{
		settype($_REQUEST["id_GALL"],"INT");
		$id_TBL_NAME = "id_GALL";
		$strid = "&id_GALL=".$_REQUEST["id_GALL"];
		$id_TBL = $_REQUEST["id_GALL"];
		$strRef = 1;
		$strRefType = "gall";
		$strSQLJOIN = " JOIN ".PREFIX."_gallery_media AS medgal ON medgal.id_MED=med.id_MED WHERE medgal.id_GALL=".$_REQUEST["id_GALL"];
	}
}
else if(isset($_REQUEST["id_NWS"]))
{
	if(!is_null($_REQUEST["id_NWS"]))
	{
		settype($_REQUEST["id_NWS"],"INT");
		$id_TBL_NAME = "id_NWS";
		$strid = "&id_NWS=".$_REQUEST["id_NWS"];
		$id_TBL = $_REQUEST["id_NWS"];
		$strRef = 1;
		$strRefType = "news";
		$strSQLJOIN = " JOIN ".PREFIX."_news_media AS mednws ON mednws.id_MED=med.id_MED WHERE mednws.id_NWS=".$_REQUEST["id_NWS"];
	}
}
else if(isset($_REQUEST["id_PAG"]))
{
	if(!is_null($_REQUEST["id_PAG"]))
	{
		settype($_REQUEST["id_PAG"],"INT");
		$id_TBL_NAME = "id_PAG";
		$strid = "&id_PAG=".$_REQUEST["id_PAG"];
		$id_TBL = $_REQUEST["id_PAG"];
		$strRef = 1;
		$strRefType = "pages";
		$strSQLJOIN = " JOIN ".PREFIX."_pages_media AS medpag ON medpag.id_MED=med.id_MED WHERE medpag.id_PAG=".$_REQUEST["id_PAG"];
	}
}
// only one media type
if(!isset($strWHERE))
{
	$strWHERE = "";
}
else
{
	if($strSQLJOIN!="")
		$strWHERE = str_replace("WHERE","AND",$strWHERE);
}

// import foto from folder
if(isset($_GET["doupldfold"]) && $_GET["doupldfold"]!="" && isset($_REQUEST["id_GALL"]))
{
	$bolDoUpl = false;
	$dr = UPLPATH."/".$_GET["doupldfold"]."/";
	if (is_dir($dr))
	{
	    if ($dh = opendir($dr))
		{
			while (($file = readdir($dh)) !== false)
			{
				if(filetype($dr.$file)=="file" && substr($file, 0, 1)!=".")
					$files[] = $file;
				//else
				//	unlink($dr.$file);
			}
			closedir($dh);
			if(isset($files)&&is_array($files))
			{
				sort($files);
				$i=$n=1;
				foreach($files AS $file)
				{
					$flname = time().".jpg";
					$targetFile = MEDIA."/images/".$flname;
					if(copy($dr.$file, $targetFile))
					{
						$bolDoUpl = true;
						$imgSize = getimagesize($targetFile);
						if($imgSize[0]>media_IMG_MAX_W || $imgSize[1]>media_IMG_MAX_H)
						{
							if(RESIZEIMG)
							{
								$bolDoUpl = resizeIMG($targetFile);
							}
							else
							{
								$errUPLMSG = str_replace("media_IMG_MAX_H",media_IMG_MAX_H,str_replace("media_IMG_MAX_W",media_IMG_MAX_W,$arrTextes["errors"]["imagesize"]));
								$strResp =  $errUPLMSG;
								$bolDoUpl = false;
							}						
						}
						if($bolDoUpl)
						{
							$bolDoUpl = createFormats($targetFile,$flname,SIZE_SMALLTHUMB_W,SIZE_SMALLTHUMB_H,true);
							$bolDoUpl = createFormats($targetFile,$flname,SIZE_THUMB_W,SIZE_THUMB_H,false);
							$bolDoUpl = createFormats($targetFile,$flname,SIZE_LARGE_W,SIZE_LARGE_H,false);
							$bolDoUpl = createFormats($targetFile,$flname,SIZE_HUGE_W,SIZE_HUGE_H,false);
						}
						if($bolDoUpl)
						{
							$strSQL = "INSERT INTO ".PREFIX."_media (tipo,url,pos) VALUES('img','$flname',(SELECT IF(MAX(db.pos) IS NULL,1,MAX(db.pos)+1) AS ps FROM ".PREFIX."_media AS db JOIN ".PREFIX."_gallery_media AS dbj ON db.id_MED=dbj.id_MED WHERE id_GALL=".$_REQUEST["id_GALL"]."))";
							//echo $strSQL;
							try
							{
								if(!isset($objConn)) $objConn = MySQL::getIstance();
								if($objConn->i_query($strSQL))
								{
									$imgID = $objConn->getInsertID();
									$strSQL = "INSERT INTO ".PREFIX."_gallery_media (id_GALL,id_MED) VALUES (".$_REQUEST["id_GALL"].",$imgID)";
									if($objConn->i_query($strSQL))
									{
										$iptc = getIPTC($targetFile);
										if(isset($iptc["2#120"]))
										{
											$strSQL = "INSERT INTO ".PREFIX."_media_caption (id_MED,lan,name) VALUES($imgID,'$cmslan','".$objConn->prepMysql($iptc["2#120"][0])."')";
											$objConn->i_query($strSQL);
										}
										$strResp = 1;
									}
									else
									{
										$errUPLMSG = $arrTextes["errors"]["general"];
										$strResp =  $errUPLMSG;
									}
								}
								else
								{
									$errUPLMSG = $arrTextes["errors"]["general"];
									$strResp =  $errUPLMSG;
								}
							}
							catch(Exception $e)
							{
								$errUPLMSG = captcha($e);
								$strResp =  $errUPLMSG;
							}
						}
						else
						{
							if(file_exists($targetFile)) unlink($targetFile);
							$errUPLMSG = $arrTextes["errors"]["general"];
							$strResp =  $errUPLMSG;
						}
						unlink($dr.$file);
						sleep(1);
					}
				}
				if($bolDoUpl)
					if(!rmdir($dr))
						echo "<div class=\"errLabel\" style=\"width:500px\">".$arrTextes["data"]["fld_nodelete"]."</div>";
				if(isset($errUPLMSG))
					echo "<div class=\"errLabel\" style=\"width:500px\">$errUPLMSG</div>";
			}
			else
				echo "<div class=\"errLabel\" style=\"width:500px\">".$arrTextes["data"]["fld_nofiles"]."</div>";
	    }
	}
}
?>
	<div style="float:right;margin-right:10px;font-size:12px;font-weight:bold;">
		<?php
		if($isFOTO||$isVIDEO||$isDATA||$isFOLD)
			echo $arrTextes["data"]["upload"].":";
		if($isFOTO)
			echo "&nbsp;<a href=\"#\" class=\"uplbtn\" id=\"img\">".$arrTextes["data"]["img"]."</a>";
		if($isVIDEO)
			echo "&nbsp;<a href=\"#\" class=\"uplbtn\" id=\"vid\">".$arrTextes["data"]["vid"]."</a>";
		if($isEXTVID)
			echo "&nbsp;<a href=\"#\" class=\"extvideobtn\" id=\"extvid\">".$arrTextes["data"]["extvid"]."</a>";
		if($isDATA)
			echo "&nbsp;<a href=\"#\" class=\"uplbtn\" id=\"data\">".$arrTextes["data"]["data"]."</a>";
		if($isFOLD)
			echo "&nbsp;|&nbsp;<a href=\"#\" class=\"foldbtn\" id=\"fold\">".$arrTextes["data"]["folder"]."</a>";
		?>
	</div>
	<br class="clear" />
	<div id="dialog"></div>
	<div id="captiondialog">
		<form name="formcaptions" id="formcaptions" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<?php
		$strSQL = "SELECT lan.name AS lanname, lan.lan, lan.notext FROM ".PREFIX."_languages AS lan";
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			echo "<ul class=\"form\">";
			while($row = $rs->fetchObject())
			{
				echo "<li><strong>$row->lanname</strong></li>";
				echo "<li>";
				echo "<textarea class=\"caption\" name=\"".$row->lan."_name\" rows=\"3\" id=\"".$row->lan."_name\" maxlenght=\"255\">$row->notext</textarea>";
				echo "</li>";
			}
			echo "</ul>";
		}
		else
		{
			echo "<ul class=\"form\">";
			echo "<li><strong>".$strLanName."</strong></li>";
			echo "<li>";
			echo "<textarea class=\"caption\" name=\"".$cmslan."_caption\" rows=\"3\" id=\"".$cmslan."_name\" maxlenght=\"255\">".$arrTextes["forms"]["notextdef"]."</textarea>";
			echo "</li>";
			echo "</ul>";
		}
		?>
		</form>
	</div>
	<div id="extvideodialog">
		<form name="formextvideo" id="formextvideo" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<?php
		echo "<ul class=\"form\">";
        echo "<li>Insert video code for the service you use, leave blank the other</li>";
        echo "<li></li>";
		echo "<li><strong>VIMEO</strong></li>";
		echo "<li>";
		echo "<input type=\"text\" name=\"vimeo\" id=\"vimeo\" size=\"50\" maxlength=\"255\" />";
		echo "</li>";
        echo "<li><strong>YOUTUBE</strong></li>";
		echo "<li>";
		echo "<input type=\"text\" name=\"youtube\" id=\"youtube\" size=\"50\" maxlength=\"255\" />";
		echo "</li>";
		echo "</ul>";
		?>
		</form>
	</div>
	<div id="upldiv">
		<p style="text-align:center;" class="note"></p>
		<?php
		if(isset($_POST["mediasubmit"]))
		{
			$noecho = true;
			$_REQUEST["isRef"] = false;
			include_once($_SERVER["DOCUMENT_ROOT"]."/data_upload.php");
			if(isset($errUPLMSG))
			{
				echo $errUPLMSG;
			}
		}
		?>
		<form method="post" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="isRef" value="<?=$strRef?>" />
			<input type="hidden" name="tipo" value="img" />
			<input type="hidden" name="refType" value="<?=$strRefType?>" />
			<input type="hidden" name="idname" value="<?=$id_TBL_NAME?>" />
			<input type="hidden" name="id" value="<?=$id_TBL?>" />
			<input type="hidden" name="inpname" value="file" />
			<input type="file" name="file" id="flimg" />
			<input type="file" name="file" id="flvid" />
			<input type="file" name="file" id="fldata" />
			<input type="submit" id="mediasubmit" name="mediasubmit" />
		</form>
		<div id="fileQueue"></div>
	</div>
	<div id="uplfolddiv">
		<p style="text-align:center;" class="note"></p>
		<?php
		echo "<p>".$arrTextes["data"]["choosefolder"]."</p>";
		if (is_dir(UPLPATH))
		{
		    if ($dh = opendir(UPLPATH))
			{
				while (($fold = readdir($dh)) !== false)
				{
					if(substr($fold, 0, 1)!="." && !is_dir($fold))
						$folds[] = $fold;
				}
		        closedir($dh);
				if(isset($folds))
				{
					echo "<br /><ul class=\"boxes\">";
					sort($folds);
					$i=$n=1;
					foreach($folds AS $fold)
					{
						echo "<li>";
						echo "<strong><a class=\"doupldfold_btn\" href=\"".$_SERVER['PHP_SELF']."?doupldfold=$fold$strid\">".$fold."</a></strong>";
						echo "</li>";
						$n++;
						$i++;
					}
					echo "<ul>";
					echo "<form>";
				}
				else
					echo "<br /><p align=\"center\"><strong>".$arrTextes["data"]["nofolder"]."</strong></p>";
		    }
			else
				echo "<br /><p align=\"center\"><strong>".$arrTextes["data"]["nofolder"]."</strong></p>";
		}
		else
			echo "<br /><p align=\"center\"><strong>".$arrTextes["data"]["nofolder"]."</strong></p>";
		?>
		<br />
		<div class="msg" style=\"text-align:center\"></div>
	</div>
	<div id="mediadiv">
		<?php	
		if(isset($_GET["id_MED"]))
		{
			settype($_GET["id_MED"],"INT");
			
			if($_GET["akt"]=="loes")
			{
				switch ($_GET["tipo"])
				{
					case "img":
						$fld="images";
						break;
					case "data":
						$fld="data";
						$db = "".PREFIX."_media";
						break;
					case "vid":
						$fld="videos";
						break;
					default:
						$fld="images";
						break;
				}
				if($_GET["tipo"]=="vid")
				{
					$fld = MEDIA."videos/".$_GET["fl"];
					if(is_dir($fld))
					{
						$vids = array_diff(scandir($fld), array('..', '.'));
						foreach($vids AS $vid)
							if(file_exists($fld."/".$vid)) unlink($fld."/".$vid);
						rmdir($fld);
					}
				}
				else
				{
					$flname = preg_replace('/\\.[^.\\s]{3,4}$/', '', $_GET["fl"]);
					foreach(glob(MEDIA.$fld."/$flname*") as $fl)
						unlink($fl);
						
				}
				$strSQL = "DELETE FROM ".PREFIX."_media WHERE id_MED=".$_GET["id_MED"]."";
				$objConn->i_query($strSQL);
			}
			elseif($_GET["akt"]=="caption")
			{
				$l = null;
				foreach($_GET as $key=>$value) 
				{
					if($key!="akt" && $key!="pg" && $key!="id_MED" && $key!=$id_TBL_NAME)
					{
						$arrV=explode("_",$key);
						if($l!=$arrV[0])
						{
							$value = $objConn->prepMysql($value);
							$value = htmlspecialchars($value);
							$strSQL = "INSERT INTO ".PREFIX."_media_caption (id_MED,lan,name) VALUES (".$_GET["id_MED"].",'".$arrV[0]."','".$value."') ON DUPLICATE KEY UPDATE name='".$value."'";
							$objConn->i_query($strSQL);
							$l=$arrV[0];
						}
					}	
				}
			}
			elseif($_GET["akt"]=="link")
			{
				$_GET["link"] = $objConn->prepMysql($_GET["link"]);
				$strSQL = "UPDATE ".PREFIX."_media SET link='".$_GET["link"]."' WHERE id_MED=".$_GET["id_MED"]."";
				$objConn->i_query($strSQL);
			}
		}
		
		if(isset($_GET["akt"])&&$_GET["akt"]=="extvideo"&&$_GET["id_GALL"]!="")
		{
            if($_GET["vimeo"]!="")
            {
                $_GET["vimeo"] = $objConn->prepMysql($_GET["vimeo"]);
                $strSQL = "INSERT INTO ".PREFIX."_media (tipo,url,pos) VALUES('vimeo','".$_GET["vimeo"]."',0)";
                if($objConn->i_query($strSQL))
                {
                    $imgID = $objConn->getInsertID();
                    if($imgID!=0)
                    {
                        $strSQL = "INSERT INTO ".PREFIX."_gallery_media (id_GALL,id_MED) VALUES (".$_GET["id_GALL"].",$imgID) ON DUPLICATE KEY UPDATE id_MED=$imgID";
                        $objConn->i_query($strSQL);
                    }	
                }
            }
            if($_GET["youtube"]!="")
            {
                $_GET["youtube"] = $objConn->prepMysql($_GET["youtube"]);
                $strSQL = "INSERT INTO ".PREFIX."_media (tipo,url,pos) VALUES('youtube','".$_GET["youtube"]."',0)";
                if($objConn->i_query($strSQL))
                {
                    $imgID = $objConn->getInsertID();
                    if($imgID!=0)
                    {
                        $strSQL = "INSERT INTO ".PREFIX."_gallery_media (id_GALL,id_MED) VALUES (".$_GET["id_GALL"].",$imgID) ON DUPLICATE KEY UPDATE id_MED=$imgID";
                        $objConn->i_query($strSQL);
                    }	
                }
            }
		}
		
		/* paging */
		
		isset($_GET["pg"]) ? $thispg = $_GET["pg"] : $thispg = 1;
		
		settype($thispg,"INT");
		
		$strSQL = "SELECT COUNT(med.id_MED) AS trs FROM ".PREFIX."_media AS med".$strSQLJOIN.$strWHERE;
		
		if($rs = $objConn->rs_query($strSQL))
		{
			$row = $rs->fetchAssocArray();
			$tRS = $row["trs"];
		}
		
		$tOBJ = 50;
		$lastpg = ceil($tRS/$tOBJ);
		
		if ($thispg < 1) 
		{ 
			$thispg = 1; 
		} 
		elseif ($thispg > $lastpg) 
		{ 
			$thispg = $lastpg; 
		}
		if($thispg==0) $thispg = 1;
		$strSQLLIMIT = "LIMIT " .($thispg - 1) * $tOBJ ."," .$tOBJ;
		
		$strSQL = "SELECT med.id_MED, med.tipo, med.url, med.link, med.pos, 
				  (SELECT GROUP_CONCAT(medn.lan,'_name=',medn.name SEPARATOR '#') FROM ".PREFIX."_media_caption AS medn WHERE medn.id_MED=med.id_MED) AS captionurl,
				  (SELECT medn.name FROM ".PREFIX."_media_caption AS medn WHERE medn.id_MED=med.id_MED AND medn.lan='$cmslan') AS caption
				  FROM ".PREFIX."_media AS med".$strSQLJOIN.$strWHERE." ORDER BY med.pos ASC $strSQLLIMIT";
        $rs = $objConn->rs_query($strSQL);
		
		if ($rs->count() > 0)
		{
			if($lastpg>1)
			{
				echo "<div class=\"navi\">";
				for($i=1;$i<=$lastpg;$i++)
				{
					$i!=$thispg ? $class="button" : $class="buttonchoosen";
					echo "<a href=\"".$_SERVER['PHP_SELF']."?pg=$i$strid\" class=\"$class\">$i</a>&nbsp;";
				}
				echo "</div>";
			}
			isset($_POST["".$cmslan."_name"])? $dvid = " name=\"".$_POST["".$cmslan."_name"]."\"" : $dvid="";
			echo "<div id=\"imgtbl\"$dvid>";
			while($img = $rs->fetchObject())
			{
				echo "<div class=\"imgsing\" id=\"arrPOS_".$img->id_MED."\">";
				switch($img->tipo)
				{
					case "img":
						$flname = getFileNameFormat($img->url,LARGE);
						$lnk = "<a href=\"".IMAGES_AS_URL."$flname\" target=\"_blank\" class=\"img\">";
						$flname = getFileNameFormat($img->url,THUMB);
						$arrImgAttr = getimagesize(MEDIA."images/$flname");
						$arrImgAttr[0]>$arrImgAttr[1]?$cls="img":$cls="imgvert";
						echo "<img src=\"".IMAGES_AS_URL."$flname\" class=\"$cls\" />";
						break;
					case "vid":
						echo "<div id=\"vid_$img->id_MED\" class=\"videodiv\">";
						$fld = MEDIA."videos/$img->url";
						echo "<p>VIDEO <strong>".strtoupper($img->url)."</strong></p><br />";
						echo "<p>Um das Video einzubetten, folgenden CODE im Textformular einngeben, wo das Video erscheinen soll:</p><br />";
						echo "<p><strong>[".strtoupper($img->url)."]</strong></p><br /><br />";
						echo "<ul>";
						echo "<li>MP4: ";
						if(!file_exists($fld."/".$img->url.".mp4"))
							echo "<span class=\"errSPAN\">NICHT VORHANDEN</span>";
						else
							echo "<span class=\"okSPAN\">VORHANDEN</span>";
						echo "</li>";
						echo "<li>OGG: ";
						if(!file_exists($fld."/".$img->url.".ogv"))
							echo "<span class=\"errSPAN\">NICHT VORHANDEN</span>";
						else
							echo "<span class=\"okSPAN\">VORHANDEN</span>";
						echo "</li>";
						echo "<li>WEBM: ";
						if(!file_exists($fld."/".$img->url.".webm"))
							echo "<span class=\"errSPAN\">NICHT VORHANDEN</span>";
						else
							echo "<span class=\"okSPAN\">VORHANDEN</span>";
						echo "</li>";
						echo "</ul><br class=\"break\" /></div>";
						break;
					case "data":
						echo "<a href=\"".DATA_AS_URL."$img->url\" target=\"_blank\">$img->url</a>";
						break;
					case "vimeo":
						echo "<div id=\"vid_$img->id_MED\" class=\"videodiv\">";
						echo "<p>VIDEO VIMEO</p><br />";
						echo "<p>Um das Video einzubetten, folgenden CODE im Textformular einngeben, wo das Video erscheinen soll:</p><br />";
						echo "<p><strong>[VIMEO#".strtoupper($img->url)."]</strong></p><br /><br />";
						echo "<p>NR. <a href=\"https://vimeo.com/$img->url\" target=\"_blank\"><strong>$img->url</strong></a></p>";
						echo "<br class=\"break\" /></div>";
						break;
                    case "youtube":
						echo "<div id=\"vid_$img->id_MED\" class=\"videodiv\">";
						echo "<p>VIDEO YOUTUBE</p><br />";
						echo "<p>Um das Video einzubetten, folgenden CODE im Textformular einngeben, wo das Video erscheinen soll:</p><br />";
						echo "<p><strong>[TUBE#".strtoupper($img->url)."]</strong></p><br /><br />";
						echo "<p>NR. <a href=\"https://www.youtube.com/watch?v=$img->url\" target=\"_blank\"><strong>$img->url</strong></a></p>";
						echo "<br class=\"break\" /></div>";
						break;
					default:
						break;
				}
				if(CAPTIONS)
				{
					echo "<div class=\"caption\" id=\"capt$img->id_MED\">";
					if(!is_null($img->captionurl))
					{
						//$img->captionurl = htmlspecialchars($img->captionurl);
						foreach(explode("#",$img->captionurl) AS $value)
						{
							$arrV = explode("=",$value);
							echo "<input type=\"hidden\" name=\"$arrV[0]\" value=\"$arrV[1]\" />";
						}
					}
					echo "$img->caption</div>";
					if($img->caption!="") echo "<hr />";
					$img->captionurl = str_replace("#","&",$img->captionurl);
				}
                //<img src=\"imago/caption_ico.png\" alt=\"".$arrTextes["aktions"]["docaption"]."\" title=\"".$arrTextes["aktions"]["docaption"]."\" />
				if(CAPTIONS)
					echo "<a href=\"".$_SERVER['PHP_SELF']."?id_MED=$img->id_MED&akt=caption&pg=$thispg&$img->captionurl$strid\" class=\"aktlink\" id=\"caption,$img->id_MED\"><i class=\"fa fa-info-circle fa-lg fa-fw\" aria-label=\"".$arrTextes["aktions"]["docaption"]."\"></i></a>&nbsp;";
				
				$msg=str_replace("#name",$img->url,$arrTextes["aktions"]["dodelete"]);
				if($img->tipo=="img"&&isset($_GET["id_GALL"]))
				{
					$msg=str_replace("#name",$img->url,$arrTextes["gallery"]["thumb"]);
					//$img->url==$gall_thumb ? $icon = "page_is_home.png" : $icon = "page_do_home.png";
                    $img->url==$gall_thumb ? $icon = "on" : $icon="off";
					echo "<a href=\"".$_SERVER['PHP_SELF']."?id_GALL=".$_GET["id_GALL"]."&akt=glthumb&pg=$thispg&thmb=$img->url\" class=\"aktlink\" id=\"glthumb,".$_GET["id_GALL"].",$img->url\"><i class=\"fa fa-home $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
				}
				//<img src=\"imago/page_delete.png\" alt=\"$msg\" title=\"$msg\" style=\"margin-left:5px;\" />
				echo "<a href=\"".$_SERVER['PHP_SELF']."?id_MED=$img->id_MED&tipo=$img->tipo&fl=$img->url&akt=loes&pg=$thispg$strid\" class=\"aktlink\" id=\"loes,$img->id_MED,$img->url\"><i class=\"fa fa-trash-o fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
				
				if($img->tipo=="img")
				{
                    //<img src=\"imago/img_big_ico.png\" alt=\"$img->caption\" title=\"$msg\" style=\"margin-left:5px;float:right\" />
					$msg=str_replace("#name",$img->url,$arrTextes["aktions"]["showbig"]);
					echo "$lnk<i class=\"fa fa-search-plus fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
				}
				echo "</div>";
			}
			echo "<br class=\"break\"/>";
			echo "</div>";
		}
		else
		{
			echo $arrTextes["errors"]["nomedia"];
		}
		?>
	</div>
	<div id="loading"><img src="imago/loader.gif" width="66" height="66" alt="loading" /><br />LOADING</div>
	<script type="text/javascript">
	$(function()
	{		
		$("#upldiv").add("#uplfolddiv").add("#mediasubmit").add("#mediadiv").add("#captiondialog").add("#extvideodialog").add("#flimg").add("#flvid").hide();
		$("#loading").show();
		$(window).load(function(){
			$("#loading").hide();
			$("#mediadiv").show();
			$("a.img").fancybox();
			$("a.vid").fancybox({
				'padding'		: 0,
				'autoScale'		: true,
				'transitionIn'	: 'none',
				'transitionOut'	: 'none',
				'href'			: this.href,
				'type'			: 'swf',
				'swf'			: {
					'wmode'		: 'transparent',
					'allowfullscreen'	: 'true',
					'flashvars' : 'aplay=1&skinfold=swf/&skinhide=0'
				}
			});
			$('.aktlink').click(function()
			{
				var d = this.id.split(",");
				url = this;
				if(d[0]=="caption")
				{
					$.openCaption(url,500,"<?=$arrTextes["help"]["caption"]?>","<?=strtoupper($arrTextes["forms"]["insert"])?>","<?=$arrTextes["aktions"]["close"]?>");
					$("#formcaptions").find("textarea").each(function(){
						$(this).val("");
					});
					$("#capt"+d[1]+"").find("input").each(function(){
						$("#formcaptions").find("textarea[name="+$(this).attr("name")+"]").val($(this).val()).css({"display":"block"});
					});
				}
				else if(d[0]=="link")
				{
					lnk = $("#link"+d[1]+"").find("input").val();
					$("#dialog").html("http:// <input type='text' name='link' value='"+lnk+"' size='50' maxlength='255' />");
					$.openLinks(url,500,"<?=$arrTextes["help"]["link"]?>","<?=strtoupper($arrTextes["forms"]["insert"])?>","<?=$arrTextes["aktions"]["close"]?>");
				}
				else if(d[0]=="loes")
				{
					msg = "<?=$arrTextes["aktions"]["delete"]?>";
					title = "<?=$arrTextes["messages"]["confirm"]?>";
					btnyes = "<?=$arrTextes["aktions"]["yes"]?>";
					btnno = "<?=$arrTextes["aktions"]["no"]?>";
					$.openDialog(url,msg,350,title,btnyes,btnno);
				}
				else if(d[0]=="glthumb")
				{
					window.location.href = url;
				}
				return false;
			});
			$(".extvideobtn").click(function(){
				var url = (""+this).replace('#','');
				title = "<?=$arrTextes["help"]["extvideo"]?>";
				btnyes = "<?=strtoupper($arrTextes["forms"]["insert"])?>";
				btnno = "<?=$arrTextes["aktions"]["close"]?>";
				$.openExtVid(url,500,title,btnyes,btnno);
				return false;
			})
			$(".uplbtn").click(function()
			{
                msg=title=fileMime=fileExtTxt="";
				if($(this).attr("id")=="img")
				{
					$.proj.aktINP = "flimg";
					title = "<?=$arrTextes["data"]["img"]?> <?=$arrTextes["data"]["upload"]?>";
                    fileMime = ["image\/gif","image\/jpeg","image\/png"];
					fileExtTxt = "*.jpg;*.jpeg;*.gif;*.png";
                    msg = "<?=$arrTextes["data"]["format"]?>: "+fileExtTxt+"<br />";
					msg += "<?=str_replace("media_IMG_MAX_H",media_IMG_MAX_H,str_replace("media_IMG_MAX_W",media_IMG_MAX_W,$arrTextes["data"]["imagesize"]))?><br />";
				}
				else if($(this).attr("id")=="vid")
				{
					$.proj.aktINP = "flvid";
					title = "<?=$arrTextes["data"]["vid"]?> <?=$arrTextes["data"]["upload"]?>";
					fileMime = ["video\/mp4","video\/ogg","video\/webm"];
                    fileExtTxt = "<?=$arrTextes["data"]["videotype"]?>";
					msg = "<?=$arrTextes["data"]["format"]?>: "+fileExtTxt+"<br />";
				}
				else if($(this).attr("id")=="data")
				{
					$.proj.aktINP = "fldata";
					title = "<?=$arrTextes["data"]["data"]?> <?=$arrTextes["data"]["upload"]?>";
					fileMime = ["application\/pdf","application\/msword","application\/rtf","application\/msexcel","application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.openxmlformats-officedocument.wordprocessingml.document","application\/vnd.oasis.opendocument.text"];
                    fileExtTxt = "*.pdf;*.doc,*.docx;*.xls,*.xlsx;*.rtf;*.odt";
					msg = "<?=$arrTextes["data"]["format"]?>: "+fileExtTxt+"<br />";
				}
				msg += "<?=$arrTextes["data"]["filesize"]?>";
				tipo = $(this).attr("id");
				fileDesc = "";
				btnTxt = "<?=$arrTextes["data"]["search"]?>";
				isRef = "<?=$strRef?>";
				refType = "<?=$strRefType?>";
				id = <?=$id_TBL?>;
				errTxt = "<?=$arrTextes["errors"]["generalshort"]?>";
				$.openUPLDialog("upldiv",$.proj.aktINP,title,msg,fileDesc,fileMime,btnTxt,tipo,isRef,refType,id,"<?=$arrTextes["data"]["upload"]?>","<?=$arrTextes["aktions"]["close"]?>");
				return false;
			});
			$(".foldbtn").click(function()
			{
				$.proj.aktINP = "flimg";
				title = "<?=$arrTextes["data"]["img"]?> <?=$arrTextes["data"]["folder"]?>";
				$.openFOLDUPLDialog("uplfolddiv",title,"<?=$arrTextes["data"]["folder"]?>","<?=$arrTextes["aktions"]["close"]?>");
				return false;
			});
			
			$("a.doupldfold_btn").click(function(){
				$("#uplfolddiv div.msg").html("bitte warten").addClass("errLabel");
			});
			
			<?php
			if($doSort)
			{
			?>
			if($("#imgtbl").length!=0)
				$("#imgtbl").sortable({
					dropOnEmpty: false,
					revert: true,
					update: function() 
					{
						var order = $(this).sortable("serialize") + "&action=updatePos&gl="+$("#imgtbl").attr("name");
						$.post("update_pos.php", order, function(theResponse){
							$("#dialog").html(theResponse);
						});
					}
				});
			<?php
			}
			?>
		});
	});
	</script>