<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_edit.php","",PAGENAME));
try
{
	$objConn = MySQL::getIstance();
}
catch(Exception $e)
{
	$errMSG = captcha($e);
}
if(isset($_POST["Modifizieren"]) && isset($_POST["id_USR"]))
{
	$objConn = MySQL::getIstance();
	$doIns = true;
	foreach($_POST as $key=>$value) 
	{
		if($value=="")
		{
			$doIns = false;
			$errMSG = $arrTextes["forms"]["allfields"];
		}
		else
		{
			if($key!="ref")
			{
				$_POST[$key] = $objConn->prepMysql($_POST[$key]);
			}
		}
	}
	
	if(!isset($_POST["ref"]))
	{
		$doIns = false;
		$errMSG = $arrTextes["forms"]["zone"];
	}
	
	if($doIns)
	{
		if(AlfaNum($_POST["pwd"]))
		{
			settype($_POST["id_USR"],"INT");
			
			$strSQL = "UPDATE ".PREFIX."_admin SET pwd='".$_POST["pwd"]."',vname='".$_POST["vname"]."',nname='".$_POST["nname"]."' WHERE id_USR=".$_POST["id_USR"]."";
			$objConn->stopCOMMIT();
			try
			{
				if($objConn->i_query($strSQL))
				{
					// links to zone
					isset($_SESSION["oREF"]) ? $sessiondata = $_SESSION["oREF"] : $sessiondata=null;
					isset($_POST["ref"]) ? $postdata = $_POST["ref"] :  $postdata=null;
					$objConn->doForeignKeys($postdata,$sessiondata,$_POST["id_USR"],$objConn);
					unset($_SESSION["oREF"]);
					trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST["nname"]." ".$_POST["vname"].") ".$arrTextes["tracking"]["modify"]."");
					$errMSG = $arrTextes["forms"]["ismodified"];
					$bolKO = false;
				}
				else
					throw new Exception($errMSG);
			}

			catch(Exception $e)
			{
				$objConn->doCOMMIT(false);
				$errMSG = captcha($e);
			}
			$objConn->doCOMMIT(true);
		}
		else
		{
			$errMSG = $arrTextes["login"]["alpha"];
		}
	}
}
else
{	
	if(isset($_GET["id_USR"]))
	{
		unset($_SESSION["oREF"]);
		settype($_GET["id_USR"],"INT");
		$strSQL = "SELECT adm.id_USR, adm.aktiv, adm.usr, adm.pwd, adm.nname, adm.vname
				   FROM ".PREFIX."_admin AS adm 
				   WHERE adm.id_USR=".$_REQUEST["id_USR"]."";
		try
		{
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				while($user = $rs->fetchObject())
				{
					if($rs->key()==0)
					{
						$_POST["id_USR"] = $user->id_USR;
						$_POST["vname"] = $user->vname;
						$_POST["nname"] = $user->nname;
						$_POST["usr"] = $user->usr;
						$_POST["pwd"] = $user->pwd;
					}
				}
			}
			else
			{
				header("location:".THISMAINPAGENAME."_neu.php");
			}
		}
		
		catch(Exception $e)
		{
			$errMSG = captcha($e);
		}
		
	}
	else
	{
		header("location:".THISMAINPAGENAME.".php");
	}
}
include_once("inc/page-head.inc.php");
?>
<script type="text/javascript">
	$(document).ready(function()
	{		
		// form validation	
		$.validator.addMethod("alpha", function(value,element) {
			return this.optional(element) || /^[a-z0-9]+$/i.test(value);
		}, "<?=$arrTextes["login"]["alpha"]?>");
		$("#nnutzer").validate({	
			rules: {
				vname: "required",
				nname: "required",
				usr: {
					required: true,
					alpha: true,
					minlength: 5
				},
				pwd: {
					required: true,
					alpha: true,
					minlength: 5
				}
			},
			errorClass: "errLabel",
			highlight: function(element, errorClass) {
				$(element).addClass("error");
			},
			
			unhighlight: function(element, errorClass) {
				$(element).removeClass("error");
				$(element.form).find("label[for=" + element.id + "]").removeClass("error");
			},
			
			submitHandler: function(form) {
				form.submit();
			}
		});
		$.proj.isLinkMSG = "<?=str_replace("LINK",$arrTextes["users"]["zone"],$arrTextes["messages"]["nokeylink"])?>";
	});
</script>
</head>

<body>
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php"><?=$arrTextes["users"]["title"]?></a> / <? echo str_replace("#name","<em>".strtoupper("".$_POST["nname"]." ".$_POST["vname"]."")."</em>",$arrTextes["aktions"]["edit"])?></div>
	<div id="mainpage">
		<?php
		if(isset($errMSG))
		{
			$bolKO ? $class=" errLabel" : $class="okLabel";
			echo "<div id=\"errBox\" class=\"$class\">$errMSG</div>";
		}
		?>
		<div class="form_one_column">
			<h1><?=$arrTextes["forms"]["titlemessage"]?></h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="nnutzer" id="nnutzer">
				<input type="hidden" name="id_USR" id="id_USR" value="<?php if(isset($_REQUEST["id_USR"])) echo $_REQUEST["id_USR"]?>">
				<input type="hidden" name="usr" id="usr" value="<?php if(isset($_POST["usr"])) echo $_POST["usr"]?>">
			<ul class="form">
				<li><?=$arrTextes["forms"]["formname"]?><br />
					<input name="vname" type="text" id="vname" size="25" maxlength="100" value="<?php if(isset($_POST["vname"])) echo $_POST["vname"]?>" />
				</li>
				<li><?=$arrTextes["forms"]["formsurname"]?><br />
					<input name="nname" type="text" id="nname" size="25" maxlength="150" value="<?php if(isset($_POST["nname"])) echo $_POST["nname"]?>" />
				</li>
				<li><?=$arrTextes["login"]["formuser"]?><br />
					<div class="aktiv"><?php if(isset($_POST["usr"])) echo $_POST["usr"]?></div>
				</li>
				<li><?=$arrTextes["login"]["formpassword"]?><br />
					<input name="pwd" type="text" id="pwd" size="25" maxlength="150" value="<?php if(isset($_POST["pwd"])) echo $_POST["pwd"]?>" />
				</li>
				<li><?=$arrTextes["users"]["zone"]?><br />
					<?php
					$ref = "zona";
					$zona = "admin";
					$isREF = false;
					$strSQL = "SELECT tb.id_ZONA AS id, tb.name,
								IFNULL((SELECT 1 FROM ".PREFIX."_admin_usr_zone AS reftb WHERE reftb.id_ZONA=tb.id_ZONA AND reftb.id_USR=".$_REQUEST["id_USR"]."),0) AS isL
								FROM ".PREFIX."_admin_zone AS tb
								WHERE tb.subzona=0
								ORDER BY name ASC";
					try
					{
						$rs = $objConn->r_query($strSQL);
						if ($rs->num_rows > 0)
						{	
							echo "<div id=\"c$ref\" class=\"aktiv\">";
							while($row = $rs->fetch_array())
							{
								if($row["isL"])
								{
									echo getUm(strtoupper($row["name"]))."<br />";
									$isREF = true;
									$arrRef[$row["id"]] = $row["id"];
								}
							}

							if(!$isREF) 
								echo str_replace("LINK",$ref,$arrTextes["messages"]["nokeylink"]);
							$isREF?$_SESSION["oREF"]["$zona-$ref"] = $arrRef : $_SESSION["oREF"]["$zona-$ref"] = array();
							//print_r($arrRef);
							//echo "<br>";
							//print_r($_SESSION["oREF"]);
							unset($arrRef);
							echo "</div>";
							$rs->data_seek(0);
							echo "<div  class=\"refdiv\">";
							echo "<ul class=\"reference\">";
							while($row = $rs->fetch_array())
							{
								$checked = "";
								if($row["isL"])
									$checked = "checked=\"checked\"";
								echo "<li><input name=\"ref[$zona-$ref][".$row["id"]."]\" type=\"checkbox\" value=\"".$row["id"]."\" id=\"$ref-".$row["id"]."\"$checked /> <span>".getUm(strtoupper($row["name"]))."</span></li>";
							}
							echo "</ul>";
							echo "</div>";
						}
						else
						{
							echo "<div id=\"c$ref\" class=\"aktiv\">".str_replace("LINK",$ref,$arrTextes["messages"]["nokeyexists"])."</div>";
						}
					}

					catch(Exception $e)
					{
						echo captcha($e);
					}
					?>
				</li>
				<li>&nbsp;</li>
				<li>
					<input type="submit" class="submit" name="Modifizieren" id="Modifizieren" value="<?=$arrTextes["forms"]["modify"]?>" />
				</li>
			</ul>
			</form>
		</div>
	</div>
</body>
</html>