<?php
/*
+---------------------------------------------------------------+
|        e107bb 3.1
|        DIPOrg (suporte@diporg.com)
|        http://www.diporg.com
|
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once("../../class2.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }

/* Show Menu */
require_once (e_PLUGIN."e107bb/bbfunctions.php");
function admin_bbtheme_adminmenu() {
 e107bb_admin_menu();
}

require_once(e_ADMIN.'auth.php');

if (defined('PHPBB_PREFIX')) {


if(isset($_POST['bbsubmitfix'])) {

$phpBBpath = PHPBB_PATH;
/* Getting the default theme name and folder (phpBB) */
$sql->db_Select_gen("SELECT * from ".PHPBB_PREFIX."config where config_name='default_style'");
$row = $sql -> db_Fetch();
$phpBBstylid  = $row['config_value'];

$sql->db_Select_gen("SELECT theme_id from ".PHPBB_PREFIX."styles where style_id='".$phpBBstylid."'");
$row = $sql -> db_Fetch();
$phpBBthemeid = $row['theme_id'];

$sql->db_Select_gen("SELECT template_path from ".PHPBB_PREFIX."styles_template where template_id='".$phpBBthemeid."'");
$row = $sql -> db_Fetch();
$phpBBthemefolder = $row['template_path'];

$phpBBthemepath = $phpBBpath."styles/".$phpBBthemefolder."/";

$text = e107bb_install_header().'<form name="e107bb" action="'.e_SELF."?".e_QUERY.'" method="post">

<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;" colspan="2">'.LAN_E107BB_18.'</td>

	</tr>

<tr>
<td colspan="2" class="forumheader">'.LAN_E107BB_19.'

<ul>';

$writemode = 0;

clearstatcache();

if(is_writable($phpBBthemepath."template/overall_header.html") ) { $text .= "<li>styles/".$phpBBthemefolder."/template/overall_header.html <strong>".LAN_E107BB_20."</strong></li>"; } else { $text .= "<li>styles/".$phpBBthemefolder."/template/overall_header.html  <strong style=\"color:#ff0000;\">".LAN_E107BB_21."</strong></li>";  $writemode=1; }

if(is_writable($phpBBthemepath."template/overall_footer.html")) { $text .= "<li>styles/".$phpBBthemefolder."/template/overall_footer.html  <strong>".LAN_E107BB_20."</strong></li>"; } else { $text .= "<li>styles/".$phpBBthemefolder."/template/overall_footer.html  <strong style=\"color:#ff0000;\">".LAN_E107BB_21."</strong></li>";  $writemode=1; }

if($writemode == 0) {
$text .= '</ul><br/>

'.LAN_E107BB_22.'<br/>
'.LAN_E107BB_23.'
</tr>

<tr>
		<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmitfix2" value="'.LAN_E107BB_25.'" /></td>
		</tr>
	</tbody>
</table>
</form>';
} else {
$text .= '</ul><br/>

'.LAN_E107BB_24.'<br/>
'.LAN_E107BB_23.'
</tr>

<tr>
		<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmitfix" value="'.LAN_E107BB_26.'" /></td>
		</tr>
	</tbody>
</table>
</form>';
}

$text .= e107bb_install_footer();
		
$ns->tablerender( LAN_E107BB_INST_PLINST, $text);
require_once(e_ADMIN."footer.php");

exit;

} else {
/* Where is phpBB? */
$phpBBpath = PHPBB_PATH;

/* Getting the default theme name and folder (phpBB) */
$sql->db_Select_gen("SELECT * from ".PHPBB_PREFIX."config where config_name='default_style'");
$row = $sql -> db_Fetch();
$phpBBstylid  = $row['config_value'];

$sql->db_Select_gen("SELECT theme_id from ".PHPBB_PREFIX."styles where style_id='".$phpBBstylid."'");
$row = $sql -> db_Fetch();
$phpBBthemeid = $row['theme_id'];

$sql->db_Select_gen("SELECT template_path from ".PHPBB_PREFIX."styles_template where template_id='".$phpBBthemeid."'");
$row = $sql -> db_Fetch();
$phpBBthemefolder = $row['template_path'];

$phpBBthemepath = $phpBBpath."styles/".$phpBBthemefolder."/";

if($_POST['e107bb_theme'] === "1") {
$pref['e107bb_theme'] = $phpBBthemefolder;
} elseif($_POST['e107bb_theme'] === "0") {
$pref['e107bb_theme'] = "0";
}

if($_POST['e107bb_tborder'] === "1") {
$pref['e107bb_tborder'] = "1";
} elseif($_POST['e107bb_tborder'] === "0") {
$pref['e107bb_tborder'] = "0";
}
	$text = e107bb_install_header();

	if($pref["e107bb_theme"] == "0") {
		$themei = "Off";
		$bbthemeoff = 'checked="checked"';
		$bbthemeon = '';
	} else {
		$themei = "On (".$pref["e107bb_theme"].")";
		$bbthemeon = 'checked="checked"';
		$bbthemeoff = '';
	}

	if($pref["e107bb_tborder"] == "0") {
		$bbthemeboff = 'checked="checked"';
		$bbthemebon = '';
	} else {
		$bbthemebon = 'checked="checked"';
		$bbthemeboff = '';
	}





//echo $phpBBsqlprefix;



$bbfiletext = "";

if($phpBBthemefolder !== "e107_prosilver") {
$bbfiletext = LAN_E107BB_27;
}

//Check if supported

$phpBBoh = $phpBBthemepath."template/overall_header.html";
$phpBBoh2 = fopen($phpBBoh, "r");
$phpBBoh3 = fread($phpBBoh2, filesize($phpBBoh));
fclose($phpBBoh2);

$phpBBof = $phpBBthemepath."template/overall_footer.html";
$phpBBof2 = fopen($phpBBof, "r");
$phpBBof3 = fread($phpBBof2, filesize($phpBBof));
fclose($phpBBof2);

// Check if mod needed
$pos = strpos($phpBBoh3, "{E107HEADER}");
if ($pos !== false) {
$pos2 = strpos($phpBBoh3, "{E107BODY}");
if ($pos2 !== false) {
$pos3 = strpos($phpBBof3, "{E107FOOTER}");
if ($pos3 !== false) {
  $bbfileok = true;
} else {
  $bbfileok = false;
}
} else {
  $bbfileok = false;
}
} else {
  $bbfileok = false;
}
if($bbfileok === false) {
if(isset($_POST['bbsubmitfix2'])) {
//Save changes
//Starting replacing
$phpBBoh = $phpBBthemepath."template/overall_header.html";
$phpBBoh2 = fopen($phpBBoh, "r");
$phpBBoh3 = fread($phpBBoh2, filesize($phpBBoh));
fclose($phpBBoh2);

$phpBBof = $phpBBthemepath."template/overall_footer.html";
$phpBBof2 = fopen($phpBBof, "r");
$phpBBof3 = fread($phpBBof2, filesize($phpBBof));
fclose($phpBBof2);

//Remove old e107bb stuff

$phpBBoh3 = preg_replace("'<!--e107HeadTag-->(.*)<!--e107HeadTagEnd-->'ms", "", $phpBBoh3);
$phpBBoh3 = preg_replace("'<!--e107BodyTag-->(.*)<!--e107BodyTagEnd-->'ms", "", $phpBBoh3);
$phpBBof3 = preg_replace("'<!--e107FootTag-->(.*)<!--e107FootTagEnd-->'ms", "", $phpBBof3);

$phpBBnewheader = $phpBBoh3;
//Now apply changes
$pos = strpos($phpBBoh3, "{E107HEADER}");
if ($pos === false) {
$phpBBnewheader = preg_replace("'</head>'", "{E107HEADER}</head>", $phpBBnewheader);
}
$pos2 = strpos($phpBBoh3, "{E107BODY}");
if ($pos2 === false) {
$phpBBnewheader = preg_replace("'<body(.*)>'", "<body$1>{E107BODY}", $phpBBnewheader);
}
if($pos === false || $pos2 === false) {
  $save = fopen($phpBBoh, 'w');
  fwrite($save, $phpBBnewheader);
  fclose($save);
}

$pos3 = strpos($phpBBof3, "{E107FOOTER}");
if ($pos3 === false) {
$phpBBnewfooter = preg_replace("'</body>'", "{E107FOOTER}</body>", $phpBBof3);

  $save = fopen($phpBBof, 'w');
  fwrite($save, $phpBBnewfooter);
  fclose($save);
}

//If you don't destroy the cache, it won't work
destroy(PHPBB_PATH."/cache/");

} else {
 $text .= '<form name="e107bb" action="'.e_SELF."?".e_QUERY.'" method="post">
<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;" colspan="2">'.LAN_E107BB_12.'</td>
	</tr>
	<tr> <td class="forumheader3">
'.sprintf(LAN_E107BB_28, $phpBBthemefolder).'
</td></tr>
<tr>
<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmitfix" value="'.LAN_E107BB_29.'" /></td>
</tr>
</tbody>
	</table><br/></form>
';

	$text .= e107bb_install_footer();
	$ns -> tablerender("e107bb", $text);

	require_once(e_ADMIN."footer.php");
	exit;
}
}
if(isset($_POST['bbsubmit1'])) {

save_prefs();

$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">Information</td>
	</tr>
	<tr> <td class="forumheader3">
'.LAN_E107BB_30.'
</td></tr></tbody>
	</table><br/>';

}
	$text .= '<form name="e107bb" action="'.e_SELF."?".e_QUERY.'" method="post"><table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;" colspan="2">'.LAN_E107BB_3.'</td>
	</tr>
	<tr> <td class="forumheader3" style="width: 50%;">

	<strong>'.LAN_E107BB_6.'</strong><br/>

</td><td class="forumheader3" style="width: 50%; text-align:right;">
	<input type="radio" name="e107bb_theme" value="1" '.$bbthemeon.'> On&nbsp;&nbsp;
	<input type="radio" name="e107bb_theme" value="0" '.$bbthemeoff.'> Off
</td></tr>
	<tr> <td class="forumheader3">
<strong>'.LAN_E107BB_31.'</strong>

	</td><td class="forumheader3" style="width: 50%; text-align:right;">
	<input type="radio" name="e107bb_tborder" value="1" '.$bbthemebon.'> On&nbsp;&nbsp;
	<input type="radio" name="e107bb_tborder" value="0" '.$bbthemeboff.'> Off
</td></tr>
<tr> <td class="forumheader3" colspan="2">

	<strong>'.LAN_E107BB_32.': </strong>'.$phpBBthemefolder.'<br/>'.$bbfiletext.'

</td></tr>
<tr>
<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmit1" value="'.LAN_E107BB_33.'" /> <input class="button" type="submit" name="bbpreview" value="'.LAN_E107BB_34.'" /></td>
</tr>
</tbody>
	</table><br/></form>';

if(isset($_POST['bbpreview'])) {

$url = "e107bb=preview&";
if($_POST['e107bb_theme'] == 1) {
$url .= "e107bb_theme=ok&";
} else {
$url .= "e107bb_theme=no&";
}

if($_POST['e107bb_tborder'] == 1) {
$url .= "e107bb_tborder=ok";
} else {
$url .= "e107bb_tborder=no";
}

$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_34.'</td>
	</tr>
	<tr> <td class="forumheader3">
<iframe src ="'.PHPBB_PATH.'index.php?'.$url.'" style="width: 100%; height:500px;">
  <p>Your browser does not support iframes.</p>
</iframe>
</td></tr></tbody>
	</table>';

}
	$text .= e107bb_install_footer();


	$ns -> tablerender(LAN_E107BB_12, $text);

}
}
require_once(e_ADMIN."footer.php");

?>