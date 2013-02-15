<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function createteam() {

	$c  = DispFunc::X1PluginStyle();
	$cookie = X1_userdetails();
	if (empty($cookie[1])){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	$c .= DispFunc::X1PluginTitle(XL_teamcreate_title)."
	<table class='".X1plugin_createteamtable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
    	<tr>
    		<th colspan='2'>".XL_teamcreate_title."</th>
    	</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<tr>
		<td class='alt1'>".XL_teamprofile_name."</td>
		<td class='alt1'><input name='teamname' id='teamname' type='text' value='' size='25'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_teamcreate_tags."</td>
		<td class='alt2'><input name='clantags' id='clantags' type='text' value='' size='25'></td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamcreate_email."</td>
		<td class='alt1'><input name='mail' id='mail' type='text' value='' size='25'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_teamcreate_homepage."</td>
		<td class='alt2'><input name='homepage' id='homepage' type='text' value='http://' size='25'></td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamcreate_jpass1."</td>
		<td class='alt1'><input name='joinpassword'  id='joinpassword' type='password' value='' size='25'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_teamcreate_jpass2."</td>
		<td class='alt2'><input name='joinpassword2' id='joinpassword2' type='password' value='' size='25'></td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamcreate_location."</td>
		<td class='alt1'>".SelectBox_country("country","")."</td>
	</tr>
	</tbody>
	<tfoot class='".X1plugin_tablefoot."'>
	<tr>
		<th colspan='2' align='center'>
			<input name='".X1_actionoperator."' type='hidden' value='newteam'>
			<input name='captain' type='hidden' value='$cookie[0]'>
			<input type='submit' name='submit' value='".XL_teamcreate_newteam."'>
			</form>
		</th>
	</tr>
    </tfoot>
	</table>";
	return DispFunc::X1PluginOutput($c);
}

function newteam() {
	
	$c  = DispFunc::X1PluginStyle();
	$cookie = X1_userdetails();
	$username = $cookie[1];
	$to_userid = $cookie[0];
	
	$samenick = GetTotalCountOf("name",X1_DB_teams," WHERE name=".MakeItemString($_POST['teamname']));
	$maxteam = GetTotalCountOf("playerone",X1_DB_teams," WHERE playerone=".MakeItemString($_POST['captain']));
	if ($maxteam >= X1_maxcreate){
		return DispFunc::X1PluginOutput(DispFunc::X1PluginTitle($c .= XL_teamcreate_toomanyteams));
	}
	if ($samenick >= 1) {
		return DispFunc::X1PluginOutput(DispFunc::X1PluginTitle($c .= XL_teamcreate_dupeteam));
	}

	if (empty($_POST['teamname'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankname)); 
		return createteam();
	}
	if(!preg_match("/^[-=\!#$%\(\)\*\+\/:\?@\[\]\\_{}a-z0-9A-Z][a-z0-9A-Z_ ]*[-=\!#$%\(\)\*\+\/:\?@\[\]\\_{}]?$/i", $_POST['teamname'])){
	 //-!"#$%&'()*+,./:;<=>?@\[\\\]_`{|}~
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_invalidfeed));
		return createteam();
	}
	if ( empty($_POST['joinpassword'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankjpass)); 
		return createteam();
	}
	if (!preg_match("/^\b[A-Z0-9._\+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b$/i", $_POST['mail'])){//empty($_POST['mail'])
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankemail)); 
		return createteam();
	}
	if (empty($_POST['clantags'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blanktags)); 
		return createteam();
	}
	if ( $_POST['joinpassword'] != $_POST['joinpassword2']){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_jpassnomatch));
		return createteam();
	}
	if ( empty($_POST['country'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankcountry)); 
		return createteam();
	}
	$c .= DispFunc::X1PluginTitle(XL_teamcreate_title);
	
	ModifySql("INSERT INTO", X1_DB_teams, "(name, mail, country, playerone, clantags, joinpassword, website) VALUES (".MakeItemString(DispFunc::X1Clean($_POST['teamname'])).",  
		".MakeItemString(DispFunc::X1Clean($_POST['mail'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['country'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['captain'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['clantags'],4)).", 
		".MakeItemString(DispFunc::X1Clean($_POST['joinpassword'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['homepage']))."	)");
		
	$team = SqlGetRow("team_id",X1_DB_teams,"WHERE name=".MakeItemString($_POST['teamname']));
	if(!$team){
		UserLog(XL_failed_team ,$func="newteam", $title="Major Error", ERROR_DIE);
	}
	
	$userinfo = SqlGetRowPre(X1_DB_usersemailkey.", ".X1_DB_usersnamekey.", ".X1_DB_usersidkey,X1_userprefix.X1_DB_userstable," WHERE ".X1_DB_usersidkey."='$to_userid'");
		  
	ModifySql("INSERT INTO",X1_DB_teamroster, "(uid, team_id, joindate) 
		VALUES (".MakeItemString($userinfo[X1_DB_usersidkey]).", 
		".MakeItemString($team['team_id']).", 
		".MakeItemString(time()).")");
		
	$user_listed=SqlGetRow("uid",X1_DB_userinfo," where uid=".$userinfo[X1_DB_usersidkey]);
	
	if(!$user_listed){
		ModifySql("Insert into", X1_DB_userinfo,"(uid, gam_name, p_mail) Values(
		".MakeItemString($userinfo[X1_DB_usersidkey]).",
		".MakeItemString($userinfo[X1_DB_usersnamekey]).",
		".MakeItemString($userinfo[X1_DB_usersemailkey]).")");
	}
		
	if(X1_emailon){
		$content = array(
			'site' =>  X1_sitename,
			'name' => $_POST['teamname'],
			'jpass' => $_POST['joinpassword'],
			'url' => X1_url	);
		$c .= X1Misc::X1PluginEmail($row[X1_DB_usersemailkey], "registration.tpl", $content, XL_teamcreate_created);
	}
	$c .= DispFunc::X1PluginTitle(XL_teamcreate_created);
	
	return DispFunc::X1PluginOutput($c);
}
//Because these are not implemented yet, but maybe in the future...
/*function requestpass() {
	$c  = DispFunc::X1PluginStyle();
	$c .= DispFunc::X1PluginTitle(XL_teamcreate_requestpass)."
	<table class='".X1plugin_mapslist."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
        <tr>
            <td>".XL_teamcreate_enteremail."</td>
        </tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>
	       <tr>
            <td class='alt1'>
        		<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
        		<input name='maddress' type='text' value=''>
            </td>
          </tr>
          </tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td>
					<input name='".X1_actionoperator."' type='hidden' value='forgotpassword'>
					<input type='Submit' name='submit' value='".XL_teamcreate_sendrequest."' >
					</form>
				</td>
            </tr>
        </tfoot>
    </table>";
	return DispFunc::X1PluginOutput($c);
}

function forgotpassword() {
	global $xdb;
	$cookie = X1_userdetails();
	$username = $cookie[1];
	if (empty($username))return DispFunc::X1PluginTitle(XL_teamcreate_emptyuser);
	$c .= DispFunc::X1PluginTitle(XL_teamcreate_reset);
	$result = $xdb->GetAll("select * from ".X1_prefix.X1_DB_teams." 
	WHERE mail=".MakeItemString($_POST['maddress']));
	$num = count($row);
	if (!$result)return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_noteam));
	foreach($result AS $row){
		$randid = X1Misc::X1PluginRandid();
		$randid2 = md5($randid);
		modifysql("UPDATE", X1_DB_teams, "SET passworddb =".MakeItemString($randid2)." 
		WHERE name=".MakeItemString($row['name']));
		if (X1_emailon){
			$content = array(
					'site' =>  X1_sitename,
					'name' => $row['name'],
					'pass' => $randid,
					'ip' => getenv("REMOTE_ADDR")
					);
			$c .= X1Misc::X1PluginEmail($row["mail"], "passreset.tpl", $content);
			$c .= DispFunc::X1PluginTitle(XL_teamcreate_reset);
		}else{
			$c .= DispFunc::X1PluginTitle(XL_teamcreate_emailoff);
		}
	}
	return DispFunc::X1PluginOutput($c);
}*/
?>
