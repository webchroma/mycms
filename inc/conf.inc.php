<?php
###########
# GENERAL #
###########
if ($localMachine) // variable set on inc/header.inc.php | both in CMS (ADM) and FRONTEND (CMS)
	define("DOMAIN", "localhost");
else
	define("DOMAIN", "domain.com");

/* PREFERENCES */
define("SUBFOLDER", ""); // is the ADM Folder in subdomain or in main domain. Leave empty if subdomain
//
define("CLIENT_DOMAIN", ""); // for client data, set if you have a subdomain
define("DEFCHAR", "utf8"); // default charset
define("FLATLINK", true); // use flatlinks or not on the front-end
define("CMSLAN", "de"); // Language for the CMS -> if no other detected or inserted, will be used as main language for FRONTEND
define("SECUREAKTIV", false); // if set to true, it is possible to protect pages with user.id and password active
define("DOARCH", false); // if set to true, archive function for pages is activated
define("NOSUB", true); // if set to true, it is not possible to insert subpages -> otherwise maximal 2 Levels

/* if set to true, it is possible to insert as many subpages as you will | navigation function on frontend not yet implemented */
define("MORESUB", true);

/* do not change these if not sure */
define("BASEURL_SUFFIX", "");
define("BASEURL", "http://" . DOMAIN . BASEURL_SUFFIX);
define("HOMEBASE", $_SERVER["DOCUMENT_ROOT"]);
define("PAGENAME", str_replace("/", "", str_replace(BASEURL_SUFFIX . "/" . SUBFOLDER, "", $_SERVER['PHP_SELF'])));

define("FRONTENDCSS", BASEURL . "/css/screen.tiny.css"); // for tinymce -> which css style sheet to use, normally front-end style sheet
############
# DATABASE #
############
define("PREFIX", "mycms"); // DATABASE PREFIX - change if modified on DB
if ($localMachine) // variable set on inc/header.inc.php | both in CMS (ADM) and FRONTEND (CMS)
{
	define("MySERVER", "localhost");
	define("MyDB", "GRT");
	define("MyUSER", "root");
	define("MyPWD", "root");
} else {
	/*online test
	define ("MySERVER","mysql5.chiussi.de");
	define ("MyDB","db355307_19");
	define ("MyUSER","db355307_19");
	define ("MyPWD","Fjda:azwn(t4");
    
    /**/
	define("MySERVER", "localhost");
	define("MyDB", "*********");
	define("MyUSER", "*********");
	define("MyPWD", "*********");
}
####################
# FILES AND IMAGES #
####################
define("MAINPATH", INCPATH . "/www");
define("UPLPATH", INCPATH . "/upload");
define("CLIENT_DATA", MAINPATH . "/client_data");
define("CLIENT_DATA_AS_URL", BASEURL . "/client_data");

define("MEDIA", MAINPATH . "/media/");

define("IMAGES_AS_URL", BASEURL . "/media/images/"); // images base folder
define("IMG_THUMB_AS_URL", BASEURL . "/media/images/thumbs/"); // images thumbnail folder
define("IMG_FS_AS_URL", BASEURL . "/media/images/fs/"); // images fullscreen folder
define("IMG_PAGE_AS_URL", BASEURL . "/media/images/inpage/"); // images page format folder

define("VIDEOS_AS_URL", BASEURL . "/media/videos/");

define("DATA_AS_URL", BASEURL . "/media/data/");
define("DATA", MAINPATH . "/media/data/");

define("MEDIAPARAM", true); // media parameters are stored in database

define("CAPTIONS", true); // manage captions for media or not
define("LINKS", true); // manage links for media or not

define("SIZE_SMALLTHUMB_W", 150);
define("SIZE_SMALLTHUMB_H", 150);
define("SIZE_THUMB_W", 400);
define("SIZE_THUMB_H", 400);
define("SIZE_LARGE_W", 1100);
define("SIZE_LARGE_H", 800);
define("SIZE_HUGE_W", 1690);
define("SIZE_HUGE_H", 1125);

define("SMALLTHUMB", "" . SIZE_SMALLTHUMB_W . "x" . SIZE_SMALLTHUMB_H . "");
define("THUMB", "" . SIZE_THUMB_W . "x" . SIZE_THUMB_H . "");
define("LARGE", "" . SIZE_LARGE_W . "x" . SIZE_LARGE_H . "");
define("HUGE", "" . SIZE_HUGE_W . "x" . SIZE_HUGE_H . "");
######################
# FOREIGN KEYS CONFIG#
######################
$arrKeyConfig["admin"]["id"] = "id_USR";
$arrKeyConfig["admin"]["maintable"] = PREFIX . "_admin";
$arrKeyConfig["zona"]["id"] = "id_ZONA";
$arrKeyConfig["zona"]["maintable"] = PREFIX . "_admin_zone";
$arrKeyConfig["admin-zona"]["keytable"] = PREFIX . "_admin_usr_zone";
$bolKO = true;
