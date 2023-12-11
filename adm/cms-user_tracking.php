<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_tracking.php","",PAGENAME));

if(!isset($_REQUEST["id_USR"]))
{
	header("location:user.php");
}

settype($_REQUEST["id_USR"],"INT");
$strSQLWHERE = "";
$strFilterUrl = "";

if(isset($_REQUEST["fromtime"]))
{
	if($_REQUEST["fromtime"]!="")
	{
		if($_REQUEST["totime"]=="") $_REQUEST["totime"]=date("d.m.Y");
		$strFilter = " | ".$arrTextes["tracking"]["filterresult"]." ".$arrTextes["tracking"]["from"]." ".$_REQUEST["fromtime"]." ".$arrTextes["tracking"]["to"]." ".$_REQUEST["totime"]."";
		$fromtime = str_replace("/",".",$_REQUEST["fromtime"])." 00:00:00";
		$totime = str_replace("/",".",$_REQUEST["totime"])." 23:59:59";
		$fromtime = format_date($fromtime, "mysql-datetime");
		$totime = format_date($totime, "mysql-datetime");
		$strSQLWHERE = " AND admtrack.datum >= '".$fromtime."' AND admtrack.datum <= '".$totime."'";
		$isFilter = true;
		$strFilterUrl = "&fromtime=".$_REQUEST["fromtime"]."&totime=".$_REQUEST["totime"];
	}
}
include_once("inc/page-head.inc.php");
?>
<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-<?=$cmslan?>.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#fromtime").datepicker({
			showButtonPanel: true,
			regional: "<?=$cmslan?>"
		});
		$("#totime").datepicker({
			showButtonPanel: true,
			regional: "<?=$cmslan?>"
		});
	 });
</script>
</head>

<body>
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php"><?=$arrTextes["users"]["title"]?></a> / <?=$arrTextes["help"]["track"]?></div>
	<div id="filter">
		<div style="float:left; margin-left:5px; margin-top:5px; font-weight:bold">
			<?php 
				echo strtoupper($_REQUEST["name"]);
				if(isset($isFilter)) echo $strFilter;
			?>
		</div>
		<div style="float:right; margin-right:25px">
			<form id="filterfrm" name="filterfrm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
				<input type="hidden" name="id_USR" id="id_USR" value="<?=$_REQUEST["id_USR"]?>" />
				<input type="hidden" name="name" id="name" value="<?=$_REQUEST["name"]?>" />
				<?php
				if(isset($isFilter))
				{
					echo "<input type=\"submit\" name=\"nofilter\" id=\"nofilter\" value=\"".$arrTextes["tracking"]["delfilter"]."\" />";
				}
				?>
				<?=$arrTextes["tracking"]["from"]?>
				<input name="fromtime" type="text" id="fromtime" size="10" maxlength="10" />
				<?=$arrTextes["tracking"]["to"]?>
				<input name="totime" type="text" id="totime" size="10" maxlength="10" />
				<input type="submit" name="filterbtn" id="filterbtn" value="<?=$arrTextes["tracking"]["filter"]?>" />
			</form>
		</div>
		<br class="break" />
	</div>
	<br class="break" />
	<?php
	$objConn = MySQL::getIstance();
	
	if(!isset($_GET["sort"])) $_GET["sort"] = "DESC";
	
	/* paging */

	isset($_GET["pg"]) ? $thispg = $_GET["pg"] : $thispg = 1;

	settype($thispg,"INT");

	$strSQL = "SELECT COUNT(adm.nname) AS trs
			   FROM ".PREFIX."_admin AS adm RIGHT JOIN ".PREFIX."_admin_tracking AS admtrack ON adm.id_USR=admtrack.id_USR
			   WHERE adm.id_USR=".$_REQUEST["id_USR"]."".$strSQLWHERE."
			   ORDER BY admtrack.datum ".$_GET["sort"]."";

	if($rs = $objConn->rs_query($strSQL))
	{
		$row = $rs->fetchAssocArray();
		$tRS = $row["trs"];
	}
	$tOBJ = 100;
	$lastpg = ceil($tRS/$tOBJ);

	if ($thispg < 1 && $thispg == 0) 
	{ 
		$thispg = 1; 
	} 
	elseif ($thispg > $lastpg) 
	{ 
		$thispg = $lastpg; 
	}
	if($thispg==0) $thispg = 1;
	$strSQLLIMIT = " LIMIT " .($thispg - 1) * $tOBJ ."," .$tOBJ;
	if($lastpg>1)
	{
		echo "<div class=\"navi\">";
		for($i=1;$i<=$lastpg;$i++)
		{
			$i!=$thispg ? $class="button" : $class="buttonchoosen";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=".$_GET["sort"]."&id_USR=".$_REQUEST["id_USR"]."&name=".$_REQUEST["name"]."$strFilterUrl&pg=$i\" class=\"$class\">$i</a>&nbsp;";
		}
		echo "</div>";
	}
	?>
	<br />
	<div id="mainpage">
			<table width="100%%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th width="15%" class="first" scope="col">
						<a href="<?=$_SERVER['PHP_SELF']?>?sort=DESC&id_USR=<?=$_REQUEST["id_USR"]?>&name=<?=$_REQUEST["name"]?><?=$strFilterUrl?>&pg=<?=$thispg?>" class="sort">
						<img src="imago/freccia_dwn.png" height="15" width="10" alt="sortieren"/>
						</a>
						<a href="<?=$_SERVER['PHP_SELF']?>?sort=ASC&id_USR=<?=$_REQUEST["id_USR"]?>&name=<?=$_REQUEST["name"]?><?=$strFilterUrl?>&pg=<?=$thispg?>" class="sort">
						<img src="imago/freccia_up.png" height="15" width="10" alt="sortieren" />
						</a>
					</th>
					<th scope="col">INFO</th>
				</tr>
				<?php
				$strSQL = "SELECT CONCAT(adm.nname,' ',adm.vname) AS name, admtrack.datum, admtrack.info
						   FROM ".PREFIX."_admin AS adm RIGHT JOIN ".PREFIX."_admin_tracking AS admtrack ON adm.id_USR=admtrack.id_USR
						   WHERE adm.id_USR=".$_REQUEST["id_USR"]."$strSQLWHERE
						   ORDER BY admtrack.datum ".$_GET["sort"]." $strSQLLIMIT";
						
				$rs = $objConn->rs_query($strSQL);
				$class = "pos";
				if ($rs->count() > 0)
				{
					while($user = $rs->fetchObject())
					{
						$class == "pos" ? $class = "neg" : $class = "pos";
						echo "<tr class=\"$class\">";
						echo "<td width=\"15%\" class=\"first\">".format_date($user->datum)."</td>";
						echo "<td>$user->info</td>";
						echo "</tr>";
					}
				}
				else
				{
					echo "<tr class=\"$class\">";
					echo "<td colspan=\"3\" class=\"first\">".$arrTextes["errors"]["nodata"]."</td>";
					echo "</tr>";
				}
				?>
			</table>
	</div>
</body>
</html>