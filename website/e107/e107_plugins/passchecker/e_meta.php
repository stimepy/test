<?php
if (!defined('e107_INIT')) { exit; }

	global $pref;

function form_passwordcheck($form_name, $form_size, $form_value, $form_maxlength, $form_class = "tbox", $form_readonly = "", $form_tooltip = "", $form_js = "" , $form_chkpass = false) {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$value = (isset($form_value) ? " value='".$form_value."'" : "");
		$size = ($form_size ? " size='".$form_size."'" : "");
		$maxlength = ($form_maxlength ? " maxlength='".$form_maxlength."'" : "");
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		$chkpass = ($form_chkpass == true ? " onkeyup=\"EvalPwdStrength(document.forms[0],this.value);\"" : "");
		return "\n<input class='".$form_class."' type='password' ".$name.$value.$size.$maxlength.$readonly.$tooltip.$form_js.$chkpass." />";
	}

	
function insert_passlength(){
	global $pref;
	$PasswordLength = ($pref['signup_pass_len'] == "" ? "0" : $pref['signup_pass_len']);
	$ReturnVar = "<script type=\"text/javascript\">
	var PasswordLength = ".$PasswordLength."; 
	</script>";
	return $ReturnVar;
}

function insert_passchk_js(){
	return "<script type=\"text/javascript\" src=\"".e_PLUGIN."passchecker/js/passwordcheck.js\"></script>\n";
}

function insert_passcheck_field(){
global $rs;
return form_passwordcheck("password1", 30, $password1, 20,"tbox","","","",true);
}


if (e_PAGE == "signup.php" && $pref['passchkenable'] == '1') {

	$pass1replace = insert_passlength().insert_passchk_js().insert_passcheck_field();
	$SIGNUP_BODY = str_replace('{SIGNUP_PASSWORD1}',$pass1replace, $SIGNUP_BODY);
	
	
	$passstyles = "{SIGNUP_PASSWORD_LEN}
<br />
<span id=\"idSM0\">
<img id=\"idSMT0\" style=\"display: inline;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/empty.png\" >
</span>
<span id=\"idSM1\">
<img id=\"idSMT1\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/short.png\" >
</span>
<span id=\"idSM2\">
<img id=\"idSMT2\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/weak.png\" >
</span>
<span id=\"idSM3\">
<img id=\"idSMT3\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/fair.png\" >
</span>
<span id=\"idSM4\">
<img id=\"idSMT4\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/medium.png\" >
</span>
<span id=\"idSM5\">
<img id=\"idSMT5\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/strong.png\" >
</span>";
	$SIGNUP_BODY = str_replace('{SIGNUP_PASSWORD_LEN}',$passstyles, $SIGNUP_BODY);
}

if (e_PAGE == "usersettings.php" && $pref['passchkenable'] == '1') {

	$pass1style = "<tr>
	<td style='width:40%' class='forumheader3'>".LAN_152."<br /><span class='smalltext'>".LAN_401."</span></td>
	<td style='width:60%' class='forumheader2'>";

	$pass1replace = insert_passlength().insert_passchk_js().$pass1style.insert_passcheck_field();
	$USERSETTINGS_EDIT = str_replace('{PASSWORD1}',$pass1replace, $USERSETTINGS_EDIT);
	
	
if($pref['signup_pass_len']>0){$passlenimport ="<span class='smalltext'>  (".LAN_SIGNUP_1." ".$pref['signup_pass_len']." ".LAN_SIGNUP_2.")</span>";}
$passstyles = "<br />".$passlenimport."
<br />
<span id=\"idSM0\">
<img id=\"idSMT0\" style=\"display: inline;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/empty.png\" >
</span>
<span id=\"idSM1\">
<img id=\"idSMT1\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/short.png\" >
</span>
<span id=\"idSM2\">
<img id=\"idSMT2\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/weak.png\" >
</span>
<span id=\"idSM3\">
<img id=\"idSMT3\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/fair.png\" >
</span>
<span id=\"idSM4\">
<img id=\"idSMT4\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/medium.png\" >
</span>
<span id=\"idSM5\">
<img id=\"idSMT5\" style=\"display: none;\" border=\"0\" src=\"".e_PLUGIN."passchecker/images/".e_LANGUAGE."/strong.png\" >
</span>";
$USERSETTINGS_EDIT = str_replace('{PASSWORD_LEN}',$passstyles, $USERSETTINGS_EDIT);
}
?>