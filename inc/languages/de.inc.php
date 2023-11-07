<?php
/* set Locale on page */
$loc_var = setlocale(LC_ALL, "de_DE@euro", "de_DE", "de", "ge","de_DE@euro.UTF8", "de_DE.UTF8", "de.UTF8", "ge.UTF8","deu","german");
/* set names for languages */
$strLanName="DEUTSCH";

/* TEXT ON FRONT AND BACKEND */
// title of the website.
define("WEBTITLE","Gruppo per le Relazioni Transculturali");
$arrTextes["front"]["update"] = "aktualisierung";
$arrTextes["front"]["noie"] = "Ihrer Browser ist Alt. Bitte aktualisieren Sie es!";
$arrTextes["front"]["fotografen"] = "Die Fotografen";
$arrTextes["front"]["close"] = "Men&uuml; verstecken";
$arrTextes["front"]["open"] = "Men&uuml; anzeigen";
$arrTextes["front"]["impressum"] = "impressum";
/* preferences */
$arrTextes["preference"]["title"] = "Einstellungen";
/* log-on */
$arrTextes["login"]["formuser"] = "User ID";
$arrTextes["login"]["formpassword"] = "Passwort";
$arrTextes["login"]["formenter"] = "Anmelden";
$arrTextes["login"]["empty"] = "user.id und passwort eintragen";
$arrTextes["login"]["alpha"] = "unerlaubten Zeichen";
$arrTextes["login"]["nouser"] = "benutzer existiert nicht oder ist inaktiv";
$arrTextes["login"]["isuser"] = "benutzer existiert bereits";
$arrTextes["login"]["dologinmsg"] = "Bitte melden Sie sich an, um die Daten herunterladen zu k&ouml;nnen";
$arrTextes["login"]["doingloginmsg"] = "Die Daten werden gepr&uuml;ft";
// First, create an array of month names, January through December 
$arrMonths = array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"); 
// Then an array of day names, starting with Sunday 
$arrDays = array("S", "M", "D", "M", "D", "F", "S");
/* user forms */
$arrTextes["forms"]["info"] = "(* - Pflichtfelder)";
$arrTextes["forms"]["formname"] = "Vorname";
$arrTextes["forms"]["formsurname"] = "Nachname";
$arrTextes["forms"]["formakt"] = "Aktiv";
$arrTextes["forms"]["title"] = "Men&uuml; Titel";
$arrTextes["forms"]["txt"] = "Text";
$arrTextes["forms"]["firm"] = "Firma";
$arrTextes["forms"]["mail"] = "Email-Adresse";
$arrTextes["forms"]["phone"] = "Telefonnummer";
$arrTextes["forms"]["fax"] = "Faxnummer";
$arrTextes["forms"]["msg"] = "Ihre Mitteilung";
$arrTextes["forms"]["submit"] = "Senden";
$arrTextes["forms"]["sent_ok"] = "Ihre Mitteilung wurde geschickt";
$arrTextes["forms"]["sent_ko"] = "Ein Fehler ist aufgetreten. Bitte versuchen Sie es nochmals";
$arrTextes["forms"]["sending"] = "Ihre Mitteilung wird geschickt ...";
/* messages */
$arrTextes["messages"]["confirm"] = "Bitte bestätigen";
$arrTextes["messages"]["nonws"] = "Kein news wurde für die startseite gewählt";
$arrTextes["messages"]["noaccess"] = "Sie können keine Bereiche verwalten. Bitte kontaktieren Sie den Administrator";
$arrTextes["messages"]["nokeylink"] = "KEIN LINK VERBUNDEN";
$arrTextes["messages"]["nokeyexists"] = "KEIN LINK EINGEF&Uuml;GT";
$arrTextes["errors"]["general"] = "Ein Fehler ist aufgetreten. Bitte versuchen Sie es nochmals bzw. kontaktieren Sie dem Administrator";
$arrTextes["errors"]["generalshort"] = "Ein Fehler ist aufgetreten.";
$arrTextes["errors"]["nopage"] = "Die Seite wurde nicht gefunden";
$arrTextes["errors"]["insert"] = "Fehler beim eintragen. Versuchen Sie es nochmals";
$arrTextes["errors"]["nodata"] = "Es liegen keine Daten vor";
$arrTextes["errors"]["nomedia"] = "Es liegen keine Media-Daten vor";
$arrTextes["errors"]["imagesize"] = "Bild zu groß. Max (media_IMG_MAX_Wpx-media_IMG_MAX_Hpx)";
$arrTextes["errors"]["filesize"] = "File zu groß. Max ".ini_get("upload_max_filesize")."B";
$arrTextes["errors"]["wrongpara"] = "Fehlerhafte Parameter. Versuchen Sie es nochmals";
$arrTextes["errors"]["insert_folder"] = "Fehler beim Erstellen eines Ordners für den Client";
/* front end */
$arrTextes["page"]["werkzeug"] = "Werkzeuge für:";
$arrTextes["page"]["impressum"] = "Impressum";
$arrTextes["page"]["protect"] = "Sie haben keinen Zugriff auf diese Seite";
$arrTextes["nwsletter"]["title"] = "HZT Berlin Newsletter bestellen";
$arrTextes["nwsletter"]["submit"] = "bestellen";
$arrTextes["nwsletter"]["nomail"] = "Die E-Mail ist nicht korrekt oder existiert nicht";
$arrTextes["nwsletter"]["done"] = "Daten gesendet.<br />Sie erhalten demn&auml;chst eine Best&auml;tigungsmail in Ihrem Postfach.";
$arrTextes["nwsletter"]["archiv"] = "ARCHIV";
$arrTextes["search"]["title"] = "SUCHEN AUF DER HZT WEBSEITE";
$arrTextes["search"]["btn"] = "SUCHEN";
$arrTextes["search"]["minsign"] = "mindestens 4 Buchstaben";
$arrTextes["search"]["result"] = "Suchergebnisse f&uuml;r:";
$arrTextes["search"]["noresult"] = "Keine Ergebnisse f&uuml;r:";
/* gallery */
$arrTextes["gallery"]["flickr"] = "Bilder bei Flickr";
$arrTextes["gallery"]["youtube"] = "Videos bei YouTube";

/*ONLY BACKEND*/
/*general*/
$arrTextes["admin"]["new"] = "NEW";
$arrTextes["admin"]["admin"] = "LIST";
$arrTextes["admin"]["changeorder"] = "CHANGE ORDER";
/* forms _ messages and buttons */
$arrTextes["forms"]["insert"] = "Eintragen";
$arrTextes["forms"]["modify"] = "Speichern";
$arrTextes["forms"]["titlemessage"] = "Formular ausfüllen";
$arrTextes["forms"]["isuser"] = "User.id bereits vergeben";
$arrTextes["forms"]["zone"] = "Bitte ein Bereich auswählen";
$arrTextes["forms"]["allfields"] = "Bitte alle Felder eintragen";
$arrTextes["forms"]["ismodified"] = "Die Daten würden verändert";
$arrTextes["forms"]["notextdef"] = "Kein Info in dieser Sprache verfügbar";
$arrTextes["forms"]["errmail"] = "Bitte eine richtige E-mail eintragen";
$arrTextes["forms"]["meta_info_title"] = "Meta Informationen für die Seite.";
$arrTextes["forms"]["meta_info_title_extra"] = "Falls leer, werden Titel, Keywords und Description aus dem Einstellung-Panel benutzt";
/* start _ browser check messages */
$arrTextes["browser"]["info"] = "Um die CMS vollständig nutzen zu können, beachten Sie bitte folgende Voraussetzungen";
$arrTextes["browser"]["active"] = "AKTIVIERT";
$arrTextes["browser"]["notactive"] = "INAKTIV";
$arrTextes["browser"]["installed"] = "INSTALLIERT";
$arrTextes["browser"]["notinstalled"] = "NICHT INSTALLIERT";
$arrTextes["browser"]["old"] = "DIE INSTALLIERTE VERSION IST ZU ALT (\"+playerVersion.major+\"). BITTE AKTUALISIEREN SIE ES";
/* help window _ icons */
$arrTextes["help"]["edit"] = "Bearbeiten";
$arrTextes["help"]["aktiv"] = "Aktiv -> Klicken zum deaktivieren";
$arrTextes["help"]["deaktiv"] = "Inaktiv -> Klicken zum aktivieren";
$arrTextes["help"]["track"] = "Tracking";
$arrTextes["help"]["delete"] = "L&ouml;schen";
$arrTextes["help"]["isdea"] = "Deaktiviert";
$arrTextes["help"]["isakt"] = "Ausgew&auml;hlt/Eigener User";
$arrTextes["help"]["page"] = "Eine neue Unterseite f&uml;r diese Hauptseite einfügen";
$arrTextes["help"]["protekt"] = "Seite ist ungeschützt. Klicken, um sie mit user.id und passwort zu schützen";
$arrTextes["help"]["deprotekt"] = "Seite ist geschützt";
$arrTextes["help"]["home"] = "Festgelegt als Hauptseite des ersten Untermen&uuml;s";
$arrTextes["help"]["dohome"] = "Nicht Hauptseite des ersten Untermen&uuml;s";
$arrTextes["help"]["caption"] = "Bild, Video oder Doc beschriften";
$arrTextes["help"]["link"] = "Externen Link einfügen";
$arrTextes["help"]["multi"] = "Mehrfachoptionen";
$arrTextes["help"]["mail"] = "<strong>Link zu Email im Text</strong><br />Das E-Mail nach <em>mailto:</em> schreiben<br />";
$arrTextes["help"]["href"] = "<strong>Link zu externen Webseiten</strong><br />Das Link nach <em>http://</em> schreiben<br />";
$arrTextes["help"]["extvideo"] = "VIMEO EINBETTEN";
/* users */
$arrTextes["users"]["title"] = "Nutzer Verwaltung";
$arrTextes["users"]["new"] = "NEUER NUTZER";
$arrTextes["users"]["zone"] = "ZUGANG ZU DEN BEREICHEN";
$arrTextes["users"]["folder"] = "NUTZER ORDNER";
$arrTextes["users"]["nodata"] = "Es liegen keine Dateien vor";
/* news */
$arrTextes["news"]["title"] = "Verwaltung Aktuelles";
$arrTextes["news"]["new"] = "NEUER EINTRAG";
$arrTextes["news"]["user"] = "VERFASST VOM:";
$arrTextes["news"]["datum"] = "DATUM";
$arrTextes["news"]["lan"] = "SPRACHEN";
$arrTextes["news"]["nolantit"] = "Kein News mit der default-Sprache (#LAN)";
$arrTextes["news"]["update"] = "Das Datum wurde aktualisiert";
$arrTextes["news"]["doupdate"] = "Das Datum aktualisieren";
/* gallery */
$arrTextes["gallery"]["name"] = "Galerie auswählen: ";
$arrTextes["gallery"]["title"] = "Verwaltung Galerie";
$arrTextes["gallery"]["new"] = "Neue Galerie";
$arrTextes["gallery"]["totalmedia"] = "Alle Medien";
$arrTextes["gallery"]["allmedia"] = "Alle Medien";
$arrTextes["gallery"]["doseiten"] = "Für die Seitenleiste auswählen";
$arrTextes["gallery"]["delseiten"] = "Nicht mehr für die Seitenleiste nutzen";
$arrTextes["gallery"]["seiten"] = "Seitenleiste-Status des Bild wechseln";
$arrTextes["gallery"]["thumb_name"] = "Gallerie Thumbnail";
$arrTextes["gallery"]["thumb"] = "#name as Thumbnail f&uuml;r diese Gallerie w&auml;hlen";
$arrTextes["gallery"]["nothumb"] = "Das Thumbnail f&uuml;r diese Gallerie w&uuml;rde nicht ausgew&auml;hlt. Das erste Bild wird benutzt";
/* pages */
$arrTextes["pages"]["title"] = "Verwaltung Seiten";
$arrTextes["pages"]["new"] = "Neue Seite";
$arrTextes["pages"]["subpages"] = "Unterseiten insgesamt";
$arrTextes["pages"]["position"] = "Reihenfolge &Auml;ndern";
$arrTextes["pages"]["symlink"] = "Identificationsname (Symlink)";
$arrTextes["pages"]["symlink_txt"] = "Einmalige Identificationsname für die Seite. Max 50 Buchstaben, keine Leerzeichen, Sonderzeichen oder Nummer. z.B. für Kontakt/Impressum -> contact";
$arrTextes["pages"]["ongoing"] = "laufend";
$arrTextes["pages"]["ongoing_txt"] = "L&auml;uft das Projekt noch?";
/* preferences */
$arrTextes["prefs"]["web_stop"] = "WEBSEITE BLOCKIEREN";
$arrTextes["prefs"]["web_stop_txt"] = "(Das Text kann in PAGES gefunden werden, unter STOP)";
$arrTextes["prefs"]["web_title"] = "TITEL DER SEITE";
$arrTextes["prefs"]["meta"] = "META TAGS (SUCHMACHINEN)";
$arrTextes["prefs"]["meta_copy"] = "COPYRIGHT INFO IN DEN META-TAGS";
$arrTextes["prefs"]["meta_mail"] = "INFO E-MAIL ADRESSE IN DEN META-TAGS";
$arrTextes["prefs"]["meta_mail_exp"] = "Das Mail wird auch für das Formular benutzt";
$arrTextes["prefs"]["meta_key-des"] = "STANDARD KEYWORDS UND DESCRIPTION (werden benutzt, wenn nicht in den einzelnen Seiten angegeben) - max 255 Buchstaben";
$arrTextes["prefs"]["meta_key"] = "KEYWORDS - mit Komma getrennt";
$arrTextes["prefs"]["meta_des"] = "DESCRIPTION";
$arrTextes["prefs"]["meta_explanation"] = "<strong>META TAGS</strong><br />Die Meta Informationen sind wichtige bestandteile des Suchmachine Optimierung.<br />Am bestens werden für jeder Seite getrennte Titel, Description und Keywords angegeben, die die wichtigen Informationen auf der Seite wiedergeben. F&uuml;r alle gilt: maximal 255 Zeichen";
$arrTextes["prefs"]["meta_explanation_title"] = "<strong>TITLE DER SEITE:</strong><br /> Der Titel der Seite muss die wichtigsten Keywords behalten, die auf der Seite zu finden ist. Erscheint oben auf der Browserleiste";
$arrTextes["prefs"]["meta_explanation_keys"] = "<strong>KEYWORDS:</strong><br /> Schl&uuml;sselw&ouml;rter, mit Komma getrennt.";
$arrTextes["prefs"]["meta_explanation_des"] = "<strong>DESCRIPTION:</strong><br />Eine kurze Beschreibung der Seite.";
$arrTextes["prefs"]["lans"] = "SPRACHEN";
$arrTextes["prefs"]["newlan"] = "neue sprache";
$arrTextes["prefs"]["landes"] = "Bezeichnung (2 Buchstaben) | Name | Standard text (255 Buchstaben)";
$arrTextes["prefs"]["chs_gal"] = "Galerie ausw&auml;hlen";
$arrTextes["prefs"]["chsen_gal"] = "ausgew&auml;hlte Galerie";
$arrTextes["prefs"]["chs_gal_link"] = "LINK ZUR GALERIE?";
$arrTextes["prefs"]["start"] = "STARTSEITE";
$arrTextes["prefs"]["start_from_pages"] = "HOMEPAGE AUS DEN SEITEN";
$arrTextes["prefs"]["start_rnd_media"] = "ZUFF&Auml;LLIGES BILD AUS DEM MEDIA ARCHIV";
$arrTextes["prefs"]["start_rnd_gall"] = "ZUFF&Auml;LLIGES BILD AUS EINE PORTFOLIO GALERIE";
$arrTextes["prefs"]["gall_tot_media"] = "Foto insgesamt";
$arrTextes["prefs"]["gall_no_media"] = "die Galerie hat kein Foto";
$arrTextes["prefs"]["no_gal"] = "keine oder keine aktive Galerie";
$arrTextes["prefs"]["menu"] = "NAVIGATION";
$arrTextes["prefs"]["menu_home_logo"] = "LOGO ODER TITEL DER WEBSEITE ALS LINK ZUR HOMEPAGE NUTZEN";
$arrTextes["prefs"]["menu_home_fix"] = "ZEIGE IMMER LINK ZUR HOMEPAGE IN DER NAVIGATION-MEN&Uuml;";
$arrTextes["prefs"]["menu_home_fix_txt"] = "Muss das Link zur Homepage in der Navigation-Men&uuml; immer angezeigt oder nur wenn in Unterseiten?";
$arrTextes["prefs"]["menu_home"] = "ZEIGE LINK ZUR HOMEPAGE IN DER NAVIGATION-MEN&Uuml;";
$arrTextes["prefs"]["menu_home_txt"] = "Muss das Link zur Homepage in der Navigation-Men&uuml; angezeigt werden?";
$arrTextes["prefs"]["menu_portfolio"] = "PORTFOLIO GALERIEN IM HAUPT NAVIGATION-MEN&Uuml;";
$arrTextes["prefs"]["menu_portfolio_txt"] = "Sollen im haupt Navigation-Men&uuml; die Galerien gezeigt werden? Wenn nicht, wird die Seite Portfolio gezeigt";
$arrTextes["prefs"]["portfolio"] = "PORTFOLIO";
$arrTextes["prefs"]["portfolio_thmbs"] = "ZEIGE GALERIEN VORSCHAUEN";
$arrTextes["prefs"]["portfolio_thmbs_txt"] = "Wenn nicht, wird die Seite Portfolio aus Pages gezeigt";
$arrTextes["prefs"]["media"] = "GALERIE / MEDIEN";
$arrTextes["prefs"]["h"]="H&Ouml;HE";
$arrTextes["prefs"]["w"]="BREITE";
$arrTextes["prefs"]["thb_ref"]="THUMBNAIL REFERENZMASS";
$arrTextes["prefs"]["resize"]="FOTO SKALIEREN";
$arrTextes["prefs"]["resize_txt"] = "wenn nicht, wird eine Fehlermeldung ausgegeben, falls die Bilder die unten angegebenen Massen &uuml;berschreiten";
/* data upload _ gallery and javascript */
$arrTextes["media"]["title"] = "Verwaltung media (photo-video)";
$arrTextes["media"]["title_data"] = "Verwaltung media (dokumente)";
$arrTextes["media"]["docs"] = "Dokumente";
$arrTextes["media"]["no_portfolio"] = "Medien nicht in Portfolio Galerien";
$arrTextes["data"]["search"] = "AUSSUCHEN";
$arrTextes["data"]["format"] = "Nur folgende Formate gültig";
$arrTextes["data"]["upload"] = "HOCHLADEN";
$arrTextes["data"]["img"] = "BILD";
$arrTextes["data"]["vid"] = "VIDEO";
$arrTextes["data"]["extvid"] = "VIMEO/YOUTUBE";
$arrTextes["data"]["data"] = "PDFS";
$arrTextes["data"]["folder"] = "ORDNER IMPORTIEREN";
$arrTextes["data"]["imagesize"] = "(maximal media_IMG_MAX_Wpx-media_IMG_MAX_Hpx groß)";
$arrTextes["data"]["videotype"] = ".mp4, (OGG).ogv, (WEBM).webm<br />Dateiname bitte nur mit Buchstaben und Zahlen ohne Umlaute (z.B. webinare1).<br />F&uuml;r eine optimale Funktionalit&auml;t, das Video in die drei Formate (MP4, OGG und WEBM) hochladen (bitte gleichen Dateinamen nutzen).<br />Zum Konvertieren können Sie eigene Programme oder folgende Onlinedienste nutzen: <a href='http://video.online-convert.com/convert-to-mp4' target='_blank' style='text-decoration:underline'>MP4 </a> - <a href='http://video.online-convert.com/convert-to-ogg' target='_blank' style='text-decoration:underline'>OGG </a> - <a href='http://video.online-convert.com/convert-to-webm' target='_blank' style='text-decoration:underline'>WEBM</a>";
$arrTextes["data"]["caption"] = "Beschriftung nicht eingefügt. Text hier schreiben und Beschriftung-Taste drücken";
$arrTextes["data"]["videopreview"] = "VIDEO ANSCHAUEN";
$arrTextes["data"]["doformfirst"] = "FORMULAR SPEICHERN VOR DEM UPLOAD ODER L&Ouml;SCHEN VON FOTOS, VIDEOS ODER DOCS";
$arrTextes["data"]["filesize"] = "Maximal ".ini_get("upload_max_filesize")."B Daten";
$arrTextes["data"]["nofolder"] = "kein Ordner zum importieren";
$arrTextes["data"]["choosefolder"] = "Ordner ausw&auml;hlen";
$arrTextes["data"]["fld_nodelete"] = "IMPORT ORNDER K&Ouml;NNTE NICHT GEL&Ouml;SCHT WERDEN.";
$arrTextes["data"]["fld_nofiles"] = "DER ORDNER ENTH&Auml;LT KEINE BILDER OR SIE BEFINDEN SICH IN EINEM UNTERORDNER";
$arrTextes["data"]["pos_update"] = "DIE REIHENFOLGE W&Uuml;RDE VER&Auml;NDERT";
$arrTextes["data"]["pos_no_update"] = "FEHLER BEIM VER&Auml;NDERN DER REIHENFOLGE";
/* lists actions */
$arrTextes["aktions"]["action"] = "AKTIONEN";
$arrTextes["aktions"]["edit"] = "Daten von (#name) bearbeiten";
$arrTextes["aktions"]["track"] = "(#name) Tracking ansehen";
$arrTextes["aktions"]["dodelete"] = "(#name) löschen";
$arrTextes["aktions"]["dodeaktiv"] = "(#name) deaktivieren";
$arrTextes["aktions"]["doaktiv"] = "(#name) aktivieren";
$arrTextes["aktions"]["docaption"] = "Captiontext einfügen";
$arrTextes["aktions"]["dolink"] = "Link einfügen";
$arrTextes["aktions"]["doprotekt"] = "Zugriff mit user.id und password";
$arrTextes["aktions"]["doopen"] = "Status als Hauptseite festlegen";
$arrTextes["aktions"]["dopages"] = "Unterseite vom (#name) einfügen";
$arrTextes["aktions"]["doarchiv"] = "(#name) archivieren";
$arrTextes["aktions"]["dodearchiv"] = "(#name) aus dem Archiv entfernen?";
/* lists actions _ javascript */
$arrTextes["aktions"]["deaktiv"] = "(\"+d[2]+\") wirklich deaktivieren?";
$arrTextes["aktions"]["aktiv"] = "(\"+d[2]+\") aktivieren?";
$arrTextes["aktions"]["delete"] = "(\"+d[2]+\") wirklich löschen?<br />Alle Daten werden gelöscht!";
$arrTextes["aktions"]["pagedelete"] = "(\"+d[2]+\") wirklich löschen?<br />Alle Daten und Unterseiten werden gelöscht!";
$arrTextes["aktions"]["yes"] = "JA";
$arrTextes["aktions"]["no"] = "NEIN";
$arrTextes["aktions"]["close"] = "SCHLIESSEN";
$arrTextes["aktions"]["caption"] = "Captiontext ('\"+caption+\"') einfügen";
$arrTextes["aktions"]["protekt"] = "Zugriff mit user.id und password";
$arrTextes["aktions"]["deprotekt"] = "Zugriff für alle";
$arrTextes["aktions"]["open"] = "Als Hauptseite f&uuml;r dieses Untermen&uuml; ausw&auml;hlen";
$arrTextes["aktions"]["deopen"] = "Als Hauptseite f&uuml;r dieses Untermen&uuml; aufheben";
$arrTextes["aktions"]["archiv"] = "(\"+d[2]+\") archivieren?";
$arrTextes["aktions"]["dearchiv"] = "(\"+d[2]+\") aus dem Archiv entfernen?";
$arrTextes["aktions"]["showbig"] = "(#name) anzeigen";
/* tracking messages */
$arrTextes["tracking"]["from"] = "Von";
$arrTextes["tracking"]["to"] = "Bis";
$arrTextes["tracking"]["filter"] = "FILTERN";
$arrTextes["tracking"]["delfilter"] = "FILTERN ENTFERNEN";
$arrTextes["tracking"]["filterresult"] = "Ergebnisse";
$arrTextes["tracking"]["newstatus"] = "status verändert";
$arrTextes["tracking"]["delete"] = "gelöscht";
$arrTextes["tracking"]["insert"] = "Eingetragen";
$arrTextes["tracking"]["modify"] = "Daten verändert";
$arrTextes["tracking"]["archiv"] = "archiviert";
/* calendar */
$arrTextes["calendar"]["view"] = "ANSICHT:";
$arrTextes["calendar"]["monthview"] = " MONAT";
$arrTextes["calendar"]["yearview"] = " JAHR";
$arrTextes["calendar"]["title"] = "Verwaltung Kalender";
$arrTextes["calendar"]["cal"] = "KALENDER: ";
$arrTextes["calendar"]["aktuell"] = "AKTUELLES: ";
$arrTextes["calendar"]["veranstaltungen"] = "VERANSTALTUNG: ";
$arrTextes["calendar"]["presse"] = "PRESSE: ";
$arrTextes["calendar"]["auszeichnungen-stipendien"] = "Auszeichnungen & stipendien: ";
$arrTextes["calendar"]["read"] = "weiter lesen >>";
?>