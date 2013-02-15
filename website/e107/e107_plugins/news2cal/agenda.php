<?php
/*
+---------------------------------------------------------------+
| Agenda by bugrain (www.bugrain.plus.com)
| see plugin.php for version information
|
| A plugin for the e107 Website System (http://e107.org)
|
| Released under the terms and conditions of the
| GNU General Public License (http://gnu.org).
|
| $Source: e:\_repository\e107_plugins/agenda/agenda.php,v $
| $Revision: 1.32 $
| $Date: 2007/06/04 21:39:29 $
| $Author: Neil $
+---------------------------------------------------------------+
*/
require_once("../../class2.php");
require_once(HEADERF);

if(e_QUERY) {
	$qs = explode(".", e_QUERY);
}

function get_select($name, $type) {
	
	$out = '
	<select name="'. $name .'" class="tbox">';
	
	switch ($type) {
	case 'hour':
		for ($h = 0; $h < 24; $h++) {
			$hour = sprintf('%02d', $h);
			$out .= "
		<option value=\"$hour\"". ($hour == 12 ? ' selected="true"' : '') .">$hour</option>";
		}
		break;
		
	case 'minute':
		for ($m = 0; $m < 60; $m += 5) {
			$min = sprintf('%02d', $m);
			$out .= "
		<option value=\"$min\"". ($min == '00' ? ' selected="true"' : '') .">$min</option>";
		}
		break;
	
	case 'category':
		$sql->db_Select("agenda_category", "cat_id, cat_name");
		while (list($cat_id, $cat_name) = $sql->db_Fetch()) $out .= "
		<option value=\"$cat_id\">$cat_name</option>";
		break;
	}
	
	$out .= '
	</select>';
	
	return $out;
	
}

if ($qs[0] > 0) {
	$sql->db_Select("news", "*", "news_id='". intval($qs[0]) ."' ");
	list($null, $ne_title, $ne_event, $null, $ne_datestamp, $ne_author, $ne_category, $null, $null, $null, $null, $null, $null, $null, $null, $null) = $sql->db_Fetch();
}


$text = '
<script type="text/javascript" src="'. e_PLUGIN .'agenda/agenda.js"></script>';

$text .= '
<form method="post" action="'. e_PLUGIN .'agenda/agenda.php?save.0.0.'. time() .'.1">
	<table style="width:100%" class="fborder" summary="*">
		<tr>
			<td class="forumheader3">Title&nbsp*<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" type="text" name="agn_title" id="agn_title" size="30" value="'. $ne_title .'" maxlength="200" /></td>
		</tr>
		<tr>
			<td class="forumheader3">Category&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3">
				<select class="tbox" name="agn_category" id="agn_category" >
				<option value="2">Birthday</option> 
				<option value="8">Gig</option> 
				<option value="7">Meeting</option> 
				<option value="1">Miscellaneous</option> 
				<option value="6">Reminder</option> 
				<option value="4">Shift - bank</option> 
				<option value="3">Shift - normal</option> 
				<option value="5">Shift - overtime</option> 
				<option value="10">Social</option> 
				<option value="9">Sport</option> 
				</select>
			</td>
		</tr>
		<tr>
			<td class="forumheader3">Start date &amp; time&nbsp*<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" size="10" name="agn_start" value="19-04-2010" id="f-calendar-field-2" type="text" /> <a href="#" id="f-calendar-trigger-2"><img style="vertical-align:middle; border:0px" src="'. e_HANDLER .'calendar/cal.gif"  alt="" /></a><script type="text/javascript">Calendar.setup({"ifFormat":"%d-%m-%Y","daFormat":"%Y/%m/%d","firstDay":1,"showsTime":false,"showOthers":true,"weekNumbers":true,"inputField":"f-calendar-field-2","button":"f-calendar-trigger-2"});</script>&nbsp;&nbsp;&nbsp;
			';

$text .= get_select('agn_start_h', 'hour');

$text .= '&nbsp;:&nbsp;
			';

$text .= get_select('agn_start_m', 'minute');

$text .= '
			</td>
		</tr>
		<tr>
			<td class="forumheader3">End date &amp; time&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" size="10" name="agn_end" value="19-04-2010" id="f-calendar-field-3" type="text" /> <a href="#" id="f-calendar-trigger-3"><img style="vertical-align:middle; border:0px" src="'. e_HANDLER .'calendar/cal.gif"  alt="" /></a>
			<script type="text/javascript">Calendar.setup({"ifFormat":"%d-%m-%Y","daFormat":"%Y/%m/%d","firstDay":1,"showsTime":false,"showOthers":true,"weekNumbers":true,"inputField":"f-calendar-field-3","button":"f-calendar-trigger-3"});</script>&nbsp;&nbsp;&nbsp;
				';

$text .= get_select('agn_end_h', 'hour');

$text .= '&nbsp;:&nbsp;
				';

$text .= get_select('agn_end_m', 'minute');

$text .= '
			</td>
		</tr>
		<tr>
			<td class="forumheader3">Location&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" type="text" name="agn_location" id="agn_location" size="50" value="" maxlength="200" /></td>
		</tr>
		<tr>
			<td class="forumheader3">Details&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><textarea class="tbox" name="agn_details" id="agn_details" cols="2" rows="2" style="width:350px;height:60px">'. $ne_event .'</textarea></td>
		</tr>
		<tr>
			<td class="forumheader3">Priority&nbsp<br><span class="smalltext">Use zero for no priority</span></td>
			<td class="forumheader3"><label for="agn_priority0"><input type="radio" name="agn_priority" id="agn_priority0" value="0" checked="checked"/>0</label> <label for="agn_priority1"><input type="radio" name="agn_priority" id="agn_priority1" value="1"/>1</label> <label for="agn_priority2"><input type="radio" name="agn_priority" id="agn_priority2" value="2"/>2</label> <label for="agn_priority3"><input type="radio" name="agn_priority" id="agn_priority3" value="3"/>3</label> <label for="agn_priority4"><input type="radio" name="agn_priority" id="agn_priority4" value="4"/>4</label> <label for="agn_priority5"><input type="radio" name="agn_priority" id="agn_priority5" value="5"/>5</label> </td>
		</tr>
		<tr>
			<td class="forumheader3">Owner&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" type="text" name="agn_owner" id="agn_owner" size="20" value="'. $ne_author .'" maxlength="100" /></td>
		</tr>
		<tr>
			<td class="forumheader3">Contact E-Mail&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><input class="tbox" type="text" name="agn_contact_email" id="agn_contact_email" size="50" value="" maxlength="200" /></td>
		</tr>
		<tr>
			<td class="forumheader3">Private&nbsp<br><span class="smalltext">Private entries can only be seen by you and administrators</span></td>
			<td class="forumheader3"><label for="agn_private"><input type="checkbox" name="agn_private" id="agn_private" value="1"/>Tick to make this a private entry</label><br /></td>
		</tr>
		<tr>
			<td class="forumheader3">Complete&nbsp<br><span class="smalltext"></span></td>
			<td class="forumheader3"><label for="agn_complete"><input type="checkbox" name="agn_complete" id="agn_complete" value="1"/>Tick to complete this entry</label><br /></td>
		</tr>
		<tr style="vertical-align:top">
			<td colspan="2" style="text-align:center" class="forumheader"><label for="multiadd"><input class="tbox" type="checkbox" name="multiadd" value="Y"  />Add another</label></td>
		</tr>
		<tr style="vertical-align:top">
			<td colspan="2" style="text-align:center" class="forumheader2"><input class="button tbox" type="submit" name="add" value="Add" />&nbsp;</td>
		</tr>
	</table>
</form>';

// Ensure the pages HTML is rendered using the theme layout.
$ns->tablerender('News 2 Agenda', $text);

// this generates all the HTML (menus etc.) after the end of the main section
e107_require_once(FOOTERF);

?>