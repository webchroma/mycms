<?php
// check if text or background image

$strSQL = "SELECT db.protekt,pagtxt.texto, med.tipo, med.url, med.link,
			(SELECT COUNT(id_MED) FROM ".PREFIX."_pages_media WHERE $strWHP) AS tmed,
			(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
			FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
			LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG
			LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
			WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
$rs = $objConn->rs_query($strSQL);
$i=1;
$strPDF="";
if ($rs->count() > 0)
{
	while($row = $rs->fetchObject())
	{
		if($i==1)
			$strTXT = $row->texto;
		$i++;
		if($row->tmed>0)
		{
			if($row->tipo=="data")
			{
				if($row->caption=="")
					$row->caption=$row->url;
				$strPDF = "<div class=\"pdfs\">PDF<br /><a href=\"".DATA_AS_URL."$row->url\"><em>$row->caption</em></a></div>";
			}
			if($row->tipo=="img")
			{
				$arrIMGS[$i]["url"] = $row->url;
				$arrIMGS[$i]["caption"] = $row->caption;	
			}
			
		}	
	}
	//echo $strTXT;
}

if($wPage=="portfolio"&&$pp==null) // show portfolio page or gallery thumbnails
{
	if(PORTFOLIO_THMBS) // show gallery thumbnails
	{
		if(FLATLINK)
			$strID = "(SELECT p.id_PAG FROM ".PREFIX."_pages AS p WHERE p.symlink='".$objConn->prepMysql($p)."')";
		else
			$strID = $p;
		$strSQL = "SELECT gln.name, gl.id_GALL AS rowID, gl.symlink, 
					IF(gl.thumb!='',gl.thumb,(SELECT med.url FROM ".PREFIX."_media AS med JOIN ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED WHERE glm.id_GALL=gl.id_GALL LIMIT 1)) AS thumb 
					FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL 
					WHERE gln.lan='$cmslan' AND gl.aktiv
					AND gl.id_GALL IN(SELECT id_GALL FROM ".PREFIX."_pages_gallery WHERE id_PAG=$strID) 
					ORDER BY gl.pos";
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			/*
			echo "<div id=\"gall_list\">";
				echo "<ul>";
					foreach ($rs as $row)
					{
						if(FLATLINK)
							$lnk = BASEURL_SUFFIX."/$cmslan/$p/$row->symlink";		
						else
							$lnk = "?p=$p&pp=$row->rowID&lan=$cmslan";
						echo "<li><a href=\"$lnk\">$row->name</a></li>";
					}
				echo "</ul>";
			echo "</div>";
			*/
			echo "<div id=\"gall_preview\">";
			while($row = $rs->fetchObject())
			{
				if(FLATLINK)
					$lnk = BASEURL_SUFFIX."/$cmslan/$p/$row->symlink";		
				else
					$lnk = "?p=$p&pp=$row->rowID&lan=$cmslan";
				echo "<div class=\"gall_thmbs\">";
				echo "<a href=\"$lnk\">";
				if($row->thumb!="")
					echo "<img src=\"".IMAGES_AS_URL."$row->thumb\" alt=\"\" /><br />";
				echo "<div class=\"gall_thmbs_txt\"><h1>$row->name</h1></div>";
				echo "</a>";
				echo "</div>";
			}
			echo "<br class=\"clear\" />";
			echo "&nbsp;";
			echo "</div>";
		}
	}
	else // show portfolio page
	{
		// get page text
		$strSQL = "SELECT db.protekt,pagtxt.texto, med.tipo, med.url, med.link,
					(SELECT COUNT(id_MED) FROM ".PREFIX."_pages_media WHERE $strWHP) AS tmed,
					(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
					FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
					LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG
					LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
					WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			while($row = $rs->fetchObject())
			{
				echo $row->texto;
			}
		}
	}
}
else // got a gallery || in portfolio or as page type
{
	if($wPage=="portfolio") // the gallery is in a portfolio
		if(FLATLINK)
			$strWHP = "db.symlink='".$objConn->prepMysql($pp)."'";
		else
			$strWHP = "db.id_GALL=$pp";
	elseif($wPage=="gallery") // the gallery is associated with a page
	{
		$strWHP = "db.id_GALL=(SELECT pgg.id_GALL FROM ".PREFIX."_pages_gallery AS pgg JOIN ".PREFIX."_pages AS pg ON pgg.id_PAG=pg.id_PAG WHERE";
		if(FLATLINK)
			$strWHP .= " pg.symlink='".$objConn->prepMysql($p)."')";
		else
			$strWHP .= " pg.id_GALL=$p)";
		if(isset($pp)&&!isset($_GET["med"]))
			$_GET["med"] = $pp;
	}
	$strSQL = "SELECT gln.name, gln.texto, med.id_MED, med.url, med.tipo,
				(SELECT medcap.name FROM ".PREFIX."_media_caption AS medcap WHERE medcap.id_MED=med.id_MED AND lan='$cmslan') AS medname
				FROM ".PREFIX."_media AS med
				LEFT JOIN  ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED
				LEFT JOIN ".PREFIX."_gallery_text AS gln ON glm.id_GALL=gln.id_GALL
				LEFT JOIN ".PREFIX."_gallery AS db ON gln.id_GALL=db.id_GALL
				WHERE $strWHP AND gln.lan='$cmslan' AND db.thumb!=med.url ORDER BY med.pos";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$activate_gallery = true;
		$i=1;
		unset($arrIMGS);
		while($row = $rs->fetchObject())
		{
			$arridMED[$row->id_MED] = $row->id_MED;
			$arrIMGS[$row->id_MED]["url"] = $row->url;
			$arrIMGS[$row->id_MED]["caption"] = $row->medname;
			$arrIMGS[$row->id_MED]["type"] = $row->tipo;
			if($i==1)
				$firstMED = $row->id_MED;
			$i++;
			$port_title = $row->name;
			$port_text = $row->texto;
			if($row->tipo=="data")
			{
				if($row->medname=="")
					$row->medname=$row->url;
				$strPDF .= "<li><a href=\"".DATA_AS_URL."$row->url\"><em>$row->medname</em></a></li>";
			}
		}

		/* 
		NAVIGATION
		*/
		
		if($wPage=="portfolio")
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/$p/$pp/";		
			else
				$lnk = "?p=$p&pp=$pp&lan=$cmslan&med=";
		elseif($wPage=="gallery")
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/$p/";		
			else
				$lnk = "?p=$p&pp=$pp&lan=$cmslan&med=";
		
		if((!isset($_GET["med"])||$_GET["med"]=="")&&$port_text!="")
		{
			$doPortTxt = true;
			$nxtlnk = $lnk.$firstMED;
		}
		else
		{
			$doPortTxt = false;
			if((isset($_GET["med"])&&$firstMED==$_GET["med"])&&$port_text!="")
				$prevlnk = $lnk;
			if(isset($_GET["med"])&&$firstMED!=$_GET["med"])
				$prevlnk = $lnk.getprevious($_GET["med"],$arridMED);
			if(!isset($_GET["med"]))
				$_GET["med"]=$firstMED;
			if($_GET["med"]!=end($arridMED)&&$rs->count() > 1)
				$nxtlnk = $lnk.getnext($_GET["med"],$arridMED);
		}		
		
		echo "<div id=\"inner_body_txt\">";
		echo "<span id=\"gall_nav\">";
		if(isset($prevlnk))
			echo "<a href=\"$prevlnk\"><img src=\"/imago/nav_back.png\" alt=\"zurueck\" /></a>";
		else
			echo "<img src=\"/imago/nav_empty.png\" alt=\"empty\" />";
		if(isset($nxtlnk))
			echo "<a href=\"$nxtlnk\"><img src=\"/imago/nav_vor.png\" alt=\"vor\" /></a>";
		else
			echo "<img src=\"/imago/nav_empty.png\" alt=\"empty\" />";
		echo "</span>";
		/* 
		END NAVIGATION
		EMBED MEDIA 
		*/
		echo "<div id=\"inner_body_content\">";
		if($doPortTxt)
			echo $port_text;
		else
		{
			if($arrIMGS[$_GET["med"]]["type"]=="img")
				echo "<img src=\"".IMAGES_AS_URL.$arrIMGS[$_GET["med"]]["url"]."\" alt=\"".$arrIMGS[$_GET["med"]]["caption"]."\" />";
			elseif($arrIMGS[$_GET["med"]]["type"]=="vid")
			{
				$fld = MEDIA."videos/".$arrIMGS[$_GET["med"]]["url"];
				$src = "";
				$vids = array_diff(scandir($fld), array('..', '.'));
				foreach($vids AS $vid)
				{
					if($vid!="." || $vid!="..")
					{
						$src .= "<source src=\"".VIDEOS_AS_URL.$arrIMGS[$_GET["med"]]["url"]."/$vid\"";
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
				echo "<div class=\"video_container\"><video id=\"".$arrIMGS[$_GET["med"]]["url"]."_".$_GET["med"]."\" class=\"video-js vjs-default-skin\" controls preload=\"auto\" width=\"auto\" height=\"auto\"
				poster=\"/imago/video_cover.png\"
				data-setup=\"{}\">
				$src
				</video></div>";
			}
			elseif($arrIMGS[$_GET["med"]]["type"]=="vimeo")
			{
				echo "<div class=\"vimeo_container\">";
				echo "<iframe src=\"https://player.vimeo.com/video/".$arrIMGS[$_GET["med"]]["url"]."\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
				echo "</div>";
			}
			if($arrIMGS[$_GET["med"]]["caption"]!="")
				echo "<span class=\"img_caption\">".$arrIMGS[$_GET["med"]]["caption"]."</span>";
		}
		
		if($strPDF!="")
			echo "<div class=\"pdfs\"><h3>PDF</h3><ul>$strPDF</ul></div>";
		echo "</div>";
		echo "</div>";
	}
	else
		$activate_gallery = false;
}
?>