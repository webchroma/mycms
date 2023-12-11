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
	});
</script>
</head>

<body>
	<div id="submenu">
		<?=$arrTextes["news"]["title"]?>
		<div style="float:right;margin-right:5px;"><a href="<?=THISMAINPAGENAME?>_neu.php"><?=$arrTextes["news"]["new"]?></a></div>
		<br class="clear" />
	</div>
	<div id="mainpage">
			<div id="dialog"></div>
			<table width="100%">
				<tr>
					<th scope="col" class="td5">-</th>
					<th scope="col" class="td5"><?=$arrTextes["news"]["user"]?></th>
					<th scope="col" class="td9"><?=$arrTextes["news"]["lan"]?></th>
					<th scope="col" class="td6"><?=$arrTextes["news"]["datum"]?></th>
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();
				
				if(isset($_GET["id_NWS"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_NWS"],"INT");

					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_news SET aktiv=".$_GET["isA"]." WHERE id_NWS=".$_GET["id_NWS"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "DELETE FROM ".PREFIX."_news WHERE id_NWS=".$_GET["id_NWS"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
				}
				
				
				$strSQL = "SELECT nws.id_NWS AS rowID, nws.datum, nws.aktiv, CONCAT(adm.nname,' ',adm.vname) AS usr,
						   (SELECT GROUP_CONCAT(nwst2.lan) FROM ".PREFIX."_news_text AS nwst2 WHERE nwst2.id_NWS=nws.id_NWS) AS lans,
						   (SELECT nwst.name FROM ".PREFIX."_news_text AS nwst WHERE nwst.id_NWS=nws.id_NWS AND nwst.lan='".$cmslan."') AS name
						   FROM ".PREFIX."_news AS nws LEFT JOIN ".PREFIX."_admin AS adm ON nws.id_USR=adm.id_USR
						   ORDER BY datum DESC";
				
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
							doActions($iID,$iname,$isA,false,false,true,false,"id_NWS",false,false,false);
						if($iID!=$row->rowID)
						{
							$row->aktiv? $class=$class : $class="disabled";
							$isA =  $row->aktiv;
							
							if(is_null($row->name)) $row->name = str_replace("#LAN",strtoupper($cmslan),$arrTextes["news"]["nolantit"]);
							
							echo "<tr class=\"$class\">";
							echo "<td class=\"td5\"><a href=\"".THISMAINPAGENAME."_edit.php?id_NWS=$row->rowID&name=$row->name\">$row->name</a></td>";
							echo "<td class=\"td5\">$row->usr</td>";
							echo "<td class=\"td9\">$row->lans</td>";
							echo "<td class=\"td6\">".format_date($row->datum,'date')."</td>";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
						}
					}	
					if($startrope)
						doActions($iID,$iname,$isA,false,false,true,false,"id_NWS",false,false,false);
				}
				else
				{
					echo "<tr>";
					echo "<td colspan=\"4\" class=\"first\">".$arrTextes["errors"]["nodata"] ."</td>";
					echo "</tr>";
				}
				?>
			</table>
	</div>
</body>
</html>