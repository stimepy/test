<?php
#####################################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4
#####################################################################
if (!defined('X1plugin_include'))exit();
###############################################################

	/*############################################
	Name: AdminPlayerSearchBox
	What does it do: Creates the search box.
	Params: boolean $moderator=false
	Returns: on success returns a string
	############################################*/	 
function AdminPlayerSearchBox($moderator=false){
	$output = "<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>";
	$output .= "<tr>
			<td class='alt1'>".XL_asearch_player."<input type='text' name='x1_pl_search' value='' size='20' maxlength='255'>".
			AdminModButton($moderator,XL_asearch_search,$action=array("ad_plsearch","mod_ad_plsearch"))."
			</td>
		</tr>
		</form>
		<tr>
				<td class='alt1'>".XL_asearch_infoplayer."</td>
		</tr>";
	return DispFunc::X1PluginOutput($output, 1);
}

	/*############################################
	Name: X1FindPlayer
	What does it do: Gets the information from the search, modifies to be a database search, and searches the database for the name
	Params: boolean $moderator=false
	Returns: On success it will either go to the display of a select box with options of what players matched 
	your search or to the display of the players editable profile.  On failure it will return to the search box
	and inform you nothing was found.
	############################################*/	 
function X1FindPlayer($moderator=false){

	$search_var=str_replace("*","%",DispFunc::X1Clean($_POST['x1_pl_search'],4), $count);
	if($count==0){
		$player=SqlGetRow("*",X1_DB_userinfo,"where gam_name=".MakeItemString($search_var));
		//If no player is found, relay the information and take us back to the appropriate panel
		if(empty($player)){
			return  NoFind(XL_playersearch_noname.$_POST['x1_pl_search'], "teams", $moderator);
		}
		return DisplayEditableProfile($moderator, $player);
	}
	elseif($count>0){
		$players=SqlGetAll("uid,gam_name",X1_DB_userinfo,"where gam_name like".MakeItemString($search_var));
		//If no players is found, relay the information and take us back to the appropriate panel
		if(empty($players)){
			return  NoFind(XL_playersearch_noname.$_POST['x1_pl_search'], "teams", $moderator);
		}
		return PlayerSelectBox($players, $moderator);
	}
	AdminLog($output="Count was not 0 or greater", $function="X1FindPlayer", $title = 'Minor Error', ERROR_RET);
	return  NoFind(XL_error_sys, "teams", $moderator);
}

	/*############################################
	Name: PlayerSekectBox
	What does it do: Creates the players select box from the search results.
	Params: databaseinfo $players, boolean $moderator=false
	Returns: on success outputs the select box, on failure....
	############################################*/	 
function PlayerSelectBox($players, $moderator=false){
		if(empty($players)){//some dataloss happened somewhere, but not sure where, ERROR big time.
			AdminLog($output="Empty players Array", $function="PlayerSelectBox", $title = 'Major Error',ERROR_DIE);
		}
		
		$output = definemodoradminmenu($moderator,"teams")."
		<table class='".X1plugin_admintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th>--".XL_ateams_editplayer."--</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
				<tr>
					<td class='alt2'>".XL_asearch_plselect."</td>
					<td class='alt2'>";
		$output  .= "<select name='player_uid'>";
		$sel = "selected";
		foreach($players As $player) {
			$output .= "<option $sel value='$player[uid]' align='left'>$player[gam_name]</option>";
			$sel = "";
			}
		$output .= "</select> 
					</td>
			</tr>
			<tr>
				<td class=alt1>
					".AdminModButton($moderator,XL_ok,$actions=array("ad_plmodify", "mod_ad_modify"))."
				</td>
			</tr>
			</form>
			".DispFunc::DisplaySpecialFooter($span=8);
					
		return DispFunc::X1PluginOutput($output);
}

/*############################################
	Name: DisplayEditableProfile
	What does it do: Creates and displays the players profile as can be edited.
	Params: boolean $moderator=false
	Returns: on success returns a string
	############################################*/	 
function DisplayEditableProfile($moderator=false, $playerdata=NULL){
	if(!isset($playerdata)){
		if(!empty($_POST['player_uid'])){
			$playerdata = SqlGetRow('*',X1_DB_userinfo,'where uid='.MakeItemString($_POST['player_uid']));
		}
		else{
			AdminLog($output="No player data specificed", $function="DisplayEditableProfile", $title = 'Major Error',ERROR_DIE);
		}
	}
	$real_name=X1TeamUser::GetUserName($playerdata['uid']);
 
	$output = definemodoradminmenu($moderator,"teams");

	$output.= "
	<table class='".X1plugin_admintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
	  		<tr>
	  			<th colspan='2'>".XL_playerprofile_title.":".$real_name."</th>
	  		</tr>
		</thead>
			<tbody class='".X1plugin_tablebody."'>
				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<tr>
						<td class='alt1'>".XL_playerprofile_name."</td>
				    <td class='alt1'><input type='text' name='ingamename' value='$playerdata[gam_name]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class='alt1'>".XL_teamprofile_homepage."</td>
						<td class='alt1'><input type='text' name='website' value='$playerdata[p_website]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class='alt2'>".XL_playerprofile_location."</td>
						<td class='alt2'>".SelectBox_Country("country", $playerdata['p_country'])."</td>
					</tr>
					<tr>
						<td class'alt1'>".XL_teamadmin_mail."
						<td class='alt1'><input type='text' name='email' value='$playerdata[p_mail]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_playerprofile_fmail."
						<td class='alt1'><input type='text' name='femail' value='$playerdata[faux_email]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_playerprofile_usefmail."
						".SelectBoxYesNo("usefmail",$playerdata['use_faux'],"alt1")."
					</tr>
					<tr>
						<td class'alt1'>".XL_ateams_aim."
						<td class='alt1'><input type='text' name='aim' value='$playerdata[p_aim]' size='20' maxlength='40'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_ateams_icq."
						<td class='alt1'><input type='text' name='icq' value='$playerdata[p_icq]' size='20' maxlength='40'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_ateams_msn."
						<td class='alt1'><input type='text' name='msn' value='$playerdata[p_msn]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_teamadmin_xfire."
						<td class='alt1'><input type='text' name='xfire' value='$playerdata[p_xfire]' size='20' maxlength='40'></td>
					</tr>
					<tr>
						<td class'alt1'>".XL_ateams_yim."
						<td class='alt1'><input type='text' name='yim' value='$playerdata[p_yim]' size='20' maxlength='255'></td>
					</tr>
					<tr>
						<td class='alt2'>
						<input type='hidden' name='player_uid' value='$playerdata[uid]'>
							".AdminModButton($moderator, XL_teamadmin_update, $action=array("admin_plyupdate","mod_plyupdate"))."
						</td>
					</tr>
				</form>";
				
				$output .="<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<tr>
						<td class='alt2'>
						<input type='hidden' name='player_uid' value='$playerdata[uid]'>
						".AdminModButton($moderator,XL_aplayer_removeall,$action=array("ad_plytremov_all","mod_plytremov_all"))."
						</td>
					</tr>
					<tr>
						<td class='alt1'>".XL_aplayer_removallnote."</td>
					</tr>
				</form>";
				
			$output.="</tbody>
		</table>".
	 X1GetPlayerTeams($moderator,$playerdata['uid']);
	
	return DispFunc::X1PluginOutput($output);
}
	
/*############################################
	Name: X1GetPlayerTeams
	What does it do: Creates a table of teams, and a delete button for all teams player is NOT a captain of
	Params: boolean $moderator=false, int $uid
	Returns: on success returns a string on failure records error and dies();
	############################################*/	
function X1GetPlayerTeams($moderator=false,$uid){
	if(empty($uid)){
		AdminLog($output="Empty uid", $function="X1GetPlayerTeams", $title = 'Major Error',ERROR_DIE);
	}

	$teaminfo=SqlGetAll(X1_prefix.X1_DB_teamroster.".team_id, ".X1_prefix.X1_DB_teams.".*",X1_DB_teamroster.",".X1_prefix.X1_DB_teams,"where ".X1_prefix.X1_DB_teamroster.".uid=".MakeItemString($uid)." and ".X1_prefix.X1_DB_teams.".team_id=".X1_prefix.X1_DB_teamroster.".team_id");
	if(empty($teaminfo)){
		AdminLog($output="Failed Database data retrieval", $function="X1GetPlayerTeams", $title = 'Sql Error',ERROR_DIE);
	}
		
	$output = DispFunc::X1PluginTitle(XL_aplayer_joined);
	$output .= "
	<table class='".X1plugin_playerprofiletable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr> 
			<th>".XL_teamlist_hcountry."</th>
			<th>".XL_teamlist_hname."</th>
			<th>".XL_playerprofile_tags."</th>
			<th>".XL_teamprofile_captain."</th>
			<th>".XL_aplayer_remove."</th>";

	$output .= "</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>";
		foreach($teaminfo As $team){
			$captain_name=X1TeamUser::GetUserName($team['playerone']);
			
			$output .= "
				<tr> 
					<td class='alt2'>
	    		    <img src='".X1_imgpath."/flags/$team[country].bmp' width='20' height='15' border='0'>$team[country]</td>
					<td class='alt1'><a href='".X1_adminpostfile.X1_linkactionoperator."teamprofile&teamname=$team[team_id]'>$team[name]</a></td>
					<td class='alt2'><a href='".X1_adminpostfile.X1_linkactionoperator."teamprofile&teamname=$team[team_id]'>$team[clantags]</a></td>
					<td class='alt2'><a href='".X1_adminpostfile.X1_linkactionoperator."playerprofile&member=$team[playerone]'>$captain_name</a></td>
					<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<td class='alt2'>".DeletePlayerButton($moderator,$uid,$team['team_id'],$team['playerone'])."</td>
					</form>
				</tr>";
		}
		$output.= DispFunc::DisplaySpecialFooter($span=8);
		return $output;
}


function DeletePlayerButton($moderator=false, $uid=NULL, $team_id=NULL, $captain_id=NULL){
	if(!isset($uid)||!isset($team_id)||!isset($captain_id)){
		if(empty($uid)){
			AdminLog($output="Empty uid", $function="DeletePlayerButton", $title = 'Minor Error', ERROR_RET);
		}
		elseif(empty($team_id)){
			AdminLog($output="Empty team_id", $function="DeletePlayerButton", $title = 'Minor Error', ERROR_RET);
		}
		else{
			AdminLog($output="Empty captain_id", $function="DeletePlayerButton", $title = 'Minor Error', ERROR_RET);
		}
	}
	if($uid!=$captain_id){
		$output="<input type='hidden' name='player_uid' value='$uid' />
		<input type='hidden' name='team_id' value='$team_id' />";
		if(!$moderator){	
			$output .="<input type='hidden' name='".X1_actionoperator."' value='remove_pl_fr_team' />
			<input type='image' title='".XL_aplayer_remove."' src='".X1_imgpath.X1_delimage."'/>";
		}
		else{
			$output .="<input type='hidden' name='".X1_actionoperator."' value='mod_remove_pl_fr_team' />
			<input type='image' title='".XL_aplayer_remove."' src='".X1_imgpath.X1_delimage."'/>";
		}
		return $output;
	}
	else{
		return "";
	}
}

function X1UpdatePlayer($moderator=false){
	$username=DispFunc::X1Clean($_POST['ingamename'],4);
	if (empty($username)){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_aplayer_namblank)); 
		return DisplayEditablePlayer($moderator);
	}
	if(!preg_match("/^[-=\!#$%\(\)\*\+\/:\?@\[\]\\_{}a-z0-9A-Z][a-z0-9A-Z_ ]*[-=\!#$\(\)\*\+\/:\?@\[\]\\_{}]?$/i", $username)){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_invalidfeed));
		return DisplayEditablePlayer($moderator);
	}
	if (!preg_match("/^\b[A-Z0-9._\+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b$/i", $_POST['email'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankemail)); 
		return DisplayEditablePlayer($moderator);
	}
	if ( empty($_POST['country'])){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamcreate_blankcountry)); 
		return DisplayEditablePlayer($moderator);
	}


	$results=ModifySql("update", X1_DB_userinfo,"Set
	  gam_name=".MakeItemString($username).",
	  p_website=".MakeItemString(DispFunc::X1Clean($_POST['website'],4)).",
		p_country=".MakeItemString(DispFunc::X1Clean($_POST['country'],4)).",
		p_mail=".MakeItemString(DispFunc::X1Clean($_POST['email'],4)).",
		faux_email=".MakeItemString(DispFunc::X1Clean($_POST['femail'],4)).",
		use_faux=".MakeItemString($_POST['usefmail']).",
		p_aim=".MakeItemString(DispFunc::X1Clean($_POST['aim'],4)).",
		p_icq=".MakeItemString(DispFunc::X1Clean($_POST['icq'],4)).",
		p_msn=".MakeItemString(DispFunc::X1Clean($_POST['msn'],4)).",
		p_xfire=".MakeItemString(DispFunc::X1Clean($_POST['xfire'],4)).",
		p_yim=".MakeItemString(DispFunc::X1Clean($_POST['yim'],4))."
		where uid=".MakeItemString(DispFunc::X1Clean($_POST['player_uid'])));
	if(!$results){
		AdminLog($output="update not completed for user:$_POST[player_uid]", $function="X1UpdatePlayer", $title = 'Major Error',ERROR_DISP);
	}
	return DispFunc::X1PluginOutput(definemodoradminmenu($moderator,"teams"));
}

function X1DeletePlayer($moderator=false){
	$player_uid=DispFunc::X1Clean($_POST['player_uid']);
	$which_delete=$_REQUEST[X1_actionoperator];
	if($which_delete=="remove_pl_fr_team" || $which_delete=="mod_remove_pl_fr_team"){
		$team_id=DispFunc::X1Clean($_POST['team_id']);
		$results=ModifySql("Delete From",X1_DB_teamroster, " where uid=".MakeItemString($player_uid)." and team_id=".MakeItemString($team_id));
		if(!$results){
			AdminLog($output="Single team removal not completed for user", $function="X1DeletePlayer", $title = 'Major Error',ERROR_DISP);
		}
		$remaining_teams=GetTotalCountOf("uid",X1_DB_teamroster," where uid=".MakeItemString($player_uid));
		if($remaining_teams==0){
			$results=ModifySql("Delete From",X1_DB_userinfo, " where uid=".MakeItemString($player_uid));
			if(!$results){
				AdminLog($output="Singe Team:User's info not removed from ".X1_prefix.X1_DB_userino, $function="X1DeletePlayer", $title = 'Major Error',ERROR_DISP);
			}
		}
	}
	elseif($which_delete=="ad_plytremov_all"||$which_delete=="mod_plytremov_all"){
		$cap_teams=SqlGetAll("team_id",X1_DB_teams,"where playerone=".MakeItemString($_POST['player_uid']));
		if($cap_teams){
			$team_id = array();
			$count=0;
			foreach($cap_teams as $team){
				$team_id[]=$team['team_id'];
				$count++;
			}
			$where_clause="where uid=".MakeItemString($_POST['player_uid'])." and team_id<>";
			for($x=0;$x<$count;$x++){
				$where_clause.= MakeItemString($team_id[$x]);
				if($x!=($count-1)){
					$where_clause.=" and team_id<>";
				}
			}
		}
		else{
			$where_clause="where uid=".MakeItemString($_POST['player_uid']);
			$results=ModifySql("Delete From",X1_DB_userinfo, " where uid=".MakeItemString($_POST['player_uid']));
			if(!$results){
				AdminLog($output="All Team:User's info not removed from ".X1_prefix.X1_DB_userinfo, $function="X1DeletePlayer", $title = 'Major Error',ERROR_DISP);
			}
		}
		$results=ModifySql("Delete From",X1_DB_teamroster, $where_clause);
		if(!$results){
			AdminLog($output="All team removal not completed for user", $function="X1DeletePlayer", $title = 'Major Error',ERROR_DISP);
		}
	}
	else{
		AdminLog($output="No Team removal Took place, single or otherwise.", $function="X1DeletePlayer", $title = 'Major Error',ERROR_DISP);
	}
	return DispFunc::X1PluginOutput(definemodoradminmenu($moderator,"teams"));
}

function NoFind($what, $panel, $moderator=false){
	if(!$moderator){
		if(isset($panel)){
			x1_admin($panel);
		}
	}
	else{
		if(isset($panel)){
			X1_moderator($panel);
		}
	}
	return DispFunc::X1PluginOutput(XL_playersearch_noname.$_POST['x1_pl_search']);
}

?>

		