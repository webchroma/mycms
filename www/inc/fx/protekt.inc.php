<?php
//session_destroy();
if(isset($_POST["dolog"]))
{
	if($_POST["usr"]!="" && $_POST["pwd"]!="")
	{
		include_once("fx.inc.php");
		if(AlfaNum($_POST["usr"]) && AlfaNum($_POST["pwd"]))
		{
			include_once("conf.inc.php");
			include_once("mysql.class.inc.php");
			$objConn = MySQL::getIstance();
			$objConn->prepMysql($_POST["usr"]);
			$objConn->prepMysql($_POST["pwd"]);
			$strSQL = "SELECT intern.id_USR, CONCAT(intern.nname,' ',intern.vname) AS name, internfld.fold 
						FROM ".PREFIX."_intern AS intern LEFT JOIN ".PREFIX."_intern_folder AS internfld ON intern.id_USR=internfld.id_USR
						WHERE intern.usr='".$_POST["usr"]."' AND intern.pwd='".$_POST["pwd"]."' AND intern.aktiv";
			try
			{
				$rs = $objConn->rs_query($strSQL);	
				if ($rs->count() > 0)
				{
					foreach ($rs as $user)
					{
						if($rs->key()==0)
						{
							$_SESSION["intern"]["id_USR"] = $user->id_USR;
							$_SESSION["intern"]["name"] = $user->name;
							$_SESSION["intern"]["folder"] = $user->fold;
						}
					}
					include_once("inc/tracking.inc.php");
					trackUSR($_SESSION["intern"]["id_USR"],"LOGIN");
				}
				else
				{
					$strMSG = $arrTextes["login"]["nouser"];
				}
			}
			
			catch(Exception $e)
			{
				$strMSG = captcha($e);
			}
		}
		else
		{
			$strMSG = $arrTextes["login"]["alpha"];
		}
	}
	else
	{
		$strMSG = $arrTextes["login"]["empty"];
	}
}
if(isset($_SESSION["intern"]))
{
	echo "<h3>".strtoupper($_SESSION["intern"]["name"])."</h3><br />";
	// get page text
	$strSQL = "SELECT db.protekt,pagtxt.texto, med.tipo, med.url, med.link,
				(SELECT COUNT(id_MED) FROM ".PREFIX."_pages_media WHERE $strWHP) AS tmed,
				(SELECT name AS caption FROM ".PREFIX."_media_caption WHERE id_MED=med.id_MED AND lan='".$cmslan."') AS caption
				FROM ".PREFIX."_pages AS db JOIN ".PREFIX."_pages_text AS pagtxt ON db.id_PAG=pagtxt.id_PAG
				LEFT JOIN ".PREFIX."_pages_media AS pagmed ON db.id_PAG=pagmed.id_PAG
				LEFT JOIN ".PREFIX."_media AS med ON pagmed.id_MED=med.id_MED
				WHERE $strWHP AND pagtxt.lan='$cmslan' ORDER BY med.tipo DESC, caption ASC";
	$rs = $objConn->rs_query($strSQL);
	if ($rs->count() > 0)
	{
		foreach ($rs as $row)
		{
			echo $row->texto;
		}
		// show files
		$usrfold = $_SESSION["intern"]["folder"];
		$dr = CLIENT_DATA."/$usrfold/";
		if (is_dir($dr))
		{
		    if ($dh = opendir($dr))
			{
				echo "<div id=\"client_files\"><ul>";
				
				while (($file = readdir($dh)) !== false)
				{
					
					if(filetype($dr.$file)=="file" && substr($file, 0, 1)!=".")
						$files[] = $file;
				}
		        closedir($dh);
				if(isset($files)&&is_array($files))
				{
					sort($files);
					$i=$n=1;
					foreach($files AS $file)
					{
						echo "<li><a href=\"".CLIENT_DATA_AS_URL."/$usrfold/$file\">$file</a></li>";
						$n++;
						$i++;
						if($n>7)
						{
							echo "<br />";
							$n=1;
						}
					}
				}
				else
					echo $arrTextes["users"]["nodata"];
				echo "</ul></div>";
		    }
		}
	}
}
else
{
	include_once("userform.inc.php");
}
?>