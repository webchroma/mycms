<?php
// set table id names
$db = "".PREFIX."_section";
$dbj = "".PREFIX."_section_text";
$jID = "id_SEC";
$wPage="section";
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
// BACKGROUND IMAGES
if($p!=null)
{
	if(FLATLINK)
		$strID = "(SELECT p.id_PAG FROM ".PREFIX."_pages AS p WHERE p.symlink='".$objConn->prepMysql($p)."')";
	else
		$strID = $p;

	$strSQL = "SELECT md.*,
				(SELECT name FROM ".PREFIX."_media_caption WHERE id_MED=md.id_MED AND lan='$cmslan') AS medname
				FROM ".PREFIX."_media AS md
				LEFT JOIN ".PREFIX."_pages_media AS pagmed ON md.id_MED=pagmed.id_MED
				WHERE tipo='img'
				AND pagmed.id_PAG=$strID";
	try
	{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			$i=1;
			while($row = $rs->fetchObject())
			{
				$arridMED[$row->id_MED] = $row->id_MED;
				$arrIMGS[$row->id_MED]["url"] = $row->url;
				$arrIMGS[$row->id_MED]["caption"] = $row->medname;
				$i++;
			}	
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
	}
}
?>