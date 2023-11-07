</header>
<?php
if(MENU_HOME_LOGO)
	echo "<a href=\"/$cmslan\">";
echo "<img src=\"/imago/logo.png\" alt=\"".WEB_TITLE."\" title=\"".WEB_TITLE."\" id=\"title\" />";
//echo STAND."<strong>ART</strong>";
if(MENU_HOME_LOGO)
	echo "</a>";

//build header menu
echo "<nav id=\"menu_header\" class=\"navbar navbar-default navbar-static-top\"><ul class=\"nav navbar-nav navbar-right\">";
$domenu = "header"; // which menu to display _ leave empty if all pages are shown
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
?>
</header>