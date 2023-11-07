<div id="usr_form_log">
	<div id="usr_form_log_txt">
	LOGIN
	</div>
	<br />
	<form action="<?=$pageurl?>" method="post" name="frmlogin" id="frmlogin">
		<input name="usr" type="text" id="usr" size="15" maxlength="100" style="line-height:2;height:20px" /> <?=$arrTextes["login"]["formuser"]?>
		<br />
		<br />
		<input name="pwd" type="password" id="pwd" size="15" maxlength="150" style="line-height:2;;height:20px" /> <?=$arrTextes["login"]["formpassword"]?>
		<br />
		<br />
		<input type="submit" name="dolog" id="dolog" value="<?=$arrTextes["login"]["formenter"]?>" class="btn" style="float:right;clear:both;" />
		<br />
	</form>
	<?php
	if(isset($strMSG)) echo "<div id=\"errmsg\" style=\"width:100%; border-top:solid 1px #000;color:#F00;margin-top:25px;padding-top:5px;\">".strtoupper($strMSG)."</div>";
	?>
</div>
