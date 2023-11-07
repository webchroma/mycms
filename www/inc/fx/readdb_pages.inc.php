<?php
// set table id names
$db = "".PREFIX."_pages";
$dbj = "".PREFIX."_pages_text";
$jID = "id_PAG";
$wPage="page";

if($p==null) // homepage
{
	 $strWHP = "db.home";
}
else
{
	if(FLATLINK)
		$strWHP = "db.symlink='".$objConn->prepMysql($p)."'";
	else
		$strWHP = "db.id_PAG=$p";
	
	// see what type of page it is || page, portfolio, gallery, blog || meta tags are stored on different tables
	$strSQL = "SELECT db.blog, db.portfolio, db.gallery, db.protekt FROM ".PREFIX."_pages AS db WHERE $strWHP";
	try
	{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			$row = $rs->fetchAssocArray();
			if($row["portfolio"])
				$wPage="portfolio";
			elseif($row["gallery"])
				$wPage="gallery";
			elseif($row["blog"])
				$wPage="blog";
			elseif($row["protekt"])
				$wPage="protekt";
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
	}
	if($pp!=null)
	{
		if($wPage=="gallery")
		{
			$db = "".PREFIX."_gallery";
			$dbj = "".PREFIX."_gallery_text";
			$jID = "id_GALL";
		}
		elseif($wPage=="blog")
		{
			$db = "".PREFIX."_news";
			$dbj = "".PREFIX."_news_text";
			$jID = "id_NEWS";
		}
		if(FLATLINK)
			$strWHP = "db.symlink='".$objConn->prepMysql($pp)."'";
		else
			$strWHP = "db.$jID=$pp";
	}
}

// get meta tags for the page
if($wPage!="blog")
{
	$strMETWHP = $strWHP;
	$pp!=null?$tp=$pp:$tp=$p;
	if($ppp!=null)	
		$tp=$ppp;
	if(FLATLINK)
		$strMETWHP = "db.symlink='".$objConn->prepMysql($tp)."'";
	else
		$strMETWHP = "db.$jID=$tp";
	
	$strSQL = "SELECT dbj.web_title, dbj.meta_key, dbj.meta_des FROM $dbj AS dbj
				JOIN $db AS db ON db.$jID=dbj.$jID  
				WHERE $strMETWHP AND dbj.lan='$cmslan'";
	try
	{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			$row = $rs->fetchAssocArray();
			foreach($row AS $key=>$value)
				if($value!="")
					$$key = $value;
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
	}
}

// BACKGROUND IMAGES
$strSQL = "SELECT md.id_MED, md.url, medcap.name
				FROM ".PREFIX."_media AS md
                LEFT JOIN ".PREFIX."_media_caption AS medcap ON md.id_MED=medcap.id_MED
				WHERE url=(SELECT thumb FROM ".PREFIX."_pages AS db WHERE $strWHP)";
try
{
    $rs = $objConn->rs_query($strSQL);
    if ($rs->count() > 0)
    {
        $i=0;
        while($row = $rs->fetchObject())
        {
            $arridMED[$i] = $row->id_MED;
            $arrIMGS[$row->id_MED]["url"] = $row->url;
            $arrIMGS[$row->id_MED]["type"] = "img";
            $arrIMGS[$row->id_MED]["caption"] = $row->medname;	
            $i++;
        }	
    }
}
catch(Exception $e)
{
    echo captcha($e);
}
?>