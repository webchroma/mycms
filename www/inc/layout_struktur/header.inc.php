<header class="clearfix">
	<div class="header_top w_95 clearfix">
        <div id="logo_smalldevice">
        <?php
		if(MENU_HOME_LOGO)
			echo "<a href=\"/$cmslan\">";
		echo "<img src=\"/imago/logo.png\" alt=\"".WEB_TITLE."\" title=\"".WEB_TITLE."\" />";
		if(MENU_HOME_LOGO)
			echo "</a>";
        ?>
        </div>
		<?php
		//build header menu
		echo "<div id=\"dv_menu_header_dx\">";
		$domenu = "header"; // which menu to display _ leave empty if all pages are shown
		echo "<nav id=\"menu_$domenu\" role=\"navigation\"><ul>";
		doNavi($p,$pp,$cmslan,$domenu,$objConn);
        echo "</ul></nav>";
		/* retrieving information from page kontakt _ fixed not deletable */
		$strSQLK = "SELECT pagtxt.texto
					FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
					WHERE db.symlink='kontakt' AND pagtxt.lan='$cmslan'";
		$rsK = $objConn->rs_query($strSQLK);
		if ($rsK->count() > 0)
		{
			$rowK = $rsK->fetchAssocArray();
			echo "<div id=\"kontakt\"><div id=\"inner_kontakt\">".$rowK["texto"]."</div></div>";
		}
		//build language menu
		echo "<nav id=\"menu_lan\" role=\"navigation\"><ul>";
		doLanMenu($cmslan,$pageurl,$objConn);
		echo "</ul></nav>";
		echo "</div>";
        //hamburger menu icon
        echo "<div class=\"dv_menu_HH\">";
        echo "<a href=\"#\" id=\"HH_menu_header_icon\" class=\"HH_icon\">&#9776;</a>";
        echo "</div>";
		?>
	</div>
	<div class="header_bottom w_95 clearfix">
		<div id="logo_main">
        <?php
		if(MENU_HOME_LOGO)
			echo "<a id=\"logo\" class=\"left\" href=\"/$cmslan\">";
		echo "<img src=\"/imago/logo.png\" alt=\"".WEB_TITLE."\" title=\"".WEB_TITLE."\" />";
		if(MENU_HOME_LOGO)
			echo "</a>";
        ?>
        </div>
        <?php
		//build section and land menu
		echo "<nav id=\"menu_main\" role=\"navigation\"><ul>";
        echo "<li class=\"menu_title\">".$arrTextes["GRT"]["menu_title_theme"]."</li>";
		doSectionMenu($cmslan,$p,$pp,$objConn);
		echo "</ul><ul>";
        echo "<li class=\"menu_title\">".$arrTextes["GRT"]["menu_title_land"]."</li>";
		doLandMenu($cmslan,$s,$p,$pp,$objConn);
		echo "</ul>";
        if($s!="nws"&&($p!=""||$pp!=""))
            echo "<div class=\"reset_menu\"></div>";
        echo "</nav>";
		//hamburger menu icon
        echo "<div class=\"dv_menu_HH\">";
        echo "<a href=\"#\" id=\"HH_menu_header_icon\" class=\"HH_icon\">&#9776;</a>";
        echo "</div>";
		?>
	</div>
</header>