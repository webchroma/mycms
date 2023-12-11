<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_neu.php","",PAGENAME));
try{
	$objConn = MySQL::getIstance();
}
catch(Exception $e)
{
	$errMSG = captcha($e);
}
if(isset($_POST["submit"]))
{
	$doIns = true;
	$l = null;
	foreach($_POST as $key=>$value) 
	{
		if($key!="submit")
		{
			if($value=="")
			{
				$doIns = false;
				$errMSG = $arrTextes["forms"]["allfields"];
			}
			else
			{
				// get total languages
				$arrV=explode("_",$key);
				if($l!=$arrV[0])
				{
					$arrL[]=$arrV[0];
					$l=$arrV[0];
				}
			}
		}
	}
	
	if($doIns)
	{
		$strSQL = "INSERT INTO ".PREFIX."_news (id_USR, datum, aktiv) VALUES (".$_SESSION["usr"]["id_USR"].",CURRENT_TIMESTAMP,1)";
		try
		{
			$objConn->stopCOMMIT();

			if($objConn->i_query($strSQL))
			{
				$nwsID = $objConn->getInsertID();
				
				foreach($arrL AS $value)
				{
					$strSQL = "INSERT INTO ".PREFIX."_news_text (id_NWS,lan,name,texto) 
							   VALUES ($nwsID,'$value','".$objConn->prepMysql($_POST[$value."_name"])."','".$objConn->prepMysql($_POST[$value."_txt"])."')";
					$objConn->i_query($strSQL);
				}				
				trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST[$cmslan."_name"].") ".$arrTextes["tracking"]["insert"]."");
				$objConn->doCOMMIT(true);
				header("location:".THISMAINPAGENAME.".php");
			}
			else
			{
				$objConn->doCOMMIT(false);
				$errMSG = $arrTextes["errors"]["insert"];
			}
		}
		catch(Exception $e)
		{
			$objConn->doCOMMIT(false);
			$errMSG = captcha($e);
		}
	}
}
include_once("inc/page-head.inc.php");
?>
<body>
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php"><?=$arrTextes["news"]["title"]?></a> / <?=strtolower($arrTextes["news"]["new"])?></div>
	<div id="mainpage">
		<div id="errBox"<?php if(isset($errMSG)) {echo " class=\"errLabel\">".$errMSG.""; }else{ echo ">";}?></div>
		<div class="form_two_columns">
			<h1><?=$arrTextes["forms"]["titlemessage"]?></h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="nnews" id="nnews">
            <div id="accordion">
                <?php
				$strJSValidation = null;
				$strSQL = "SELECT lan.name AS lanname, lan.lan, lan.notext FROM ".PREFIX."_languages AS lan";
				$rs = $objConn->rs_query($strSQL);
				if ($rs->count() > 0)
				{
					while($row = $rs->fetchObject())
					{
						$strJSValidation .= "".$row->lan."_name: \"required\",";
						$strJSValidation .= "".$row->lan."_txt: \"required\",";
						echo "<h3>".$row->lanname."</h3>";
				        echo "<div>";
				        echo "<ul class=\"form\">";
						echo "<li>".$arrTextes["forms"]["title"]."<br />";
						isset($_POST["".$row->lan."_name"]) ? $val = $_POST["".$row->lan."_name"] : $val = $row->notext;
						echo "<input name=\"".$row->lan."_name\" type=\"text\" id=\"".$row->lan."_name\" size=\"50\" maxlength=\"255\" value=\"$val\" />";
						echo "</li>";
						echo "<li>".$arrTextes["forms"]["txt"]."<br />";
						isset($_POST["".$row->lan."_txt"]) ? $val = $_POST["".$row->lan."_txt"] : $val = $row->notext;
						echo "<textarea name=\"".$row->lan."_txt\" cols=\"47\" class=\"mceEditor\" rows=\"25\" id=\"".$row->lan."_txt\">$val</textarea>";
						echo "</li>";
						echo "</ul>";
						echo "</div>";
					}
					$strJSValidation = substr_replace($strJSValidation ,"",-1);
				}
				else
				{
					$strJSValidation .= "".$cmslan."_name: \"required\",";
					$strJSValidation .= "".$cmslan."_txt: \"required\"";
					echo "<h3>".$_SESSION["lannames"][$l]."</h3>";
				    echo "<div>";
				    echo "<ul class=\"form\">";
					echo "<li>".$arrTextes["forms"]["title"]."<br />";
					isset($_POST["".$cmslan."_name"]) ? $val = $_POST["".$cmslan."_name"] : $val = "";
					echo "<input name=\"".$cmslan."_name\" type=\"text\" id=\"".$cmslan."_name\" size=\"50\" maxlength=\"255\" value=\"$val\" />";
					echo "</li>";
					echo "<li>".$arrTextes["forms"]["txt"]."<br />";
					isset($_POST["".$cmslan."_txt"]) ? $val = $_POST["".$cmslan."_txt"] : $val = $arrTextes["forms"]["notextdef"];
					echo "<textarea name=\"".$cmslan."_txt\" cols=\"47\" rows=\"25\" class=\"mceEditor\" id=\"".$cmslan."_txt\">$val</textarea>";
					echo "</li>";
					echo "</ul>";
					echo "</div>";
				}
				?>
                </div>
				<ul class="form">
                    <li class="centerbold noborder">
						<input type="submit" class="ui-button ui-widget subbtn" name="submit" id="submit" value="<?=$arrTextes["forms"]["insert"]?>" />
					</li>
				</ul>
			</form>
		</div>
	</div>
</body>
</html>