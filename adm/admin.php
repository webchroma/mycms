<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
include_once("inc/page-head.inc.php");
?>
<body>
	<div id="head">
		<?php
		echo $_SESSION["usr"]["name"];
		?>
		&nbsp;-&nbsp;
		<span><a href="logO.php">LOGOUT</a></span>
	</div>
	<div id="icons">
		<i class="fa fa-info-circle fa-lg hilfe" aria-hidden="true"></i>
		<ul class="icons">
            <li><i class="fa fa-pencil fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["edit"]?>"></i><?=$arrTextes["help"]["edit"]?></li>
			<li><i class="fa fa-circle-o on fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["aktiv"]?>"></i> <?=$arrTextes["help"]["aktiv"]?></li>
			<li><i class="fa fa-circle-o red fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["deaktiv"]?>"></i> <?=$arrTextes["help"]["deaktiv"]?></li>
			<li><i class="fa fa-bar-chart fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["track"]?>"></i> <?=$arrTextes["help"]["track"]?></li>
			<li><i class="fa fa-trash-o fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["delete"]?>"></i> <?=$arrTextes["help"]["delete"]?></li>
			<li><i class="fa fa-info-circle fa-lg fa-fw" aria-label="<?=$arrTextes["help"]["caption"]?>"></i> <?=$arrTextes["help"]["caption"]?></li>
			<li><i class="fa fa-plus fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["page"]?>"></i> <?=$arrTextes["help"]["page"]?></li>
			<li><i class="fa fa-lock fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["deprotekt"]?>"></i> <?=$arrTextes["help"]["deprotekt"]?></li>
			<li><i class="fa fa-unlock fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["protekt"]?>"></i> <?=$arrTextes["help"]["protekt"]?></li>
			<li><i class="fa fa-home on fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["home"]?>"></i> <?=$arrTextes["help"]["home"]?></li>
			<li><i class="fa fa-home off fa-2x fa-fw" aria-label="<?=$arrTextes["help"]["dohome"]?>"></i> <?=$arrTextes["help"]["dohome"]?></li>			
			<li><span class="iconGREEN"></span> <?=$arrTextes["help"]["isakt"]?></li>
		</ul>
	</div>
	<div id="menu">
		<?php
		/* checking which sections are activ for user */
		if(isset($_SESSION["usr"]["zona"]))
		{
			$strWHERE = "";
			foreach($_SESSION["usr"]["zona"] as $value)
			{
				if($strWHERE!="") $strWHERE .= " OR";
				$strWHERE .= " id_ZONA=$value";
			}
			if($strWHERE!="") $strWHERE = " WHERE ".$strWHERE;
			$strSQL = "SELECT zona AS tz, name, page, (SELECT COUNT(subzona) FROM ".PREFIX."_admin_zone WHERE zona=tz) AS tsub FROM ".PREFIX."_admin_zone$strWHERE AND aktiv GROUP BY zona ORDER BY zona";
			$objConn = MySQL::getIstance();
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				echo "<ul class=\"mainmenu\">";
				while($zona = $rs->fetchObject())
				{
					$sname = strtolower(str_replace(" ","",doUrlUm($zona->name)));
					$zona->tsub>1?$link="subzona":$link=$zona->page;
					echo "<li id=\"li_$sname\" class=\"mainmenu\"><a class=\"amenu\" id=\"a_$sname\" href=\"".$link.".php?zona=$zona->tz\" target=\"mainframe\">".$zona->name."</a></li>";
				}
				echo "</ul>";
			}			
		}
		else
		{
			echo $arrTextes["messages"]["noaccess"];
		}
		?>
	</div>
	
	<br class="break" />
	<div id="framecontainer">
		<iframe id="mainframe" name="mainframe" class="mainframe" frameborder="0" src="start.php"></iframe>
	</div>
</body>
</html>