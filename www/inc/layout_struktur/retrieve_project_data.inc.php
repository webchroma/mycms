<?php
try
{
	$rs = $objConn->rs_query($strSQL);
	$i=0;
	$strPDF = "";
	if ($rs->count() > 0)
	{
		while($row = $rs->fetchObject())
		{
			if($i==0)
			{   
                
				$strTXT = "<h1>$row->name</h1>";
				$strTXT .= "<div class=\"columns\">$row->texto</div>";
                $strINFO = "";
                if($row->target!="")
				    $strINFO .= "<div class=\"clm_2\"><h2 class=\"mb5\">".$arrTextes["forms"]["target"]."</h2>$row->target</div>";
                if($row->result!="")
				    $strINFO .= "<div class=\"clm_2\"><h2 class=\"mb5\">".$arrTextes["forms"]["result"]."</h2>$row->result</div>";
				if($row->fund!="")
				    $strINFO .= "<h2 class=\"mb5\">".$arrTextes["forms"]["fund"]."</h2>$row->fund";
                
                if($row->thumb!=""||$row->id_MED!=null)
                {
                    unset($arridMED);
                    unset($arrIMGS);
                }
                if($row->thumb!="")
                {
                    $arridMED[$i] = $i;
				    $arrIMGS[$i]["url"] = $row->thumb;
				    $arrIMGS[$i]["type"] = "img";
				    $arrIMGS[$i]["caption"] = "";
                    $i++;
                }
                if($row->id_GALL!=null)
                    $id_PROJ_GALL=$row->id_GALL;
			}
			if($row->id_MED!=null)
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
		include("project_section.inc.php");
	}
}
catch(Exception $e)
{
	echo captcha($e);
}
?>