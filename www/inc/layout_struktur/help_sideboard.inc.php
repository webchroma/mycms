<?php
/* HELP IS PAGE _ symlink help, ID 15*/
$strSQL = "SELECT DISTINCT dbtxt.name, med.id_MED, med.tipo, med.url, med.link,
            (SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
            FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS dbtxt ON db.id_PAG=dbtxt.id_PAG
            LEFT JOIN ".PREFIX."_pages_media AS dbmed ON db.id_PAG=dbmed.id_PAG
            LEFT JOIN ".PREFIX."_media AS med ON dbmed.id_MED=med.id_MED
            WHERE db.id_PAG=15 AND dbtxt.lan='$cmslan'";
$rs = $objConn->rs_query($strSQL);
if ($rs->count() > 0)
{
    $row = $rs->fetchObject();
    if(FLATLINK)
        $lnk = BASEURL_SUFFIX."/$cmslan/page/help";		
    else
        $lnk = "?s=page&p=15&lan=$cmslan";
    $img = MEDIA."/images/".$row->url;
    $img = getFileNameFormat(IMAGES_AS_URL.$row->url,THUMB,false);
    echo "<ul>";
    echo "<li style=\"min-height:200px;background: url('$img');background-position:center top;background-repeat: no-repeat;background-size:cover;\">";
    echo "<a href=\"$lnk\">";
    //echo "<img src=\"$img\" alt=\"".$row->caption."\" />";
    echo "<h2><span>".strtoupper($row->name)."</span></h2>";
    echo "</a>";
    echo "</li></ul>";
    echo "<style></style>";
}
?>