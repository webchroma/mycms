<div id="dv_main">
	<main>
	<?php
	if(isset($ppp)) // SHOW SINGLE PROJECT
	{
		$sqlID = "id_PROJ";
		if(FLATLINK)
			$strWHP = "db.symlink='".$objConn->prepMysql($ppp)."'";
		else
			$strWHP = "db.$sqlID=$ppp";
		$strSQL = "SELECT db.*, dbtxt.*, med.*, dbgall.id_GALL,
					(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
					FROM ".PREFIX."_project AS db JOIN ".PREFIX."_project_text AS dbtxt ON db.$sqlID=dbtxt.$sqlID
					LEFT JOIN ".PREFIX."_project_gallery AS dbgall ON db.$sqlID=dbgall.$sqlID
                    LEFT JOIN ".PREFIX."_project_media AS medproj ON medproj.id_PROJ=db.id_PROJ
					LEFT JOIN ".PREFIX."_media AS med ON medproj.id_MED=med.id_MED
					WHERE $strWHP AND dbtxt.lan='$cmslan' ORDER BY med.pos ASC, med.tipo DESC";
        try
		{
			include("inc/layout_struktur/retrieve_project_data.inc.php");
		}
		catch(Exception $e)
		{
			echo captcha($e);
		}
	}
	else
	{
		// Fetch Projects
		if(isset($p)&&$p!="-")
		{
			if(FLATLINK)
				$strWHPSEC = "sec.symlink='".$objConn->prepMysql($p)."' AND ";
			else
				$strWHPSEC = "sec.id_SEC=$p AND";
		}
		else
			$strWHPSEC="";
		if(isset($pp)&&$pp!="-")
		{
			if(FLATLINK)
				$strWHPSEC = $strWHPSEC."lnd.symlink='".$objConn->prepMysql($pp)."' AND ";
			else
				$strWHPSEC = $strWHPSEC."lnd.id_LAND=$pp AND ";
		}
        $strSQL = "SELECT DISTINCT db.id_PROJ AS rowID, db.symlink, db.thumb, dbtxt.name, dbtxt.texto, sec.id_SEC,sec.symlink AS secsym, lnd.id_LAND, lnd.symlink AS lndsym
					FROM ".PREFIX."_project AS db JOIN ".PREFIX."_project_text AS dbtxt ON db.id_PROJ=dbtxt.id_PROJ
					LEFT JOIN ".PREFIX."_project_section AS secp ON secp.id_PROJ=db.id_PROJ
					LEFT JOIN ".PREFIX."_section AS sec ON secp.id_SEC=sec.id_SEC
					LEFT JOIN ".PREFIX."_project_land AS lndp ON lndp.id_PROJ=db.id_PROJ
					LEFT JOIN ".PREFIX."_land AS lnd ON lndp.id_LAND=lnd.id_LAND
					WHERE $strWHPSEC db.aktiv AND dbtxt.lan='$cmslan' ORDER BY db.pos";
        $rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			echo "<div id=\"proje_roll\" class=\"row\">";
			$iID = null;
			while($row = $rs->fetchObject())
			{
				if($iID!=$row->rowID)
				{
					$iID=$row->rowID;
					$lnk = BASEURL_SUFFIX."/";
                    if($row->secsym=="")
                        $row->secsym="-";
                    if($row->lndsym=="")
                        $row->lndsym="-";
					if(FLATLINK)
						$lnk = BASEURL_SUFFIX."/$cmslan/project/$row->secsym/$row->lndsym/$row->symlink";		
					else
						$lnk = "?s=project&ppp=$row->rowID&lan=$cmslan";
                    if($row->thumb!="")
                    {
                        $img=getFileNameFormat(IMAGES_AS_URL.$row->thumb,LARGE);
                        $cls="";
                    }   
                    else
                    {
                        $cls="  class=\"noimage\"";
                        $img = "/imago/placebo.png";
                    }
					echo "<article id=\"proje_$iID\"$cls>";
					echo "<a href=\"$lnk\">";
					echo "<h2 class=\"o9\">$row->name</h2>";
                    
					echo "<img src=\"$img\" alt=\"".$row->name."\"/>";
					echo "</a>";
					echo "</article>";

				}
			}
			echo "</div>";
		}
	}
	?>	
	</main>
</div>
<?php
if(($p==null||$p=="-")&&($pp==null||$pp=="-"))
    include("inc/layout_struktur/sideboard.inc.php");
else
{
    echo "<div id=\"dv_sideboard\">";
    echo "<div id=\"dv_sideboard_intern\">";
    $strSQL="";
    // get information on section/project - build sideboard
    if(isset($p)) // have a section
    {
        if(FLATLINK)
            $strWHP = "db.symlink='".$objConn->prepMysql($p)."'";
        else
            $strWHP = "db.id_SEC=$p";
        $strSQL = "SELECT db.thumb, pagtxt.name, pagtxt.texto, med.id_MED, med.tipo, med.url, med.link,
                    (SELECT COUNT(id_MED) FROM ".PREFIX."_section_media WHERE id_SEC=db.id_SEC) AS tmed,
                    (SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
                    FROM ".PREFIX."_section AS db JOIN ".PREFIX."_section_text AS pagtxt ON db.id_SEC=pagtxt.id_SEC
                    LEFT JOIN ".PREFIX."_section_media AS pagmed ON db.id_SEC=pagmed.id_SEC
                    LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
                    WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
        include("inc/layout_struktur/retrieve_page-sideboard_data.inc.php");
    }
    if(isset($pp)) // have a land
    {
        if(FLATLINK)
            $strWHP = "db.symlink='".$objConn->prepMysql($pp)."'";
        else
            $strWHP = "db.id_LAND=$pp";
        $strSQL = "SELECT db.thumb, pagtxt.name, pagtxt.texto, med.id_MED, med.tipo, med.url, med.link,
                    (SELECT COUNT(id_MED) FROM ".PREFIX."_land_media WHERE id_LAND=db.id_LAND) AS tmed,
                    (SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
                    FROM ".PREFIX."_land AS db JOIN ".PREFIX."_land_text AS pagtxt ON db.id_LAND=pagtxt.id_LAND
                    LEFT JOIN ".PREFIX."_land_media AS pagmed ON db.id_LAND=pagmed.id_LAND
                    LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
                    WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";

        include("inc/layout_struktur/retrieve_page-sideboard_data.inc.php");
    }
    echo "</div></div>";
}
?>
