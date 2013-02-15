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
function admin_index_adminmenu() {
 e107bb_admin_menu();
}

require_once(e_ADMIN.'auth.php');
require(e_PLUGIN.'e107bb/plugin.php');

//Language Compatibility with e107 v0.7.6+
$lan_file = "e107bb/languages/".e_LANGUAGE.".php";
include_once(file_exists($lan_file) ? $lan_file : "e107bb/languages/English.php");

if (defined('PHPBB_PREFIX')) {
	//e107bb 3.1: Get phpBB version, the e107 way
	$sql->db_Select_gen("SELECT * from ".PHPBB_PREFIX."config where config_name='version'");
	$row = $sql -> db_Fetch();
	$phpBBversion = $row['config_value'];

	$text = e107bb_install_header();

	if($pref["e107bb_theme"] == "0") {
		$themei = "Off";
	} else {
		$themei = "On (".$pref["e107bb_theme"].")";
	}

	if($sql->db_Select_gen("SHOW COLUMNS FROM ".PHPBB_PREFIX."users LIKE 'loginname'")===0) {
	$modtext = LAN_E107BB_1;
	} else {
	$modtext = LAN_E107BB_2;
	}

	$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_3.'</td>
	</tr>
	<tr> <td class="forumheader3">
	'.LAN_E107BB_4.': '.$eplug_version.' (phpBB '.$phpBBversion.')<br/>
	'.LAN_E107BB_5.': <a href="'.e_HTTP.$pref['e107bb_url'].'">'.e_HTTP.$pref['e107bb_url'].'</a><br/>
	'.LAN_E107BB_6.': '.$themei.'<br/>
	'.LAN_E107BB_7.': '.$modtext.'<br/>
	</td></tr></tbody>
	</table><br/>';

	//Check if e107 forum is installed
	if($sql->db_Select_gen("SHOW TABLES LIKE '".MPREFIX."forum_t'")===1) {
		$text .= '<table style="width: 100%;" class="fborder">
		<tbody><tr>
		<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_8.'</td>
		</tr>
		<tr> <td class="forumheader3">
		'.LAN_E107BB_9.'
<form name="e107bb" action="admin_import.php" method="post"><input class="button" type="submit" name="bbimport" value="'.LAN_E107BB_10.'" /></form>
		</td></tr></tbody>
		</table><br/>';
	}

	$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_11.'</td>
	</tr>
	<tr> <td class="forumheader3">
<strong>'.LAN_E107BB_6.'</strong><br/>
'.LAN_E107BB_13.'<br/>
<br/>
<strong>'.LAN_E107BB_14.'</strong><br/>
'.LAN_E107BB_15.'<br/>
<br/>
<strong>'.LAN_E107BB_7.'</strong><br/>
'.LAN_E107BB_16.'<br/>
'.LAN_E107BB_17.': <a href="http://www.phpbb.com/mods/db/index.php?i=misc&mode=display&contrib_id=9965">phpBB MOD Database - Separate Login and User Name</a><br/><br/>
<strong>Support at <a href="http://www.diporgos.com/forum">DIPOrgOS Forums</a><br/>
<a href="http://www.diporgos.com/e107bb/">Official e107bb site</a></strong>
	</td></tr></tbody>
	</table><br/>';

	$text .= e107bb_install_footer();


	$ns -> tablerender(LAN_E107BB_12, $text);
}
require_once(e_ADMIN."footer.php");

?>