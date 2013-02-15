<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function quitteamform() {
	$c  = DispFunc::X1PluginStyle();
	$cookie = X1_userdetails();
	
	if (!isset($cookie[1])) {
		$c .= DispFunc::X1PluginTitle(XL_teamquit_login);
		return DispFunc::X1PluginOutput($c);
	}
	
	$uid=MakeItemString($cookie[0]);
	$row = SqlGetAll(X1_prefix.X1_DB_teams.".team_id, ".X1_prefix.X1_DB_teams.".name, ".X1_prefix.X1_DB_userinfo.".gam_name" ,X1_DB_teamroster.", ".X1_prefix.X1_DB_teams.",".X1_prefix.X1_DB_userinfo," WHERE ".X1_prefix.X1_DB_teamroster.".uid = ".$uid." and ".X1_prefix.X1_DB_teams.".team_id=".X1_prefix.X1_DB_teamroster.".team_id and ".X1_prefix.X1_DB_userinfo.".uid=".$uid." and ".X1_prefix.X1_DB_teams.".playerone<>".$uid." order by ".X1_prefix.X1_DB_teams.".name");
	//$row = SqlGetRowPre(X1_DB_usersidkey.",".X1_DB_usersnamekey,X1_userprefix.X1_DB_userstable," WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));
	
	if (!$row) {
		$c .= DispFunc::X1PluginTitle(XL_teamquit_login);
		return DispFunc::X1PluginOutput($c);
	}
	$member_name=GetName($row);

	$c .= DispFunc::X1PluginTitle(XL_teamquit_title);
	$c .="
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_quitteamtable."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_teamquit_header."</th>
    		</tr>
		</thead>

		<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamprofile_husername."</td>
			<td class='alt1'><input name='member' type='text' disabled value='$member_name'></td>
		</tr>
		<tr> 
			<td class='alt2'>".XL_select_team."</td>
			<td class='alt2'>";
			$c .= SelectBox_JoinedTeamDrop("team_id", $row);
			$c .= "
			</td>
		</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
		<tr> 
			<td colspan='2' align='center'>
				<input type='Submit' name='submit' value='".XL_teamquit_button."' >
				<input name='".X1_actionoperator."' type='hidden' value='quitteam'> 
			</td>
		</tr>
		</tfoot>
	</table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}

function GetName($info){
	$temp=$info[0];
	$name=$temp['gam_name'];
	return $name;
}

function quitteam() {
	$c  = DispFunc::X1PluginStyle();
	$cookie = X1_userdetails();
	$theuser = $cookie[1];
	
	if (empty($cookie[1])){
		$c .= DispFunc::X1PluginTitle(XL_teamquit_login);
		return DispFunc::X1PluginOutput($c);
	}
	else{
		$result = GetTotalCountOf("uid",X1_DB_teamroster," WHERE team_id = ".MakeItemString($_POST['team_id'])." AND uid = ".MakeItemString($cookie[0])); 
		if ($result < 1){      
			$c .= XL_teamquit_none;
			return DispFunc::X1PluginOutput($c);
		}
		else{
			ModifySql("delete from", X1_DB_teamroster, " WHERE uid=".MakeItemString($cookie[0])." 	AND team_id=".MakeItemString($_POST['team_id']));
			
			$row = SqlGetRow("name",X1_DB_teams," WHERE team_id =".MakeItemString($_POST['team_id']));
			$c .= XL_teamquit_removed." ".$row["name"];
		}
	}
	return DispFunc::X1PluginOutput($c);
}
?>
