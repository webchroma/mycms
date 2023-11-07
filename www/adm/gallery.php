<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
include_once("inc/page-head.inc.php");
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.aktlink').click(function(){
			var d = this.id.split(",");
			if(d[0]=="dea")
			{
				if(d[3]==1)
				{
					msg = "<?=$arrTextes["aktions"]["deaktiv"]?>";
				}
				else
				{
					msg = "<?=$arrTextes["aktions"]["aktiv"]?>";
				}
			}
			else
			{
				msg = "<?=$arrTextes["aktions"]["delete"]?>";
			}
			url = this;
			$.openDialog(url,msg,400,"<?=$arrTextes["messages"]["confirm"]?>","<?=$arrTextes["aktions"]["yes"]?>","<?=$arrTextes["aktions"]["no"]?>");
			return false;
		});
		
		$(".gall_to_page").change(function(event){
			$.post("pages_to_gallery.php", "params="+$(this).val(), function(theResponse){
				$("#response").html(theResponse);
				setTimeout(function(){
				    $("#response").html("");
				}, 1000);
			});
		});
		
		$(".proj_to_page").change(function(event){
			$.post("project_to_gallery.php", "params="+$(this).val(), function(theResponse){
				$("#response").html(theResponse);
				setTimeout(function(){
				    $("#response").html("");
				}, 1000);
			});
		});
	});
</script>
</head>

<body>
	<div id="submenu">
		<div style="float:left;margin-right:5px;"><a href="<?=THISMAINPAGENAME?>_neu.php"><?=$arrTextes["admin"]["new"]?></a></div>
		<br class="clear" />
	</div>
	<div id="mainpage">
			<div id="dialog"></div>
			<form method="post" action="<?=THISMAINPAGENAME?>.php" name="fport" id="fport">
			<table width="100%">
				<tr>
					<th scope="col" class="td1"></th>
					<th scope="col" class="td2"></th>
					<th scope="col" class="td4">PAGE</th>
					<th scope="col" class="td5">PROJECT</th>
					<th scope="col" class="td8"><?=$arrTextes["gallery"]["totalmedia"]?></th>
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();
				
				if(isset($_POST["cpos"]) && isset($_POST["pos"]))
				{
					foreach($_POST["pos"] as $key=>$value)
					{
						$strSQL = "UPDATE ".PREFIX."_gallery SET pos=".$value." WHERE id_GALL=".$key."";
						$objConn->i_query($strSQL);
					}
				}
				
				if(isset($_GET["id_GALL"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_GALL"],"INT");

					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_gallery SET aktiv=".$_GET["isA"]." WHERE id_GALL=".$_GET["id_GALL"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "SELECT med.id_MED, med.url 
									FROM ".PREFIX."_media AS med JOIN ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED WHERE glm.id_GALL=".$_GET["id_GALL"]."";
						$rs = $objConn->rs_query($strSQL);
						if ($rs->count() > 0)
							while($row = $rs->fetchObject())
							{
								if(file_exists(MEDIA."images/".$row->url)) unlink(MEDIA."images/".$row->url);
								if(file_exists(MEDIA."images/thumbs/".$row->url)) unlink(MEDIA."images/thumbs/".$row->url);
								$strSQL = "DELETE FROM ".PREFIX."_media WHERE id_MED=".$row->id_MED."";
								$objConn->i_query($strSQL);
							}
						$strSQL = "DELETE FROM ".PREFIX."_gallery WHERE id_GALL=".$_GET["id_GALL"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
				}
				
				/* get pages title for connection _ gallery */
				$strSQL = "SELECT pag.id_PAG AS id_PAG, pagn.name
						   FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
						   WHERE pagn.lan='".$cmslan."' AND pag.gallery=1 ORDER BY pagn.name";
				$rs = $objConn->rs_query($strSQL);
				$rs->count() > 0?$rsGallToPag = $rs:$rsGallToPag=NULL;
				
				/* get pages title for connection _ portfolio */
				$strSQL = "SELECT proj.id_PROJ AS id_PROJ, projn.name
						   FROM ".PREFIX."_project AS proj JOIN ".PREFIX."_project_text AS projn ON proj.id_PROJ=projn.id_PROJ
						   WHERE projn.lan='".$cmslan."' ORDER BY projn.name";
				$rs = $objConn->rs_query($strSQL);
				$rs->count() > 0?$rsPortToPag = $rs:$rsPortToPag=NULL;
				
				$strSQL = "SELECT gl.id_GALL AS rowID, gl.pos, gln.name, gl.aktiv, 
							(SELECT COUNT(med.id_MED) 
							FROM ".PREFIX."_media AS med 
							JOIN ".PREFIX."_gallery_media AS glm ON med.id_MED=glm.id_MED WHERE glm.id_GALL=rowID) AS tM,
							(SELECT GROUP_CONCAT(ID_PAG) FROM ".PREFIX."_pages_gallery WHERE id_GALL=rowID) AS id_PAGS,
							(SELECT GROUP_CONCAT(ID_PROJ) FROM ".PREFIX."_project_gallery WHERE id_GALL=rowID) AS id_PROJS
						   FROM ".PREFIX."_gallery AS gl JOIN ".PREFIX."_gallery_text AS gln ON gl.id_GALL=gln.id_GALL
						   WHERE gln.lan='".$cmslan."' ORDER BY gl.pos, rowID";
				$rs = $objConn->rs_query($strSQL);
				if ($rs->count() > 0)
				{
					$iID = "";
					$class = "pos";
					$startrope = false;
					while($row = $rs->fetchObject())
					{
						$class == "pos" ? $class = "neg" : $class = "pos";
						if(($iID!=$row->rowID) && $startrope)
							doActions($iID,$iname,$isA,false,false,true,false,"id_GALL",false,false,false);
						if($iID!=$row->rowID)
						{
							$row->aktiv? $class=$class : $class.=" disabled";
							$isA =  $row->aktiv;			
							echo "<tr class=\"$class\">";
							echo "<td class=\"td1\"><input type=\"text\" id=\"pos\" size=\"3\" name=\"pos[$row->rowID]\" value=\"$row->pos\" class=\"input_pos\" /></td>";
							echo "<td class=\"td2\">";
							echo "<a href=\"".THISMAINPAGENAME."_edit.php?id_GALL=$row->rowID&name=$row->name\">$row->name</a>";
							echo "</td>";
							echo "<td class=\"td5\">";
							echo "<select class=\"gall_to_page\" name=\"galltopag[$row->rowID][g]\">";
							echo "<option value=\"$row->rowID#0\"></option>";
							if(isset($rsGallToPag)&&!is_null($rsGallToPag))
							{
								while($optRow = $rsGallToPag->fetchObject())
								{
									$sel = "";
									if(!is_null($row->id_PAGS))
										if(in_array($optRow->id_PAG,explode(",",$row->id_PAGS)))
											$sel = " selected=\"selected\"";
									echo "<option value=\"$row->rowID#$optRow->id_PAG\"$sel>$optRow->name</option>";
								}
								$rsGallToPag->rewind();
							}
							echo "</select>";
							echo "</td>";
							echo "<td class=\"td6\">";
							echo "<select class=\"proj_to_page\" name=\"projtopag[$row->rowID][p]\">";
							echo "<option value=\"$row->rowID#0\"></option>";
							if(isset($rsPortToPag)&&!is_null($rsPortToPag))
							{
								while($optRow = $rsPortToPag->fetchObject())
								{
									$sel = "";
									if(!is_null($row->id_PROJS))
										if(in_array($optRow->id_PROJ,explode(",",$row->id_PROJS)))
											$sel = " selected=\"selected\"";
									echo "<option value=\"$row->rowID#$optRow->id_PROJ\"$sel>$optRow->name</option>";
								}
								$rsPortToPag->rewind();
							}
							echo "</select>";
							echo "</td>";
							echo "<td class=\"td9\">$row->tM</td>";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
						}
					}	
					if($startrope)
						doActions($iID,$iname,$isA,false,false,true,false,"id_GALL",false,false,false);
					echo "<tr class=\"table_command\">";
					echo "<td colspan=\"2\"><input type=\"submit\" name=\"cpos\" value=\"".$arrTextes["pages"]["position"]."\" /></td>";
					echo "<td colspan=\"4\"><div id=\"response\"></div></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td colspan=\"6\" class=\"first\">".$arrTextes["errors"]["nodata"]."</td>";
					echo "</tr>";
				}
				?>
			</table>
			</form>
	</div>
</body>
</html>