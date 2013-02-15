<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function cookiechallenge() {
	$c = DispFunc::X1PluginStyle();
	if(!checklogin())return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	list ($teamid, $teamname) = X1Cookie::CookieRead(X1_cookiename);
	$box = SelctBox_CommonEvents($teamid, $_POST['team_id'], 'ladder_id', $_POST['event_id']);
	$show_button = (!$box) ? false: true;
	$box = (!$box) ? XL_challenges_nocommonevents : $box ;
	$c .= "
	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_challengeteamtable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th class='alt3'>".XL_challenges_challengeteam."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td class='alt1'>".XL_challenges_commonevents."</td>
			</tr>
			<tr>
				<td class='alt2'>$box</td>
			</tr>
			</tbody>
			<tfoot class='".X1plugin_tablefoot."'>
				<tr>
					<td class='alt3'>";
						if($show_button){
							$c .="<input type='Submit' name='Submit' value='".XL_teamadmin_challnew."' >
							<input name='".X1_actionoperator."' type='hidden' value='challengeteamform'>
							<input name='challengeid' type='hidden' value='$_POST[team_id]'>";
						}else{
							$c .="&nbsp;";
						}
					$c .="</td>
				</tr>
			</tfoot>
		</table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}
?>
