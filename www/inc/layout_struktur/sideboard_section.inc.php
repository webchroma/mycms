<?php
echo "<section>";
echo $strTXT;
if($dosideboardimages&&isset($arrIMGS))
{
	echo "<div class=\"sideboard_gallery\"><ul class=\"small_thumbs clearfix\">";
	foreach($arridMED as $value)
	{
        if($value!=0)
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
                    echo "<a href=\"$img\" data-fancybox=\"group\" data-caption=\"".$arrIMGS[$value]["caption"]."\"><img src=\"$img_small\" alt=\"".$arrIMGS[$value]["caption"]."\" class=\"$cls\" /></a>";
                    break;
                case "vid":
				    // check which formats are available
				    $fld = MEDIA."videos/".$arrIMGS[$value]["url"];
				    $src = "";
				    $vids = array_diff(scandir($fld), array('..', '.'));
				    foreach($vids AS $vid)
				    {
				        if($vid!="." || $vid!="..")
				        {
				            $src .= "<source src=\"".VIDEOS_AS_URL.$arrIMGS[$value]["url"]."/$vid\"";
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
				    $strVID = "<div class=\"video_container invisible\" id=\"dv_".$arrIMGS[$value]["url"]."_".$value."\"><video id=\"".$arrIMGS[$value]["url"]."_".$value."\" class=\"video-js vjs-default-skin\" controls preload=\"auto\" width=\"auto\" height=\"auto\"
						poster=\"/imago/video_cover.png\"
						data-setup=\"{}\">
						$src
						</video></div>";
				        echo "<a data-fancybox data-src=\"#dv_".$arrIMGS[$value]["url"]."_".$value."\" href=\"javascript:;\"><img src=\"/imago/video_thumb_cover.png\" alt=\"$row->caption\" class=\"stretch_img_height\" /></a>";
                    break;
                case "vimeo":
				    echo "<a data-fancybox href=\"https://vimeo.com/".$arrIMGS[$value]["url"]."\" data-caption=\"".$arrIMGS[$value]["caption"]."\"><img src=\"/imago/video_thumb_cover.png\" alt=\"".$arrIMGS[$value]["caption"]."\" class=\"stretch_img_height\" /></a>";
            }
            echo "</li>";
        }
	}
	echo "</ul></div>";
}
if($strPDF!="")
	echo "<div class=\"pdfs\"><h3>PDF</h3><ul>$strPDF</ul></div>";
echo "</section>";
?>