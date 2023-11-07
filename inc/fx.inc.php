<?php
/***********************
FORM HANDLING


check if string is only alfanumeric // use on log.in and modify password
***********************/
function AlfaNum($str)
{
	return ctype_alnum($str);
}

/***********************
check if string is only numeric // use on log.in and modify password
***********************/
function Numeric($str)
{
	return ctype_digit($str);
}

/***********************
check if string is only alphabetic // use on log.in and modify password
***********************/
function Alpha($str)
{
	return ctype_alpha($str);
}

function timestamp2itdate($timestamp){				// converts MySql TimeStamp to readable date // italian
   $intDay = substr($timestamp, 6, 2);
   $intMonth = substr($timestamp, 4, 2);
   $intYear = substr($timestamp, 0, 4); 
   return date ("d M Y", mktime(0,0,0, $intMonth, $intDay, $intYear));
}

function format_date($original='', $format="%R, %d. %B %Y") { // converts MySql Date to readable date // italian
    $format = ($format=='date' ? "%d. %B %Y" : $format);
 	$format = ($format=='datenum' ? "%d.%m.%Y" : $format); 
    $format = ($format=='datetime' ? "%m-%d-%Y %H:%M:%S" : $format); 
    $format = ($format=='mysql-date' ? "%Y-%m-%d" : $format); 
    $format = ($format=='mysql-datetime' ? "%Y-%m-%d %H:%M:%S" : $format); 
    return (!empty($original) ? strftime($format, strtotime($original)) : "" ); 
}

function checkmail($mail) 
{ 
	if(ereg('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $mail))
	{
		if(checkDSN($mail))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	} 
}

function checkDSN($mail)
{
	list($User,$Domain) = explode("@",$mail);
	return (checkdnsrr($Domain, "MX"));
	//return 1;
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
   
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
   
    $bytes /= pow(1024, $pow); 
   
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}

// remove naughty characters from file name
function cleanUp($fl)
{
	$fl = preg_replace("/[^\w\.]/", "-", strtolower($fl)); 
	return $fl;
}

// check if empty fields on form //

function check_empty_fields($post,$arrTextes){
	$errFLD = $errMSG = $l = null;
	global $arrL;
	foreach($post as $key=>$value) 
	{
		$arrV=explode("_",$key);
		if(strrpos($key, "name")||$key=="symlink")
		{		
			if($value=="")
			{
				$errMSG = $arrTextes["forms"]["allfields"];
				if($key=="symlink")
					$errFLD .= "<br /><strong>".strtoupper($arrTextes["pages"]["symlink"])."</strong>";
				else
					$errFLD .= "<br /><strong>".strtoupper($arrTextes["forms"]["title"])." / ".strtoupper($arrV[0])."</strong>";
			}
			else
			{
				if($key!="symlink")
				{
					// get total languages
					
					if($l!=$arrV[0])
					{
						$arrL[]=$arrV[0];
						$l=$arrV[0];
					}
				}
				else
					if(!AlfaNum($value))
						$errMSG .= "<br />".strtoupper($arrTextes["pages"]["symlink"])."<br />".$arrTextes["login"]["alpha"];
			}	
		}
	}
	return $errMSG.=$errFLD;
}

/**************
IMAGE FUNCTIONS
**************/
function getIPTC($img) // retrieve iptc fields from the image - if present 
{   
	/*
	2#120 -> description
	*/
	$size = getimagesize ($img,$info);
	if(is_array($info)) 
	{
		if(isset($info["APP13"]))
		{
			$iptc = iptcparse($info["APP13"]);
			//if(isset($iptc["2#120"])) return $iptc["2#120"][0];
			return $iptc;
		}         
    }             
}

function getMEDIAPARAM()
{
	try
	{
		$objConn = MySQL::getIstance();
		$strSQL = "SELECT * FROM ".PREFIX."_preferences";
		$rs = $objConn->rs_query($strSQL);
		if ($rs->count() > 0)
		{
			foreach ($rs as $row)
			{
				foreach($row AS $key=>$value)
				{
					define($key,$value);
				}
			}
		}
	}
	catch(Exception $e)
	{
		return 0;
	}
}
function resizeIMG($file)
{
	$strOrImg = $file;
	$arrOrImgAttr = getimagesize($file);
	$intOrImgW = $arrOrImgAttr[0];
	$intOrImgH = $arrOrImgAttr[1];
	
	$intW = media_IMG_MAX_W;
	$intH = ($intOrImgH * $intW) / $intOrImgW;
	if ($intH>media_IMG_MAX_H)
	{
		$intH = media_IMG_MAX_H;
		$intW = $intH*($intOrImgW/$intOrImgH);
	}
	$imgType = image_type_to_mime_type($arrOrImgAttr[2]);
	if($imgType=="image/png")
	{
		$imagecreatefx = "imagecreatefrompng";
		$imagefx = "imagepng";
	}
	else if($imgType=="image/gif")
	{
		$imagecreatefx = "imagecreatefromgif";
		$imagefx = "imagegif";
	}
	else
	{
		$imagecreatefx = "imagecreatefromjpeg";
		$imagefx = "imagejpeg";
	}
	$handImgRead = $imagecreatefx($strOrImg);
	$handImgDest = ImageCreateTrueColor($intW,$intH);
	imagecopyresampled($handImgDest,$handImgRead,0,0,0,0,$intW,$intH,$intOrImgW,$intOrImgH);

	if($imagefx($handImgDest,$file))
	{
		imagedestroy($handImgRead);
		imagedestroy($handImgDest);
		return true;
	}
	else
	{
		imagedestroy($handImgRead);
		imagedestroy($handImgDest);
		return false;
	}
}

function CreateFormats($file,$flname,$targetW,$targetH,$crop)
{
	$strThbFold = MEDIA."/images/";
	$strOrImg = $file;
	$arrOrImgAttr = getimagesize($strOrImg);
	$intOrImgW = $arrOrImgAttr[0];
	$intOrImgH = $arrOrImgAttr[1];
	$intThImgW = GetThumbWidth($intOrImgW,$intOrImgH,$targetW,$targetH);
	$targetformatname = $targetW."x".$targetH;
	if ($intOrImgW > $intThImgW)
	{
		$intThImgH = GetTheHeight($intOrImgW,$intOrImgH,$intThImgW);
		if($intThImgH>$targetH)
		{
			$intThImgH = $targetH;
			$intThImgW = GetTheWidth($intOrImgW,$intOrImgH,$intThImgH);
		}
		$imgType = image_type_to_mime_type($arrOrImgAttr[2]);
		if($imgType=="image/png")
		{
			$imagecreatefx = "imagecreatefrompng";
			$imagefx = "imagepng";
			$flname_retina = str_replace(".png","_".$targetformatname."@2x.png",$flname);
			$flname = str_replace(".png","_".$targetformatname.".png",$flname);
		}
		else if($imgType=="image/gif")
		{
			$imagecreatefx = "imagecreatefromgif";
			$imagefx = "imagegif";
			$flname_retina = str_replace(".gif","_".$targetformatname."@2x.gif",$flname);
			$flname = str_replace(".gif","_".$targetformatname.".gif",$flname);
		}
		else
		{
			$imagecreatefx = "imagecreatefromjpeg";
			$imagefx = "imagejpeg";
			$flname_retina = str_replace(array(".jpg",".jpeg"),"_".$targetformatname."@2x.jpg",$flname);
			$flname = str_replace(array(".jpg",".jpeg"),"_".$targetformatname.".jpg",$flname);
		}
		$handImgRead = $imagecreatefx($strOrImg);
		$handImgDest = ImageCreateTrueColor($intThImgW,$intThImgH);
		imagecopyresampled($handImgDest,$handImgRead,0,0,0,0,$intThImgW,$intThImgH,$intOrImgW,$intOrImgH);
		$isRetina = false;
		if($intOrImgW>(2*$intThImgW))
		{
			$handImgDest_retina = ImageCreateTrueColor((2*$intThImgW),(2*$intThImgH));
			imagecopyresampled($handImgDest_retina,$handImgRead,0,0,0,0,(2*$intThImgW),(2*$intThImgH),$intOrImgW,$intOrImgH);
			$isRetina = true;
		}
		if ($imagefx($handImgDest,$strThbFold.$flname))
		{
			if($isRetina)
			{
				$imagefx($handImgDest_retina,$strThbFold.$flname_retina);
				imagedestroy($handImgDest_retina);
			}
			imagedestroy($handImgRead);
			imagedestroy($handImgDest);
			return 1;
		}
		else
		{
			imagedestroy($handImgRead);
			imagedestroy($handImgDest);
			if($isRetina)
				imagedestroy($handImgDest_retina);
			return 0;
		}
	}
	else
	{
		return 1;
	}
}

function GetTheHeight($intOrImgW,$intOrImgH,$intTW)
{
	return round(($intOrImgH * $intTW) / $intOrImgW);
}

function GetTheWidth($intOrImgW,$intOrImgH,$intTH)
{
	return round(($intOrImgW * $intTH) / $intOrImgH);
}

function GetThumbWidth($intOrImgW,$intOrImgH,$targetW,$targetH)
{
	if ($intOrImgW >= $intOrImgH)	// orizzontal format
	{
		$intRet = $targetW;
	}
	else							// vertical format
	{
		$intRet = GetTheWidth($intOrImgW,$intOrImgH,$targetH);
	}
	return $intRet;
}

// portfolio navigation

function getprevious($id,$array)
{
	foreach($array AS $key=>$value)
	{
		if($value==$id)
		{
			return $tID;
			exit;
		}
		$tID = $value;
	}
}
function getnext($id,$array)
{
	foreach($array AS $key=>$value)
	{
		if(isset($isK))
		{
			return $value;
			exit;
		}	
		if($value==$id)
			$isK = true;
	}
}

function getFileNameFormat($fl,$format,$retina=false)
{
	$retina?$sfx_retn = "@2x":$sfx_retn = "";
    if(file_exists(MEDIA."images/".str_replace(array(".jpg",".jpeg"),"_".$format.$sfx_retn.".jpg",$fl)))
	   return str_replace(array(".jpg",".jpeg"),"_".$format.$sfx_retn.".jpg",$fl);
    else
       return $fl; 
}
/***************
PAGING FUNCTIONS
***************/

/*
doActions
$id => the ID of the line // page, news, portfolio
$name => the name
$isA => is the line aktiv
$isH => is the line main (only pages)
$isP => is the line password protected
$isE => is the line editable (if not, only edit, activate and delete funcion, otherwise all functions)
$isR => is the line reserved (only pages) _ only info editing ist permitted
$idName => the id_NAME on tabel (id_PAG, id_NWS, id_PORT)
$cmsusr => the user-id, for tracking
$track => do tracking
$pages => are we editing pages or other stuff (pages can set subpages)
$issub=false => are we on a subpage set
$dosub=false => set on single page possibilities to insert or not subpages _ default true because setted als COSTANT -> NOSUB
$dosecure=false => set on single page possibilities to protect page _ default true because setted als COSTANT -> SECUREAKTIV
*/

function doActions($id,$name,$isA,$isH,$isP,$isE,$isR,$idName,$cmsusr,$track,$pages,$issub=false,$dosub=true,$dosecure=true)
{
	$pages? $ico_vor = "page" : $ico_vor = "user";
	global $cmslan, $arrTextes;
	isset($_REQUEST["id_ZONA"]) ? $stridZONA = "&id_ZONA=".$_REQUEST["id_ZONA"] : $stridZONA = "";
	if($cmsusr) echo "</td>";
	echo "<td class=\"akttd\">";
	
	$msg=str_replace("#name",$name,$arrTextes["aktions"]["edit"]);
    //  <i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>
    // <img src=\"imago/".$ico_vor."_edit.png\" alt=\"$msg\" title=\"$msg\" />
	echo "<a href=\"".THISMAINPAGENAME."_edit.php?$idName=$id&name=$name".$stridZONA."\"><i class=\"fa fa-pencil fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
	
	/* TRACKING - ONLY CMS USER SECTION */
	if($track)
	{
        // fa-bar-chart
        // <img src=\"imago/".$ico_vor."_edit.png\" alt=\"$msg\" title=\"$msg\" />
		$msg=str_replace("#name",$name,$arrTextes["aktions"]["track"]);
		echo "<a href=\"".THISMAINPAGENAME."_tracking.php?$idName=$id&name=$name".$stridZONA."\"><i class=\"fa fa-bar-chart fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
	}
	if($cmsusr && ($id==$_SESSION["usr"]["id_USR"]))
	{
		echo "</td>";
		echo "</tr>";
		return true;
	}
	
	/* END TRACKING 
	
	*/
	if(!$isR)
	{
		/* ACTIVATE */
		if($isA)
		{
            // fa-check
            // <i class=\"fa fa-check fa-2x fa-fw\" aria-label=\"$msg\"></i>
			// $ico = $ico_vor."_enabled.png";
            //$icon = "fa-circle";
			$msg=$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodeaktiv"]);
		}
		else
		{
            // fa-check fa-inverse
            // <i class=\"fa fa-check fa-inverse fa-2x fa-fw\" aria-label=\"$msg\"></i>
			// $ico = $ico_vor."_disabled.png";
            //$icon = "fa-circle-o";
			$msg=str_replace("#name",$name,$arrTextes["aktions"]["doaktiv"]);
		}
        $isA ? $icon = "on" : $icon="red";
        // <img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" />
		echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=dea&isA=$isA".$stridZONA."\" class=\"aktlink\" id=\"dea,$id,$name,$isA\"><i class=\"fa fa-circle-o $icon fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";

		/* END ACTIVATE 

		PAGE ONLY ACTIONS 
		*/
		if($isE) // if page is editable and not reserved
		{
			/* PASSWORD PROTECTION */
			if(SECUREAKTIV&&$dosecure)
			{
				$msg=str_replace("#name",$name,$arrTextes["aktions"]["doprotekt"]);
				//$isP?$icon="page_protect":$icon="page_open";
				//<img src=\"imago/".$icon.".png\" alt=\"$msg\" title=\"$msg\" />
                $isP?$icon="fa-lock":$icon="fa-unlock";
                echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=protekt&isP=$isP".$stridZONA."\" class=\"aktlink\" id=\"protekt,$id,$name,$isP\"><i class=\"fa $icon fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
			}
            
            if($idName=="id_PROJ")
            {
                $msg=str_replace("#name",$name,$arrTextes["aktions"]["doopen"]);
				//$isH ? $ico = $ico_vor."_is_home.png": $ico=$ico_vor."_do_home.png";
                // <img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" />
                $isH ? $icon = "on" : $icon="off";
				echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=open&isH=$isH".$stridZONA."\" class=\"aktlink\" id=\"open,$id,$name,$isH\"><i class=\"fa fa-home $icon fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
            }
            
			/* SUBPAGE */
			if(!NOSUB&&$dosub)
			{
				$msg=str_replace("#name",$name,$arrTextes["aktions"]["doopen"]);
				//$isH ? $ico = $ico_vor."_is_home.png": $ico=$ico_vor."_do_home.png";
                // <img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" />
                $isH ? $icon = "on" : $icon="off";
				echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=open&isH=$isH".$stridZONA."\" class=\"aktlink\" id=\"open,$id,$name,$isH\"><i class=\"fa fa-home $icon fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
				$msg=str_replace("#name",$name,$arrTextes["aktions"]["dopages"]);
                //<img src=\"imago/page_new.png\" width=\"15px\" height=\"15px\" alt=\"$msg\" title=\"$msg\" />
				$lnk = "<a href=\"".THISMAINPAGENAME."_neu.php?$idName=$id&name=$name&parent_id=$id".$stridZONA."\"><i class=\"fa fa-plus fa-2x fa-fw\" aria-label=\"$msg\"></i></a>&nbsp;";
				if($issub)
				{
					if(MORESUB)
						echo $lnk;
				}
				else
					echo $lnk;	
			}
			else
				if($issub)
					echo "";//<img src=\"imago/invisible.png\" width=\"70px\" height=\"15px\" />";
					
			/* ARCHIVE */
			if(DOARCH&&!$issub) // subpage
			{
				$msg=str_replace("#name",$name,$arrTextes["aktions"]["doarchiv"]);
                //<img src=\"imago/archiv.png\" width=\"15px\" height=\"15px\" alt=\"$msg\" title=\"$msg\"/>
				echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=archiv".$stridZONA."\" class=\"aktlink\" id=\"archiv,$id,$name\"><i class=\"fa fa-archive fa-2x fa-fw\" aria-label=\"$msg\"></i></a>";
			}
			else
				if($issub)
					echo "";//<img src=\"imago/invisible.png\" width=\"70px\" height=\"15px\" />";
		}
		else
			if($pages)
				echo "";//<img src=\"imago/invisible.png\" width=\"70px\" height=\"15px\" />";
		
		/* 
		END PAGE ONLY ACTIONS
		
		DELETE */
        // <i class=\"fa fa-trash fa-2x fa-fw\" aria-label=\"$msg\"></i>
        // <img src=\"imago/".$ico_vor."_delete.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\" style=\"margin-left:15px\" />
		$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodelete"]);
        
		echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=loes".$stridZONA."\" class=\"aktlink\" id=\"loes,$id,$name\"><i class=\"fa fa-trash-o fa-2x fa-fw\" aria-label=\"$msg\"></i></a>";
	}
	echo "</td>";
	echo "</tr>";
}

function doArchivActions($id,$name,$isA,$idName)
{
	$ico_vor = "page";
	global $cmslan, $arrTextes;
	isset($_REQUEST["id_ZONA"]) ? $stridZONA = "&id_ZONA=".$_REQUEST["id_ZONA"] : $stridZONA = "";
	echo "<td width=\"125px\" class=\"akttd\">";
	$msg=str_replace("#name",$name,$arrTextes["aktions"]["edit"]);
	echo "<a href=\"".THISMAINPAGENAME."_edit.php?$idName=$id&name=$name".$stridZONA."\"><img src=\"imago/".$ico_vor."_edit.png\" alt=\"$msg\" title=\"$msg\" /></a>&nbsp;";
	if($isA)
	{
		$ico = $ico_vor."_enabled.png";
		$msg=$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodeaktiv"]);
	}
	else
	{
		$ico = $ico_vor."_disabled.png";
		$msg=str_replace("#name",$name,$arrTextes["aktions"]["doaktiv"]);
	}
	echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=dea&isA=$isA".$stridZONA."\" class=\"aktlink\" id=\"dea,$id,$name,$isA\"><img src=\"imago/$ico\" alt=\"$msg\" title=\"$msg\" /></a>&nbsp;";
	$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodearchiv"]);
	echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=dearchiv".$stridZONA."\" class=\"aktlink\" id=\"dearchiv,$id,$name\"><img src=\"imago/archiv.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\"/></a>";
	$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodelete"]);
	echo "<a href=\"".THISMAINPAGENAME.".php?$idName=$id&name=$name&akt=loes".$stridZONA."\" class=\"aktlink\" id=\"loes,$id,$name\"><img src=\"imago/".$ico_vor."_delete.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\" style=\"margin-left:15px\"/></a>";
	echo "</td>";
	echo "</tr>";
}

function doSubPage($id,$cmslan,$pad,$class,$dosub=true,$dosecure=true)
{
	isset($_REQUEST["id_ZONA"]) ? $stridZONA = "&id_ZONA=".$_REQUEST["id_ZONA"] : $stridZONA = "";
	$strSQL = "SELECT pag.pos, pag.id_PAG AS rowID, pagn.name, pag.reserved,pag.protekt, pag.open, pag.aktiv,pag.home, pag.portfolio,pag.gallery,pag.blog,pag.ext_link,pag.ext_link_url, 
			   (SELECT COUNT(id_PAG) FROM ".PREFIX."_pages WHERE parent_id=pag.id_PAG AND parent_id!=0) AS tsub,
			   (SELECT pos FROM ".PREFIX."_pages WHERE id_PAG=$id) AS parent_pos
			   FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
			   WHERE pagn.lan='".$cmslan."' AND pag.archiv=0 AND pag.parent_id=$id ORDER BY pag.parent_id, pag.pos";
	$objConn = MySQL::getIstance();
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		echo "<tr class=\"subpage\"><td colspan=\"9\" class=\"main_td\">";
		echo "<div id=\"sub_$id\" class=\"subpagesdiv\">";
		echo "<table width=\"100%\">";
		$iID = "";
		$startrope = false;
		$tsub = "";
		foreach ($rs as $row)
		{
			if(($iID!=$row->rowID) && $startrope)
			{
				doActions($iID,$iname,$isA,$isH,$isP,$isE,$isR,"id_PAG",false,false,true,true,$doSub,$dosecure);
				if($tsub>0)
					doSubPage($iID,$cmslan,($pad+15),$class,$doSub,$dosecure);
			}
			$class == "pos" ? $class = "neg" : $class = "pos";
			if($iID!=$row->rowID)
			{
				//$row->open? $class="me" : $class=$class;
				$row->aktiv? $class=$class : $class.=" disabled";
				$isA =  $row->aktiv;
				$isH =  $row->open;
				$isP =  $row->protekt;
				$isE =  true;
				$isR =  $row->reserved;			
				echo "<tr class=\"$class $id\">";
				$tw = 50-$pad;
				echo "<td class=\"td1\">";
				echo "<strong>$row->parent_pos.</strong>&nbsp;<input type=\"text\" id=\"pos\" size=\"3\" name=\"pos[$row->rowID]\" value=\"$row->pos\" class=\"input_pos\"/>";
				echo "</td>";
				echo "<td class=\"td2\"><a href=\"".THISMAINPAGENAME."_edit.php?id_PAG=$row->rowID&name=$row->name&$stridZONA\">$row->name</a></td>";
				echo "<td class=\"td3\">";
				if(!$row->ext_link&&!$row->gallery&&!$row->portfolio&&!$row->blog)
				{
					$checked=" checked=\"checked\"";
					$isType = "normal";
				}	
				else
					$checked="";
				echo "<input type=\"radio\" id=\"norm#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"normal\"$checked />";
				echo "</td>";
				echo "<td class=\"td4\">";
				$checked="";
				$disabled="disabled=\"disabled\"";
				if($row->ext_link)
				{
					$checked=" checked=\"checked\"";
					$isType = "ext_link";
					$isE = false;
					$disabled="";
				}
				echo "<input type=\"radio\" id=\"ext_link#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"ext_link\"$checked />";
				echo "<input type=\"text\" id=\"ext_link_url_$row->rowID\" name=\"ext_link_url_$row->rowID\" value=\"$row->ext_link_url\" class=\"input_ext_link_url\"$disabled />";
				echo "</td>";
				echo "<td class=\"td5\">";
				$checked="";
				if($row->gallery)
				{
					$checked=" checked=\"checked\"";
					$isType = "gallery";
					$isE = false;
				} 
				echo "<input type=\"radio\" id=\"gallery#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"gallery\"$checked />";	
				echo "</td>";
				$checked="";
				if($row->portfolio)
				{
					$checked=" checked=\"checked\"";
					$isType = "portfolio";
				}
				echo "<td class=\"td6\"><input type=\"radio\" id=\"portfolio#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"portfolio\"$checked /></td>";
				$checked="";
				if($row->blog)
				{
					$checked=" checked=\"checked\"";
					$isType = "blog";
					$isE = false;
				}
				echo "<td class=\"td7\">";
				echo "<input type=\"radio\" id=\"blog#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"blog\"$checked />";
				echo "<input type=\"hidden\" name=\"isType_$row->rowID\" value=\"$isType\" />";
				echo "</td>";
				echo "<td class=\"td8\"><a href=\"\" class=\"mainpage\" id=\"main_$row->rowID\">";
				if($row->tsub>0) echo $row->tsub."";
				echo "</a></td>";
				$iID = $row->rowID;
				$iname = $row->name;
				$startrope = true;
				$tsub = $row->tsub;
			}		
		}	
		if($startrope)
		{
			doActions($iID,$iname,$isA,$isH,$isP,$isE,$isR,"id_PAG",false,false,true,true,$doSub,$dosecure);
			if($tsub>0)
				doSubPage($iID,$cmslan,($pad+15),$class,$doSub,$dosecure);
		}
		echo "</table></div></td></tr>";
	}
}

function doFrontSubPage($id,$symlink,$cmslan,$pad,$p,$pp,$openmenu,$openid)
{
	if(is_null($pp))
		$pp = $id;
	$strSQL = "SELECT pag.pos, pag.id_PAG AS rowID, pagn.name, pag.protekt, pag.open, pag.aktiv, pag.symlink,
			   (SELECT COUNT(id_PAG) FROM ".PREFIX."_pages WHERE parent_id=pag.id_PAG AND parent_id!=0) AS tsub
			   FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
			   WHERE pagn.lan='".$cmslan."' AND pag.archiv=0 AND pag.aktiv AND pag.parent_id=$id ORDER BY pag.parent_id, pag.pos";
	$objConn = MySQL::getIstance();
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		$openmenu ? $class="submenu_open" : $class="submenu";
		echo "<ul class=\"$class\">";
		$iID = "";
		$startrope = false;
		$tsub = "";
		foreach ($rs as $row)
		{
			if($p==$row->rowID)
			{
				$class=" class=\"aktiv\"";
				$openid = $row->rowID;
			}
			else
			{
				if(($p==0||$p==$pp)&&($openmenu&&$row->open))
				{
					$class=" class=\"aktiv\"";
					$openid = $row->rowID;
				}
				else
					$class="";
			}
			if(FLATLINK)
				echo "<li><a href=\"/$cmslan/$symlink/$row->symlink\"$class>$row->name</a>";
			else
				echo "<li><a href=\"?p=$pp&pp=$row->rowID&l=$cmslan\"$class>$row->name</a>";
			if($row->tsub>0)
				$openid = doFrontSubPage($row->rowID,$symlink,$cmslan,0,$p,$pp,$openmenu,$openid);
			echo "</li>";	
		}	
		echo "</ul>";
	}
	return $openid;
}

/* 
MANAGE FOREIGN KEYS
*/

function doSelectReference($zona,$ref,$refnamefield,$id,$foreignselelect,$objConn)
{
	global $arrKeyConfig;
	$dbtable = $arrKeyConfig[$ref]["maintable"];
	$refdbtable = $arrKeyConfig[$zona."-".$ref]["keytable"];
	$refdbidname = $arrKeyConfig[$ref]["id"];
	$idname = $arrKeyConfig[$zona]["id"];
	$isREF = false;
	if(is_null($id))
		$id = 0;
	
	$strSQL = "SELECT tb.$refdbidname AS id, tb.$refnamefield AS name,
				IFNULL((SELECT 1 FROM $refdbtable AS reftb WHERE reftb.$refdbidname=tb.$refdbidname AND reftb.$idname=$id),0) AS isL
				FROM $dbtable AS tb 
				ORDER BY name ASC";
	//echo $strSQL;
	try
	{
		$rs = $objConn->r_query($strSQL);
		if ($rs->num_rows > 0)
		{	
			echo "<div id=\"c$ref\" class=\"aktiv\">";
			if($foreignselelect)
			{
				while($row = $rs->fetch_array())
				{
					if($row["isL"])
					{
						echo getUm(strtoupper($row["name"]))."<br />";
						$isREF = true;
						$arrRef[$row["id"]] = $row["id"];
					}
				}
				
				if(!$isREF) 
					echo "KEINE $ref VERBUNDEN";
				$isREF?$_SESSION["oREF"]["$zona-$ref"] = $arrRef : $_SESSION["oREF"]["$zona-$ref"] = array();
				//print_r($arrRef);
				//echo "<br>";
				//print_r($_SESSION["oREF"]);
				unset($arrRef);
			}
			else
			{
				echo "KEINE $ref VERBUNDEN";
			}
			echo "</div>";
			$rs->data_seek(0);
			echo "<div  class=\"refdiv\">";
			echo "<ul class=\"reference\">";
			while($row = $rs->fetch_array())
			{
				$checked = "";
				if($row["isL"])
					$checked = "checked=\"checked\"";
				echo "<li><input name=\"ref[$zona-$ref][".$row["id"]."]\" type=\"checkbox\" value=\"".$row["id"]."\" id=\"$ref-".$row["id"]."\"$checked /> <span>".getUm(strtoupper($row["name"]))."</span></li>";
			}
			echo "</ul>";
			echo "</div>";
		}
		else
		{
			echo "<div id=\"c$ref\" class=\"aktiv\">KEINE $ref EINGEF&Uuml;GT</div>";
		}
	}
	
	catch(Exception $e)
	{
		echo captcha($e);
	}
}

/* VIMEO */
function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}
?>