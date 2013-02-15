<?php 

// This must be included first, since other libs depend upon it.
require_once("../../class2.php"); 

if (!getperms ("P")) {
	header("location:".e_BASE."index.php");
	exit;
}

// Include page header stuff for admin pages. INCLUDE OTHER STUFF BEFORE THIS!
require_once(e_ADMIN . "auth.php");
require_once(e_HANDLER.'ren_help.php');
require_once(e_HANDLER.'userclass_class.php');

if ($_POST['saveopts_news2cal']) {
	$pref['news2cal_calendar_plugin'] = $_POST['news2cal_calendar_plugin'];
	save_prefs();
	$message = 'Settings Saved';
}

$text = "
<div class='center'>";

if ($message) $text .= "
<div style='text-align: center; font-weight: bold;'>$message</div><br />";

$text .= "
<form method='post' action='".e_SELF."?' id='opt_form'>
	<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
			<td class='forumheader3'>Select the calendar plugin to use</td>
			<td class='forumheader3'>
				<select name='news2cal_calendar_plugin' class='tbox'>
				<option value='calendar_menu'". ($pref['news2cal_calendar_plugin'] == 'calendar_menu' ? ' selected="true"' : '') .">calendar_menu</option>
				<option value='agenda'". ($pref['news2cal_calendar_plugin'] == 'agenda' ? ' selected="true"' : '') .">agenda</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan='3' style='text-align:center' class='forumheader'>
			<input class='button' type='submit' name='saveopts_news2cal' value='Save' />
			</td>
		</tr>
	</table>
</form>
</div>";

$ns->tablerender('News 2 Calendar', $text);
	
require_once (e_ADMIN."footer.php");

?>