<?php
#####################################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2011
##Version 2.6.4
#####################################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function teamsmanager($moderator=false){
	$c = "
		<table class='".X1plugin_admintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th>".XL_ateams_editglobal."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
				<tr>
					<td class='alt1'>".displayTeams($moderator)."</td>
				</tr>
			</tbody>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th>".XL_ateams_editevent."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
				<tr>
					<td class='alt2'>".DisplayLadder($moderator)."</td>
				</tr>
			</tbody>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th>".XL_ateams_editplayer."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
				<tr>
					<td class='alt2'>".AdminPlayerSearchBox($moderator)."</td>
				</tr>
			</tbody>
			<tfoot class='".X1plugin_tablefoot."'>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
    	</table>";
	return DispFunc::X1PluginOutput($c, 1);
}

/*#######################################
Name:DisplayLadder
Needs:Boolean $moderator=falss
Returns: N/A
What does it do:Creates the menu for the team and event selection for team edit and event team edit.
#########################################*/
function DisplayLadder($moderator=false) {
	$c = "<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".XL_achallenges_selectevent;
	$c .= SelectBox_LadderDrop("ladder_id");
	if(!isset($team_id)){
		$team_id="";
	}
	$c .= AdminModButton($moderator,XL_ok,$action=array("displayeventteams","mod_displayeventteams"))."
	</form>";
	return DispFunc::X1PluginOutput($c, 1);
}

function modifyladderTeam($moderator=false) {
	$ladder_id=DispFunc::X1Clean($_POST['ladder_id']);
	$team_id=DispFunc::X1Clean($_POST['team_id']);
    $row = SqlGetRow(X1_prefix.X1_DB_teamsevents.".* , ".X1_prefix.X1_DB_teams.".name, ".X1_prefix.X1_DB_events.".title" ,X1_DB_teamsevents.", ".X1_prefix.X1_DB_teams.", ".X1_prefix.X1_DB_events," WHERE ".X1_prefix.X1_DB_teamsevents.".team_id=".MakeItemString($team_id)." AND ".X1_prefix.X1_DB_teamsevents.".ladder_id=".MakeItemString($ladder_id)." AND ".X1_prefix.X1_DB_teamsevents.".team_id=".X1_prefix.X1_DB_teams.".team_id AND ".X1_prefix.X1_DB_events.".sid=".MakeItemString($ladder_id));
	if(!$row){
		AdminLog($output="Database returned a blank row", $function="modifyladderTeam", $title = 'Major Error',ERROR_DIE);
	}	
	
	if($row['challyesno']==1){
		$challyesno = XL_yes;
	}
	else{
		$challyesno = XL_no;
	}
	
	$c = definemodoradminmenu($moderator,"teams");
	$c .= "<br />";
    $c .= DispFunc::X1PluginTitle(XL_ateams_teamadmin);
	if($row) {
		$c .= XL_ateams_editteam."$row[name]
		<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_admintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
        <tr>
			<td colspan='4'>".XL_ateams_editevent."</td>
        </tr>
        <tbody class='".X1plugin_tablebody."'>
	    <tr>
			<td class='alt1'>".XL_teamprofile_hevent."</td>
			<td class='alt1'><input readonly='text' name='ladder_id' value='$row[ladder_id]' size='30' maxlength='60'></td>
			<td class='alt1'><input type='hidden' name='ladder_id' value='$row[ladder_id]' size='30' maxlength='60'></td>
		</tr>
	    <tr>
			<td class='alt2'>".XL_ateams_id."</td>
			<td class='alt1'><input readonly='text' name='team_id' value='$row[team_id]' size='30' maxlength='60'></td>
		</tr>
	    <tr>
			<td class='alt1'>".XL_teamprofile_name."</td>
			<td class='alt1'><input readonly='text' name='tname' value='$row[name]' size='30' maxlength='60'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_ateams_rung."</td>
			<td><input type='text' name='rung' value='$row[rung]' size='20' maxlength='20'></td>
		</tr>
	    <tr>
			<td class='alt2'>".XL_ateams_games."</td>
			<td class='alt2'><input type='text' name='games' value='$row[games]' size='20' maxlength='20'></td>
		</tr>
	    <tr>
			<td class='alt1'>".XL_teamprofile_w."</td>
			<td class='alt1'><input type='text' name='wins' value='$row[wins]' size='20' maxlength='20'></td>
		</tr>
	    <tr>
			<td class='alt2'>".XL_teamprofile_l."</td>
			<td class='alt2'><input type='text' name='losses' value='$row[losses]' size='20' maxlength='20'></td>
		</tr>
	    <tr>
			<td class='alt1'>".XL_teamprofile_p."</td>
			<td class='alt1'><input type='text' name='points' value='$row[points]' size='25' maxlength='60'></td>
		</tr>
	    <tr>
			<td class='alt2'>".XL_ateams_penalties."</td>
			<td class='alt2'><input type='text' name='penalties' value='$row[penalties]' size='25' maxlength='60'></td>
		</tr>
	    <tr>
			<td class='alt1'>".XL_ateams_swins."</td>
			<td class='alt1'><input type='text' name='streakwins' value='$row[streakwins]' size='25' maxlength='255'></td>
		</tr>
	    <tr>
			<td class='alt2'>".XL_ateams_slosses."</td>
			<td class='alt2'><input type='text' name='streaklosses' value='$row[streaklosses]' size='20' maxlength='20'></td>
		</tr>
	    <tr>
			<td class='alt1'>".XL_ateams_rest."</td>
			".SelectBoxYesNo("rest",0,"alt1")."
			<!--<td class='alt1'><input type='text' name='rest' value='$row[rest]' size='20' maxlength='20'></td>-->
		</tr>
	    <tr>
			<td class='alt2'>".XL_teamadmin_challstatus."</td>
			<td class='alt2'><input readonly='text' name='challenged' value='$row[challenged]' size='25' maxlength='255'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_ateams_challyesno."</td>
			<td class='alt1'><input readonly='text' name='challyesnos' value='$challyesno' size='25' maxlength='255'>
			<input type='hidden' name='challyesno' value='$row[challyesno]'></td>
		</tr>

		<tr>
			<td class='alt1'>".XL_ateams_updatemain."</td>
			".SelectBoxYesNo("updatemain",0,"alt1")."
		</tr>
		
	    </tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td colspan='2'>
					<input type='hidden' name='oldpoints' value='$row[points]' /> 
					<input type='hidden' name='oldgames' value='$row[games]' />
					<input type='hidden' name='oldwins' value='$row[wins]' />
					<input type='hidden' name='oldlosses' value='$row[losses]' />
					".AdminModButton($moderator, XL_save,$action=array("updateladderTeam","mod_updateladderTeam"))."					
            		</td>
            </tr>
        </tfoot>
        </table>
		</form>";
    } 
		else {
			$c .= DispFunc::X1PluginTitle(XL_ateams_none);
    }
	return DispFunc::X1PluginOutput($c);
}

function updateladderTeam($moderator=false) {
		$suc_count=0;
		$wins=DispFunc::X1Clean($_POST['wins']);	
		$losses=DispFunc::X1Clean($_POST['losses']);
		$points=DispFunc::X1Clean($_POST['points']);
		$games=DispFunc::X1Clean($_POST['games']);
    $team_id=MakeItemString(DispFunc::X1Clean($_POST['team_id']));
    
		$results[$suc_count++] = ModifySql("update ",X1_DB_teamsevents," SET
		games=".MakeItemString($games).",
		wins=".MakeItemString($wins).",
		losses=".MakeItemString($losses).",
		points=".MakeItemString($points).",
		penalties=".MakeItemString(DispFunc::X1Clean($_POST['penalties'],4)).",
		streakwins=".MakeItemString(DispFunc::X1Clean($_POST['streakwins'],4)).",
		streaklosses=".MakeItemString(DispFunc::X1Clean($_POST['streaklosses'],4)).",
		challenged=".MakeItemString(DispFunc::X1Clean($_POST['challenged'],4)).",
		rung=".MakeItemString(DispFunc::X1Clean($_POST['rung'],4)).",
		rest=".MakeItemString(DispFunc::X1Clean($_POST['rest'],4))."
		WHERE team_id=".$team_id." AND ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));

	if($_POST['updatemain']=="1"){
	 	
		$totalwins_diff = $_POST['oldwins'] - $wins;
		$totallosses_diff = $_POST['oldlosses'] - $losses;
		$totalpoints_diff = $_POST['oldpoints'] - $points;
		$totalgames_diff = $_POST['oldgames'] - $games;
		
		$results[$suc_count++] = ModifySql("update ",X1_DB_teams," SET 
					totalwins=totalwins-$totalwins_diff,
					totallosses=totallosses-$totallosses_diff,
					totalpoints=totalpoints-$totalpoints_diff,
					totalgames=totalgames-$totalgames_diff
					WHERE team_id=".$team_id);
		
	}
	$c = definemodoradminmenu($moderator,"teams");
	for($i=0;$i<$suc_count;$i++){
		if(!$results[$i]){
			AdminLog($output=X1_failed_updat."Result: $i (Table:".X1_DB_teams.")", $function="updateladderTeam", $title = 'Major Error',ERROR_DIE);
			return DispFunc::X1PluginOutput($c);
		}
	}
	$c .= DispFunc::X1PluginTitle(XL_ateams_teamadmin);
	return DispFunc::X1PluginOutput($c);
}

function displayTeams($moderator=false) {
	$c  = "<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".XL_ateams_editteam;
	$c .= SelectBox_TeamDrop("team_id");
	if(!$moderator){
		$c .= "<select name='".X1_actionoperator."'>
				<option value='modifyTeam'>".XL_edit."</option>\n
				<option value='delTeam'>".XL_delete."</option>
			</select>\n
			<input type='submit' value='".XL_ok."'>
			</form>";
	}
	else{
		$c .= "<select name='".X1_actionoperator."'>
				<option value='mod_modifyTeam'>".XL_edit."</option>\n
				<option value='mod_delTeam'>".XL_delete."</option>
			</select>\n
			<input type='submit' value='".XL_ok."'>
			</form>";
	}
	return DispFunc::X1PluginOutput($c, 1);
}

function X1_removeteam($moderator=false){
	$suc_count=0;
	$team_id=MakeItemString(DispFunc::X1Clean($_POST['team_id']));
	$results[$suc_count++] = ModifySql("DELETE FROM ",X1_DB_teams," WHERE team_id=".$team_id);
	$results[$suc_count++] = ModifySql("DELETE FROM ",X1_DB_teamsevents," WHERE team_id=".$team_id);
	$results[$suc_count++] = ModifySql("Delete From ",X1_DB_teamtempchallenge," Where winner =".$team_id." OR loser =".$team_id);
	$results[$suc_count++] = ModifySql("Delete From ",X1_DB_teamchallenges," Where winner =".$team_id." OR loser =".$team_id);
	$results[$suc_count++] = ModifySql("Delete From ",X1_DB_messages," Where steam_id =".$team_id." OR rteam_id =".$team_id);
	$c = definemodoradminmenu($moderator,"teams");	
	
	for($i=0;$i<$suc_count;$i++){
		if(!$results[$i]){
			AdminLog($output=X1_failed_updat."Result: $i (Table:0:".X1_DB_teams.",1:".X1_DB_teamsevents.",2:".X1_DB_teamtempchallenge.",3:".X1_DB_teamchallenges.",4:".X1_DB_messages.")", $function="X1_removeteam", $title = 'Major Error',ERROR_DISP);
			return DispFunc::X1PluginOutput($c);
		}
	}
    $c .= DispFunc::X1PluginTitle(XL_ateams_removed);
    return DispFunc::X1PluginOutput($c);
}

function X1_removeladderteam($moderator=false){
	$suc_count=0;
	$team_id=MakeItemString(DispFunc::X1Clean($_POST['team_id']));
	$ladder_id=MakeItemString(DispFunc::X1Clean($_POST['ladder_id']));
	
	RemoveLadderTeamMessages($team_id, $ladder_id);
  $results[$suc_count++] = ModifySql("DELETE FROM ",X1_DB_teamsevents," WHERE team_id=".$team_id." AND ladder_id=".$ladder_id);
	$results[$suc_count++] = ModifySql("Delete From ",X1_DB_teamtempchallenge," Where winner =".$team_id."OR loser =".$team_id." and ladder_id =".$ladder_id);
	$results[$suc_count++] = ModifySql("Delete From ",X1_DB_teamchallenges," Where winner =".$team_id." OR loser =".$team_id." and ladder_id =".$ladder_id);

	$c = definemodoradminmenu($moderator,"teams");	
	for($i=0;$i<$suc_count;$i++){
		if(!$results[$i]){
			AdminLog($output=X1_failed_updat."Result: $i (Table:0:".X1_DB_teamsevents.",1:".X1_DB_teamtempchallenge.",2:".X1_DB_teamchallenges.")", $function="X1_removeladderteam", $title = 'Major Error',ERROR_DISP);
			return DispFunc::X1PluginOutput($c);
		}
	}
    $c .= DispFunc::X1PluginTitle(XL_ateams_removed);
    return DispFunc::X1PluginOutput($c);
}

/*#############################
	Name: RemoveLadderTeamMessages
	What does it do: Removes any messages in the system for that team on that ladder
	Params: string $team_id, string $ladder_id
	Returns: writes to the error log on failure and returns to last called function, else just returns to the last called function on success.
	###############################*/	 
function RemoveLadderTeamMessages($team_id,$ladder_id){
	if(empty($team_id)){
	  AdminLog($output="Empty var team_id", $function="RemoveLadderTeamMessages", $title = 'Major Error',ERROR_DIE);
	}
	if(empty($ladder_id)){
		AdminLog($output="Empty var ladder_id", $function="RemoveLadderTeamMessages", $title = 'Major Error',ERROR_DIE);
	}
	
	$randidchal=SqlGetAll("randid",X1_DB_teamtempchallenge," Where winner =".$team_id."OR loser =".$team_id." and ladder_id =".$ladder_id);
	$randidtem=SqlGetAll("randid",X1_DB_teamtempchallenge," Where winner =".$team_id."OR loser =".$team_id." and ladder_id =".$ladder_id);
	if($randidchall||$randidtem){
		if($randidchall){
			$where_clause="where randid=";
			foreach($randidchall as $rand){
				$where_clause.=MakeItemString($rand)." and randid=";
			}
		}
		if($randidtem){
			foreach($randidtem as $rand){
				$where_clause.=MakeItemString($rand)." and randid=";
			}
		}
		//We are getting rid of the last "and randid=" in the string to make this a valid where statment for the sql statement
		$stip_value=strrpos($where_clause, a, -7);
		$where_clause[$strip_value]="x";
		$where_cause=explode("x",$where_clause);
		$where_cause=$where_cause[0];
		
		$results = ModifySql("Delete From ",X1_DB_messages,$where_clause);
		
		if(!results){
			AdminLog($output="Failed a database delete", $function="RemoveLadderTeamMessages", $title = 'Major Error',ERROR_DISP);
		}
	}
	return;
}


function modifyTeam($moderator=false) {
	$c = definemodoradminmenu($moderator,"teams");
                   
    $result = SqlGetRow("*",X1_DB_teams," WHERE team_id=".MakeItemString(DispFunc::X1Clean($_POST['team_id']))." OR name=".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));
    if($result) {
    	$main_captain_name=X1TeamUser::GetUserName($result['playerone']);
		$c .= "<br />
	    <form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    <table class='".X1plugin_admintable."' width='100%'>
	    <thead class='".X1plugin_tablehead."'>
			<tr>
				<th colspan='2'>".XL_ateams_editteam."$result[name]</th>
			</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>
			<tr>
				<td class='alt1'>".XL_ateams_id."</td>
				<td class='alt1'><input readonly='text' name='team_id' value='$result[team_id]'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_teamprofile_name."</td>
				<td class='alt2'><input type='text' name='tname' value='$result[name]'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_teamadmin_mail."</td>
				<td class='alt2'><input type='text' name='mail' value='$result[mail]' size='30' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_ateams_aim."</td>
				<td class='alt2'><input type='text' name='aim' value='$result[aim]' size='20' maxlength='20'></td>
			</tr>			
			<tr>
				<td class='alt2'>".XL_ateams_icq."</td>
				<td class='alt2'><input type='text' name='icq' value='$result[icq]' size='20' maxlength='20'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_ateams_msn."</td>
				<td class='alt2'><input type='text' name='msn' value='$result[msn]' size='20' maxlength='20'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_teamadmin_xfire."</td>
				<td class='alt2'><input type='text' name='xfire' value='$result[xfire]' size='20' maxlength='20'></td>
			</tr>	
			<tr>
				<td class='alt2'>".XL_ateams_yim."</td>
				<td class='alt2'><input type='text' name='yim' value='$result[yim]' size='20' maxlength='20'></td>
			</tr>			
			<tr>
				<td class='alt1'>".XL_teamlist_hcountry."</td>
				<td class='alt1'>".SelectBox_Country("country", $result['country'])."</td>
			</tr>			
			<tr>
				<td class='alt2'>".XL_teamprofile_tw."</td>
				<td class='alt2'><input type='text' name='totalwins' value='$result[totalwins]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_teamprofile_tl."</td>
				<td class='alt1'><input type='text' name='totallosses' value='$result[totallosses]' size='25' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_teamprofile_tp."</td>
				<td class='alt2'><input type='text' name='totalpoints' value='$result[totalpoints]' size='20' maxlength='20'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_ateams_tgames."</td>
				<td class='alt1'><input type='text' name='totalgames' value='$result[totalgames]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_ateams_captain."</td>
			 	<td class='alt1'><input type='text' name='captain_name' value='$main_captain_name' size='25' maxlength='60'> 
				<input type='hidden' name='playerone' value='$result[playerone]' size='25' maxlength='60'></td>			 	
			</tr>
			<tr>
				<td class='alt2'>".XL_teamprofile_tprofile."</td>
			 	<td class='alt2'><input type='text' name='playerone2' value='$result[playerone2]' size='25' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_ateams_clantags."</td>
			 	<td class='alt1'><input type='text' name='clantags' value='$result[clantags]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_teamprofile_homepage."</td>
				<td class='alt1'><input type='text' name='homepage' value='$result[website]' size='20' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_ateams_logo."</td>
				<td class='alt2'><input type='text' name='clanlogo' value='$result[clanlogo]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_ateams_ircserver."</td>
				<td class='alt1'><input type='text' name='ircserver' value='$result[ircserver]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_ateams_ircchannel."l</td>
				<td class='alt2'><input type='text' name='ircchannel' value='$result[ircchannel]' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_teamadmin_joinpass."</td>
				<td class='alt1'><input type='password' name='joinpassword' value='password' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt1'>Update the Join Password</td>
				".SelectBoxYesNo("updatejoinpass",0,"alt1")."
			</tr>
	    </tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='2'>
            ".AdminModButton($moderator,XL_save,$action=array("adminupdateteam","mod_updateteam"))."
    				</td>
        </tr>
    </tfoot>
    </table>
	</form>";
    } 
	else {
		AdminLog($output="Error Retrieving team info with team ID:".$_POST['team_id'], modifyTeam, "Major Error", ERROR_RET);
		$c .= DispFunc::X1PluginTitle(XL_achallenges_databaseopps." Error Retrieving team with ID:".$_POST['team_id']);
  }
	return DispFunc::X1PluginOutput($c);
}

function adminupdateteam($moderator=false) {

	if($_POST['captain_name']==X1TeamUser::GetUserName($_POST['playerone'])){
		$captain_id=$_POST['playerone'];
	}
	else{
		$captain_id==X1TeamUser::GetUserId($_POST['captain_name']);
	}
	
	if(!$_POST['updatejoinpass']){
	    $results = ModifySql("update ",X1_DB_teams," SET 
					name=".MakeItemString(DispFunc::X1Clean($_POST['tname'])).",
					mail=".MakeItemString(DispFunc::X1Clean($_POST['mail'])).",
					aim=".MakeItemString(DispFunc::X1Clean($_POST['aim'])).",
					icq=".MakeItemString(DispFunc::X1Clean($_POST['icq'])).",
					msn=".MakeItemString(DispFunc::X1Clean($_POST['msn'])).",
					xfire=".MakeItemString(DispFunc::X1Clean($_POST['xfire'])).",
					yim=".MakeItemString(DispFunc::X1Clean($_POST['yim'])).",
					country=".MakeItemString(DispFunc::X1Clean($_POST['country'])).",
					totalwins=".MakeItemString(DispFunc::X1Clean($_POST['totalwins'])).",
					totallosses=".MakeItemString(DispFunc::X1Clean($_POST['totallosses'])).",
					totalpoints=".MakeItemString(DispFunc::X1Clean($_POST['totalpoints'])).",
					totalgames=".MakeItemString(DispFunc::X1Clean($_POST['totalgames'])).",
					playerone=".MakeItemString(DispFunc::X1Clean($captain_id)).",
					playerone2=".MakeItemString(DispFunc::X1Clean($_POST['playerone2'])).",
					clantags=".MakeItemString(DispFunc::X1Clean($_POST['clantags'])).",
					website=".MakeItemString(DispFunc::X1Clean($_POST['homepage'])).",
					clanlogo=".MakeItemString(DispFunc::X1Clean($_POST['clanlogo'])).",
					ircserver=".MakeItemString(DispFunc::X1Clean($_POST['ircserver'])).",
					ircchannel=".MakeItemString(DispFunc::X1Clean($_POST['ircchannel']))." 
					WHERE team_id=".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));
	}
	else{
    	$results = ModifySql("update ",X1_DB_teams," SET
				name=".MakeItemString(DispFunc::X1Clean($_POST['tname'])).",
				mail=".MakeItemString(DispFunc::X1Clean($_POST['mail'])).",
				aim=".MakeItemString(DispFunc::X1Clean($_POST['aim'])).",
				icq=".MakeItemString(DispFunc::X1Clean($_POST['icq'])).",
				msn=".MakeItemString(DispFunc::X1Clean($_POST['msn'])).",
				xfire=".MakeItemString(DispFunc::X1Clean($_POST['xfire'])).",
				yim=".MakeItemString(DispFunc::X1Clean($_POST['yim'])).",
				country=".MakeItemString(DispFunc::X1Clean($_POST['country'])).",
				totalwins=".MakeItemString(DispFunc::X1Clean($_POST['totalwins'])).",
				totallosses=".MakeItemString(DispFunc::X1Clean($_POST['totallosses'])).",
				totalpoints=".MakeItemString(DispFunc::X1Clean($_POST['totalpoints'])).",
				totalgames=".MakeItemString(DispFunc::X1Clean($_POST['totalgames'])).",
				playerone=".MakeItemString(DispFunc::X1Clean($captain_id)).",
				playerone2=".MakeItemString(DispFunc::X1Clean($_POST['playerone2'])).",
				clantags=".MakeItemString(DispFunc::X1Clean($_POST['clantags'])).",
				website=".MakeItemString(DispFunc::X1Clean($_POST['homepage'])).",
				clanlogo=".MakeItemString(DispFunc::X1Clean($_POST['clanlogo'])).",
				ircserver=".MakeItemString(DispFunc::X1Clean($_POST['ircserver'])).",
				ircchannel=".MakeItemString(DispFunc::X1Clean($_POST['ircchannel'])).",
				joinpassword=".MakeItemString(DispFunc::X1Clean($_POST['joinpassword']))." 
				WHERE team_id=".MakeItemString(DispFunc::X1Clean($_POST['team_id'])));		
	}
	
	$c = definemodoradminmenu($moderator,"teams");	
	if(!$results){
		AdminLog("Failed Database update for teams", "adminupdateteams","Major Error",ERROR_DISP);
		return DispFunc::X1PluginOutput($c);
	}
	
	$c .= DispFunc::X1PluginTitle(XL_ateams_teamupdated);
	return DispFunc::X1PluginOutput($c);
}

/*#############################################
Name:DisplayTeamFromEvent
Needs:Boolean $moderator=false
Returns:N/A
What does it do:Once a event is selected it gets the teams for that event and selects them.
#############################################*/
function DisplayTeamFromEvent($moderator=false){
	$ladder_id=DispFunc::X1Clean($_POST['ladder_id']);
	$ladder=SqlGetRow("title", X1_DB_events,"Where sid=".MakeItemString($ladder_id));
	if(!$ladder){
		AdminLog("Failed database retrieval of data.", "DisplayTeamFromEvent", "Major Error",ERROR_DIE);
	}
	
	$c = definemodoradminmenu($moderator,"teams");
	$c .= "
		<table class='".X1plugin_admintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th>".XL_ateams_editevent."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
				<tr>
					<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".XL_teamprofile_name." From ".$ladder['title']."::
						".SelectBox_LadderTeamDrop("team_id", $ladder_id)."
						<input type='hidden'  name='ladder_id' value='$ladder_id'>";
						
						if(!$moderator){
							$c .= "<select name='".X1_actionoperator."'>
								<option value='modifyladderTeam'>".XL_edit."</option>\n
								<option value='delladderTeam'>".XL_delete."</option>\n
						</select>\n
						<input type='submit' value='".XL_ok."'>
					</form>";
						
						}
					else{
						$c .= "
						<select name='".X1_actionoperator."'>
							<option value='mod_modifyladderTeam'>".XL_edit."</option>\n
							<option value='mod_delladderTeam'>".XL_delete."</option>\n
						</select>\n
						<input type='submit' value='".XL_ok."'>
					</form>";
					}
			
				$c .= "</tr>
			</tbody>
			<tfoot class='".X1plugin_tablefoot."'>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
    	</table>";
	return DispFunc::X1PluginOutput($c);	
}


?>