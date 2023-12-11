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
        
        $(".land_to_sec").multiSelect({
			afterSelect: function(values){
				$.post("land_to_section.php", "a=ins&params="+values, function(theResponse){
					$("#response").html(theResponse);
					setTimeout(function(){
					    $("#response").html("");
					}, 1000);
				});
			},
			afterDeselect: function(values){
				$.post("land_to_section.php", "a=del&params="+values, function(theResponse){
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
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();
				
				if(isset($_POST["cpos"]) && isset($_POST["pos"]))
				{
					foreach($_POST["pos"] as $key=>$value)
					{
						$strSQL = "UPDATE ".PREFIX."_section SET pos=".$value." WHERE id_SEC=".$key."";
						$objConn->i_query($strSQL);
					}
				}
				
				if(isset($_GET["id_SEC"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_SEC"],"INT");

					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_section SET aktiv=".$_GET["isA"]." WHERE id_SEC=".$_GET["id_SEC"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "DELETE FROM ".PREFIX."_section WHERE id_SEC=".$_GET["id_SEC"]."";
                        echo $strSQL;
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
				}
				
                // Fetch Lands
				$strSQL = "SELECT lnd.id_LAND, lnd.pos, lndn.name, lnd.aktiv
							FROM ".PREFIX."_land AS lnd JOIN ".PREFIX."_land_text AS lndn ON lnd.id_LAND=lndn.id_LAND
						   WHERE lndn.lan='".$cmslan."' ORDER BY lndn.name, lnd.id_LAND";
				$rsLand = $objConn->rs_query($strSQL);
    
				$strSQL = "SELECT lnd.id_SEC AS rowID, lnd.pos, lndn.name, lnd.aktiv,
                            (SELECT GROUP_CONCAT(id_LAND) FROM ".PREFIX."_land_section WHERE id_SEC=rowID) AS id_LANDS
							FROM ".PREFIX."_section AS lnd JOIN ".PREFIX."_section_text AS lndn ON lnd.id_SEC=lndn.id_SEC
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
							doActions($iID,$iname,$isA,false,false,true,false,"id_SEC",false,false,false);
						if($iID!=$row->rowID)
						{
							$row->aktiv? $class=$class : $class.=" disabled";
							$isA =  $row->aktiv;			
							echo "<tr class=\"$class\">";
							echo "<td class=\"td1\"><input type=\"text\" id=\"pos\" size=\"3\" name=\"pos[$row->rowID]\" value=\"$row->pos\" class=\"input_pos\" /></td>";
							echo "<td class=\"td2\">";
							echo "<a href=\"".THISMAINPAGENAME."_edit.php?id_SEC=$row->rowID&name=$row->name\">$row->name</a>";
							echo "</td>";
                            echo "<td class=\"td5\">";
							echo "<select class=\"land_to_sec\" id=\"land_to_sec_$row->rowID\" name=\"landtosec[$row->rowID]\" multiple=\"multiple\">";
							echo "<option value=\"$row->rowID#0\"></option>";
							if(isset($rsLand)&&!is_null($rsLand))
							{
								while($optRow = $rsLand->fetchObject())
								{
									$sel = "";
									if(!is_null($row->id_LANDS))
										if(in_array($optRow->id_LAND,explode(",",$row->id_LANDS)))
											$sel = " selected=\"selected\"";
									echo "<option value=\"$optRow->id_LAND#$row->rowID\"$sel>$optRow->name</option>";
								}
								$rsLand->rewind();
							}
							echo "</select>";
							echo "</td>";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
						}
					}	
					if($startrope)
						doActions($iID,$iname,$isA,false,false,true,false,"id_SEC",false,false,false);
					echo "<tr class=\"table_command\">";
					echo "<td colspan=\"2\"><input type=\"submit\" name=\"cpos\" value=\"".$arrTextes["admin"]["changeorder"]."\" /></td>";
					echo "<td colspan=\"2\"><div id=\"response\"></div></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td colspan=\"3\" class=\"first\">".$arrTextes["errors"]["nodata"]."</td>";
					echo "</tr>";
				}
				?>
			</table>
			</form>
	</div>
</body>
</html>