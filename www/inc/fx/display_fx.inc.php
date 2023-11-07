<?php
/* LANGUAGE MENU 
will be shown only of there are more than 1 language

$cmslan -> actual language
$pageurl -> the url of the page with querystring
$objConn -> the db connection object
*/

function doLanMenu($cmslan,$pageurl,$objConn){
	$strSQL = "SELECT lan FROM ".PREFIX."_languages WHERE aktiv";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 1)
	{
		while($row = $rs->fetchObject())
		{
			if($pageurl=="") // still on the homepage
				$lnk = $row->lan;
			else
				$lnk = str_replace("$cmslan","$row->lan",$pageurl);
			if($row->lan!=$cmslan)
				echo "<li><a href=\"$lnk\">$row->lan</a></li>";
		}
	}
}

/* NAVIGATION _FRONT END
MENU_HOME -> show link to homepage 
MENU_HOME_FIX -> show always link to homepage
MENU_PORTFOLIO -> show gallery's in main menu
PORTFOLIO_THMBS -> portfolio page is set to show thumbnails gallery, don't show gallerie's in menu
$p -> page id or symlink
$pp -> subpage id or symlink
$s -> page typology
$cmslan -> language
$menu -> which menu has to be shown
*/

function doNavi($p,$pp,$cmslan,$domenu,$objConn)
{
	if(MENU_HOME)
	{
		$strH = "";
		if(!MENU_HOME_FIX)
			$p==null ? $strH = " AND pag.home=0" : $strH = "";
	}
	else
		$strH = " AND pag.home=0";
	
	if($domenu!="")
		$strM = " AND pag.id_PAG IN (SELECT pagmen.id_PAG FROM ".PREFIX."_pages_menues AS pagmen 
		JOIN ".PREFIX."_menues AS mens ON pagmen.id_MENU=mens.id_MENU WHERE mens.symlink='$domenu')";
	else
		$strM="";
	
	$opensmenu = $openport = false;
	$strSQL = "SELECT pag.pos, pag.id_PAG AS rowID, pag.symlink, pagn.name, pag.protekt, pag.open, pag.home, pag.portfolio, pag.blog, pag.ext_link, pag.ext_link_url,
				(SELECT COUNT(id_PAG) FROM ".PREFIX."_pages WHERE parent_id=pag.id_PAG AND parent_id!=0 AND archiv=0 AND aktiv) AS tsub
		   		FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
		   		WHERE pagn.lan='".$cmslan."' AND pag.archiv=0 AND pag.aktiv AND pag.parent_id=0$strH$strM ORDER BY pag.pos";
    try{
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			$i = 1;
			while($menu = $rs->fetchObject())
			{
				if(FLATLINK)
					$dbid = $menu->symlink;
				else
					$dbid = $menu->rowID;
				if(($p==$dbid)||(strstr($p,"nws")&&$menu->blog)||($menu->home&&MENU_HOME&&$p==null))
				{
					$class=" class=\"active\"";
					if($menu->tsub>0||(!MENU_PORTFOLIO&&$menu->portfolio&&!PORTFOLIO_THMBS))
					{
						$openid = $menu->rowID;
						$opensymlink = $menu->symlink;
						$opensmenu = false;
						if($menu->portfolio)
							$openport = false;
					}
				}
				else
					$class="";	
				
				$lnk = BASEURL_SUFFIX."/";
				if($menu->blog)
				{
					$s_for_lnk = "nws";
					$menu->symlink = "";
				}	
				elseif($menu->portfolio)
					$s_for_lnk = "portfolio";
				else
					$s_for_lnk = "page";
				
				if(!$menu->home)
					if(FLATLINK)
						$lnk = BASEURL_SUFFIX."/$cmslan/$s_for_lnk/$menu->symlink";		
					else
						$lnk = "?p=$menu->rowID&lan=$cmslan&s=$s_for_lnk";
				else
					if($cmslan!=CMSLAN)
						$lnk = BASEURL_SUFFIX."/$cmslan";
						
				if($menu->ext_link)
					$lnk=$menu->ext_link_url;
					
				echo "<li id=\"nav_$menu->symlink\"$class><a href=\"$lnk\" $class>$menu->name</a>";
				if($menu->tsub>0&&$p==$dbid)
				{
					$openid = $menu->rowID;
					$opensymlink = $menu->symlink;
					//doSubMenu($cmslan,$openid,$opensymlink,$p,$pp,$objConn);
				}
				if($menu->portfolio&&MENU_PORTFOLIO&&($p==$dbid))
					doGallMenu($cmslan,$menu->rowID,$p,$pp,$objConn);
				echo "</li>";
				$i++;	
			}
		}
	}
	catch(Exception $e)
	{
		echo captcha($e);
	}
	if($opensmenu)
		if($openport)
			doGallMenu($cmslan,$openid,$p,$pp,$objConn);
		else
			doSubMenu($cmslan,$openid,$opensymlink,$p,$pp,$objConn);
}

function doGallMenu($cmslan,$id,$p,$pp,$objConn)
{
	if(FLATLINK)
		$strID = "(SELECT p.id_PAG FROM ".PREFIX."_pages AS p WHERE p.symlink='".$objConn->prepMysql($id)."')";
	else
		$strID = $id;
		
	$strSQL = "SELECT gl.id_GALL AS rowID, gl.pos, gln.name, gl.symlink
			   FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL
			   WHERE gln.lan='".$cmslan."' AND gl.aktiv 
			   AND gl.id_GALL IN(SELECT id_GALL FROM ".PREFIX."_pages_gallery WHERE id_PAG=$id)
			   ORDER BY gl.pos, rowID";
	$rsg = $objConn->rs_query($strSQL);
	if ($rsg->count() > 0)
	{
		isset($pp)?$class=" class=\"active\"":$class="";
		//if(!MENU_PORTFOLIO)
			echo "<ul class=\"menu_gall\"$class>";
		while($gall = $rsg->fetchObject())	
		{
			if(FLATLINK)
				$dbid = $gall->symlink;
			else
				$dbid = $gall->rowID;
			if(($pp==$dbid))
				$class=" class=\"active\"";
			else
				$class="";
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/page/$p/$gall->symlink";		
			else
				$lnk = "?p=$id&pp=$gall->rowID&lan=$cmslan&s=page";
			echo "<li$class><a id=\"nav_$gall->symlink\" href=\"$lnk\"$class>$gall->name</a></li>";
		}
		//if(!MENU_PORTFOLIO)
			echo "</ul>";
	}
}

function doSubMenu($cmslan,$id,$opensymlink,$p,$pp,$objConn)
{
	$opensmenu = $openport = false;
	$strSQL = "SELECT pag.pos, pag.id_PAG AS rowID, pag.symlink, pagn.name, pag.protekt, pag.open, pag.portfolio,
				(SELECT COUNT(id_PAG) FROM ".PREFIX."_pages WHERE parent_id=pag.id_PAG AND parent_id!=0 AND archiv=0 AND aktiv) AS tsub
		   		FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
		   		WHERE pagn.lan='".$cmslan."' AND pag.archiv=0 AND pag.aktiv AND pag.parent_id=$id ORDER BY pag.pos";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		echo "<ul class=\"menu_sub\">";
		$i = 1;
		while($menu = $rs->fetchObject())
		{
			if(FLATLINK)
				$dbid = $menu->symlink;
			else
				$dbid = $menu->rowID;
			if(($pp==$dbid)||$menu->open)
			{
				$class=" class=\"active\"";
				$opensymlink = $menu->symlink;
				$openid = $menu->rowID;
				if($menu->tsub>0)
					$opensmenu = true;
				if($menu->portfolio)
					$openport = true;
			}
			else
				$class="";
			$lnk = BASEURL_SUFFIX."/";
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/page/$opensymlink/$menu->symlink";		
			else
				$lnk = "?p=$id&pp=$menu->rowID&lan=$cmslan&s=page";
			echo "<li><a id=\"nav_$menu->symlink\" href=\"$lnk\"$class>$menu->name</a></li>";
			$i++;
		}
		echo "</ul>";
	}
	if($opensmenu)
		doSubMenu($cmslan,$openid,$opensymlink,$p,$pp,$objConn);
	if($openport)
		doGallMenu($cmslan,$openid,$p,$pp,$objConn);
}

/* get section -> create menu */
function doSectionMenu($cmslan,$p,$pp,$objConn)
{
	if($pp!="" && $pp!="-")
	{
        
		if(FLATLINK)
			$strWHP = "landsec.id_LAND=(SELECT id_LAND FROM ".PREFIX."_land WHERE symlink='".$objConn->prepMysql($pp)."')";
		else
			$strWHP = "landsec.id_LAND=$pp";
		$strSQL = "SELECT db.id_SEC AS rowID, db.pos, db.symlink, dbn.name
					FROM ".PREFIX."_section AS db JOIN ".PREFIX."_section_text AS dbn ON db.id_SEC=dbn.id_SEC
					JOIN ".PREFIX."_land_section AS landsec ON db.id_SEC=landsec.id_SEC
				    WHERE dbn.lan='".$cmslan."' AND db.aktiv 
					AND $strWHP ORDER BY db.pos";
	}
	else
		$strSQL = "SELECT db.id_SEC AS rowID, db.pos, db.symlink, dbn.name
					FROM ".PREFIX."_section AS db JOIN ".PREFIX."_section_text AS dbn ON db.id_SEC=dbn.id_SEC
				   WHERE dbn.lan='".$cmslan."' AND db.aktiv ORDER BY dbn.name ASC";
    $rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$i = 1;
		while($menu = $rs->fetchObject())
		{
			if(FLATLINK)
				$dbid = $menu->symlink;
			else
				$dbid = $menu->rowID;
			if($p==$dbid)
			{
				$class=" class=\"active\"";
				$openid = $menu->rowID;
				$menu->symlink="-";
			}
			else
				$class="";
			$lnk = "";
			if(FLATLINK)
            {
                if($menu->symlink=="-"&&$pp=="")
                    $lnk = BASEURL_SUFFIX."/$cmslan";
                else
                   $lnk = BASEURL_SUFFIX."/$cmslan/project/$menu->symlink/$pp";
            }
			else
				$lnk = "?p=$id&pp=$menu->rowID&lan=$cmslan&s=project";
			echo "<li><a id=\"nav_$menu->symlink\" href=\"$lnk\"$class>$menu->name</a></li>";
			$i++;
		}
	}
}

/* get lands -> create menu */
function doLandMenu($cmslan,$s,$p,$pp,$objConn)
{
	if($s=="page"||$s=="nws")
		$p=$pp=null;
	if($p!=null&&$p!="-")
	{
		if(FLATLINK)
			$strWHP = "landsec.id_SEC=(SELECT id_SEC FROM ".PREFIX."_section WHERE symlink='".$objConn->prepMysql($p)."')";
		else
			$strWHP = "landsec.id_SEC=$p";
		$strSQL = "SELECT lnd.id_LAND AS rowID, lnd.symlink, lndn.name
					FROM ".PREFIX."_land AS lnd 
					JOIN ".PREFIX."_land_text AS lndn ON lnd.id_LAND=lndn.id_LAND
					JOIN ".PREFIX."_land_section AS landsec ON lnd.id_LAND=landsec.id_LAND
				    WHERE lndn.lan='".$cmslan."' AND lnd.aktiv 
					AND $strWHP ORDER BY lndn.name, rowID";
	}
	else
	{
		$p="-";
		$strSQL = "SELECT lnd.id_LAND AS rowID, lnd.pos, lnd.symlink, lndn.name, lnd.aktiv
					FROM ".PREFIX."_land AS lnd JOIN ".PREFIX."_land_text AS lndn ON lnd.id_LAND=lndn.id_LAND
				   WHERE lndn.lan='".$cmslan."' ORDER BY lndn.name";
	}
	//echo $strSQL;
    $rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$i = 1;
		while($menu = $rs->fetchObject())
		{
			if(FLATLINK)
				$dbid = $menu->symlink;
			else
				$dbid = $menu->rowID;
			if($pp==$dbid)
			{
				$class=" class=\"active\"";
				$menu->symlink="";
			}
			else
				$class="";
			$lnk = "";
			if(FLATLINK)
            {
                if($p=="-"&&$menu->symlink=="")
                    $lnk = BASEURL_SUFFIX."/$cmslan";
                else
                    $lnk = BASEURL_SUFFIX."/$cmslan/project/$p/$menu->symlink";
            }
			else
				$lnk = "?p=$id&pp=$menu->rowID&lan=$cmslan&s=project";
			echo "<li><a id=\"nav_$menu->symlink\" href=\"$lnk\"$class>$menu->name</a></li>";
			$i++;
		}
	}
	//if($opensmenu)
		//doSubSectionMenu($cmslan,$openid,$opensymlink,$p,$pp,$objConn);
}
function doSubSectionMenu($cmslan,$id,$opensymlink,$p,$pp,$objConn)
{
	$opensmenu = $openport = false;
	$strSQL = "SELECT lnd.id_LAND AS rowID, lnd.symlink, lndn.name
				FROM ".PREFIX."_land AS lnd 
				JOIN ".PREFIX."_land_text AS lndn ON lnd.id_LAND=lndn.id_LAND
				JOIN ".PREFIX."_project_land AS projland ON lnd.id_LAND=projland.id_LAND
				LEFT JOIN ".PREFIX."_project AS proj ON proj.id_PROJ=projland.id_PROJ
			    WHERE lndn.lan='".$cmslan."' AND lnd.aktiv AND proj.aktiv 
				AND projland.id_PROJ IN (SELECT id_PROJ FROM ".PREFIX."_project_section WHERE id_SEC=$id) ORDER BY lndn.name, rowID";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		echo "<ul class=\"menu_sub\">";
		$i = 1;
		while($menu = $rs->fetchObject())
		{
			if(FLATLINK)
				$dbid = $menu->symlink;
			else
				$dbid = $menu->rowID;
			if(($pp==$dbid))
			{
				$class=" class=\"active\"";
				$openid = $menu->rowID;
			}
			else
				$class="";
			$lnk = BASEURL_SUFFIX."/";
			if(FLATLINK)
				$lnk = BASEURL_SUFFIX."/$cmslan/project/$opensymlink/$menu->symlink";		
			else
				$lnk = "?p=$id&pp=$menu->rowID&lan=$cmslan&s=project";
			echo "<li><a id=\"nav_$menu->symlink\" href=\"$lnk\"$class>$menu->name</a></li>";
			$i++;
		}
		echo "</ul>";
	}
}
?>