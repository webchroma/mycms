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
            else if(d[0]=="open")
			{
				if(d[3]==1)
				{
					msg = "<?=$arrTextes["aktions"]["nohigh"]?>";
				}
				else
				{
					msg = "<?=$arrTextes["aktions"]["high"]?>";
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
		
		$(".proj_to_land").change(function(event){
			$.post("project_to_land.php", "params="+$(this).val(), function(theResponse){
				$("#response").html(theResponse);
				setTimeout(function(){
				    $("#response").html("");
				}, 1000);
			});
		});
		
		$(".proj_to_sec").multiSelect({
			afterSelect: function(values){
				$.post("project_to_section.php", "a=ins&params="+values, function(theResponse){
					$("#response").html(theResponse);
					setTimeout(function(){
					    $("#response").html("");
					}, 1000);
				});
			},
			afterDeselect: function(values){
				$.post("project_to_section.php", "a=del&params="+values, function(theResponse){
					$("#response").html(theResponse);
					setTimeout(function(){
					    $("#response").html("");
					}, 1000);
				});
			},
            selectionHeader: "<div class='selectionHeadertext'>ASSOCIATED WITH:</div>"
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
					<th scope="col" class="td5">LAND</th>
					<th scope="col" class="td5">SECTION</th>
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();
				
				if(isset($_POST["cpos"]) && isset($_POST["pos"]))
				{
					foreach($_POST["pos"] as $key=>$value)
					{
						$strSQL = "UPDATE ".PREFIX."_project SET pos=".$value." WHERE id_PROJ=".$key."";
						$objConn->i_query($strSQL);
					}
				}
				
				if(isset($_GET["id_PROJ"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_PROJ"],"INT");

					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_project SET aktiv=".$_GET["isA"]." WHERE id_PROJ=".$_GET["id_PROJ"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "DELETE FROM ".PREFIX."_project WHERE id_PROJ=".$_GET["id_PROJ"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
                    elseif($_GET["akt"]=="open")
					{
						$_GET["isH"]? $_GET["isH"] = 0 : $_GET["isH"] = 1;
				        $strSQL = "UPDATE ".PREFIX."_project SET highlight=".$_GET["isH"]." WHERE id_PROJ=".$_GET["id_PROJ"]."";
				        $objConn->i_query($strSQL);
				        trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
					}
				}
				
				// Fetch Lands
				$strSQL = "SELECT lnd.id_LAND, lnd.pos, lndn.name, lnd.aktiv
							FROM ".PREFIX."_land AS lnd JOIN ".PREFIX."_land_text AS lndn ON lnd.id_LAND=lndn.id_LAND
						   WHERE lndn.lan='".$cmslan."' ORDER BY lndn.name, lnd.id_LAND";
				$rsLand = $objConn->rs_query($strSQL);
				// Fetch Sections
				$strSQL = "SELECT lnd.id_SEC, lnd.pos, lndn.name, lnd.aktiv
							FROM ".PREFIX."_section AS lnd JOIN ".PREFIX."_section_text AS lndn ON lnd.id_SEC=lndn.id_SEC
						   WHERE lndn.lan='".$cmslan."' ORDER BY lndn.name, lnd.id_SEC";
				$rsSection = $objConn->rs_query($strSQL);
				
				// Fetch Projects
				$strSQL = "SELECT lnd.id_PROJ AS rowID, lnd.pos, lndn.name, lnd.aktiv, lnd.highlight,
							(SELECT GROUP_CONCAT(ID_SEC) FROM ".PREFIX."_project_section WHERE id_PROJ=rowID) AS id_SECS,
							(SELECT GROUP_CONCAT(ID_LAND) FROM ".PREFIX."_project_land WHERE id_PROJ=rowID) AS id_LANDS
							FROM ".PREFIX."_project AS lnd JOIN ".PREFIX."_project_text AS lndn ON lnd.id_PROJ=lndn.id_PROJ
						   WHERE lndn.lan='".$cmslan."' ORDER BY lnd.pos, rowID";
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
							doActions($iID,$iname,$isA,$isH,false,true,false,"id_PROJ",false,false,true);
						if($iID!=$row->rowID)
						{
							$row->aktiv? $class=$class : $class.=" disabled";
							$isA =  $row->aktiv;
                            $isH =  $row->highlight;
							echo "<tr class=\"$class\">";
							echo "<td class=\"td1\"><input type=\"text\" id=\"pos\" size=\"3\" name=\"pos[$row->rowID]\" value=\"$row->pos\" class=\"input_pos\" /></td>";
							echo "<td class=\"td2\">";
							echo "<a href=\"".THISMAINPAGENAME."_edit.php?id_PROJ=$row->rowID&name=$row->name\">$row->name</a>";
							echo "</td>";
							echo "<td class=\"td5\">";
							echo "<select class=\"proj_to_land\" name=\"projtoland[$row->rowID]\">";
							echo "<option value=\"$row->rowID#0\"></option>";
							if(isset($rsLand)&&!is_null($rsLand))
							{
								while($optRow = $rsLand->fetchObject())
								{
									$sel = "";
									if(!is_null($row->id_LANDS))
										if(in_array($optRow->id_LAND,explode(",",$row->id_LANDS)))
											$sel = " selected=\"selected\"";
									echo "<option value=\"$row->rowID#$optRow->id_LAND\"$sel>$optRow->name</option>";
								}
								$rsLand->rewind();
							}
							echo "</select>";
							echo "</td>";
							echo "<td class=\"td5\">";
							echo "<select class=\"proj_to_sec\" id=\"proj_to_sec_$row->rowID\" name=\"projtosec[$row->rowID]\" multiple=\"multiple\">";
							echo "<option value=\"$row->rowID#0\"></option>";
							if(isset($rsSection)&&!is_null($rsSection))
							{
								while($optRow = $rsSection->fetchObject())
								{
									$sel = "";
									if(!is_null($row->id_SECS))
										if(in_array($optRow->id_SEC,explode(",",$row->id_SECS)))
											$sel = " selected=\"selected\"";
									echo "<option value=\"$row->rowID#$optRow->id_SEC\"$sel>$optRow->name</option>";
								}
								$rsSection->rewind();
							}
							echo "</select>";
							echo "</td>";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
						}
					}	
					if($startrope)
						doActions($iID,$iname,$isA,$isH,false,true,false,"id_PROJ",false,false,true);
					echo "<tr class=\"table_command\">";
					echo "<td colspan=\"3\"><input type=\"submit\" name=\"cpos\" value=\"".$arrTextes["admin"]["changeorder"]."\" /></td>";
					echo "<td colspan=\"2\"><div id=\"response\"></div></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td colspan=\"5\" class=\"first\">".$arrTextes["errors"]["nodata"]."</td>";
					echo "</tr>";
				}
				?>
			</table>
			</form>
	</div>
</body>
</html>