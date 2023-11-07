<?php
// part of a gallery or news?
$strSQLJOIN = "";
$strid = null;
$id_TBL_NAME = "null";
$id_TBL = "null";
$strRef = 0;
$strRefType = "null";
if(isset($_REQUEST["id_GALL"]))
{
	if(!is_null($_REQUEST["id_GALL"]))
	{
		settype($_REQUEST["id_GALL"],"INT");
		$id_TBL_NAME = "id_GALL";
		$strid = "&id_GALL=".$_REQUEST["id_GALL"];
		$id_TBL = $_REQUEST["id_GALL"];
		$strRef = 1;
		$strRefType = "gall";
		$strSQLJOIN = " JOIN ".PREFIX."_gallery_media AS medgal ON medgal.id_MED=med.id_MED WHERE medgal.id_GALL=".$_REQUEST["id_GALL"];
	}
}

if(isset($_REQUEST["showonly"]))
{
	if($_REQUEST["showonly"]=="doc") // show onyl documents
	{
		$strWHERE = " WHERE med.tipo='data'";
	}
	elseif($_REQUEST["showonly"]=="nogall") // show all media which are not in a gallery
	{
		$strWHERE = " WHERE med.id_MED NOT IN (SELECT id_MED FROM ".PREFIX."_gallery_media)";
	}
}

// only one media type
if(!isset($strWHERE))
{
	$strWHERE = "";
}
else
{
	if($strSQLJOIN!="")
		$strWHERE = str_replace("WHERE","AND",$strWHERE);
}
$querystring = $_SERVER["PHP_SELF"]."?$id_REF=".$_REQUEST[$id_REF]."&name=".$_POST[$cmslan."_name"]."&id_ZONA=".$_REQUEST["id_ZONA"];
?>
	<div id="meddialog"></div>
	<div style="float:right;margin-right:10px;font-size:12px;font-weight:bold;">
		<?php
		$strSQL = "SELECT gl.id_GALL AS id, gln.name AS title
				   FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL
				   WHERE gln.lan='".$cmslan."'";
		try
		{
			$objConn = MySQL::getIstance();
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				echo $arrTextes["tracking"]["filter"].":&nbsp;";
				echo "<select id=\"choosegall\" name=\"choosegall\">";
				echo "<option value=\"\"></option>";
				echo "<option value=\"$querystring\">".$arrTextes["gallery"]["allmedia"]."</option>";
				echo "<option value=\"\"></option>";
				echo "<option value=\"$querystring&showonly=doc\">".$arrTextes["media"]["docs"]."</option>";
				echo "<option value=\"$querystring&showonly=nogall\">".$arrTextes["media"]["no_portfolio"]."</option>";
				echo "<option value=\"\"></option>";
				echo "<option value=\"\">PORTFOLIO</option>";
				while($row = $rs->fetchObject())
				{
					echo "<option value=\"$querystring&id_GALL=$row->id\">$row->title</option>";
				}
				echo "<option value=\"\"></option>";
				echo "</select>";
			}
		}

		catch(Exception $e)
		{
			if($debug)
				echo captcha($e);
		}
		?>
	</div>
	<br class="break" />
	<div id="mediadiv">
		<?php	
		/* paging */
		
		isset($_GET["pg"]) ? $thispg = $_GET["pg"] : $thispg = 1;
		
		settype($thispg,"INT");
		
		$strSQL = "SELECT COUNT(med.id_MED) AS trs FROM ".PREFIX."_media AS med".$strSQLJOIN.$strWHERE;
		
		if($rs = $objConn->rs_query($strSQL))
		{
			$row = $rs->fetchAssocArray();
			$tRS = $row["trs"];
		}
		
		$tOBJ = 30;
		$lastpg = ceil($tRS/$tOBJ);
		
		if ($thispg < 1) 
		{ 
			$thispg = 1; 
		} 
		elseif ($thispg > $lastpg) 
		{ 
			$thispg = $lastpg; 
		}
		if($thispg==0) $thispg = 1;
		$strSQLLIMIT = "LIMIT " .($thispg - 1) * $tOBJ ."," .$tOBJ;
		
		$strSQL = "SELECT med.id_MED, med.tipo, med.url,
				  (SELECT medn.$id_REF FROM ".PREFIX."_".$ref_tbl_name."_media AS medn WHERE medn.id_MED=med.id_MED AND medn.".$id_REF."='".$_REQUEST[$id_REF]."') AS isH
				  FROM ".PREFIX."_media AS med".$strSQLJOIN.$strWHERE." ORDER BY med.id_MED DESC $strSQLLIMIT";
		$rs = $objConn->rs_query($strSQL);
		
		if ($rs->count() > 0)
		{
			if($lastpg>1)
			{
				echo "<div class=\"navi\">";
				for($i=1;$i<=$lastpg;$i++)
				{
					$i!=$thispg ? $class="button" : $class="buttonchoosen";
					echo "<a href=\"$querystring&pg=$i$strid\" class=\"$class\">$i</a>&nbsp;";
				}
				echo "</div>";
			}
			echo "<div id=\"imgtbl\">";
			while($img = $rs->fetchObject())
			{
				echo "<div class=\"imgsing\">";
				switch($img->tipo)
				{
					case "img":
						$flname=IMAGES_AS_URL."/".$img->url;
						$flname_small = getFileNameFormat($flname,THUMB);
						$flname = getFileNameFormat($flname,LARGE);
						echo "<a href=\"$flname\" target=\"_blank\" class=\"img\"><img src=\"$flname_small\" class=\"img\" /></a>";
						break;
					case "vid":
						echo "VIDEO: $img->url";
						break;
					case "vimeo":
						echo "VIMEO: <a href=\"https://vimeo.com/$img->url\" target=\"_blank\">$img->url</a>";
						break;
					case "data":
						echo "<a href=\"".DATA_AS_URL."$img->url\" target=\"_blank\">$img->url</a>";
						break;
					default:
						break;
				}
				echo "<br class=\"break\"/>";
				if($img->isH)
				{
					$isH=1;
					$icon = "fa-minus-circle";
					$strMsg = $arrTextes["gallery"]["delseiten"];
				}
				else
				{
					$isH=0;
					$icon = "fa-plus";
					$strMsg = $arrTextes["gallery"]["doseiten"];
				}
				$msg=$arrTextes["gallery"]["seiten"];
				echo "<a href=\"$querystring&id_MED=$img->id_MED&isH=$isH&pg=$thispg$strid\" class=\"imghref\" id=\"$img->id_MED,$isH,".$_REQUEST[$id_REF].",$id_REF,$ref_tbl_name\"><i class=\"fa $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
                $msg=str_replace("#name",$img->url,$arrTextes["gallery"]["thumb"]);
                $img->url==$_POST["thumb"] ? $icon = "on" : $icon="off";
				echo "<a href=\"$querystring&akt=glthumb&pg=$thispg&thmb=$img->url\" class=\"aktlink\" id=\"glthumb,".$_REQUEST[$id_REF].",$img->url\"><i class=\"fa fa-home $icon fa-lg fa-fw\" aria-label=\"$msg\"></i></a>";
				echo "</div>";
			}
			echo "<br class=\"break\"/>";
			echo "</div>";
		}
		else
		{
			echo $arrTextes["errors"]["nomedia"];
		}
		?>
	</div>
	<div id="loading"><img src="imago/loader.gif" width="66" height="66" alt="loading" /><br />LOADING</div>
	<script type="text/javascript">
	$(function()
	{		
		$("#upldiv").add("#mediasubmit").add("#mediadiv").add("#captiondialog").add("#flimg").add("#flvid").hide();
		$("#loading").show();
		$(window).load(function(){
			$("#loading").hide();
			$("#mediadiv").show();
			$("a.img").fancybox();
			$("a.vid").fancybox({
				'padding'		: 0,
				'autoScale'		: true,
				'transitionIn'	: 'none',
				'transitionOut'	: 'none',
				'href'			: this.href,
				'type'			: 'swf',
				'swf'			: {
					'wmode'		: 'transparent',
					'allowfullscreen'	: 'true',
					'flashvars' : 'aplay=1&skinfold=swf/&skinhide=0'
				}
			});
			$("#choosegall").change( function() {
				if($(this).val()!="")
					window.location = $(this).val();
			});
		});
	});
	</script>