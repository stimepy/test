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

/* e107bb 3.0.t3: Page Cleaned and Updated */
/* e107bb 3.0.t3: "I didn't want to do that!" Protection */

require_once("../../class2.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }

/* Show Menu */
require_once (e_PLUGIN."e107bb/bbfunctions.php");
function admin_remod_adminmenu() {
 e107bb_admin_menu();
}

require_once(e_ADMIN.'auth.php');

//Language Compatibility with e107 v0.7.6+
$lan_file = "e107bb/languages/".e_LANGUAGE.".php";
include_once(file_exists($lan_file) ? $lan_file : $plugindir."e107bb/languages/English.php");

if(e_QUERY == "remod") {

if(isset($_POST['bbsubmit2'])) {
    e107bb_setupfiles();

$text = e107bb_install_header();
$text .= LAN_E107BB_35;

$text .= e107bb_install_footer();
$ns->tablerender( LAN_E107BB_12, $text);
require_once(e_ADMIN."footer.php");
exit;

} else {
    $phpBBsqlprefix = PHPBB_PREFIX;
    $_POST['bbpath'] = $phpBBpath;

    e107bb_install_stage2();
}

} else {

$text = e107bb_install_header();

		$text .= '<table style="width: 100%;" class="fborder">
		<tbody><tr>
		<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_12.'</td>
		</tr>
		<tr> <td class="forumheader3">
		'.LAN_E107BB_36.'
<form name="e107bb" action="'.e_SELF.'?remod" method="post"><input class="button" type="submit" name="bbremod" value="'.LAN_E107BB_37.'" /></form>
		</td></tr></tbody>
		</table><br/>';

$text .= e107bb_install_footer();
$ns->tablerender( LAN_E107BB_12, $text);
require_once(e_ADMIN."footer.php");
exit;
}

?>