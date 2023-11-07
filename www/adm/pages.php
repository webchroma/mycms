<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace(".php","",PAGENAME));
$strJOIN = "";
if(!isset($strWHERE))
	$strWHERE = " AND pag.parent_id=0";
if(!isset($stridZONA))
	$stridZONA = "";
if(!isset($doSub))
	$doSub = true;
if(!isset($dosecure))
	$dosecure = true;
$id_ZONA = null;

/**/
$_REQUEST["id_ZONA"] = 1;
if(isset($_REQUEST["id_ZONA"]))
{
	settype($_REQUEST["id_ZONA"],"INT");
	$strJOIN = "JOIN ".PREFIX."_struktur_str_page AS stp ON pag.id_PAG=stp.id_PAG";
	$strWHERE .= " AND stp.id_ZONA=".$_REQUEST["id_ZONA"]."";
	$stridZONA .= "id_ZONA=".$_REQUEST["id_ZONA"]."";
	$id_ZONA = $_REQUEST["id_ZONA"];
}
include_once("inc/page-head.inc.php");
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.aktlink').click(function(){
			var d = this.id.split(",");
			url = this;
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
			else if(d[0]=="protekt")
			{
				if(d[3]==1)
				{
					msg = "<?=$arrTextes["aktions"]["deprotekt"]?>";
				}
				else
				{
					msg = "<?=$arrTextes["aktions"]["protekt"]?>";
				}
			}
			else if(d[0]=="open")
			{
				if(d[3]==1)
				{
					msg = "<?=$arrTextes["aktions"]["deopen"]?>";
				}
				else
				{
					msg = "<?=$arrTextes["aktions"]["open"]?>";
				}
			}
			else if(d[0]=="archiv")
				msg = "<?=$arrTextes["aktions"]["archiv"]?>";
			else if(d[0]=="dearchiv")
				msg = "<?=$arrTextes["aktions"]["dearchiv"]?>";
			else
			{
				msg = "<?=$arrTextes["aktions"]["pagedelete"]?>";
				url = String(url).replace("seiten","seiten_delete");
			}
			$.openDialog(url,msg,400,"<?=$arrTextes["messages"]["confirm"]?>","<?=$arrTextes["aktions"]["yes"]?>","<?=$arrTextes["aktions"]["no"]?>");
			return false;
		});
		
		$(".radio").change(function(event)
			{
				attrs = $(this).attr("id").explode("#");
				inp_id = "ext_link_url_"+attrs[1];
		        if(attrs[0]=="ext_link")
					$("#"+inp_id).removeAttr("disabled");
				else
					$("#"+inp_id).attr("disabled","disabled");
		    }
		);
		$(".menue_to_page").change(function(event){
			$.post("menue_to_page.php", "params="+$(this).val(), function(theResponse){
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
		<div style="float:left;margin-right:5px;"><a href="<?=THISMAINPAGENAME?>_neu.php?<?=$stridZONA?>"><?=$arrTextes["admin"]["new"]?></a></div>
		<br class="clear" />
	</div>
	<div id="mainpage">
			<div id="dialog"></div>
			<form method="post" action="<?=THISMAINPAGENAME?>.php" name="fpage" id="fpage">
				<input type="hidden" name="id_ZONA" value="<?=$_REQUEST["id_ZONA"]?>">
			<table width="100%">
				<tr>
					<th scope="col" class="td1"></th>
					<th scope="col" class="td2"></th>
					<th scope="col" class="td3">MEN&Uuml;</th>
					<th scope="col" class="td4">STANDARD</th>
					<th scope="col" class="td5">EXTERNAL LINK</th>
					<th scope="col" class="td6">GALLERY</th>
					<th scope="col" class="td7">PORTFOLIO</th>
					<th scope="col" class="td8">BLOG</th>
					<th scope="col" class="td9"><? //$arrTextes["pages"]["subpages"]?>SUBPAGES</th>
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();
				
				if(isset($_POST["cpos"]) && isset($_POST["pos"]))
				{
					foreach($_POST["pos"] as $key=>$value)
					{
						$strSQL = "UPDATE ".PREFIX."_pages SET pos=".$value." WHERE id_PAG=".$key."";
						$objConn->i_query($strSQL);
					}
				}
				
				if(isset($_POST["ctype"]) && isset($_POST["type"]))
				{
					foreach($_POST["type"] as $key=>$value)
					{
						$strSQL="";
						if($value!=$_POST["isType_$key"])
						{
							if($_POST["isType_$key"]!="normal")
								$strSQL = $_POST["isType_$key"]."=0";
							else
								$strSQL = "";
							if($value!="normal")
							{
								if($strSQL!="")
									$strSQL = "$strSQL,";
								$strSQL = "$strSQL$value=1";
							}
						}
						if($value=="ext_link")
						{
							if($strSQL!="")
								$strSQL .= ",";
							if(!isset($_POST["ext_link_url_$key"]))
								$_POST["ext_link_url_$key"] = "";
							$strSQL .= "ext_link_url='".$_POST["ext_link_url_$key"]."'";
						}	
						if($strSQL!="")
						{
							$strSQL = "UPDATE ".PREFIX."_pages SET $strSQL WHERE id_PAG=".$key."";
							$objConn->i_query($strSQL);
						}
					}
				}
				
				if(isset($_GET["id_PAG"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_PAG"],"INT");

					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_pages SET aktiv=".$_GET["isA"]." WHERE id_PAG=".$_GET["id_PAG"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="protekt")
					{
						if(isset($_GET["isP"]))
						{
							$_GET["isP"]? $_GET["isP"] = 0 : $_GET["isP"] = 1;
							$strSQL = "UPDATE ".PREFIX."_pages SET protekt=".$_GET["isP"]." WHERE id_PAG=".$_GET["id_PAG"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="open")
					{
						if(isset($_GET["isH"]))
						{
							$_GET["isH"]? $_GET["isH"] = 0 : $_GET["isH"] = 1;
							$strSQL = "UPDATE ".PREFIX."_pages SET open=".$_GET["isH"]." WHERE id_PAG=".$_GET["id_PAG"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="archiv")
					{
						$strSQL = "UPDATE ".PREFIX."_pages SET archiv=1 WHERE id_PAG=".$_GET["id_PAG"]." OR parent_id=".$_GET["id_PAG"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["archiv"]."");
					}
					elseif($_GET["akt"]=="dearchiv")
					{
						$strSQL = "UPDATE ".PREFIX."_pages SET archiv=0 WHERE id_PAG=".$_GET["id_PAG"]." OR parent_id=".$_GET["id_PAG"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["archiv"]."");
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "DELETE FROM ".PREFIX."_pages WHERE id_PAG=".$_GET["id_PAG"]." OR parent_id=".$_GET["id_PAG"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
				}
				
				/* get menues for connection _ pages */
				$strSQL = "SELECT id_MENU, position FROM ".PREFIX."_menues ORDER BY position";
				$rsMenues = $objConn->rs_query($strSQL);
				
				$strSQL = "SELECT pag.pos, pag.id_PAG AS rowID, pagn.name, pag.protekt, pag.open, pag.aktiv, pag.reserved, pag.home, pag.portfolio,pag.gallery,pag.blog,pag.ext_link,pag.ext_link_url,pag.symlink,
						   (SELECT COUNT(id_PAG) 
							FROM ".PREFIX."_pages WHERE parent_id=pag.id_PAG AND parent_id!=0 AND archiv=0) 
							AS tsub,
						   (SELECT GROUP_CONCAT(gln.name) 
							FROM ".PREFIX."_gallery_text AS gln 
							JOIN ".PREFIX."_pages_gallery AS glp ON gln.id_GALL=glp.id_GALL 
							WHERE glp.id_PAG=rowID AND gln.lan='".$cmslan."') 
							AS gname,
							(SELECT GROUP_CONCAT(ID_MENU) FROM ".PREFIX."_pages_menues WHERE id_PAG=rowID) AS id_MENS
						   FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
						   $strJOIN
						   WHERE pag.edit_show AND pagn.lan='".$cmslan."' AND pag.archiv=0$strWHERE ORDER BY pag.parent_id, pag.pos";
				$rs = $objConn->rs_query($strSQL);
				if ($rs->count() > 0)
				{
					$iID = "";
					$class = "pos";
					$startrope = false;
					$tsub = "";
					while($row = $rs->fetchObject())
					{
						if(($iID!=$row->rowID) && $startrope)
						{
							/*
							$isA-> is aktiv
							$isH-> is startpage for this tree
							$isP-> is protected
							$isE-> is editable
							$isR -> is reserved // only page information can be modified
							echo "$iID,$iname,$isA,$isH,$isP,$isE,$isR<br><br>";
							*/
							doActions($iID,$iname,$isA,$isH,$isP,$isE,$isR,"id_PAG",false,false,true,false,$doSub,$dosecure);
							if($doSub&&$tsub>0)
								doSubPage($iID,$cmslan,15,$class,$doSub,$dosecure);
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
							$isR = $row->reserved;
							//if(!$doSub&&$isE)
								//$isE = 0;
							echo "<tr class=\"$class\">";
							echo "<td class=\"td1\"><input type=\"text\" id=\"pos\" size=\"3\" name=\"pos[$row->rowID]\" value=\"$row->pos\" class=\"input_pos\" /></td>";
							echo "<td class=\"td2\">";
							echo "<a href=\"".THISMAINPAGENAME."_edit.php?id_PAG=$row->rowID&name=$row->name&$stridZONA\"><strong>$row->name</strong></a>";
							echo "&nbsp;(".strtoupper($row->symlink).")";
							echo "</td>";
							echo "<td class=\"td3\">";
							if(!$isR)
							{
								echo "<select class=\"menue_to_page\" name=\"menuetopag[$row->rowID]\">";
								echo "<option value=\"$row->rowID#0\"></option>";
								if(isset($rsMenues)&&!is_null($rsMenues))
								{
									while($optRow = $rsMenues->fetchObject())
									{
										$sel = "";
										if(!is_null($row->id_MENS))
											if(in_array($optRow->id_MENU,explode(",",$row->id_MENS)))
												$sel = " selected=\"selected\"";
										echo "<option value=\"$row->rowID#$optRow->id_MENU\"$sel>$optRow->position</option>";
									}
									$rsMenues->rewind();
								}
								echo "</select>";
							}
							else
								echo "-";
							echo "</td>";
							echo "<td class=\"td4\">";
							if(!$row->ext_link&&!$row->gallery&&!$row->portfolio&&!$row->blog)
							{
								$checked=" checked=\"checked\"";
								$isType = "normal";
							}	
							else
								$checked="";
							echo "<input type=\"radio\" id=\"norm#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"normal\"$checked />";
							echo "</td>";
							echo "<td class=\"td5\">";
							if(!$isR)
							{
								$checked="";
								$disabled="disabled=\"disabled\"";
								if($row->ext_link)
								{
									$checked=" checked=\"checked\"";
									$isType = "ext_link";
									$disabled="";
								}
								echo "<input type=\"radio\" id=\"ext_link#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"ext_link\"$checked />";
								echo "<input type=\"text\" id=\"ext_link_url_$row->rowID\" name=\"ext_link_url_$row->rowID\" value=\"$row->ext_link_url\" class=\"input_ext_link_url\"$disabled />";
							}
							else
								echo "-";
							echo "</td>";
							echo "<td class=\"td6\">";
							if(!$isR)
							{
								$checked="";
								if($row->gallery)
								{
									$checked=" checked=\"checked\"";
									$isType = "gallery";
								} 
								echo "<input type=\"radio\" id=\"gallery#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"gallery\"$checked />";
								if($row->gallery)
								{
									echo "<span class=\"pages_gall_name\">";
									if($row->gname!="")
										echo $row->gname;
									else
										echo "PLEASE CHOOSE A GALLERY";
									echo "</span>";
								}
							}
							else
								echo "-";	
							echo "</td>";
							
							echo "<td class=\"td7\">";
							if(!$isR)
							{
								$checked="";
								if($row->portfolio)
								{
									$checked=" checked=\"checked\"";
									$isType = "portfolio";
								}
								echo "<input type=\"radio\" id=\"portfolio#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"portfolio\"$checked />";
							}
							else
								echo "-";	
							echo "</td>";
							
							echo "<td class=\"td8\">";
							if(!$isR)
							{
								$checked="";
								if($row->blog)
								{
									$checked=" checked=\"checked\"";
									$isType = "blog";
									$isE = false;
								}
								echo "<input type=\"radio\" id=\"blog#$row->rowID\" class=\"radio\" name=\"type[$row->rowID]\" value=\"blog\"$checked />";
							}
							else
								echo "-";
							echo "<input type=\"hidden\" name=\"isType_$row->rowID\" value=\"$isType\" />";
							echo "</td>";
							echo "<td class=\"td9\"><a href=\"\" class=\"mainpage\" id=\"main_$row->rowID\">";
							if($row->tsub>0)
								echo $row->tsub;
							else
								echo "-";
							echo "</a></td>";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
							$tsub = $row->tsub;
						}		
					}	
					if($startrope)
					{
						doActions($iID,$iname,$isA,$isH,$isP,$isE,$isR,"id_PAG",false,false,true,false,$doSub,$dosecure);
						if($doSub&&$tsub>0)
							doSubPage($iID,$cmslan,15,$class,$doSub,$dosecure);
					}
					echo "<tr class=\"table_command\">";
					echo "<td colspan=\"2\"><input type=\"submit\" name=\"cpos\" value=\"".$arrTextes["pages"]["position"]."\" /></td>";
					echo "<td>&nbsp;</td>";
					echo "<td colspan=\"2\"><input type=\"submit\" name=\"ctype\" value=\"CHANGE PAGE TYPOLOGY\" /></td>";
					echo "<td colspan=\"3\"><div id=\"response\"></div></td>";
					echo "<td colspan=\"2\">&nbsp;</td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td colspan=\"9\">".$arrTextes["errors"]["nodata"]."</td>";
					echo "</tr>";
				}
				
				if(DOARCH) // show Archive -> activate in conf.inc.php
				{
					echo "<tr>";
					echo "<td colspan=\"9\" class=\"td1\">&nbsp;</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td colspan=\"9\" class=\"td1\"><a name=\"archiv\"></a><strong>ARCHIV</strong></td>";
					echo "</tr>";

					$strSQL = "SELECT pag.parent_id,pag.pos, pag.id_PAG AS rowID, pagn.name, pag.protekt, pag.open, pag.aktiv,
							   (SELECT name FROM ".PREFIX."_pages_text WHERE id_PAG=pag.parent_id AND lan='".$cmslan."') AS parent,
							   (SELECT CASE 
								(SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=rowID) 
								WHEN 0 THEN (SELECT id_PAG FROM ".PREFIX."_pages WHERE id_PAG=rowID) 
								ELSE (SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=rowID) END) 
							   AS orderID
							   FROM ".PREFIX."_pages AS pag JOIN ".PREFIX."_pages_text AS pagn ON pag.id_PAG=pagn.id_PAG
							   $strJOIN
							   WHERE pagn.lan='".$cmslan."' AND pag.archiv=1$strWHERE ORDER BY orderID";
					$rs = $objConn->rs_query($strSQL);
					if ($rs->count() > 0)
					{
						$iID = "";
						$class = "pos";
						$startrope = false;
						$tsub = "";
						while($row = $rs->fetchObject())
						{

							if(($iID!=$row->rowID) && $startrope)
							{
								doArchivActions($iID,$iname,$isA,"id_PAG");
								if($tsub>0)
									doSubPage($iID,$cmslan,15,$class);
							}
							$class == "pos" ? $class = "neg" : $class = "pos";	
							if($iID!=$row->rowID)
							{
								//$row->open? $class="me" : $class=$class;
								$row->aktiv? $class=$class : $class.=" disabled";
								$isA =  $row->aktiv;
								$isH =  $row->open;
								$isP =  $row->protekt;		
								echo "<tr class=\"$class\">";
								echo "<td width=\"45px\" class=\"first\"></td>";
								if($row->parent_id==0)
								{
									$parentID=$row->rowID;
									$name = "<a href=\"".THISMAINPAGENAME."_edit.php?id_PAG=$row->rowID&name=$row->name&$stridZONA\"><strong>$row->name</strong></a>";
								}
								else
								{
									if($parentID==$row->parent_id)
										$name = "<span style=\"margin-left:15px;\">$row->parent/ <a href=\"".THISMAINPAGENAME."_edit.php?id_PAG=$row->rowID&name=$row->name&$stridZONA\">$row->name</a></span>";
									else
										$name = "$row->parent/ <a href=\"".THISMAINPAGENAME."_edit.php?id_PAG=$row->rowID&name=$row->name&$stridZONA\">$row->name</a>";
								}
								echo "<td width=\"60%\">$name</td>";
								echo "<td width=\"10%\"><a href=\"\" class=\"mainpage\" id=\"main_$row->rowID\">";
								//if($row->tsub>0) echo $row->tsub." (Ausklappen)";
								echo "</a></td>";
								$iID = $row->rowID;
								$iname = $row->name;
								$startrope = true;
								$tsub = $row->tsub;
							}		
						}	
						if($startrope)
						{
							doArchivActions($iID,$iname,$isA,"id_PAG");
							if($tsub>0)
								doSubPage($iID,$cmslan,15,$class);
						}
					}
					else
					{
						echo "<tr>";
						echo "<td colspan=\"4\" class=\"first\">".$arrTextes["errors"]["nodata"]."</td>";
						echo "</tr>";
					}	
				}
				?>
			</table>
			</form>
	</div>
</body>
</html>