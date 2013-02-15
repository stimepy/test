<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version: 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

/*#################################
Function:disputeform
Needs: N/A
Returns:N/A
What does it do:Creates the dispute form.
##################################*/
function disputeform() {
	$c  = DispFunc::X1PluginStyle();
	if (!X1Cookie::checklogin()){
		return DispFunc::X1PluginOutput(XL_notlogggedin);
	}
	
	list($cookieteamid, $cookieteam) = X1Cookie::CookieRead(X1_cookiename);
	
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString($_POST['randid']));
	$names=X1TeamUser::SetTeamName(array($challenge['winner'], $challenge['loser']));
	$event = SqlGetRow("*",X1_DB_events," WHERE sid =".MakeItemString($challenge['ladder_id']));
	$otherteam = ($challenge['winner'] == $cookieteamid) ? $names[$challenge["loser"]] : $names[$challenge["winner"]];
		
	if($event['whoreports'] == "winner"){
		$temp = $cookieteam;
		$cookieteam = $otherteam;
		$otherteam = $temp;
		unset($temp);
	}
	
	$c .= "
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_disputestable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_teamdisputes_filedispute."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
	<tr>
		<td class='alt1'>".XL_teamprofile_hwinner."</td>
		<td class='alt1'><input type='text' readonly='readonly' name='offender' value='$otherteam'></td></tr>
	<tr>
		<td class='alt2'>".XL_teamprofile_hloser."</td>
		<td class='alt2'><input type='text' readonly='readonly' name='sender' value='$cookieteam'></td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamprofile_hevent.":</td>
		<td class='alt1'><input type='text' readonly='readonly' name='laddername' value='$event[title]'></td>
	</tr>
	<tr> 
		<td class='alt2'>".XL_matchinfo_comments."</td>
		<td class='alt2'><textarea name='comments'cols='50' wrap='VIRTUAL' rows='5'></textarea></td>
	</tr>
	</tbody>
	<tfoot class='".X1plugin_tablefoot."'>
		<tr>
			<th colspan='2'>
				<input type='Submit' name='Submit' value='".XL_teamdisputes_button."' >
				<input name='".X1_actionoperator."' type='hidden' value='dispute'>
				<input name='ladder_id' type='hidden' value='$challenge[ladder_id]'>
				<input name='randid' type='hidden' value='$challenge[randid]'>
			</th>
		</tr>
	</tfoot>
	</table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}

/*###############################
Function: dispute
Needs:N/A
Returns:N/A
What does it do: Insertes the dispute into the database.
################################*/
function dispute() {
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::checklogin()){
		$c .=  DispFunc::X1PluginTitle(XL_notlogggedin);
		return DispFunc::X1PluginOutput($c);
	}
	list ($cookieteamid, $cookieteam, $password) = X1Cookie::CookieRead(X1_cookiename);
	$row = SqlGetAll("team_id",X1_DB_teams," WHERE name = ".MakeItemString($_POST['offender'])." or name=".MakeItemString($_POST['sender'])); 
	
	foreach($row as $nam){
		if($cookieteamid!=$nam['team_id']){
			if($cookieteam==$_POST['offender']){
				$sender=$nam['team_id'];	
			}
			else{
				$offender=$nam['team_id'];													
			}
		}
		else{
			if($cookieteam==$_POST['offender']){
				$offender=$nam['team_id'];	
			}
			else{
				$sender=$nam['team_id'];													
			}	
		}
	}	
	ModifySql("INSERT INTO", X1_DB_teamdisputes, "
	(sender, offender, ladder_id, date, info)
	 VALUES (".MakeItemString($sender).", 
	 ".MakeItemString($offender).", 
	 ".MakeItemString($_POST['ladder_id']).", 
	 ".MakeItemString(time()).", 
	 ".MakeItemString($_POST['comments']).")");
	 
	$c .=  DispFunc::X1PluginTitle(XL_teamdisputes_submitted);
	return DispFunc::X1PluginOutput(displayteam("home",$c));
}

?>
