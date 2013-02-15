<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include')){
	die ("You cannot load this file outfile of X1plugin");
	}
###############################################################
function X1_activate_team(){
	//$team_info = X1Cookie::CookieRead(X1_cookiename);
	if(X1Cookie::CheckLogin(X1_cookiename)){
		return displayteam();
	}
	$team_id=DispFunc::X1Clean($_REQUEST['t']);
	$c  = DispFunc::X1PluginStyle();
	if(X1TeamUser::X1SetLogin($team_id)){
		$c .= "<meta http-equiv='refresh' content='".X1_refreshtime.";URL=".X1_publicpostfile.X1_linkactionoperator."displayteam'>";	
		$c .= DispFunc::X1PluginTitle("<a href='".X1_publicpostfile.X1_linkactionoperator."displayteam'>".XL_teamadmin_activating."</a>");	
		return DispFunc::X1PluginOutput($c);
	}
	$c .= DispFunc::DirectToRefresh('myteams');
	$c .= DispFunc::X1PluginTitle(XL_failed_login);	
	return DispFunc::X1PluginOutput($c);
}

/*######################
Function: displayteam
Variables: string $Panel, string $msg
Returns:
What it does: Creates the panel of button options that take you to the appropriate menu's and creates them.   These menus include home, team roster, events, matches.
#######################*/
function displayteam($panel='home', $msg=''){
	$c  = DispFunc::X1PluginStyle();
	$c .= "<script type='text/javascript' >
	var panels = new Array('panel1', 'panel2', 'panel3', 'panel4', 'panel5', 'panel6', 'panel7', 'panel8');
	function x1showPanel(name){
		for(i = 0; i < panels.length; i++){
			document.getElementById(panels[i]).style.display = (name == panels[i]) ? 'block':'none';
		}
	}
	</script>\n";
	if (!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));		
	}
	if(!empty($msg))$c .= DispFunc::X1PluginTitle($msg);
	list ($cookieteamid, $cookieteam) = X1Cookie::CookieRead(X1_cookiename);	
	if(!isset($cookieteam))return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));	
	
	$user_info = X1_userdetails();
	
	$row = SqlGetRow("*",X1_DB_teams, "WHERE team_id =".MakeItemString($cookieteamid));  

	$team_id = $row['team_id'];	
	$team = $row['name'];
	
	$messanger = new ChallengeMessageSystem($team_id, $team);
	
	$iscaptain = ($row['playerone'] == $user_info[0]) ? true : false;
	$captain = X1TeamUser::GetUserName($row['playerone']);
	
	
	if(!X1_custommenu){
		$c .= "
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel1');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamprofile_tprofile."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel2');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamprofile_troster."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel3');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamadmin_invites."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel4');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_index_events."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel5');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamadmin_matches."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel6');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamprofile_thistory."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel8');return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamadmin_message.$messanger->GetAndDisplayTotalNewMessage()."</a>";

		if($iscaptain)$c .= "<a href='javascript:' class='tab' onclick=\"x1showPanel('panel7');return false;\" STYLE='text-decoration:none'><img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>".XL_teamadmin_quit."</a>";
	}
	
	$c .= DispFunc::X1PluginTitle(XL_teamadmin_title."$team");	

	
	$panstyle = ( $panel=="home" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel1' $panstyle>";
	$c .=DisplayPanelHome($row);
	
	
	$panstyle = ( $panel=="roster" ) ? '' : 'style="display:none"';
	$c .="<div class='panel' id='panel2' $panstyle>";
	$c .= displaypanelteamroster($team_id);	
	
	
	$panstyle = ( $panel=="invites" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel3' $panstyle>";
	$c .=displaypanelinvite($team, $team_id);
		
	$panstyle = ( $panel=="challenges" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel5' $panstyle>";
	$c .=DisplayPanelChallenges($team,$team_id, $messanger);
	
	$panstyle = ( $panel=="events" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel4' $panstyle>";
	$c .=DisplayPanelEvents($team_id);
	
	$panstyle = ( $panel=="matches" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel6' $panstyle>";
	$c .= DisplayPanelMatches($team, $team_id);

	$panstyle = ( $panel=="messages" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel8' $panstyle>";
	$c .= $messanger->ViewMessageMenu()."</div>";
		
	if($iscaptain){
		$panstyle = ( $panel=="quit" ) ? '' : 'style="display:none"';
		$c .= "<div class='panel' id='panel7' $panstyle>
		".DisplayPanelQuit($cookieteamid);
				
	}
	return DispFunc::X1PluginOutput($c);
}

/*################################
Function: coreupdateteam
Needs: N/A
Returns:String $output
What does it do:Updates the database for the teams
#################################*/
function coreupdateteam() {
	list ($cookieteamid, $cookieteam) = X1Cookie::CookieRead(X1_cookiename);
	$c  = DispFunc::X1PluginStyle();
	if (!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));	
	}
	
	ModifySql("UPDATE", X1_DB_teams, "SET
	name = ".MakeItemString(DispFunc::X1Clean($_POST['team'])).",
	mail = ".MakeItemString(DispFunc::X1Clean($_POST['mail'])).",
	aim = ".MakeItemString(DispFunc::X1Clean($_POST['aim'])).",
	icq = ".MakeItemString(DispFunc::X1Clean($_POST['icq'])).",
	msn = ".MakeItemString(DispFunc::X1Clean($_POST['msn'])).",
	xfire = ".MakeItemString(DispFunc::X1Clean($_POST['xfire'])).",
	yim = ".MakeItemString(DispFunc::X1Clean($_POST['yim'])).",
	playerone2 = ".MakeItemString(DispFunc::X1Clean($_POST['playerone2'])).",
	clantags = ".MakeItemString(DispFunc::X1Clean($_POST['clantags'])).",
	clanlogo = ".MakeItemString(DispFunc::X1Clean($_POST['clanlogo'])).",
	website = ".MakeItemString(DispFunc::X1Clean($_POST['homepage'])).",
	ircserver = ".MakeItemString(DispFunc::X1Clean($_POST['ircserver'])).",
	ircchannel=".MakeItemString(DispFunc::X1Clean($_POST['ircchannel'])).",
	joinpassword = ".MakeItemString(DispFunc::X1Clean($_POST['joinpassword'])).",
	recruiting = ".MakeItemString(DispFunc::X1Clean($_POST['recruiting'])).",
	country = ".MakeItemString(DispFunc::X1Clean($_POST['country']))."
	WHERE team_id = ".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));

	return DispFunc::X1PluginOutput(displayteam('home', XL_teamadmina_teamupdated));
}


/*####################################
Function endteam
Needs:N/A
Returns:N/A
What does it do: Removes the team from the database
####################################*/
function endteam() {
	$tables=array(X1_DB_teamchallenges,X1_DB_messages,X1_DB_teamsevents,X1_DB_teams,X1_DB_teamroster,X1_DB_userinfo);
	$suc_count=$index=$faulty=0;
	$sorted=false;
	
	$c = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	
	$info = X1Cookie::CookieRead(X1_cookiename);
	
	$team_id =(isset($info[0]) ? $info[0]:NULL);
	$team =(isset($info[1]) ? $info[1]:NULL);
	$user_id =(isset($info[2]) ? $info[2]:NULL);
	$other =(isset($info[3]) ? $info[3]:NULL);
	
	$cookie = X1_userdetails();

	$team_info= SqlGetRow("team_id, playerone ",X1_DB_teams," WHERE team_id = ".MakeItemString($team_id));
	if(!$team_info){
		UserLog(XL_failed_retr."(Var:team_info, Table: ".X1_DB_teams.")",$func="endteam", $title="Minor Error", ERROR_DISP);
	}

	if($team_info['playerone'] != $cookie[0] && $team_info['team_id']!=$team_id){
		return DispFunc::X1PluginOutput($c .= XL_teamadmina_captainonly);
	}
	
	//Getting all the users of the team, figuring out who should not be delelted when users are deleted.
	$team_users=SqlGetAll("uid", X1_DB_teamroster, "where team_id=".MakeItemString($team_info['team_id']));
	if(!$team_users){
		UserLog(XL_failed_retr."(Var:team_users Table: ".X1_DB_teamroster.")",$func="endteam", $title="Major Error", ERROR_DIE);
	}
	foreach($team_users as $user){
		$searched_users[]=$user['uid'];
	}
	$search=implode(",",$searched_users);
	unset($team_users);
	$team_users=SqlGetAll("uid", X1_DB_teamroster, "where team_id IN(".$search.") and team_id<>".$team_info['team_id']);
	if($team_users){
		foreach($team_user as $user){
			$searched_other_users[]=$user['uid'];
		}
	}
	else{
		$searched_other_users=array(0);
	}
	$delete_users=array_merge(array_diff($searched_users,$searched_other_users));
	$delete_users=implode(",",$delete_users);
	unset($team_user,$searched_users,$searched_other_users);
	
	//Deleting the team and appropriate users from the database.
	$teamz_id=MakeItemString($team_info['team_id']);
	$select_rand=SqlGetRow("randid", X1_DB_teamchallenges,"WHERE winner=".$teamz_id." OR loser=".$teamz_id);
	if($select_rand){
		$results[$suc_count++]=ModifySql("delete from", X1_DB_teamchallenges, "	WHERE winner=".$teamz_id." OR loser=".$teamz_id);	
		$results[$suc_count++]=ModifySql("delete from". X1_DB_messages, "where steam_id=".$teamz_id." or rteam_id=".$teamz_id);
	}
	else{
		for($keep_up=0; $keep_up<2; $keep_up++){
			$results[$suc_count++]=1;
		}
	}
	$results[$suc_count++]=ModifySql("delete from", X1_DB_teamsevents, " WHERE team_id=".$teamz_id);
	//$results[$suc_count++]=ModifySql("delete from", X1_DB_teamtempchallenges, "	WHERE winner=".MakeItemString($teaminfo['team_id'])." OR loser=".MakeItemString($team_info['team_id']));
	$results[$suc_count++]=ModifySql("delete from", X1_DB_teams , " WHERE team_id=".$teamz_id);
	$results[$suc_count++]=ModifySql("delete from", X1_DB_teamroster, "	WHERE team_id=".$teamz_id);
	if(isset($delete_users)){
		$results[$suc_count++]=ModifySql("delete from", X1_DB_userinfo, "	WHERE uid IN(".$delete_users.")");
	}
	
	
	for($i=0;$i<$suc_count;$i++){
		if(!$results[$i]){
			if($i!=1 || $i!=2){
				UserLog("Failed database delete, A Team was unable to be completely removed from the database(Table:".$tables[$i].", Team:$team_id)", $func="endteam", $title="Error", ERROR_DISP);
			}
			$faulty=1;
		}
	}
	if(!$faulty){
		$c .= DispFunc::X1PluginTitle(XL_teamadmina_teamremoved);
	}
	
	X1Cookie::RemoveCookie(X1_cookiename);
	$c .= "<meta http-equiv='refresh' content='3;URL=".X1_logoutpage."'>";
	return DispFunc::X1PluginOutput($c);
}

/*###################################
Function: removemember
Needs:N/A
Returns:
What does it do:Removes a member from the team.
####################################*/
function removemember() {
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	list($user_id, $user_name)=X1_userdetails();
	if($user_name==$_POST['member']){
		return DispFunc::X1PluginOutput(X1_teamuser_removself);
	}
	list ($cookieteamid, $team, $id, $cocap) = X1Cookie::CookieRead(X1_cookiename);
	if($cocap=="cocap" && $_POST['precocap']==1){
		return Dispfunc::X1PluginOutput(X1_cocap_nono);
	}
	if($cocap=="cocap" && $_POST['captain']=="1"){
		return Dispfunc::X1PluginOutput(X1_cocap_nono);
	}
	ModifySql("delete from", X1_DB_teamroster, " WHERE uid=".MakeItemString(DispFunc::X1Clean($_POST['member']))." AND team_id=".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));
	$other_teams=GetTotalCountOf("uid", X1_DB_teamroster, "WHERE uid=".MakeItemString(DispFunc::X1Clean($_POST['member'])));
	if($other_teams<=0){
		ModifySql("delete from", X1_DB_userinfo, " WHERE uid=".MakeItemString(DispFunc::X1Clean($_POST['member'])));
	}
	
	
	$c .= displayteam("roster", XL_teamadmina_memberremoved);
	return DispFunc::X1PluginOutput($c);
}

/*###################################
Function:updatemember
Needs:N/A
Returns:N/A
What does it do:Updates the information of the members
######################################*/
function updatemember() {
	$extra1=$extra2=$extra3='';
	
	$user_info=X1_userdetails();
	$team_info=X1Cookie::CookieRead();
	$c  = DispFunc::X1PluginStyle();
	if (!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= XL_notlogggedin);
	}
	
	switch(X1_extrarosterfields){
		case 3:
			$extra3=DispFunc::X1Clean($_POST['extra3']);
		case 2:
			$extra2=DispFunc::X1Clean($_POST['extra2']);
		case 1:
			$extra1=DispFunc::X1Clean($_POST['extra1']);
			break;
	}

	list($cookieteamid, $team) = X1Cookie::CookieRead(X1_cookiename);

	$cocap = ((isset($_POST['cocaptain'])? true:false) == "checked") ? 1:0;
	
	if($_POST['member']==$user_info[0] && $cocap==1){
		$cocap=0;
	}
	if(isset($team_info[3])){
		if($team_info[3]=="cocap"){
			$cocap=$_POST['precocap'];	
		}
	}

	ModifySql("UPDATE", X1_DB_teamroster, "SET 
	extra1 ='".$extra1."',
	extra2 ='".$extra2."',
	extra3 ='".$extra3."',
	cocaptain ='".DispFunc::X1Clean($cocap)."' 
	WHERE uid=".MakeItemString(DispFunc::X1Clean($_POST['member']))."  
	AND team_id=".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));

	return DispFunc::X1PluginOutput(displayteam("roster", XL_teamadmina_memberupdated));
}

/*#########################
Function mailteam
Needs:N/a
Returns:N/A
What does it do: Sets up the email for a challenge and calls the function to send the email.
###########################*/
function mailteam() {
	$c  = DispFunc::X1PluginStyle();
	if (!X1Cookie::CheckLogin(X1_cookiename)){
		$c .= DispFunc::X1PluginTitle(XL_notlogggedin);
		return DispFunc::X1PluginOutput($c);
	}
	list ($teamid, $teamname) = X1Cookie::CookieRead(X1_cookiename);
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString($_POST['randid']));
	$event = SqlGetRow("title",X1_DB_events,"where sid=".MakeItemString($challenge['ladder_id']));
	$result=SqlGetAll("p_mail",X1_DB_teamroster.",".X1_prefix.X1_DB_userinfo,"WHERE ".X1_prefix.X1_DB_teamroster.".team_id=".MakeItemString($teamid)." and ".X1_prefix.X1_DB_teamroster.".uid =".X1_prefix.X1_DB_userinfo.".uid");
	
	foreach($result AS $row){
		if (X1_emailon){
			$content = array(
				'team1' =>  $challenge['winner'],
				'team2' =>  $challenge['loser'],
				'date' => date(X1_extendeddateformat, $challenge['matchdate']),
				'event' => $event['title']
				);
			$c .= X1Misc::X1PluginEmail($row["mail"], "teamnotify.tpl", $content);
			$c .= XL_teamadmina_msgsent." $mailname.<br />";
		}
	}
	return DispFunc::X1PluginOutput(displayteam("roster", $c));
}

/*#########################
Function: DisplayPanelHome
Needs: databaseinfo $row
returns: string $output
What it Does: Makes a call to the teams database, getting all the information and allowing it to be displayed in a 
readable and editable manner.
############################*/
function DisplayPanelHome($row){
	 $output ="
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_teamadmintable."' width='100%'>
    <thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_teamadmin_title.":</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamprofile_name.":</td>
			<td class='alt1'><input type='text' name='team' readonly value='$row[name]' size='25' maxlength='25'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamcreate_tags.":</td>
			<td class='alt2'><input type='text' name='clantags' value='$row[clantags]' size='7' maxlength='7'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamadmin_joinpass.":</td>
			<td class='alt2'><input type='password' name='joinpassword' value='$row[joinpassword]' size='25' maxlength='25'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_teamadmin_homepage.":</td>
			<td class='alt1'><input type='text' name='homepage' value='$row[website]' size='25' maxlength='200'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamadmin_logo.":</td>
			<td class='alt2'><input type='text' name='clanlogo' value='$row[clanlogo]' size='25' maxlength='200'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_ateams_ircchannel.":#</td>
			<td class='alt1'><input type='text' name='ircchannel' value='$row[ircchannel]' size='25' maxlength='200'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_ateams_ircserver.":</td>
			<td class='alt2'><input type='text' name='ircserver' value='$row[ircserver]' size='25' maxlength='200'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_teamprofile_captain.":</td>
			<td class='alt1'><input type='text' name='playerone' disabled value='".X1TeamUser::GetUsername($row['playerone'])."' size='25' maxlength='35'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamadmin_mail.":</td>
			<td class='alt2'><input type='text' name='mail' value='$row[mail]' size='25' maxlength='35'></td>
		</tr>
				<tr>
			<td class='alt2'>".XL_ateams_aim.":</td>
			<td class='alt2'><input type='text' name='aim' value='$row[aim]' size='25' maxlength='40'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_ateams_icq.":</td>
			<td class='alt2'><input type='text' name='icq' value='$row[icq]' size='25' maxlength='40'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_ateams_msn.":</td>
			<td class='alt2'><input type='text' name='msn' value='$row[msn]' size='25' maxlength='40'></td>
		</tr>
				<tr>
			<td class='alt2'>".XL_teamadmin_xfire.":</td>
			<td class='alt2'><input type='text' name='xfire' value='$row[xfire]' size='25' maxlength='40'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_ateams_yim.":</td>
			<td class='alt2'><input type='text' name='yim' value='$row[yim]' size='25' maxlength='40'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_teamlist_hcountry.":</td>
			<td class='alt1'><img src='".X1_imgpath."/flags/$row[country].bmp'>".SelectBox_Country('country', $row['country'])."</td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamlist_recruiting.":</td>
			".SelectBoxYesNo('recruiting', $row['recruiting'])."
		</tr>
		<tr>
			<td colspan='2' class='alt1'><strong>".XL_teamprofile_tprofile.":</strong></td>
		</tr>
		<tr>
			<td colspan='2' class='alt2'>
			<textarea name='playerone2' cols='60' rows='15'>$row[playerone2]</textarea>
			</td>
		</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
    		<tr>
    			<th colspan='2' align='center'>
    			<input type='Submit' name='Submit' value='".XL_teamadmin_update."' >
    			</th>
    		</tr>
		</tfoot>
	</table>
	<input name='".X1_actionoperator."' type='hidden' value='coreupdateteam'>
	<input type='hidden' name='team_id'  value='$row[team_id]'>
	</form>
	</div>";
	
	return $output;
}

/*################################
Function: displaypanelteamroster
Needs: databaseinfo $rows
Returns:String $output
What does it do: creates the view for the teamroster for the captain.
#################################*/
function displaypanelteamroster($team_id){
	$rows=SqlGetAll(X1_prefix.X1_DB_teamroster.".*,".X1_prefix.X1_DB_userinfo.".*, ".X1_prefix.X1_DB_userinfo.".uid ", X1_DB_teamroster.",".X1_prefix.X1_DB_userinfo , "WHERE ".X1_prefix.X1_DB_teamroster.".team_id=".MakeItemString($team_id)." and ".X1_prefix.X1_DB_teamroster.".uid=".X1_prefix.X1_DB_userinfo.".uid ORDER BY ".X1_rostersort);
	$captain=SqlGetRow("name, playerone",X1_DB_teams, " Where team_id=".MakeItemString($team_id));
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamprofile_husername."</th>
				<th>".XL_teamprofile_hcontact."</th>
				<th>".XL_teamprofile_hjoindate."</th>";
				
				$output.=displayextrafields(true);

				$output.="<th>".XL_teamprofile_captain."</th>
				<th>".XL_teamadmin_rostermodify."</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>";
			foreach ($rows AS $row){
				$uid = $row["uid"];		
				$joindate = date(X1_dateformat, $row["joindate"]);			
				
				list ($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink) = X1TeamUser::ContactIcons($row);		
				
				$smurfteams = count($row);
				$checked = ($row['cocaptain']) ? "checked": "";
				$cap_status = ($row['uid'] == $captain['playerone']) ? "*":"";
				
				$output .=  "
				<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
				<tr>
					<td class='alt1'>$cap_status<a href='".X1_publicpostfile.X1_linkactionoperator."playerprofile&member=$row[uid]'>$row[gam_name]</a></td>
					<td class='alt2'>$maillink $msnlink $icqlink $aimlink $yimlink $weblink $xfirelink</td>
					<td class='alt1'>$joindate</td>";

						$output.=displayextrafields(false, $row);

					$output .="
					<td class='alt1' align='center'><input name='cocaptain' type='checkbox' value='checked' $checked><input type='hidden' name='precocap' value='$row[cocaptain]'> </td>
					<td class='alt2'>
						<input name='member' type='hidden' value='$row[uid]'>";
					if($row['uid']==$captain['playerone']){
						$output .="<input name='captain' type='hidden' value='1'>";
					}
						$output .="<input name='team_id' type='hidden' value='$team_id'>
						<select name='".X1_actionoperator."'>
						<option value='updatemember'>".XL_teamadmin_rosterupdate."</option>
						<option value='removemember'>".XL_teamadmin_resterremove."</option>
						</select>
						<input type='image' title='submit' src='".X1_imgpath.X1_saveimage."' >
					</td>
				</tr>
				</form>
		</tbody>";	
		}
	$output .= "<tfoot class='".X1plugin_tablefoot."'>
    		<tr>
    			<td colspan='8'>&nbsp;</td>
    		</tr>
		</tfoot>
	</table>
	</div>";
	return $output;
}

/*###############################
Functiion: displayextrafields
Needs: boolean title
Return: string $output
What it does:Deteremines what # of extra fields are required as defined in the config.php.
#################################*/

function displayextrafields($title, $row=""){
	if($title){
		if(X1_extrarosterfields==1){
		return	$output="<th>".X1_extraroster1."</th>";
		 }
		 elseif(X1_extrarosterfields==2){
		return	$output="<th>".X1_extraroster1."</th>
			<th>".X1_extraroster2."</th>";
		}
		elseif(X1_extrarosterfields==3){
			return $output="<th>".X1_extraroster1."</th>
			<th>".X1_extraroster2."</th>
			<th>".X1_extraroster3."</th>";
		}
	}
	else{
		if(X1_extrarosterfields==1){
			return $output="<td class='alt2'><input name='extra1' size='10' type='text' value='$row[extra1]'></td>";
		}
		elseif(X1_extrarosterfields==2){
			return $output="<td class='alt2'><input name='extra1' size='10' type='text' value='$row[extra1]'></td>
			<td class='alt1'><input name='extra2' size='10' type='text' value='$row[extra2]'></td>";
		}
		elseif(X1_extrarosterfields==3){
			return $output="<td class='alt2'><input name='extra1' size='10' type='text' value='$row[extra1]'></td>
			<td class='alt1'><input name='extra2' size='10' type='text' value='$row[extra2]'></td>
			<td class='alt2'><input name='extra3' size='10' type='text' value='$row[extra3]'></td>";
		}
	}
}

/*####################################
Function:displaypanelinvite
Needs: databaseinfo $rows, int $team_id, string $team
Returns:string $output
What does it do:
#####################################*/
function displaypanelinvite($team, $team_id){
	$invites=SqlGetAll(X1_prefix.X1_DB_teaminvites.".*, ".X1_userprefix.X1_DB_userstable.".".X1_DB_usersnamekey.", ".X1_userprefix.X1_DB_userstable.".".X1_DB_usersemailkey,X1_DB_teaminvites.",".X1_userprefix.X1_DB_userstable, "WHERE ".X1_prefix.X1_DB_teaminvites.".team_id=".MakeItemString(DispFunc::X1Clean($team_id))." and ".X1_userprefix.X1_DB_userstable.".".X1_DB_usersidkey."=".X1_prefix.X1_DB_teaminvites.".uid");
	
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamadmin_invname."</th>
				<th>".XL_teamadmin_invcontact."</th>
				<th>".XL_teamadmin_invcancel."</th>
			</tr>
		</thead>
    	<tbody class='".X1plugin_tablebody."'>";
    	
		if($invites){
			foreach($invites As $invite){
				//Does not make sence to get all icons, just the mail icon as we never know who has what IM systems.
				$maillink=X1TeamUser::GetMailIcon($invite[X1_DB_usersemailkey]);
			
				$output .=  "<tr>
						<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
							<td class='alt1'>".$invite[X1_DB_usersnamekey]."</td>
							<td class='alt2'>$maillink</td>
							<td class='alt1'>
								<input type='Submit' name='Submit' value='".XL_teamadmin_invcancelbut."' >
								<input name='".X1_actionoperator."' type='hidden' value='removeinvite'>
								<input name='randid' type='hidden' value='$invite[randid]'>
								<input name='team' type='hidden' value='$team'>
							</td>
						</form>
						</tr>";
			}
		}
		else{
			$output .="<tr>
					<td colspan='3'>".XL_teamadmin_invnone."</td>
				</tr>";	
		}
	$output .= DispFunc::DisplaySpecialFooter($span=3)."
	<table class='".X1plugin_teamadmintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
    	<tr>
            <td>&nbsp;</td>
        </tr>
	</thead>
    <tbody class='".X1plugin_tablebody."'>
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<tr>
		<td>	
		
		".SelectBoxInviteAnyUser()."
		
		<input name='Submit' type='Submit' value='".XL_teamadmin_invuser."' >
		<input name='team_id' type='hidden' value='$team_id'>
		<input name='".X1_actionoperator."' type='hidden' value='sendinvite'>
		</td>
	</tr>
	</form>
	".DispFunc::DisplaySpecialFooter($span=8,$break=false)."
	</div>";
	
	return $output;
}

/*#####################################
Function: DisplayPanelChallenges
Need: String $team, int $team_id, ChallengeMessageSystem $messanger
Return string Output
what does it do:Displays the challange page for the captains.
#####################################*/
function DisplayPanelChallenges($team,$team_id, $messanger){
	$span=4;
	$output = DisplayEventChoice($team_id);
	$output .= DisplayYouAreChallenged($team,$team_id);
	$output .= DisplayYouHaveChallenged($team, $team_id);

	$output .= "<table class='".X1plugin_teamadmintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>";
	
	$rows= SqlGetAll("*",X1_DB_teamchallenges," WHERE (winner = ".MakeItemString(DispFunc::X1Clean($team_id))."  OR loser = ".MakeItemString(DispFunc::X1Clean($team_id)).") and ctemp<>1 ORDER BY date");	
	
	if ($rows){
		foreach ($rows AS $row){

		 $names=X1TeamUser::SetTeamName(array($row['winner'],$row['loser']));
			$event = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString(DispFunc::X1Clean($row['ladder_id'])));
			$output .=  "<tr>
					<th>".XL_matchpreview_challenger."</th>
					<th>".XL_matchpreview_challenged."</th>
					<th>".XL_teamprofile_hevent."</th>
					<th>".XL_teamprofile_hdate."</th>
				</tr>
				</thead>
				<tbody class='".X1plugin_tablebody."'>
				<tr>
					<td><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".$row['loser']."'>".$names[$row['loser']]."</a></td>
					<td><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".$row['winner']."'>".$names[$row['winner']]."</a></td>
					<td><a href='".X1_publicpostfile.X1_linkactionoperator."ladderhome&sid=$event[sid]'>$event[title]</a></td>
					<td>".date(X1_extendeddateformat, $row['matchdate'])."</td>
				</tr>
				</tbody>
				<thead class='".X1plugin_tablehead."'>
				<tr>
					<th class='alt1'>".XL_teamadmin_challmaps."($event[nummaps1])</th>
					<th class='alt1'>".XL_teamadmin_challmaps."($event[nummaps2])</th>
				</tr>
				</thead>
				<tbody class='".X1plugin_tablebody."'>
				<tr>
			<td class='alt1'>
			<textarea name='textarea' cols='15' rows='3' readonly='readonly'>";
			$output .= DisplayMaps($row, 'map1', $event['nummaps1']);
			$output .=  "
			</textarea>
			</td>
			<td class='alt1'>
			<textarea name='textarea' cols='15' rows='3' readonly='readonly'>";
			$output .= DisplayMaps($row, 'map2',$event['nummaps2']);		
			
			if ($row['winner'] == "$team_id") 
			{
				$thewinner = $names[$row['loser']];
				$contacticons = $row['loser'];
			}
			else 
			{
			 	$thewinner = $names[$row['winner']]; 
				$contacticons = $row['winner'];
			}
			
			list($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink, $irclink) = X1TeamUser::TeamContactIcons($contacticons, $old=true);
			$rep_but = ($event['whoreports']=="winner") ? XL_teamadmin_challreportwin : XL_teamadmin_challreportloss;
			//area where cms will be implemented.
			$output .= "</textarea>
					</td>
				</tr>
				<tr>
					<td colspan='$span' class='alt1'><p/>".XL_teamadmin_matchcomments."</td>
				</tr>
				".$messanger->GetAndDisplayTotalNewChallengeMess($row['randid'])."
				<tr>
					<td colspan='$span' class='alt1'><p/><p/>".XL_teamadmin_matchcontact."</td>
				</tr>
				<tr>
					<td colspan='$span' class='alt2'>$maillink $msnlink $icqlink $aimlink $yimlink, $xfirelink $weblink $irclink</td>
				</tr>
				<tr>
				<td class='alt1'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='Submit' type='Submit' value='$rep_but' >
						<input name='".X1_actionoperator."' type='hidden' value='reportform'>
						<input name='randid' type='hidden' value='$row[randid]'>
					</form>
				</td>
				<td class='alt2'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='Submit' type='Submit' value='".XL_teamadmin_challreportdraw."' >
						<input name='".X1_actionoperator."' type='hidden' value='reportform'>
						<input name='draw' type='hidden' value='1'>
						<input name='randid' type='hidden' value='$row[randid]'>
					</form>
				</td>";
				if (X1_emailon){
					$output .= "<td class='alt1'>
						<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
							<input name='Submit' type='Submit' value='".XL_teamadmin_challnotify."' >
							<input name='".X1_actionoperator."' type='hidden' value='mailteammatch'>
							<input name='randid' type='hidden' value='$row[randid]'>
						</form>
					</td>";
				}
				$output .="<td class='alt2'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='Submit' type='Submit' value='".XL_teamadmin_challdispute."' >
						<input name='".X1_actionoperator."' type='hidden' value='disputeform'>
						<input name='randid' type='hidden' value='$row[randid]'>
					</form>
				</td>
				</tr>";	
		}
	}
	else
	{
		$output .="
			<tr>
				<th colspan='$span'>".XL_teamadmin_nosetmatches."</td>
			</tr>";	
	}
	$output .= DispFunc::DisplaySpecialFooter($span,$break=false);
    $output .="</div>";
    return $output;
}

/*#################################
Function: DisplayEventChoice
Need: N/A
return: String $output
What does it do: Displays the area and Box for picking event you to challenge on.
#################################*/
function DisplayEventChoice($team_id){
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    	        <td>".XL_teamadmin_challnew."</td>
			</tr>
		</thead>
    	<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td>
					<form method='post' action='".X1_publicpostfile.X1_linkactionoperator."challengeteamform' style='".X1_formstyle."'>
						".SelectBox_JoinedLadderDrop($team_id)."
						<input type='Submit' name='Submit' value='".XL_teamadmin_challnew."' >
						<input name='".X1_actionoperator."' type='hidden' value='challengeteamform'>
					</form>
				</td>
			</tr>
			".DispFunc::DisplaySpecialFooter($span=3);
	return $output;
}

/*#########################
Function: DisplayYouAreChallenged
Need: string $team
Return: string $output
what does it do: Displays teams that have challenged you.
#########################*/
function DisplayYouAreChallenged($team, $team_id){
	$span =5;
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_matchpreview_challenger."</th>
				<th>".XL_teamprofile_hcontact."</th>
				<th>".XL_teamprofile_hevent."</th>
				<th>".XL_teamprofile_hdate."</th>
				<th>".XL_teamadmin_challconfirm."</th>
			</tr>
		</thead>
    	<tbody class='".X1plugin_tablebody."'>";
			$rows=SqlGetAll("*",X1_DB_teamchallenges," WHERE winner=".MakeItemString(DispFunc::X1Clean($team_id))." and ctemp=1 ORDER BY date");	
		if($rows){
			foreach($rows AS $row){
			 	$names=X1TeamUser::SetTeamName(array($row['loser']));
				list ($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink, $irclink) = X1TeamUser::TeamContactIcons($row['loser'], $old=true);
				
				$event = SqlGetRow("title",X1_DB_events,"where sid=".MakeItemString(DispFunc::X1Clean($row['ladder_id'])));
				
				$output .=  "<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
				<tr>
					<td class='alt1'>".$names[$row['loser']]."</td>
					<td class='alt2'>$maillink $msnlink $icqlink $aimlink $yimlink $xfirelink $weblink $irclink</td>
					<td class='alt1'>$event[title]</td>
					<td class='alt2'>".date(X1_dateformat, $row['date'])."</td>
					<td class='alt1'>
						<input type='hidden' name='randid' value='$row[randid]'>
						<input name='".X1_actionoperator."' type='hidden' value='confirmchallform'>
						<input type='submit' name='submit' value='".XL_teamadmin_challconfirm."' >
					</td>
				</tr>
				</form>";
			}
		}
		else{
			$output .="
				<tr>
					<td colspan='$span'>".XL_teamadmin_challnone."</td>
				</tr>";	
		}
		
		$output .=DispFunc::DisplaySpecialFooter($span);
    return $output;
}

/*###########################################
Function: DisplayYouHaveChallenged
Needs:string $team
Return: String $output
What does it do: Displays teams that have been challanged by the user.
#############################################*/

function DisplayYouHaveChallenged($team, $team_id){
		$span =5;
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_matchpreview_challenged."</th>
				<th>".XL_teamprofile_hcontact."</th>
				<th>".XL_teamprofile_hevent."</th>
				<th>".XL_teamprofile_hdate."</th>
				<th>".XL_teamadmin_challstatus."</th>
			</tr>
    	</thead>
    <tbody class='".X1plugin_tablebody."'> ";
	
	$rows = SqlGetAll("*",X1_DB_teamchallenges," WHERE loser = ".MakeItemString(DispFunc::X1Clean($team_id))." and ctemp=1 ORDER BY date");	
	
	if ($rows)
	{
	foreach ($rows As $row){
		$names=X1TeamUser::SetTeamName(array($row['winner']));
		list($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink, $irclink) = X1TeamUser::TeamContactIcons($row["winner"], $old=true);
		
		$event = SqlGetRow("title",X1_DB_events,"where sid=".MakeItemString(DispFunc::X1Clean($row['ladder_id'])));
		
		$output .= "<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
				<tr>
					<td class='alt1'>".$names[$row['winner']]."</td>
					<td class='alt2'>$maillink $msnlink $icqlink $aimlink $yimlink $xfirelink $weblink $irclink</td>
					<td class='alt1'>$event[title]</td>
					<td class='alt2'>".date(X1_dateformat, $row['date'])."</td>
					<td class='alt1'>
						<input type='hidden' name='randid' value='$row[randid]'>
						<input name='".X1_actionoperator."' type='hidden' value='withdrawchall'>
						<input type='submit' name='submit' value='".XL_teamadmin_challwidthdraw."' >
					</td>
				</tr>
				</form>";		
		}
	}
	else
	{
		$output .="
			<tr>
				<td colspan='$span'>".XL_teamadmin_challnone."</td>
			</tr>";	
	}

	$output .= DispFunc::DisplaySpecialFooter($span);
	return $output;
}

/*#####################
Function DisplayMaps
Needs: databaseinfo $row, string $map_Id, int $max_Maps
Returns: string $output
What does it do: Creates the box where the maps to be played are displayed
#######################*/
function DisplayMaps($row,$map_Id,$max_Maps)
{
	$output ='';
	$curmap=0;
	$mapsarray=explode(",",$row[$map_Id]);
	$maps = X1Misc::MapInfo($row[$map_Id]);
	while ($curmap < $max_Maps)
	{
		list($mapname, $mappic, $mapdl) = $maps[$mapsarray[$curmap]];
		$output .=  "$mapname\n";
		
		if(!empty($mapdl)){
			$output.= "Download URL=$mapdl \n" ;
		}
		$output.="--------\n";
		$curmap++;
	}
	return $output;
}

/*##########################
Function: DisplayPanelEvents
Needs: int $team_Id
Returns: string $output
What does it do:Displays the events panel that shows off the events you in and the events your can join.
############################*/
function DisplayPanelEvents($team_id) {
	$span = 14;
	//<th>".XL_teamprofile_hevent."</th>
	//<td class='alt1'><a href='".X1_publicpostfile.X1_linkactionoperator."ladderhome&sid=$lad[ladder_id]'>$lad[ladder_id]</a></td>
	$output = "<table class='".X1plugin_teamadmintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr><th>".XL_teamprofile_hevent."</th>
			<th>".XL_teamprofile_gp."</th>
			<th>".XL_teamprofile_w."</th>
			<th>".XL_teamprofile_l."</th>
			<th>".XL_teamprofile_d."</th>
			<th>".XL_teamprofile_p."</th>
			<th>".XL_teamadmin_eventchl."</th>
			<th>".XL_teamadmin_quit."</th>
		</tr>
	</thead>
    <tbody class='".X1plugin_tablebody."'>";
	$rows = SqlGetAll(X1_prefix.X1_DB_teamsevents.".*, ".X1_prefix.X1_DB_events.".title",X1_DB_teamsevents.", ".X1_prefix.X1_DB_events," WHERE ".X1_prefix.X1_DB_teamsevents.".team_id=".MakeItemString(DispFunc::X1Clean($team_id))." and ".X1_prefix.X1_DB_teamsevents.".ladder_id=".X1_prefix.X1_DB_events.".sid ORDER BY ".X1_prefix.X1_DB_teamsevents.".ladder_id ASC");	
	if ($rows) {		
		foreach ($rows As $lad){
			if($lad['challyesno']=='Yes'){
				$chal=XL_yes;
			}
			else{
				$chal=XL_no;
			}
			$output .= "<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
			<tr>
				<td class='alt2'>
					<a href='".X1_publicpostfile.X1_linkactionoperator."ladderhome&sid=$lad[ladder_id]'>$lad[title]</a>
				</td>
				<td class='alt2'>$lad[games]</td>
				<td class='alt1'>$lad[wins]</td>
				<td class='alt2'>$lad[losses]</td>
				<td class='alt1'>$lad[draws]</td>
				<td class='alt1'>$lad[points]</td>
				<td class='alt1'>$chal</td>
				<td class='alt2'>
					<input name='".X1_actionoperator."' type='hidden' value='quitladder'>
					<input name='ladder_id' type='hidden' value='$lad[ladder_id]'>
					<input name='team_id' type='hidden' value='$team_id'>
					<input type='submit' name='submit' value='".XL_teamadmin_quit."' >
				</td>
			</tr>
		</form>";
		}
	}
	else
	{
		$output .="<tr>
			<td colspan='".$span."'>".XL_teamadmin_eventsnone.".</td>
		</tr>";	
	}
	unset($lad,$rows);
		$rows=SqlGetRow("*",X1_DB_teams,"where team_id=".MakeItemString($team_id));
	$output.="</tbody> 
		<tbody class='".X1plugin_tablebody."'>
		<tr><td><br /></td></tr>
		<tr>
			<td colspan=".$span.">".XL_teamprofile_totaldetails."</td>
		</tr>
		<thead class='".X1plugin_tablehead."'>
			<tr> 
				<th>".XL_playerprofile_team."</th>
				<th>".XL_teamprofile_tgp."</th>
				<th>".XL_teamprofile_tw."</th>
				<th>".XL_teamprofile_tl."</th>
				<th>".XL_teamprofile_td."</th>
				<th>".XL_teamprofile_tp."</th>
			</tr>
		</thead>
		<tr> 
		<td class='alt1'><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=$rows[team_id]'>
			$rows[name]</a></td>
			<td class='alt1'>$rows[totalgames]</td>
			<td class='alt2'>$rows[totalwins]</td>
			<td class='alt1'>$rows[totallosses]</td>
			<td class='alt1'>$rows[totaldraws]</td>
			<td class='alt1'>$rows[totalpoints]</td>
		</tr>";
	
	$output .= DispFunc::DisplaySpecialFooter($span=14, $break=false)."
	
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>";	
		$output .= SelectBox_LadderDropUser('ladder');	
		$output .= "
		<input name='Submit' type='Submit' value='".XL_teamadmin_eventsjoin."' >
		<input name='".X1_actionoperator."' type='hidden' value='joinladderpre'>
	</form>
	</div>";
	return $output;
}

/*###################################
Function: DisplayPanelMatches
Needs: string $team
Returns: string $output
What does it do: Displays the match history of the team.
####################################*/
function DisplayPanelMatches($team, $team_id){
	$span =6;
	$output ="<table class='".X1plugin_teamadmintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th>".XL_teamprofile_hid."</th>
			<th>".XL_teamprofile_hevent."</th>
			<th>".XL_teamprofile_hwinner."</th>
			<th>".XL_teamprofile_hloser."</th>
			<th>".XL_teamprofile_hdate."</th>
			<th>".XL_teamprofile_hdetails."</th>
		</tr>
	</thead>
    <tbody class='".X1plugin_tablebody."'>";	
    //Looking to optimize with a join.
	$teamhistory = SqlGetAll("*",X1_DB_teamhistory," WHERE winner_id=".MakeItemString($team_id)." OR loser_id=".($team_id)."  ORDER BY game_id DESC");
	if ($teamhistory){
		foreach($teamhistory AS $row){
			$event = SqlGetRow("*",X1_DB_events,"WHERE sid=".MakeItemString(DispFunc::X1Clean($row['laddername'])));
			$output .= "
			<tr>
				<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
					<td class='alt1'>$row[game_id]</td>
					<td class='alt2'>$event[title]</td>
					<td class='alt1'>$row[winner]</td>
					<td class='alt2'>$row[loser]</td>
					<td class='alt1'>".date(X1_dateformat, $row['date'])."</td>
					<td class='alt2'>
						<input name='".X1_actionoperator."' type='hidden' value='matchdetails'>
						<input name='game_id' type='hidden' value='$row[game_id]'>
						<input type='Submit' name='Submit' value='".XL_teamprofile_hdetails."' >
					</td>
				</form>
			</tr>";		
		}
	}
	else
	{
		$output .="
			<tr>
				<td colspan='$span'>".XL_teamadmin_matchesnone."</td>
			</tr>";	
	}

	$output .= DispFunc::DisplaySpecialFooter($span);
	$output .="</div>";
	return $output;
}

/*########################################
Function:DisplayPanelQuit
Needs: string $cookieteamid
Returns: String $output
What does it doe: Displayes the panel for the quit area.
#########################################*/
function DisplayPanelQuit($cookieteamid)
{
	$output = "<table class='".X1plugin_teamadmintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th>".XL_teamadmin_removeteam."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td align='center' valign='middle'>".XL_teamadmin_removeteamwarming."
				<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
					<input type='Submit' name='Submit' value='".XL_teamadmin_removeteambut."'>
					<input name='".X1_actionoperator."' type='hidden' value='endteam'>
				</form>
			</td>
		</tr>
	</tbody>
	<tfoot class='".X1plugin_tablefoot."'>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</tfoot>
	</table>
	<br/>
	<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamadmin_transferteam."</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td align='center' valign='middle'>".XL_teamadmin_transferteamwarming."
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						'".SelectBoxTeamTransfer($cookieteamid)."'						
						<input type='Submit' name='Submit' value='".XL_teamadmin_transferteambut."'>
						<input name='".X1_actionoperator."' type='hidden' value='transferteam'>
					</form>
				</td>
			</tr>
			".DispFunc::DisplaySpecialFooter($span=3, $break=false)."
		</div>";
	return $output;
}

/*#########################################
Function: X1TransferLeadership
Needs:N/A
Retruns:N/A
what Does it do:Function to give another user captain status.
# Old admin will no longer be captain.
#########################################*/
function X1TransferLeadership(){
	#Style
	$c  = DispFunc::X1PluginStyle();
	#Check Login
	if(!X1Cookie::CheckLogin(X1_cookiename)){
	 	return DispFunc::X1PluginOutput(DispFunc::X1PluginTitle($c .=XL_notlogggedin));
	}
	#Cookie Information
	list ($team_id, $team) = X1Cookie::CookieRead(X1_cookiename);
	#Lookup Team
	$team_info = SqlGetRow("playerone",X1_DB_teams, "WHERE team_id=".MakeItemString(DispFunc::X1Clean($team_id)));
	#Exit if No Team Found
	if(!$team_info){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_cantloadteam));
	}
	#User Information
	$user = X1_userdetails();
	#If Captian is the logged in user, allow transfer
	if($team_info['playerone'] == $user[0]){
		#Check if the user is a member on the roster
		$ex = SqlGetRow("uid, p_mail",X1_DB_teamroster." join ".X1_prefix.X1_userinfo." using(uid)","WHERE uid=".MakeItemString(DispFunc::X1Clean($_POST['user_id']))." AND team_id=".MakeItemString(DispFunc::X1Clean($team_id)));
		if($ex){
			#Update team record with selected user
			$uid=MakeItemString($ex['uid']);
			
			$row[0] = ModifySql("UPDATE ",X1_DB_teams," SET playerone =".$uid.",	mail =".MakeItemString($ex['p_mail'])." WHERE team_id=".MakeItemString(DispFunc::X1Clean($team_id)));
			$row[1] = ModifySql("Update ", X1_DB_teamroster,"Set cocaptain='0' where uid=".$uid);
			X1Cookie::RemoveCookie(X1_cookiename);

			if(!$row[0] && !$row[1]){
				if(!$row[0]){
					$log="(Table:".X1_DB_teams.")";
				}
				else{
					$log="(Table:".X1_DB_teamroster.")";
				}
				UserLog("Failed to transfer Captain $log",$function="X1TransferLeadership", $title="Major Error", ERROR_DISP);
			}
			else{
				$c .= DispFunc::X1PluginTitle(XL_leadership_transfered);
			}
			return DispFunc::X1PluginOutput($c);
		}
		else{
			#Output Failure
			$c .= DispFunc::X1PluginTitle(XL_leadership_notonroster);
			return DispFunc::X1PluginOutput($c);
		}
	}
	else{
		#Not Captain, Exit
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notcaptian));
	}
}

?>
