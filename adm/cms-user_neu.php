<?php
include_once("inc/header.inc.php");
include_once("inc/aut.inc.php");
define("THISMAINPAGENAME",str_replace("_neu.php","",PAGENAME));
if(isset($_POST["Eintragen"]))
{
	$doIns = true;
	$objConn = MySQL::getIstance();
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
		if(AlfaNum($_POST["usr"]) && AlfaNum($_POST["pwd"]))
		{
			//check if user exists
			$strSQL = "SELECT id_USR FROM ".PREFIX."_admin WHERE usr='".$_POST["usr"]."'";
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() == 0)
			{
				$strSQL = "INSERT INTO ".PREFIX."_admin (usr,pwd,vname,nname,aktiv) 
						   VALUES ('".$_POST["usr"]."','".$_POST["pwd"]."','".$_POST["vname"]."','".$_POST["nname"]."',".$_POST["aktiv"].")";
				try
				{
					$objConn->stopCOMMIT();

					if($objConn->i_query($strSQL))
					{
						$usrID = $objConn->getInsertID();
						// links to zone
						if(isset($_POST["ref"]))
						{
							$objConn->doForeignKeys($_POST["ref"],null,$usrID,$objConn);
						}
						trackUSR($_SESSION["usr"]["id_USR"],"".strtoupper(THISMAINPAGENAME)." - (".$_POST["nname"]." ".$_POST["vname"].") ".$arrTextes["tracking"]["insert"]."");
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
			else
			{
				$errMSG = $arrTextes["login"]["isuser"];
			}
			
		}
		else
		{
			$errMSG = $arrTextes["login"]["alpha"];
		}
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
	<div id="submenu"><a href="<?=THISMAINPAGENAME?>.php"><?=$arrTextes["users"]["title"]?></a> / <?=strtolower($arrTextes["users"]["new"])?></div>
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
			<ul class="form">
				<li><?=$arrTextes["forms"]["formname"]?><br />
					<input name="vname" type="text" id="vname" size="25" maxlength="100" value="<?php if(isset($_POST["vname"])) echo $_POST["vname"]?>" />
				</li>
				<li><?=$arrTextes["forms"]["formsurname"]?><br />
					<input name="nname" type="text" id="nname" size="25" maxlength="150" value="<?php if(isset($_POST["vname"])) echo $_POST["nname"]?>" />
				</li>
				<li><?=$arrTextes["login"]["formuser"]?><br />
					<input name="usr" type="text" id="usr" size="25" maxlength="100" value="<?php if(isset($_POST["vname"])) echo $_POST["usr"]?>" />
				</li>
				<li><?=$arrTextes["login"]["formpassword"]?><br />
					<input name="pwd" type="text" id="pwd" size="25" maxlength="150" value="<?php if(isset($_POST["vname"])) echo $_POST["pwd"]?>" />
				</li>
				<li><?=$arrTextes["forms"]["formakt"]?><br />
					<input name="aktiv" type="checkbox" id="aktiv" value="true" checked="checked" />
				</li>
				<li><?=$arrTextes["users"]["zone"]?><br />
					<?php
					$strSQL = "SELECT 'id_ZONA' AS idname, id_ZONA AS id, name FROM ".PREFIX."_admin_zone WHERE subzona=0 ORDER BY name";
					$objConn = MySQL::getIstance();
					$rs = $objConn->rs_query($strSQL);
					$ref="zona";
					$zona="admin";
					if ($rs->count() > 0)
					{
						echo "<div id=\"czona\" class=\"aktiv\">".str_replace("LINK",$arrTextes["users"]["zone"],$arrTextes["messages"]["nokeylink"])."</div>";
						echo "<div  class=\"refdiv\">";
						echo "<ul class=\"reference\">";
						while($row = $rs->fetch_array())
						{
							echo "<li><input name=\"ref[$zona-$ref][".$row["id"]."]\" type=\"checkbox\" value=\"".$row["id"]."\" id=\"zona-".$row["id"]."\" /> <span>".getUm(strtoupper($row["name"]))."</span></li>";
						}
						echo "</ul>";
						echo "</div>";
					}
					?>
				</li>
				<li>&nbsp;</li>
				<li>
					<input type="submit" class="submit" name="Eintragen" id="Eintragen" value="<?=$arrTextes["forms"]["insert"]?>" />
				</li>
			</ul>
			</form>
		</div>
	</div>
</body>
</html>