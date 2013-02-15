<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function jointeamform() {
	$c  = DispFunc::X1PluginStyle();
	$cookie = X1_userdetails();
  if(JoinedTeamLimit($cookie[0], $total_teams)){
    return;
  }
	if (!isset($cookie[1])){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamjoin_login));
	}
	$row = SqlGetRowPre(X1_DB_usersidkey.",".X1_DB_usersnamekey ,X1_userprefix.X1_DB_userstable," WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));
	if (!$row){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamjoin_login));
	}
	$c .= DispFunc::X1PluginTitle(XL_teamjoin_title)."
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_jointeamtable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
    			<tr>
    				<th colspan='2'>".XL_teamjoin_header.":</th>
    			</tr>
   			</thead>
    	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamprofile_husername."</td>
			<td class='alt1'><input name='member' type='text' disabled value='$cookie[1]'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_select_team.":</td>
			<td class='alt2'>". SelectBox_TeamDrop("team_id")."</td>
		</tr>
		<tr> 
			<td class='alt1'>".XL_teamjoin_password.":</td>
			<td class='alt1'>
				<input name='".X1_actionoperator."' type='hidden' value='jointeam'>
				<input type='password' name='joinpassword'>
			</td>
		</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
    		<tr> 
    			<th colspan='2'><input type='Submit' name='submit' value='".XL_teamjoin_joinbutton."'/></th>
    		</tr>
		</tfoot>
		</table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}

function jointeam($usertojoin=0,$other_infor=0) {
		$c  = DispFunc::X1PluginStyle();
	if($usertojoin==0){//User is joining team on their own.
		$cookie = X1_userdetails();
	  $team_id = DispFunc::X1Clean($_POST["team_id"]);
	  $team_password=DispFunc::X1Clean($_POST['joinpassword']);
	}
	else{//user has accpeted an invite to join a team.
		$inv_team=SqlGetRow("joinpassword",X1_DB_teams," WHERE team_id = ".MakeItemString(DispFunc::X1Clean($other_infor["team_id"])));
		$cookie = array($other_infor['uid']);
	  $team_id = $other_infor["team_id"];
	  $team_password=$inv_team['joinpassword'];
	}
	$row = SqlGetRowPre(X1_DB_usersidkey.",".X1_DB_usersnamekey.",".X1_DB_usersemailkey,X1_userprefix.X1_DB_userstable," WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));
	if (!$row){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamjoin_login));
  }
  if ($cookie[0] != $row[X1_DB_usersidkey]){

		return DispFunc::X1PluginOutput($c .= XL_teamjoin_login);
	}
	
	$result = GetTotalCountOf("team_id",X1_DB_teams," WHERE team_id =".MakeItemString($team_id)." and joinpassword =".MakeItemString($team_password));
	if ($result < 1){
		return DispFunc::X1PluginOutput($c .= XL_teamjoin_none);
	}

	$result2 = GetTotalCountOf("team_id",X1_DB_teamroster," WHERE team_id = ".MakeItemString($team_id)." AND uid = ".MakeItemString($cookie[0]));
	if ($result2 >= 1){
		return DispFunc::X1PluginOutput($c .= XL_teamjoin_dupe);
	}

 if(JoinedTeamLimit($cookie[0], &$total_teams))
 {
   return;
 }
	//$row = SqlGetRow("SELECT * FROM ".X1_userprefix.X1_DB_userstable." WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));
	ModifySql("INSERT INTO", X1_DB_teamroster, "(uid, team_id, joindate)
	VALUES (".MakeItemString($row[X1_DB_usersidkey]).",
	".MakeItemString($team_id).",
	".MakeItemString(time()).")");
	
	if($total_teams<=0){
      ModifySql("INSERT INTO", X1_DB_userinfo, "(uid,  gam_name, p_mail)
	     VALUES (".MakeItemString($row[X1_DB_usersidkey]).",
	     ".MakeItemString($row[X1_DB_usersnamekey]).",
	     ".MakeItemString($row[X1_DB_usersemailkey]).")");
	}

 $team_name = SqlGetRow("name",X1_DB_teams," WHERE team_id =".MakeItemString($team_id));
	$c .= XL_teamjoin_joined.$team_name["name"];
	return DispFunc::X1PluginOutput($c);
}


 /*############################################
	name:JoinedTeamLimit
	what does it do:Checks to see if you've joined too many teams.
	needs:int $user_id  int &$team_count
	returns:boolean true on limit reached, false on not reached.
	###########################################*/
	function JoinedTeamLimit($user_id, &$team_count){
    if(empty($user_id)){
      $team_count=-1;
      Dispfunc::X1PluginOutput(XL_teamcreate_logintocreate);
      return true;
    }
   	$team_count = GetTotalCountOf("uid",X1_DB_teamroster," WHERE uid =".MakeItemString($user_id));
    if ($team_count>= X1_maxjoin){
		   DispFunc::X1PluginOutput($c .= XL_teamjoin_limit);
       return true;
	  }
	   return false;
  }
?>
