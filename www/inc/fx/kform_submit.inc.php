<?php
if(!isset($cmslan))
{
	session_start();
	include_once("header.inc.php");
}
if(isset($_REQUEST["k_tomail"]))
{
	sleep(2);
	$doSND = true;
	$strMAIL = "Contact from GRT website\n\n";
    if(isset($_POST["k_informativa"])&&$_POST["k_informativa"])
    {
       foreach($_POST as $key=>$value) 
	   {
           if($key!="k_send"&&$key!="l"&&$key!="k_tomail")
           {
               if(($key=="k_name"||$key=="k_surname"||$key=="k_mail"||$key=="k_fon"))
                   if($value=="")
                   {
                       $strMSG = $arrTextes["forms"]["allfields"];
                       $doSND = false;
                       break;
                   }
               if($key=="k_mail"&&!checkmail($value))
               {
                   $strMSG = $arrTextes["forms"]["errmail"];
                   $doSND = false;
                   break;
               }
               $strMAIL .= "".strtoupper(str_replace("k_","",$key)).": $value\n";
           }
       }
        if($doSND)
        {
            $header = "From: ".WEB_TITLE." <".$_POST["k_tomail"].">\n";
            $header .= "X-Mailer: PHP/" . phpversion() . "\n";
            $header .= "X-Priority: 3\n";
            if(!mail($_POST["k_tomail"], "".WEB_TITLE." - Kontaktformular/Anfrage", $strMAIL, $header))
                $strMSG = $arrTextes["forms"]["sent_ko"];
            else
                $strMSG = $arrTextes["forms"]["sent_ok"];
        }
    }
    else
        $strMSG = $arrTextes["forms"]["informativa_ko"];
}
?>