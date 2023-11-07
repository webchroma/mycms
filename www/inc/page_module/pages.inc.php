<div id="dv_main">
	<main>
		<div class="row">
		<?php
		// get page text
		$strSQL = "SELECT db.protekt, pagtxt.name, pagtxt.texto, med.id_MED, med.tipo, med.url, med.link,
					(SELECT COUNT(id_MED) FROM ".PREFIX."_pages_media WHERE $strWHP) AS tmed,
					(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
					FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
					LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG
					LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
					WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
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
                    $i++;
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
			echo "<article>";
            /* include if requested contact form */
            include_once("inc/fx/kform.inc.php");
            echo $strTXT;
			echo "</article>";
            if($strPDF!="")
				echo "<div class=\"pdfonpage pdfs\"><h3>PDF</h3><ul>$strPDF</ul></div>";
		}
		?>
		</div>
	</main>
</div>
<?php
 try
{
     include("inc/layout_struktur/sideboard.inc.php");
}
catch(Exception $e)
{
    echo captcha($e);
}
?>