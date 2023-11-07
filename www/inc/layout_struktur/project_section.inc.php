<?php
echo "<section class=\"single_project\"><article class=\"clearfix\">";
echo $strTXT;
if(isset($id_PROJ_GALL)) // gallery associated with the project / retrieve images
{
    $strSQL = "SELECT med.*,
                (SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
                FROM ".PREFIX."_media AS med
                LEFT JOIN ".PREFIX."_gallery_media AS medgall ON med.id_MED=medgall.id_MED
                WHERE medgall.id_GALL=$id_PROJ_GALL ORDER BY POS";
	$rs = $objConn->rs_query($strSQL);
    if ($rs->count() > 0)
	{
        echo "<div class=\"proj_gallery\"><ul class=\"small_thumbs clearfix\">";
		while($row = $rs->fetchObject())
		{
            echo "<li class=\"borderround_6\">";
            $cls="";
            switch ($row->tipo)
            {
                case "img":
                    $img = MEDIA."/images/".$row->url;
                    $imgSize = getimagesize($img);
                    if($imgSize[0]>$imgSize[1])
                        $cls="stretch_img_height";
                    else
                        $cls="stretch_img_width";
                    $img=IMAGES_AS_URL."/".$row->url;
                    $img_retina = getFileNameFormat($img,LARGE,true);
                    $img_small = getFileNameFormat($img,THUMB,false);
                    $img = getFileNameFormat($img,LARGE,false);
                    echo "<a href=\"$img\" data-fancybox=\"group\" data-caption=\"$row->caption\"><img src=\"$img_small\" alt=\"$row->caption\" class=\"$cls\" /></a>";
                    break;
                case "vid":
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
				    $strVID = "<div class=\"video_container invisible\" id=\"dv_".$row->url."_".$row->id_MED."\"><video id=\"".$row->url."_".$row->id_MED."\" class=\"video-js vjs-default-skin\" controls preload=\"auto\" width=\"auto\" height=\"auto\"
						poster=\"/imago/video_cover.png\"
						data-setup=\"{}\">
						$src
						</video></div>";
				        echo "<a data-fancybox data-src=\"#dv_".$row->url."_".$row->id_MED."\" href=\"javascript:;\"><img src=\"/imago/video_thumb_cover.png\" alt=\"$row->caption\" class=\"stretch_img_height\" /></a>";
                    break;
                case "vimeo":
				    echo "<a data-fancybox href=\"https://vimeo.com/$row->url\" data-caption=\"$row->caption\"><img src=\"/imago/video_thumb_cover.png\" alt=\"$row->caption\" class=\"stretch_img_height\" /></a>";
            }
            echo "</li>";
        }
        echo "</ul></div>";
	}
}
echo $strINFO;
if($strPDF!="")
	echo "<div class=\"pdfonpage pdfs\"><h3>PDF</h3><ul>$strPDF</ul></div>";
echo "</article>";
echo "</section>";
unset($strPDF);
unset($strTXT);
?>