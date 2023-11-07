<?php
// get page text // about us or photograph
$strSQL = "SELECT db.protekt,pagtxt.texto, med.tipo, med.url, med.link,
			(SELECT COUNT(id_MED) FROM ".PREFIX."_pages_media WHERE $strWHP) AS tmed,
			(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
			FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
			LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG
			LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
			WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
$rs = $objConn->rs_query($strSQL);
$i=1;
$strPDF = "";
if ($rs->count() > 0)
{
	foreach ($rs as $row)
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
				$strPDF .= "<a href=\"".DATA_AS_URL."$row->url\"><em>$row->caption</em></a>";
			}
			if($row->tipo=="img")
			{
				$arrIMGS[$i]["url"] = $row->url;
				$arrIMGS[$i]["caption"] = $row->caption;	
			}
		}	
	}
	echo "<div id=\"inner_body_txt\" class=\"ueber\"><div class=\"inner_body_content\">";
	echo $strTXT;
	if($strPDF!="")
		echo "<div class=\"pdfs\"><h3>PDF</h3>$strPDF</div>";
	echo "</div></div>";
}

// get the photographers
if($pp=="")
{
	/* random preview image */
	$strSQL = "SELECT pag.symlink, CONCAT(pag.symlink,'port') AS portsym, pag.id_PAG AS rowID, pagtxt.name,pagtxt.texto,
				(SELECT med.url FROM ".PREFIX."_media AS med 
				LEFT JOIN ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED
				LEFT JOIN ".PREFIX."_gallery AS gl ON gl.id_GALL=glm.id_GALL
				WHERE gl.symlink=portsym ORDER BY rand() LIMIT 1) AS media
				FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagtxt ON pag.id_PAG=pagtxt.id_PAG
				WHERE pag.parent_id=(SELECT id_PAG FROM ".PREFIX."_pages AS db WHERE $strWHP) AND pagtxt.lan='$cmslan' ORDER BY pag.pos";
				
	/* gallery thumbnail */
	$strSQL = "SELECT pag.symlink, CONCAT(pag.symlink,'port') AS portsym, pag.id_PAG AS rowID, pagtxt.name,pagtxt.texto,
				(SELECT thumb FROM ".PREFIX."_gallery WHERE symlink=portsym) AS media,
				(SELECT texto FROM ".PREFIX."_gallery_text AS glt
				LEFT JOIN ".PREFIX."_gallery AS gl on glt.id_GALL=gl.id_GALL 
				WHERE gl.symlink=portsym AND glt.lan='$cmslan') AS gtext
				FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagtxt ON pag.id_PAG=pagtxt.id_PAG
				WHERE pag.parent_id=(SELECT id_PAG FROM ".PREFIX."_pages AS db WHERE $strWHP) AND pagtxt.lan='$cmslan' ORDER BY pag.pos";
				
	$rsF = $objConn->rs_query($strSQL);
	if ($rsF->count() > 0)
	{
		echo "<div id=\"inner_body_photograph\"><div class=\"inner_body_content\">";
		echo "<h1>".$arrTextes["front"]["fotografen"]."</h1>";
		foreach ($rsF as $rowF)
		{
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/$p/$rowF->symlink";		
			else
				$lnk = "?p=$p&pp=$rowF->rowID&lan=$cmslan";
			echo "<div class=\"foto_row\">";
			echo "<div class=\"foto_img\">";
			echo "<a href=\"$lnk\">";
			echo "<img src=\"".IMAGES_AS_URL."/thumbs/$rowF->media\" alt=\"\" />";
			echo "</a>";
			echo "</div>";
			echo "<div class=\"foto_txt\">";
			echo "<h3><a href=\"$lnk\">$rowF->name</a></h3><a href=\"$lnk\">$rowF->gtext</a></div>";
			echo "<br class=\"break\" />";
			echo "</div>";
		}
		echo "</div></div>";
	}
}

if($pp!="") // a single photograph
{
	unset($arridMED);
	$glsym = $pp."port";
	$strSQL = "SELECT gln.name, gln.texto, med.id_MED, med.url, med.tipo,
				(SELECT name FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='$cmslan') AS medname
				FROM ".PREFIX."_gallery_text AS gln
				LEFT JOIN ".PREFIX."_gallery AS db ON gln.id_GALL=db.id_GALL
				LEFT JOIN ".PREFIX."_gallery_media AS glm ON gln.id_GALL=glm.id_GALL
				LEFT JOIN ".PREFIX."_media AS med ON med.id_MED=glm.id_MED
				WHERE db.symlink='$glsym' AND gln.lan='$cmslan' ORDER BY med.pos";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		echo "<div id=\"inner_body_photograph\"><div class=\"inner_body_content\">";
		echo "<div class=\"foto_row\">";
		/*
		foreach ($rs as $row)
		{	
			echo "<div class=\"foto_row\">";
			echo "<div class=\"port_row\"><img src=\"".IMAGES_AS_URL."/$row->url\" /></div>";
			echo "</div>";
			
		}
		*/
		$i=0;
		$strThmb = "";
		$w = 0;
		foreach ($rs as $row)
		{
			// gallery information and get first image
			$cls = "";
			$i++;
			if($i==1)
			{
				if($row->texto!="")
				{
					echo "<div id=\"gall_txt\"><div>$row->texto</div></div>";
					$isT = true;
				}
				$firstMED = $pageMED = $row->id_MED;
				$img=$row->url;
				$capt = $row->medname;
				if(!isset($_GET["med"]))
					$cls = " class=\"aktiv\"";
			}
			// an image is passed
			if(isset($_GET["med"]))
			{
				settype($_GET["med"],"INT");
				if($_GET["med"]==$row->id_MED)
				{
					$pageMED = $row->id_MED;
					$img = $row->url;
					$capt = $row->medname;
					$cls = " class=\"aktiv\"";
				}
			}
			// thumbnails
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/$p/$pp/$row->id_MED";		
			else
				$lnk = "?p=$p&pp=$pp&lan=$cmslan&med=$row->id_MED";
			$strThmb .= "<div class=\"image_thumb\" id=\"$row->id_MED\"><a href=\"$lnk\"$cls><img src=\"".IMAGES_AS_URL."thumbs/$row->url\" alt=\"$row->medname\" /></a></div>";

			//navigation
			$arridMED[$row->id_MED] = $row->id_MED;
		}
		echo "<div id=\"gall_img\">";
		$imgAttr = getimagesize(MEDIA."images/".$img);
		if($imgAttr[1]>$imgAttr[0])
		{
			$imgH = 425;
			$imgW = GetTheWidth($imgAttr[0],$imgAttr[1],$imgH);
			$style = "style=\"width:".$imgW."px;height:".$imgH."px;\"";
		}
		else
			$style = "style=\"width:100%\"";
		echo "<img src=\"".IMAGES_AS_URL."$img\" $style />";
		echo "<div id=\"gall_img_txt\">$capt</div>";
		echo "</div>";
		echo "<div class=\"loader\"><img src=\"/imago/loader.gif\" /></div>";
		echo "<div id=\"image_thumbs_con\"><div id=\"image_thumbs\">$strThmb</div></div>";

		$isL = $isR = $isD = $bolTHMB = true;
		$bolDIA = true;

		if($isL || $isR || $isD)
		{
			echo "<div id=\"pointers\">";
			if($bolDIA&&$i>1)
			{
				echo "<div id=\"image_navigation\"><img src=\"/imago/supersized/play.png\" class=\"player\" /></div>";
			}

			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/$p/$pp/";		
			else
				$lnk = "?p=$p&pp=$pp&lan=$cmslan&med=";

			if(isset($_GET["med"])&&$firstMED!=$_GET["med"])
			{
				$urlMED = getprevious($pageMED,$arridMED);
				echo "<a href=\"$lnk$urlMED\" id=\"btn_left\">";
				echo "<img src=\"/imago/supersized/thumb-back.png\" alt=\"previous\" width=\"30\" height=\"30\" class=\"akt\" />";
				echo "</a>";
			}
			else
			{
				echo "<img src=\"/imago/btns/empty.png\" alt=\"\" width=\"30\" height=\"30\" />";
			}
			$lastidMED = end($arridMED);
			if(!isset($_GET["med"])||$_GET["med"]!=$lastidMED)
			{
				if(count($arridMED)>1)
				{
					$urlMED = getnext($pageMED,$arridMED);
					echo "<a href=\"$lnk$urlMED\" id=\"btn_right\">";
					echo "<img src=\"/imago/supersized/thumb-forward.png\" alt=\"next\" width=\"30\" height=\"30\" class=\"akt\" />";
					echo "</a>";
				}
				else
					echo "<img src=\"/imago/btns/empty.png\" width=\"30\" height=\"30\" alt=\"\" />";
			}
			else
			{
				echo "<img src=\"/imago/btns/empty.png\" width=\"30\" height=\"30\" alt=\"\" />";
			}
			if($bolTHMB)
			{
				echo "<a href=\"#\" class=\"thumb_btn\">";
				echo "<img src=\"/imago/supersized/button-tray-up.png\" alt=\"index\" width=\"30\" height=\"30\" class=\"akt\" />";
				echo "</a>";	
			}
			echo "</div>\n";
		}
		//echo "<br style=\"clear:both;\" />";
		if($isL || $isR || $imgT!="") echo "</div>\n";
		echo "</div>";
		echo "</div></div>";
	}
}
?>