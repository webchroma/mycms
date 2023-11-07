<?php
if(isset($_POST["k_send"]))
	include_once("inc/fx/kform_submit.inc.php");
$strKFormulare = "<div id=\"kontaktformular\">";
$strKFormulare .= "<form method=\"post\" action=\"$pageurl\" name=\"k_form\" id=\"k_form\">";
$strKFormulare .= "<input type=\"hidden\" name=\"l\" value=\"$cmslan\" />"; 
$strKFormulare .= "<input type=\"hidden\" name=\"k_tomail\" value=\"".META_MAIL."\" />";
if(isset($strMSG)){
	$strKFormulare .= "<div id=\"k_response\"><h3>";

		$strKFormulare .= strtoupper($strMSG);
	//else
		//$strKFormulare .= $arrTextes["forms"]["sending"];
	$strKFormulare .= "</h3></div>";
}
$strKFormulare .= "<div class=\"k_validate\"></div>";
$strKFormulare .= "<div class=\"kontakt_zeile\" style=\"text-align:right;\">".$arrTextes["forms"]["info"]."</div>";
$strKFormulare .= "<div class=\"kontakt_zeile\"><label for=\"\">".$arrTextes["forms"]["formname"]."*:</label> <input type=\"text\" id=\"k_name\" name=\"k_name\" value=\"\" class=\"kontakt_input\" size=\"50\" /></div>";
$strKFormulare .= "<div class=\"kontakt_zeile\"><label for=\"k_vorname\">".$arrTextes["forms"]["formsurname"]."*:</label> <input type=\"text\" id=\"k_surname\" name=\"k_surname\" value=\"\" class=\"kontakt_input\" size=\"50\" /></div>";
$strKFormulare .= "<div class=\"kontakt_zeile\"><label for=\"k_mail\">".$arrTextes["forms"]["mail"]."*:</label> <input type=\"text\" id=\"k_mail\" name=\"k_mail\" value=\"\" class=\"kontakt_input\" size=\"50\" /></div>";
$strKFormulare .= "<div class=\"kontakt_zeile\"><label for=\"k_fon\">".$arrTextes["forms"]["phone"]."*:</label> <input type=\"text\" id=\"k_fon\" name=\"k_fon\" value=\"\" class=\"kontakt_input\" size=\"50\" /></div>";
$strKFormulare .= "<div class=\"kontakt_zeile\"><label for=\"k_fax\">".$arrTextes["forms"]["fax"].":</label> <input type=\"text\" id=\"k_fax\" name=\"k_fax\" value=\"\" class=\"kontakt_input\" size=\"50\" /></div>";
$strKFormulare .= "<div class=\"kontakt_zeile textfeld\"><label for=\"k_msg\"></label> <textarea id=\"k_msg\" name=\"k_msg\" cols=\"50\" rows=\"10\" class=\"kontakt_nachricht\"></textarea></div>";
$strKFormulare .= "<div class=\"kontakt_zeile send-response\">";
$strKFormulare .= "<span class=\"contact_informativa\">".$arrTextes["forms"]["informativa"]."</span>";
$strKFormulare .= "<span class=\"contact_informativa\"><input id=\"k_informativa\" type=\"checkbox\" value=\"1\" name=\"k_informativa\" />&nbsp;&nbsp;".$arrTextes["forms"]["informativa_ok"]."</span>";
$strKFormulare .= "<label>&nbsp;</label><input type=\"submit\" id=\"k_send\" name=\"k_send\" value=\"".$arrTextes["forms"]["submit"]."\" class=\"kontakt_senden\" />";
//$strKFormulare .= "<div id=\"k_response_ormsg\">".$arrTextes["forms"]["sending"]."</div>";
$strKFormulare .= "</div>";
$strKFormulare .= "</form>";
$strKFormulare .= "<br class=\"break\" />";
$strKFormulare .= "</div>";
$strTXT = str_replace("[CONTACTFORM]",$strKFormulare,$strTXT);
?>