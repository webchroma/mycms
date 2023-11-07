<?php
$meta_des = META_DES;
$meta_key = META_KEY;
$strWHP = "db.symlink='stop'";
$p=$pp=null;
include_once("inc/layout_struktur/head.inc.php"); // header _ meta and scripting
echo "<div id=\"header\" class=\"clearfix\">";
echo "<img src=\"imago/logo.png\" alt=\"".WEB_TITLE."\" title=\"".WEB_TITLE."\" id=\"title\" />";
echo "</div>";
echo "<div id=\"body_stop\">";
include_once("inc/page_module/pages.inc.php");
echo "</div>";
include_once("inc/layout_struktur/footer.inc.php"); // footer
exit;
?>