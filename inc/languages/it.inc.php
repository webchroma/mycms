<?php
/* set Locale on page */
$loc_var = setlocale (LC_ALL, "it_IT@euro", "it_IT", "it", "it_IT@euro.UTF8", "it_IT.UTF8", "it.UTF8","ita","italy");
/* set names for languages */
$strLanName="ITALIANO";

/* TEXT ON FRONT AND BACKEND */
// title of the website.
define("WEBTITLE","Gruppo per le Relazioni Transculturali");
$arrTextes["front"]["update"] = "update";
/* preferences */
$arrTextes["preference"]["title"] = "Preferences";
/* log-on */
$arrTextes["login"]["formuser"] = "user.id";
$arrTextes["login"]["formpassword"] = "password";
$arrTextes["login"]["formenter"] = "enter";
$arrTextes["login"]["empty"] = "please insert user.id and password";
$arrTextes["login"]["alpha"] = "only alphanumerical charachters";
$arrTextes["login"]["nouser"] = "the user doesn't exists or is not activ";
$arrTextes["login"]["isuser"] = "user already present in the database";
$arrTextes["login"]["dologinmsg"] = "Please log-in to download the files";
$arrTextes["login"]["doingloginmsg"] = "The data is being checked";
// First, create an array of month names, January through December 
$arrMonths = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); 
// Then an array of day names, starting with Sunday 
$arrDays = array("S", "M", "T", "W", "T", "F", "S");
/* user forms */
$arrTextes["forms"]["info"] = "(* - campo obbligatorio)";
$arrTextes["forms"]["formname"] = "Nome";
$arrTextes["forms"]["formsurname"] = "Cognome";
$arrTextes["forms"]["formakt"] = "Attivo";
$arrTextes["forms"]["title"] = "Titolo";
$arrTextes["forms"]["txt"] = "Testo";
$arrTextes["forms"]["short-txt"] = "Testo corto";
$arrTextes["forms"]["target"] = "Beneficiari";
$arrTextes["forms"]["result"] = "Risultati";
$arrTextes["forms"]["fund"] = "Fondi";
$arrTextes["forms"]["firm"] = "Compagnia";
$arrTextes["forms"]["mail"] = "E-Mail";
$arrTextes["forms"]["phone"] = "Telefono";
$arrTextes["forms"]["fax"] = "Fax";
$arrTextes["forms"]["msg"] = "Messaggio";
$arrTextes["forms"]["submit"] = "Invia";
$arrTextes["forms"]["sent_ok"] = "Grazie! Messaggio inviato";
$arrTextes["forms"]["sent_ko"] = "C'&egrave; stato un errore, rirpovare";
$arrTextes["forms"]["sending"] = "Spedendo il messaggio ...";
$arrTextes["forms"]["informativa"] = "<strong>Informativa privacy</strong><br />I dati contenuti in questo modulo di richiesta informazioni verranno trattati in conformit&agrave; alla legge 675 (legge privacy).";
$arrTextes["forms"]["informativa_ok"] = "Ho preso visione dell'Informativa";
$arrTextes["forms"]["informativa_ko"] = "Informativa non accettata";
/* forms _ messages and buttons */
$arrTextes["forms"]["insert"] = "Insert";
$arrTextes["forms"]["modify"] = "Modify";
$arrTextes["forms"]["titlemessage"] = "Fill the form and press the button at the end";
$arrTextes["forms"]["isuser"] = "User.id already given";
$arrTextes["forms"]["zone"] = "Please choose an access zone for the user";
$arrTextes["forms"]["allfields"] = "Riempire tutti i campi";
$arrTextes["forms"]["ismodified"] = "Data has been changed";
$arrTextes["forms"]["notextdef"] = "No text in this language available";
$arrTextes["forms"]["errmail"] = "Mail inserita non corretta o inesistente";
$arrTextes["forms"]["meta_info_title"] = "Meta Informationen für die Seite.";
$arrTextes["forms"]["meta_info_title_extra"] = "Falls leer, werden Titel, Keywords und Description aus dem Einstellung-Panel benutzt";
/* messages */
$arrTextes["messages"]["confirm"] = "Please confirm";
$arrTextes["messages"]["nonws"] = "No news ist selected for the startpage";
$arrTextes["messages"]["noaccess"] = "You don't have access to any section. Please contact the administrator";
$arrTextes["messages"]["nokeylink"] = "NO LINK LINKED";
$arrTextes["messages"]["nokeyexists"] = "NO LINK IS PRESENT IN THE DATABASE";
$arrTextes["errors"]["general"] = "An error as occurred. please contact the site administrator";
$arrTextes["errors"]["generalshort"] = "An error as occurred.";;
$arrTextes["errors"]["nopage"] = "Page was not found";
$arrTextes["errors"]["insert"] = "Error while inserting data. Please try again";
$arrTextes["errors"]["nodata"] = "No records available";
$arrTextes["errors"]["nomedia"] = "No media available";
$arrTextes["errors"]["imagesize"] = "Image too big. Max (media_IMG_MAX_Wpx-media_IMG_MAX_Hpx)";
$arrTextes["errors"]["filesize"] = "File too heavy. Max ".ini_get("upload_max_filesize")."B";
$arrTextes["errors"]["wrongpara"] = "Wrond parameter were sent. Try againg";
$arrTextes["errors"]["insert_folder"] = "Error while creating a folder for the Client";
/* front end */
$arrTextes["page"]["impressum"] = "Disclaimer";
$arrTextes["page"]["protect"] = "This page is not accessible";
$arrTextes["nwsletter"]["title"] = "Subscribe the HZT Berlin newsletter";
$arrTextes["nwsletter"]["submit"] = "subscribe";
$arrTextes["nwsletter"]["nomail"] = "The E-Mail is false or doesn't exist";
$arrTextes["nwsletter"]["done"] = "Done.<br />You will receive a confirmation mail in Your mail account.";
$arrTextes["nwsletter"]["archiv"] = "ARCHIVE";
$arrTextes["search"]["title"] = "SEARCH THE HZT WEBSITE";
$arrTextes["search"]["btn"] = "SEARCH";
$arrTextes["search"]["minsign"] = "at least 4 letters";
$arrTextes["search"]["result"] = "Search results for:";
$arrTextes["search"]["noresult"] = "No search results for:";
$arrTextes["page"]["werkzeug"] = "Tools for:";
/* gallery */
$arrTextes["gallery"]["flickr"] = "Photos hosted on Flickr";
$arrTextes["gallery"]["youtube"] = "Videos hosted on YouTube";

/*ONLY BACKEND*/
/*general*/
$arrTextes["admin"]["new"] = "NEW";
$arrTextes["admin"]["admin"] = "LIST";
$arrTextes["admin"]["changeorder"] = "CHANGE ORDER";
/* start _ browser check messages */
$arrTextes["browser"]["info"] = "To be able to use the CMS completely, please refer to these requirements";
$arrTextes["browser"]["active"] = "ACTIVE";
$arrTextes["browser"]["notactive"] = "NOT ACTIVE";
$arrTextes["browser"]["installed"] = "INSTALLED";
$arrTextes["browser"]["notinstalled"] = "NOT INSTALLED";
$arrTextes["browser"]["old"] = "THE INSTALLED VERSION IS TOO OLD (\"+playerVersion.major+\"). PLEASE UPDATE IT";
/* help window _ icons */
$arrTextes["help"]["edit"] = "Edit";
$arrTextes["help"]["deaktiv"] = "Not active -> Click to activate";
$arrTextes["help"]["aktiv"] = "Active -> Click to deactivate";
$arrTextes["help"]["track"] = "Tracking";
$arrTextes["help"]["delete"] = "Delete";
$arrTextes["help"]["isdea"] = "Not active";
$arrTextes["help"]["isakt"] = "Choosen/Aktual user";
$arrTextes["help"]["page"] = "Create a new page in this section";
$arrTextes["help"]["protekt"] = "Page is open. Click to protect this page with user.id and password";
$arrTextes["help"]["deprotekt"] = "Page is protected";
$arrTextes["help"]["home"] = "The page will be opened as the first for the section";
$arrTextes["help"]["dohome"] = "Set this page as default page for the section";
$arrTextes["help"]["caption"] = "Caption for image or video";
$arrTextes["help"]["link"] = "External link for image or video";
$arrTextes["help"]["multi"] = "Multiple option";
$arrTextes["help"]["mail"] = "<strong>Link to an Email in a text</strong><br />Write the E-Mail after <em>mailto:</em><br />";
$arrTextes["help"]["href"] = "<strong>Link to an external website</strong><br />Write the link after <em>http://</em><br />";
/* users */
$arrTextes["users"]["title"] = "User administration";
$arrTextes["users"]["new"] = "NEW USER";
$arrTextes["users"]["zone"] = "ACCESS TO";
$arrTextes["users"]["folder"] = "USER FOLDER";
$arrTextes["users"]["nodata"] = "There are no files present";
/* news */
$arrTextes["news"]["title"] = "News administration";
$arrTextes["news"]["new"] = "NEW NEWS";
$arrTextes["news"]["user"] = "CREATED FROM:";
$arrTextes["news"]["datum"] = "DATE";
$arrTextes["news"]["lan"] = "LANGUAGES";
$arrTextes["news"]["nolantit"] = "No news in default language (#LAN)";
$arrTextes["news"]["update"] = "Update the news date.";
/* gallery */
$arrTextes["gallery"]["name"] = "Choose a gallery: ";
$arrTextes["gallery"]["title"] = "Gallery administration";
$arrTextes["gallery"]["new"] = "New gallery";
$arrTextes["gallery"]["totalmedia"] = "Total media";
$arrTextes["gallery"]["allmedia"] = "All Media";
$arrTextes["gallery"]["doseiten"] = "Choose this image";
$arrTextes["gallery"]["delseiten"] = "Don't use this image";
$arrTextes["gallery"]["seiten"] = "Change image status";
$arrTextes["gallery"]["thumb_name"] = "Gallerie Thumbnail";
$arrTextes["gallery"]["thumb"] = "#name as Thumbnail f&uuml;r diese Gallerie w&auml;hlen";
$arrTextes["gallery"]["nothumb"] = "Das Thumbnail f&uuml;r diese Gallerie w&uuml;rde nicht ausgew&auml;hlt. Das erste Bild wird benutzt";
/* pages */
$arrTextes["pages"]["title"] = "Pages administration";
$arrTextes["pages"]["new"] = "New Page";
$arrTextes["pages"]["subpages"] = "Total subpages";
$arrTextes["pages"]["position"] = "Change page order";
$arrTextes["pages"]["symlink"] = "Identification name (Symlink)";
$arrTextes["pages"]["symlink_txt"] = "A unique identification name for the page. Max 50 characters, no spaces, special characters or numbers. Ex. for Contact/Disclaimer -> contact";
$arrTextes["pages"]["ongoing"] = "corrente";
$arrTextes["pages"]["ongoing_txt"] = "Spuntare la casella nel caso il progetto sia ancora attivo";
/* preferences */
$arrTextes["prefs"]["web_stop"] = "STOP THE WEBSITE";
$arrTextes["prefs"]["web_stop_txt"] = "(text can be found in PAGES - STOP)";
$arrTextes["prefs"]["web_title"] = "TITLE OF THE WEBSITE";
$arrTextes["prefs"]["meta"] = "META TAGS (SEARCH ENGINES)";
$arrTextes["prefs"]["meta_copy"] = "COPYRIGHT INFO IN THE META-TAGS";
$arrTextes["prefs"]["meta_mail"] = "INFO E-MAIL IN THE META-TAGS";
$arrTextes["prefs"]["meta_mail_exp"] = "Das Mail wird auch für das Formular benutzt";
$arrTextes["prefs"]["meta_key-des"] = "DEFAULT KEYWORDS AND DESCRIPTION (used on all pages if not otherwise setted)";
$arrTextes["prefs"]["meta_key"] = "KEYWORDS";
$arrTextes["prefs"]["meta_des"] = "DESCRIPTION";
$arrTextes["prefs"]["meta_explanation"] = "<strong>META TAGS</strong><br />Die Meta Informationen sind wichtige bestandteile des Suchmachine Optimierung.<br />Am bestens werden für jeder Seite getrennte Titel, Description und Keywords angegeben, die die wichtigen Informationen auf der Seite wiedergeben. F&uuml;r alle gilt: maximal 255 Zeichen";
$arrTextes["prefs"]["meta_explanation_title"] = "<strong>TITLE DER SEITE:</strong><br /> Der Titel der Seite muss die wichtigsten Keywords behalten, die auf der Seite zu finden ist. Erscheint oben auf der Browserleiste";
$arrTextes["prefs"]["meta_explanation_keys"] = "<strong>KEYWORDS:</strong><br /> Schl&uuml;sselw&ouml;rter, mit Komma getrennt.";
$arrTextes["prefs"]["meta_explanation_des"] = "<strong>DESCRIPTION:</strong><br />Eine kurze Beschreibung der Seite.";
$arrTextes["prefs"]["lans"] = "LANGUAGES";
$arrTextes["prefs"]["newlan"] = "new language";
$arrTextes["prefs"]["landes"] = "ID (2 characters) | Name | Standard text (255 characters)";
$arrTextes["prefs"]["chs_gal"] = "choose gallery";
$arrTextes["prefs"]["chsen_gal"] = "choosen gallery";
$arrTextes["prefs"]["chs_gal_link"] = "LINK TO GALLERY?";
$arrTextes["prefs"]["start"] = "HOMEPAGE";
$arrTextes["prefs"]["start"] = "STARTSEITE";
$arrTextes["prefs"]["menu"] = "NAVIGATION";
$arrTextes["prefs"]["menu_home_logo"] = "LOGO OR TITLE AS LINK TO THE HOMEPAGE";
$arrTextes["prefs"]["menu_home_fix"] = "ALWAYS SHOW LINK TO HOMEPAGE IN MAIN NAVIGATION-MENU";
$arrTextes["prefs"]["menu_home_fix_txt"] = "Should the link to the homepage be always present or just on subpages?";
$arrTextes["prefs"]["menu_home"] = "SHOW LINK TO HOMEPAGE IN MAIN NAVIGATION-MENU";
$arrTextes["prefs"]["menu_home_txt"] = "Should the link to the homepage be shown in the main navigation menu?";
$arrTextes["prefs"]["menu_portfolio"] = "PORTFOLIO GALLERY'S IN MAIN NAVIGATION-MENU";
$arrTextes["prefs"]["menu_portfolio_txt"] = "Should the portfolio gallerys be shown in the main navigation menu? If not, the portfolio page link will be shown";
$arrTextes["prefs"]["start_from_pages"] = "HOMEPAGE FROM PAGES";
$arrTextes["prefs"]["start_rnd_media"] = "RANDOM PICTURE FROM THE MEDIA ARCHIVE";
$arrTextes["prefs"]["start_rnd_gall"] = "RANDOM PICTURE FROM A PORTOFOLIO GALLERY";
$arrTextes["prefs"]["gall_tot_media"] = "Total fotos";
$arrTextes["prefs"]["gall_no_media"] = "The gallery has no foto";
$arrTextes["prefs"]["no_gal"] = "no gallery";
$arrTextes["prefs"]["portfolio"] = "PORTFOLIO";
$arrTextes["prefs"]["portfolio_thmbs"] = "SHOW GALLERY'S PREVIEWS";
$arrTextes["prefs"]["portfolio_thmbs_txt"] = "If not, the portfolio page aus PAGES will be shown";
$arrTextes["prefs"]["media"] = "GALLERY - MEDIA";
$arrTextes["prefs"]["h"]="HEIGHT";
$arrTextes["prefs"]["w"]="WIDTH";
$arrTextes["prefs"]["thb_ref"]="THUMBNAIL REFERENCE";
$arrTextes["prefs"]["resize"]="RESIZE IMAGES";
$arrTextes["prefs"]["resize_txt"] = "if not, an error will be given if the fotos oversize the given sizes";
/* data upload _ gallery and javascript */
$arrTextes["media"]["title"] = "Media administration (photo-video)";
$arrTextes["media"]["title_data"] = "Media administration (documents)";
$arrTextes["media"]["docs"] = "documents";
$arrTextes["media"]["no_portfolio"] = "Media not in portfolio galleries";
$arrTextes["data"]["search"] = "SEARCH";
$arrTextes["data"]["format"] = "Only this datatype";
$arrTextes["data"]["upload"] = "UPLOAD";
$arrTextes["data"]["img"] = "IMAGE";
$arrTextes["data"]["vid"] = "VIDEO";
$arrTextes["data"]["data"] = "DOCS";
$arrTextes["data"]["folder"] = "IMPORT FOLDER";
$arrTextes["data"]["imagesize"] = "(image maximal media_IMG_MAX_Wpx-media_IMG_MAX_Hpx big)";
$arrTextes["data"]["videotype"] = "Only: .avi, .mov, .flv";
$arrTextes["data"]["caption"] = "No Caption present. Write here a text and click on caption button";
$arrTextes["data"]["videopreview"] = "PREVIEW";
$arrTextes["data"]["doformfirst"] = "CONFIRM INSERTION OF THE DATA OF THE FORMULARE PRIOR TO INSERT OR DELETE AN IMAGE OR VIDEO";
$arrTextes["data"]["filesize"] = "Max ".ini_get("upload_max_filesize")."B file size";
$arrTextes["data"]["nofolder"] = "No folder to import";
$arrTextes["data"]["choosefolder"] = "Choose a folder";
$arrTextes["data"]["fld_nodelete"] = "COULD NOT DELETE THE FOTO FOLDER";
$arrTextes["data"]["fld_nofiles"] = "THE FOLDER IS EMPTY OR THE FOTO ARE IN A SUBFOLDER";
$arrTextes["data"]["pos_update"] = "THE SEQUENCE WAS CHANGED";
$arrTextes["data"]["pos_no_update"] = "ERROR WHILE CHANGING THE SEQUENCE";
/* lists actions */
$arrTextes["aktions"]["action"] = "ACTIONS";
$arrTextes["aktions"]["edit"] = "Edit (#name)";
$arrTextes["aktions"]["track"] = "See the tracking of (#name)";
$arrTextes["aktions"]["dodelete"] = "Delete (#name)";
$arrTextes["aktions"]["dodeaktiv"] = "Deactivate (#name)";
$arrTextes["aktions"]["doaktiv"] = "Activate (#name)";
$arrTextes["aktions"]["docaption"] = "Insert caption";
$arrTextes["aktions"]["dolink"] = "Insert a link";
$arrTextes["aktions"]["doprotekt"] = "Access with user.id and password";
$arrTextes["aktions"]["doopen"] = "Open as default page for this section";
$arrTextes["aktions"]["dopages"] = "Insert subpage of (#name)";
$arrTextes["aktions"]["doarchiv"] = "Archive (#name)";
$arrTextes["aktions"]["high"] = "mark as highlight";
$arrTextes["aktions"]["nohigh"] = "remove from highlight";
/* lists actions _ javascript */
$arrTextes["aktions"]["deaktiv"] = "Deactivate (\"+d[2]+\")?";
$arrTextes["aktions"]["aktiv"] = "Attivare (\"+d[2]+\")?";
$arrTextes["aktions"]["delete"] = "Elimina (\"+d[2]+\")?<br />All infos will be deleted!";
$arrTextes["aktions"]["pagedelete"] = "Elimina (\"+d[2]+\")?<br />All infos and subpages will be deleted!";
$arrTextes["aktions"]["yes"] = "YES";
$arrTextes["aktions"]["no"] = "NO";
$arrTextes["aktions"]["close"] = "CLOSE";
$arrTextes["aktions"]["caption"] = "Insert caption ('\"+caption+\"')?";
$arrTextes["aktions"]["protekt"] = "Access with user.id and password";
$arrTextes["aktions"]["deprotekt"] = "Access for everyone";
$arrTextes["aktions"]["open"] = "Open as default page for this section";
$arrTextes["aktions"]["deopen"] = "Open as normal page<br />When entering this section, the text inserted in the main page will be displayed";
$arrTextes["aktions"]["archiv"] = "Archive (\"+d[2]+\")?";
$arrTextes["aktions"]["dearchiv"] = "(\"+d[2]+\") aus dem Archiv entfernen?";
$arrTextes["aktions"]["showbig"] = "Show (#name)";
/* tracking messages */
$arrTextes["tracking"]["from"] = "From";
$arrTextes["tracking"]["to"] = "To";
$arrTextes["tracking"]["filter"] = "FILTER";
$arrTextes["tracking"]["delfilter"] = "DELETE FILTER";
$arrTextes["tracking"]["filterresult"] = "Records";
$arrTextes["tracking"]["newstatus"] = "status modified";
$arrTextes["tracking"]["delete"] = "deleted";
$arrTextes["tracking"]["insert"] = "Inserted";
$arrTextes["tracking"]["modify"] = "Information has been changed";
$arrTextes["tracking"]["archiv"] = "archived";
/* calendar */
$arrTextes["calendar"]["view"] = "SHOW:";
$arrTextes["calendar"]["monthview"] = " MNTH";
$arrTextes["calendar"]["yearview"] = " YEAR";
$arrTextes["calendar"]["title"] = "Calender management";
$arrTextes["calendar"]["cal"] = "CALENDAR: ";
$arrTextes["calendar"]["aktuell"] = "NEWS: ";
$arrTextes["calendar"]["veranstaltungen"] = "EVENT: ";
$arrTextes["calendar"]["presse"] = "PRESS: ";
$arrTextes["calendar"]["auszeichnungen-stipendien"] = "Auszeichnungen & stipendien: ";
$arrTextes["calendar"]["read"] = "read the article >>";
/* pages */
$arrTextes["GRT"]["NEWS"] = "NEWS";
$arrTextes["GRT"]["morenews"] = "CONTINUA ...";
$arrTextes["GRT"]["highlights"] = "HIGHLIGHTS";
$arrTextes["GRT"]["menu_title_land"] = "PAESI";
$arrTextes["GRT"]["menu_title_theme"] = "TEMI";
?>