<div id="accordion">
<?php
$strSQL = "SELECT lan.name AS lanname, lan.lan, lan.notext, lan.aktiv, meta.meta_key, meta.meta_des
			FROM ".PREFIX."_languages AS lan LEFT JOIN ".PREFIX."_preferences_metatext AS meta ON lan.lan=meta.lan";
$rs = $objConn->rs_query($strSQL);
if ($rs->count() > 0)
{
	while($row = $rs->fetchObject())
	{
        echo "<h3>$row->lanname</h3>";
        echo "<div>";
        echo "<ul class=\"form\">";
		echo "<li>".$arrTextes["forms"]["title"]."<br />";
		isset($_POST["".$row->lan."_name"]) ? $val = $_POST["".$row->lan."_name"] : $val = "";
		echo "<input name=\"".$row->lan."_name\" type=\"text\" id=\"".$row->lan."_name\" maxlength=\"255\" value=\"$val\" />";
		echo "</li>";
		echo "<li>".$arrTextes["forms"]["txt"]."<br />";
		isset($_POST["".$row->lan."_txt"]) ? $val = $_POST["".$row->lan."_txt"] : $val = "";
		echo "<textarea name=\"".$row->lan."_txt\" class=\"mceEditor\" rows=\"25\" id=\"".$row->lan."_txt\">$val</textarea>";
		echo "</li>";
		echo "</ul>";
		echo "<ul class=\"form\">";
		echo "<li style=\"border:none;\"><strong>".$arrTextes["forms"]["meta_info_title"]."</strong></li>";
		echo "<li>".$arrTextes["prefs"]["web_title"]."<br />";
		isset($_POST["".$row->lan."_web_title"]) ? $val = $_POST["".$row->lan."_web_title"] : $val = "";
		echo "<input name=\"".$row->lan."_web_title\" type=\"text\" id=\"".$row->lan."_web_title\" maxlength=\"255\" value=\"$val\" />";
		echo "</li>";
		echo "<li>".$arrTextes["prefs"]["meta_key"]."<br />";
		isset($_POST["".$row->lan."_meta_key"]) ? $val = $_POST["".$row->lan."_meta_key"] : $val = "";
		echo "<textarea name=\"".$row->lan."_meta_key\" rows=\"5\" id=\"".$row->lan."_meta_key\">$val</textarea>";
		echo "</li>";
		echo "<li>".$arrTextes["prefs"]["meta_des"]."<br />";
		isset($_POST["".$row->lan."_meta_des"]) ? $val = $_POST["".$row->lan."_meta_des"] : $val = "";
		echo "<textarea name=\"".$row->lan."_meta_des\" rows=\"5\" id=\"".$row->lan."_meta_des\">$val</textarea>";
		echo "</li>";
		echo "</ul>";
		echo "</div>";
	}
}
else
{
	echo "<div>";
	echo "<ul class=\"form\">";
	echo "<li><strong>".$strLanName."</strong></li>";
	echo "<li>".$arrTextes["forms"]["title"]."<br />";
	isset($_POST["".$cmslan."_name"]) ? $val = $_POST["".$cmslan."_name"] : $val = "";
	echo "<input name=\"".$cmslan."_name\" type=\"text\" id=\"".$cmslan."_name\" maxlength=\"255\" value=\"$val\" />";
	echo "</li>";
	echo "<li>".$arrTextes["forms"]["txt"]."<br />";
	isset($_POST["".$cmslan."_txt"]) ? $val = $_POST["".$cmslan."_txt"] : $val = "";
	echo "<textarea name=\"".$cmslan."_txt\" rows=\"25\" class=\"mceEditor\" id=\"".$cmslan."_txt\">$val</textarea>";
	echo "</li>";
	echo "</ul>";
	echo "<ul class=\"form\">";
	echo "<li style=\"border:none;\"><strong>".$arrTextes["forms"]["meta_info_title"]."</strong></li>";
	echo "<li>".$arrTextes["prefs"]["web_title"]."<br />";
	isset($_POST["".$l."_web_title"]) ? $val = $_POST["".$l."_web_title"] : $val = "";
	echo "<input name=\"".$l."_web_title\" type=\"text\" id=\"".$l."_web_title\" maxlength=\"255\" value=\"$val\" />";
	echo "</li>";
	echo "<li>".$arrTextes["prefs"]["meta_key"]."<br />";
	isset($_POST["".$l."_meta_key"]) ? $val = $_POST["".$l."_meta_key"] : $val = "";
	echo "<textarea name=\"".$l."_meta_key\" rows=\"5\" id=\"".$l."_meta_key\">$val</textarea>";
	echo "</li>";
	echo "<li>".$arrTextes["prefs"]["meta_des"]."<br />";
	isset($_POST["".$l."_meta_des"]) ? $val = $_POST["".$l."_meta_des"] : $val = "";
	echo "<textarea name=\"".$l."_meta_des\" rows=\"5\" id=\"".$l."_meta_des\">$val</textarea>";
	echo "</li>";
	echo "</ul>";
	echo "</div>";
}
?>
</div>