<?php
/* import functions and php settings */
include_once("inc/fx/page_header.inc.php");

$strXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$strXML .= "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
			xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\"
			xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
$strXML .= "\t<url>\n";
$strXML .= "\t\t<loc>".BASEURL."/</loc>\n";
$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime(LASTUPD))."</lastmod>\n";
$strXML .= "\t\t<changefreq>monthly</changefreq>\n";
$strXML .= "\t\t<priority>1</priority>\n";
$strXML .= "\t</url>\n";

/* get pages */
$strSQL = "SELECT DISTINCT 
			db.id_PAG as thID, db.symlink, db.parent_id AS parID,
			(SELECT symlink FROM ".PREFIX."_pages WHERE id_PAG=parID) AS parSymlink,
			IF((SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=parID)!=0,(SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=parID),
			0) AS parparID,
			(SELECT symlink FROM ".PREFIX."_pages WHERE id_PAG=parparID) AS parparSymlink,
			db.uptime AS puptime, pagtxt.uptime AS ptxtuptime, pagmed.uptime AS pmeduptime, 
			(SELECT uptime FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='$cmslan')AS medcuptime,
			db.pos AS pos, db.home, db.portfolio, db.blog
			FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG 
			LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG 
			LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED 
			WHERE pagtxt.lan='$cmslan' AND db.aktiv ORDER BY thID ASC, pos ASC, parID ASC, parparID ASC
			";
//echo $strSQL;
//exit;
try{
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		
		$i = 1;
		while($row = $rs->fetchObject())
		{
			if(!$row->home)
			{
				if($row->portfolio) // there is a portfolio
				{
					$portID = $row->thID;
					$portSYM = $row->symlink;
				}		
				if($row->blog) // there is a news page
				{
					$newsID = $row->thID;
					$newsSYM = $row->symlink;
				}
				
				if(FLATLINK)
				{
					$lnk = "$cmslan/";
					if($row->parparSymlink!=null)
						$lnk .= "$row->parparSymlink/";
					if($row->parSymlink!=null)
						$lnk .= "$row->parSymlink/";	
					$lnk .= "$row->symlink";
				}		
				else
				{

					$lnk = "?lan=$cmslan&amp;";
					$p = "p=$row->thID";
					if($row->parID!=0)
						$p = "p=$row->parID&amp;pp=$row->thID";
					if($row->parparID!=0)
						$p = "p=$row->parparID&amp;pp=$row->parID&amp;ppp=$row->thID";	
					$lnk .= $p;
				}	
				$strXML .= "\t<url>\n";
				$strXML .= "\t\t<loc>".BASEURL."/$lnk</loc>\n";
				$row->puptime>$row->ptxtuptime?$uptime=$row->puptime:$uptime=$row->ptxtuptime;
				if($row->pmeduptime>$uptime)
					$uptime=$row->pmeduptime;
				if($row->medcuptime>$uptime)
					$uptime=$row->medcuptime;	
				$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime($uptime))."</lastmod>\n";
				$strXML .= "\t\t<changefreq>monthly</changefreq>\n";
				if($row->parID==0)
					$prior = "0.9";
				else
				{
					if($row->parparID==0)
						$prior = "0.8";
					else
						$prior = "0.7";
				}
				$strXML .= "\t\t<priority>$prior</priority>\n";
				$strXML .= "\t</url>\n";
			}
		}
	}
}
catch(Exception $e)
{
	echo captcha($e);
	exit;
}

/* get news */
if(isset($newsID)&&$newsID!="")
{
	$strSQL = "SELECT DISTINCT 
				db.id_PAG as thID, db.symlink, db.parent_id AS parID,
				(SELECT symlink FROM ".PREFIX."_pages WHERE id_PAG=parID) AS parSymlink,
				IF((SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=parID)!=0,(SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=parID),
				0) AS parparID,
				(SELECT symlink FROM ".PREFIX."_pages WHERE id_PAG=parparID) AS parparSymlink,
				db.uptime AS puptime, pagtxt.uptime AS ptxtuptime, pagmed.uptime AS pmeduptime, 
				(SELECT uptime FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='$cmslan')AS medcuptime,
				db.pos AS pos
				FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG 
				LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG 
				LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED 
				WHERE pagtxt.lan='$cmslan' AND db.aktiv ORDER BY thID ASC, pos ASC, parID ASC, parparID ASC
				";
	//echo $strSQL;
	//exit;
	try{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{

			$i = 1;
			while($row = $rs->fetchObject())
			{
				$strXML .= "\t<url>\n";
				if(FLATLINK)
				{
					$lnk = "$cmslan/";
					if($row->parparSymlink!=null)
						$lnk .= "$row->parparSymlink/";
					if($row->parSymlink!=null)
						$lnk .= "$row->parSymlink/";	
					$lnk .= "$row->symlink";
				}		
				else
				{

					$lnk = "?lan=$cmslan&amp;";
					$p = "p=$row->thID";
					if($row->parID!=0)
						$p = "p=$row->parID&amp;pp=$row->thID";
					if($row->parparID!=0)
						$p = "p=$row->parparID&amp;pp=$row->parID&amp;ppp=$row->thID";	
					$lnk .= $p;
				}	
				$strXML .= "\t\t<loc>".BASEURL."/$lnk</loc>\n";
				$row->puptime>$row->ptxtuptime?$uptime=$row->puptime:$uptime=$row->ptxtuptime;
				if($row->pmeduptime>$uptime)
					$uptime=$row->pmeduptime;
				if($row->medcuptime>$uptime)
					$uptime=$row->medcuptime;	
				$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime($uptime))."</lastmod>\n";
				$strXML .= "\t\t<changefreq>monthly</changefreq>\n";
				if($row->parID==0)
					$prior = "0.9";
				else
				{
					if($row->parparID==0)
						$prior = "0.8";
					else
						$prior = "0.7";
				}
				$strXML .= "\t\t<priority>$prior</priority>\n";
				$strXML .= "\t</url>\n";
			}
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
		exit;
	}
}

/* get portfolio */
if(isset($portID)&&$portID!="")
{
	/* all medias */
	$strSQL = "SELECT DISTINCT 
				db.id_GALL as gallID, db.symlink AS symlink, med.id_MED AS medID,
				db.uptime AS puptime, dbtxt.uptime AS ptxtuptime, dbmed.uptime AS pmeduptime, 
				(SELECT uptime FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='$cmslan') AS medcuptime,
				db.pos AS pos, med.pos AS medpos
				FROM ".PREFIX."_gallery AS db JOIN ".PREFIX."_gallery_text AS dbtxt ON db.id_GALL=dbtxt.id_GALL 
				LEFT JOIN ".PREFIX."_gallery_media AS dbmed ON db.id_GALL=dbmed.id_GALL 
				LEFT JOIN ".PREFIX."_media AS med ON dbmed.id_MED=med.id_MED 
				WHERE dbtxt.lan='$cmslan' AND db.aktiv 
				ORDER BY pos ASC, medpos ASC, puptime DESC, ptxtuptime DESC, pmeduptime DESC, medcuptime DESC";
	//echo $strSQL;
	//exit;
	try{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			$i = 1;
			$thSYM = null;
			foreach($rs AS $row)
			{
				if($thSYM!=$row->symlink) // put only galleries, no media
				{
					$thSYM=$row->symlink;
					if(FLATLINK)
						$lnk = "$cmslan/$portSYM/$row->symlink";		
					else
						$lnk = "?lan=$cmslan&amp;p=$portID&amp;pp=$row->gallID";
					$strXML .= "\t<url>\n";
					$strXML .= "\t\t<loc>".BASEURL."/$lnk</loc>\n";
					
					$row->puptime>$row->ptxtuptime?$uptime=$row->puptime:$uptime=$row->ptxtuptime;
					if($row->pmeduptime>$uptime)
						$uptime=$row->pmeduptime;
					if($row->medcuptime>$uptime)
						$uptime=$row->medcuptime;	
					$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime($uptime))."</lastmod>\n";
					$strXML .= "\t\t<changefreq>monthly</changefreq>\n";
					$prior = "0.8";
					$strXML .= "\t\t<priority>$prior</priority>\n";
					$strXML .= "\t</url>\n";
				}
				if(FLATLINK)
					$lnk = "$cmslan/$portSYM/$row->symlink/$row->medID";		
				else
					$lnk = "?lan=$cmslan&amp;p=$portID&amp;pp=$row->gallID&amp;med=$row->medID";
				$strXML .= "\t<url>\n";
				$strXML .= "\t\t<loc>".BASEURL."/$lnk</loc>\n";
				
				$row->puptime>$row->ptxtuptime?$uptime=$row->puptime:$uptime=$row->ptxtuptime;
				if($row->pmeduptime>$uptime)
					$uptime=$row->pmeduptime;
				if($row->medcuptime>$uptime)
					$uptime=$row->medcuptime;	
				$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime($uptime))."</lastmod>\n";
				$strXML .= "\t\t<changefreq>monthly</changefreq>\n";
				$prior = "0.6";
				$strXML .= "\t\t<priority>$prior</priority>\n";
				$strXML .= "\t</url>\n";
			}
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
		exit;
	}
}

/* output */
$strXML .= "</urlset>";
echo $strXML;
?>


     

     

     
