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
		<?=$arrTextes["users"]["title"]?>
		<div style="float:right;margin-right:5px;"><a href="<?=THISMAINPAGENAME?>_neu.php"><?=$arrTextes["users"]["new"]?></a></div>
		<br class="clear" />
	</div>
	<div id="mainpage">
			<div id="dialog"></div>
			<table width="100%">
				<tr>
					<th scope="col" class="first">-</th>
					<th scope="col"><?=strtoupper($arrTextes["login"]["formuser"])?></th>
					<th scope="col"><?=strtoupper($arrTextes["login"]["formpassword"])?></th>
					<th scope="col"><?=$arrTextes["users"]["zone"]?></th>
					<th scope="col" class="akttd"><?=$arrTextes["aktions"]["action"]?></th>
				</tr>
				<?php
				$objConn = MySQL::getIstance();	
				if(isset($_GET["id_USR"]) && isset($_GET["akt"]))
				{
					settype($_GET["id_USR"],"INT");
					if($_GET["akt"]=="dea")
					{
						if(isset($_GET["isA"]))
						{
							$_GET["isA"]? $_GET["isA"] = 0 : $_GET["isA"] = 1;
							$strSQL = "UPDATE ".PREFIX."_admin SET aktiv=".$_GET["isA"]." WHERE id_USR=".$_GET["id_USR"]."";
							$objConn->i_query($strSQL);
							trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["newstatus"]."");
						}
					}
					elseif($_GET["akt"]=="loes")
					{
						$strSQL = "DELETE FROM ".PREFIX."_admin WHERE id_USR=".$_GET["id_USR"]."";
						$objConn->i_query($strSQL);
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_GET["name"].") ".$arrTextes["tracking"]["delete"]."");
					}
				}
				
				$strSQL = "SELECT adm.id_USR AS rowID, adm.aktiv, adm.usr, adm.pwd, CONCAT(adm.nname,' ',adm.vname) AS name, admzo.name AS znome
						   FROM ".PREFIX."_admin AS adm LEFT JOIN ".PREFIX."_admin_usr_zone AS admzu ON adm.id_USR=admzu.id_USR
						   LEFT JOIN ".PREFIX."_admin_zone AS admzo ON admzu.id_ZONA=admzo.id_ZONA
						   ORDER BY adm.nname ASC, admzo.name ASC";
				
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
							doActions($iID,$iname,$isA,false,false,true,false,"id_USR",true,true,false);
						if($iID!=$row->rowID)
						{
							$row->aktiv? $class=$class : $class.=" disabled";
							if($row->rowID==$_SESSION["usr"]["id_USR"]) $class="me";
							$isA =  $row->aktiv;
							echo "<tr class=\"$class\">";
							echo "<td width=\"20%\" class=\"first\">$row->name</td>";
							echo "<td width=\"10%\">$row->usr</td>";
							echo "<td width=\"10%\">$row->pwd</td>";
							echo "<td class=\"45%\">";
							$iID = $row->rowID;
							$iname = $row->name;
							$startrope = true;
						}
						echo $row->znome."&nbsp;";
					}
					
					if($startrope)
						doActions($iID,$iname,$isA,false,false,true,false,"id_USR",true,true,false);
				}
				?>
			</table>
	</div>
</body>
</html>