<div id="dv_main">
	<main>
        <?php
        /*fetch sections */
        $strSQL = "SELECT db.id_SEC AS rowID, db.symlink AS symlink, db.thumb, pagtxt.name, pagtxt.texto
                    FROM ".PREFIX."_section AS db JOIN ".PREFIX."_section_text AS pagtxt ON db.id_SEC=pagtxt.id_SEC
                    WHERE db.aktiv AND pagtxt.lan='$cmslan' ORDER BY db.pos ASC";
        $rs = $objConn->rs_query($strSQL);
        if ($rs->count() > 1)
        {
            echo "<div id=\"owl_roll\" class=\"start-carousel owl-theme\">";
            $iID = null;
            while($row = $rs->fetchObject())
            {
                if($iID!=$row->rowID)
                {
                    $iID=$row->rowID;
                    $lnk = BASEURL_SUFFIX."/";
                    if(FLATLINK)
                        $lnk = BASEURL_SUFFIX."/$cmslan/project/$row->symlink/";		
                    else
                        $lnk = "?s=project&p=$row->rowID&lan=$cmslan";
                    echo "<article id=\"proje_$iID\" class=\"item\">";
                    echo "<a href=\"$lnk\">";
                    echo "<h2 class=\"w_90\">".strtoupper($row->name)."</h2>";
                    if($row->thumb!="")
                        $img = getFileNameFormat(IMAGES_AS_URL.$row->thumb,LARGE,false);
                    else
                        $img = "imago/placebo.png";
                    echo "<img src=\"$img\" alt=\"".strtoupper($row->name)."\"/>";
                    echo "</a>";
                    echo "</article>";

                }
            }
            echo "</div>";
        }
        /*x
        // SUPPORT GRT
        $strSQL = "SELECT DISTINCT dbtxt.name, db.thumb
            FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS dbtxt ON db.id_PAG=dbtxt.id_PAG
            WHERE db.id_PAG=15 AND dbtxt.lan='$cmslan'";
        $rs = $objConn->rs_query($strSQL);
        if ($rs->count() > 0)
        {
            $row = $rs->fetchObject();
            if(FLATLINK)
                $lnk = BASEURL_SUFFIX."/$cmslan/page/help";		
            else
                $lnk = "?s=page&p=15&lan=$cmslan";
            $img = MEDIA."/images/".$row->thumb;
            $img = getFileNameFormat(IMAGES_AS_URL.$row->thumb,LARGE,false);
            echo "<div id=\"proje_roll\" class=\"proje_roll start row\">";
            echo "<ul class=\"support_grt\">";
            echo "<li style=\"min-height:200px;background: url('$img');background-position:center center;background-repeat: no-repeat;background-size:cover;\">";
            echo "<a href=\"$lnk\">";
            //echo "<img src=\"$img\" alt=\"".$row->caption."\" />";
            echo "<h2>".strtoupper($row->name)."</h2>";
            echo "</a>";
            echo "</li></ul></div>";
        }
        */
        // NEWS
        echo "<div id=\"proje_roll\" class=\"start row\">";
        echo "<h1>".$arrTextes["GRT"]["NEWS"]."</h1>";
        echo "<ul class=\"small_thumbs clearfix\">";
        $tnwsfetch = 4;
        // fetch news
        unset($row);
        $strSQL = "SELECT nws.id_NWS AS rowID, nws.datum, nws.thumb, nwst.name, nwst.texto, (SELECT COUNT(id_NWS) FROM ".PREFIX."_news WHERE nwst.lan='$cmslan' AND nws.aktiv) AS tnws 
        FROM ".PREFIX."_news AS nws JOIN ".PREFIX."_news_text AS nwst ON nws.id_NWS=nwst.id_NWS
        WHERE nwst.lan='$cmslan' AND nws.aktiv ORDER BY nws.datum DESC LIMIT $tnwsfetch";
        $rs = $objConn->rs_query($strSQL);
        if ($rs->count() > 0)
        {
            $iID = null;
            while($row = $rs->fetchObject())
            {
                $tnws=$row->tnws;
                if($iID!=$row->rowID)
                {
                    $iID=$row->rowID;
                    $lnk = BASEURL_SUFFIX."/";
                    if(FLATLINK)
						$lnk = BASEURL_SUFFIX."/$cmslan/nws/nws-$iID";		
                    else
						$lnk = "?s=nws&p=$row->rowID&lan=$cmslan";
                    echo "<li class=\"news\">";
                    if($row->thumb!="")
                    {
                        $img = MEDIA."images/".$row->thumb;
                        $imgSize = getimagesize($img);
                        if($imgSize[0]>$imgSize[1])
                           $cls="stretch_img_height";
                        else
                           $cls="stretch_img_width";
                        $img = getFileNameFormat(IMAGES_AS_URL.$row->thumb,THUMB,false);
                    }
                    else
                        $img = "/imago/placebo.png";
                    echo "<span class=\"img_cover\"><img src=\"$img\" alt=\"$row->name\" /></span>";
                    //echo "<span class=\"nws_datum\">".date("d M Y", strtotime($row->datum))."</span>";
                    echo "<h2><span>".strtoupper($row->name)."</span></h2>";
                    echo "<span class=\"small_thumbs_text\">".substr($row->texto, 0, 250)." ...</span>";
                    echo "<span class=\"morenws\"><a href=\"$lnk\">".$arrTextes["GRT"]["morenews"]."</a></span>";
                    echo "</li>";
                }
            }
            unset($row);
		}
        unset($rs);
        echo "</div>";
        // PROJECT HIGHLIGHTS
        $thighfetch = 8;
        $strSQL = "SELECT DISTINCT db.id_PROJ AS rowID, db.symlink, db.thumb, dbtxt.name, sec.id_SEC, sec.symlink AS secsym, lnd.id_LAND, lnd.symlink AS lndsym
                    FROM ".PREFIX."_project AS db JOIN ".PREFIX."_project_text AS dbtxt ON db.id_PROJ=dbtxt.id_PROJ
                    LEFT JOIN ".PREFIX."_project_section AS secp ON secp.id_PROJ=db.id_PROJ
                    LEFT JOIN ".PREFIX."_section AS sec ON secp.id_SEC=sec.id_SEC
                    LEFT JOIN ".PREFIX."_project_land AS lndp ON lndp.id_PROJ=db.id_PROJ
                    LEFT JOIN ".PREFIX."_land AS lnd ON lndp.id_LAND=lnd.id_LAND
                    WHERE db.aktiv AND db.highlight AND dbtxt.lan='$cmslan' ORDER BY db.pos";
        $rs = $objConn->rs_query($strSQL);
        if ($rs->count() > 1)
        {
            echo "<div id=\"proje_roll\" class=\"start row\">";
            echo "<h1>".$arrTextes["GRT"]["highlights"]."</h1>";
            echo "<ul class=\"small_thumbs clearfix\">";
            $iID = null;
            while($row = $rs->fetchObject())
            {
                if($iID!=$row->rowID)
                {
                    echo "<li class=\"proj\">";
                    $iID=$row->rowID;
                    $lnk = BASEURL_SUFFIX."/";
                    if($row->secsym=="")
                        $row->secsym="-";
                    if($row->lndsym=="")
                        $row->lndsym="-";
                    if(FLATLINK)
                        $lnk = BASEURL_SUFFIX."/$cmslan/project/$row->secsym/$row->lndsym/$row->symlink";		
                    else
                        $lnk = "?s=project&p=$row->id_SEC&pp=$row->id_LAND&ppp=$row->rowID&lan=$cmslan";
                    echo "<a href=\"$lnk\">";
                    if($row->thumb!="")
                    {
                        $img = MEDIA."images/".$row->thumb;
                        $imgSize = getimagesize($img);
                        if($imgSize[0]>$imgSize[1])
                           $cls="stretch_img_height";
                        else
                           $cls="stretch_img_width";
                        $img = getFileNameFormat(IMAGES_AS_URL.$row->thumb,THUMB,false);
                    }
                    else
                        $img = "/imago/placebo.png";
                    echo "<span class=\"img_cover\"><img src=\"$img\" alt=\"$row->name\" /></span>";
                    echo "<h2><span>".strtoupper($row->name)."</span></h2>";
                    echo "</a>";
                    echo "</li>";				
                }	
            }
            echo "</ul></div>";
            unset($row);
        }
        unset($rs);
        ?>
	</main>
</div>
<?php
try
{
     //include("inc/layout_struktur/sideboard.inc.php");
}
catch(Exception $e)
{
    echo captcha($e);
}
?>