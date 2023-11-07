<div id="dv_main">
	<main>
		<div class="row">
		<?php
		if(isset($p)&&$p!=$s) //got a news
		{
			
			// get news
			$nwsID = explode("-",$p);
			$strWHP = "db.id_NWS=".$nwsID[1];
			$strSQL = "SELECT nwst.name, nwst.texto, med.id_MED, med.tipo, med.url, med.link,
						(SELECT COUNT(id_MED) FROM ".PREFIX."_news_media WHERE $strWHP) AS tmed,
						(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
						FROM ".PREFIX."_news AS db JOIN ".PREFIX."_news_text AS nwst ON db.id_NWS=nwst.id_NWS
						LEFT JOIN ".PREFIX."_news_media AS pagmed ON db.id_NWS=pagmed.id_NWS
						LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
						WHERE $strWHP AND nwst.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
            $rs = $objConn->rs_query($strSQL);
			$i=0;
			$strPDF = "";
			if ($rs->count() > 0)
			{
				while($row = $rs->fetchObject())
				{
					if($i==0)
					{
						echo "<h1>$row->name</h1>";
						$strTXT = $row->texto;
					}	
					if($row->tmed>0)
					{
						if($row->tipo=="data")
						{
							if($row->caption=="")
								$row->caption=$row->url;
							$strPDF .= "<li><a href=\"".DATA_AS_URL."$row->url\"><em>$row->caption</em></a></li>";
						}
						if($row->tipo=="img")
						{
							$arridMED[$i] = $row->id_MED;
							$arrIMGS[$row->id_MED]["url"] = $row->url;
							$arrIMGS[$row->id_MED]["type"] = $row->tipo;
							$arrIMGS[$row->id_MED]["caption"] = $row->caption;	
						}
						if($row->tipo=="vid")
						{
							$arridMED[$i] = $row->id_MED;
							$arrIMGS[$row->id_MED]["url"] = $row->url;
							$arrIMGS[$row->id_MED]["type"] = $row->tipo;
							$arrIMGS[$row->id_MED]["caption"] = $row->caption;
							// check which formats are available
							$fld = MEDIA."videos/$row->url";
							$src = "";
							$vids = array_diff(scandir($fld), array('..', '.'));
							foreach($vids AS $vid)
							{
								if($vid!="." || $vid!="..")
								{
									$src .= "<source src=\"".VIDEOS_AS_URL.$row->url."/$vid\"";
									switch(pathinfo($vid,PATHINFO_EXTENSION))
									{
										case "mp4":
											$src .= "type=\"video/mp4\" />";
											break;
										case "ogv":
											$src .= "type=\"video/ogg\" />";
											break;
										case "webm":
											$src .= "type=\"video/webm\" />";
											break;
									}	
								}
							}
							$strVID = "<div class=\"video_container\"><video id=\"".$row->url."_".$row->id_MED."\" class=\"video-js vjs-default-skin\" controls preload=\"auto\" width=\"auto\" height=\"auto\"
							poster=\"/imago/video_cover.png\"
							data-setup=\"{}\">
							$src
							</video></div>";
							$strTXT = str_replace("[".strtoupper($row->url)."]",$strVID,$strTXT);
						}
						if($row->tipo=="vimeo")
						{
							$arridMED[$i] = $row->id_MED;
							$arrIMGS[$row->id_MED]["url"] = $row->url;
							$arrIMGS[$row->id_MED]["type"] = $row->tipo;
							$arrIMGS[$row->id_MED]["caption"] = $row->caption;
							$strVID = "<div class=\"vimeo_container\">";
							$strVID .= "<iframe src=\"https://player.vimeo.com/video/$row->url\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
							$strVID .= "</div>";
							$strTXT = str_replace("[".strtoupper($row->url)."]",$strVID,$strTXT);
						}
					}
					$i++;	
				}
				echo "<section class=\"single_project\"><article class=\"clearfix\">";
				echo $strTXT;
				if(isset($arrIMGS))
				{
					echo "<div class=\"proj_gallery\"><ul class=\"small_thumbs clearfix\">";
					foreach($arridMED as $value)
					{
						echo "<li class=\"borderround_6\">";
						switch ($arrIMGS[$value]["type"])
						{
							case "img":
								$img = MEDIA."/images/".$arrIMGS[$value]["url"];
								$imgSize = getimagesize($img);
								if($imgSize[0]>$imgSize[1])
									$cls="stretch_img_height";
								else
									$cls="stretch_img_width";
								$img=IMAGES_AS_URL."/".$arrIMGS[$value]["url"];
								$img_retina = getFileNameFormat($img,LARGE,true);
								$img_small = getFileNameFormat($img,THUMB,false);
								$img = getFileNameFormat($img,LARGE,false);
								echo "<a href=\"$img\" class=\"fancybox\" rel=\"group\"><img src=\"$img_small\" alt=\"".$arrIMGS[$value]["caption"]."\" class=\"$cls\" /></a>";
								break;
							case "vid":
								echo "<a href=\"#\" ><img src=\"/imago/video_thumb_cover.png\" alt=\"".$arrIMGS[$value]["caption"]."\" class=\"stretch_img_height\" /></a>";
								break;
						}
						echo "</li>";
					}
					echo "</ul></div>";
				}
				if($strPDF!="")
					echo "<div class=\"pdfs\"><h3>PDF</h3><ul>$strPDF</ul></div>";
				echo "</article></section>";
				unset($strPDF);
				unset($strTXT);
			}
		}
		else // get all news
		{	
			$strSQL = "SELECT nws.id_NWS AS rowID, nwst.name, nwst.texto
						FROM ".PREFIX."_news AS nws JOIN ".PREFIX."_news_text AS nwst ON nws.id_NWS=nwst.id_NWS
						WHERE nwst.lan='$cmslan' AND nws.aktiv ORDER BY nws.uptime DESC";
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				echo "<section>";
				echo "<h1>news</h1>";
				$iID = null;
				while($row = $rs->fetchObject())
				{
					if($iID!=$row->rowID)
					{
						$iID=$row->rowID;
						$lnk = BASEURL_SUFFIX."/";
						if(FLATLINK)
							$lnk = BASEURL_SUFFIX."/$cmslan/nws/nws-$iID";		
						else
							$lnk = "?s=nws&p=$row->rowID&lan=$cmslan";
						echo "<article id=\"nws_$iID\" class=\"nws_roll\">";
						echo "<h2>$row->name</h2>";
						echo substr($row->texto, 0, 250)." <a href=\"$lnk\">[...]</a>";
						echo "</article>";
					}
				}
				echo "</section>";
			}
		}
		?>
		</div>
	</main>
</div>
<div id="dv_sideboard">
	<div id="dv_sideboard_intern">
		<section class="row help_thumb">
			<?php
            /*include HELP*/
            include_once("inc/layout_struktur/help_sideboard.inc.php");
            ?>
		</section>
	</div>
</div>