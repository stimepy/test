<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2009 (2.6.0)
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

/*
Class: X1TeamUser
Description: Creates, sets, retrieves anything todo with cookies.
*/
class X1TeamUser{
	/*############################################
	name:X1SetLogin
	what does it do: Logs in the team based on user (whom it is assumed is logged in already!)
	needs:int $team_id: The teams ID
	returns:true if you login, false otherwise
	###########################################*/  //x1_setlogin
	function X1SetLogin($team_id=0){
		$cookie = X1_userdetails();
		$row = SqlGetRow("playerone, team_id, name",X1_DB_teams," WHERE team_id =".MakeItemString($team_id));
		$rows = SqlGetAll("uid",X1_DB_teamroster," WHERE team_id =".MakeItemString($team_id)." AND cocaptain=1");
		$cocaps=array();
		if($rows){
			foreach($rows AS $cocap){
				$cocaps[] = $cocap['uid'];
			}
		 }
		if(strtolower($cookie[0])==strtolower($row['playerone'])){
			X1Cookie::SetCookie(X1_cookiename, $row['team_id'],$row['name']);
			return true;
		}
		elseif(in_array($cookie[0], $cocaps)){
			X1Cookie::SetCookie(X1_cookiename, $row['team_id'],$row['name'], "cocap");
			return true;
		}
		else{
			return false;
		}
	}

	/*############################################
	name:TeamContactIcons
	what does it do: Gets Team Icons to display
	needs:int $team_id: The teams ID
	returns:true if you login, false otherwise
	###########################################*/
	function TeamContactIcons($team, $old=false){
		if($old){
			 $team = SqlGetRow("*",X1_DB_teams," WHERE team_id=".MakeItemString($team));
		} 
			$icons = X1TeamUser::GetContactIcons($team);

		$irclink =(empty($team['ircserver'])) ? '' : "<a href='irc://$team[ircserver]/$team[ircchannel]'>
		<img src='".X1_imgpath."/mirc.gif' title='$team[ircserver] / $team[ircchannel]' width='21' height='17' border='0'></a>";
		array_push($icons,$irclink);
		return $icons;//array($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink, $irclink);
	}
	
	/*############################################
	name:ContactIcons
	what does it do: Gets personal Icons to display
	needs:databaseinfo $members, boolean $old=false
	returns:member's contact icons
	###########################################*/
	function ContactIcons($member, $old=false){
		if(empty($member)){
			//to do error
			return;
		}
		if($old){
			$member = SqlGetRow("*",X1_DB_userinfo," WHERE uid=".MakeItemString($member));
		}
		return X1TeamUser::GetContactIcons($member, $member['use_faux'],'p_');
	}
	

	
	/*############################################
	name:SetTeamName
	what does it do: Set up the Team Names 
	needs:array (int) $ids
	returns:array (strings)$Names
	###########################################*/
	function SetTeamName($ids){
	 	$sizid=sizeof($ids);
		$value ='';
	 	for($x=1;$x<$sizid;$x++){
			$value .=" or team_id=".MakeItemString($ids[$x]);
		}
		$names = SqlGetAll("team_id, name", X1_DB_teams, " Where team_id=".MakeItemString($ids[0]).$value);
		foreach($names as $nam){
			$name[$nam['team_id']]=$nam['name'];
		}
		return $name;
	}

	/*############################################
	name:GetUserName
	what does it do: Set up the Team Names 
	needs:array (int) $ids
	returns:array (strings)$Names
	###########################################*/
	function GetUserName($user_id){
		if(empty($user_id)){
			return DispFunc::X1PluginOutput(X1_myteam_baduser);
		}
		$name=SqlGetRowPre(X1_DB_usersnamekey,X1_userprefix.X1_DB_userstable," Where ".X1_DB_usersidkey."=".$user_id);
		return $name[0];
	}

	/*############################################
	name:GetUserId
	what does it do: Set up the Team Names 
	needs:array (int) $ids
	returns:array (strings)$Names
	###########################################*/	
	function GetUserId($user_name){
		if(empty($user_name)){
			return DispFunc::X1PluginOutput(X1_myteam_baduser);
		}
		$id=SqlGetRowPre(X1_DB_usersnamekey,X1_userprefix.X1_DB_userstable," Where ".X1_DB_usersnamekey."=".$user_name);
		return $id[0];
	}
	
	/*############################################
	name:GetMailIconn
	what does it do: Set up the Icon for the mail
	needs:the email
	returns:array (strings)$emailicon on success, nothing on failure or empty email
	###########################################*/		
	function GetMailIcon($mail){
		 return (empty($mail)) ? '' : "<a href='mailto:$mail'> <img src='".X1_imgpath."/mail.gif' width='21' height='17' border='0' title='$mail'></a>";
	}
	
	/*############################################
	name:GetContactIcons
	what does it do: Sets up the format for the contact icons
	needs:database $info
	returns:array (strings)$icons(array($maillink,$msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink))
	###########################################*/
	private function GetContactIcons($info, $faux=0, $player_ext=""){
		$mail = ($faux) ? $info['faux_email'] : $info[$player_ext.'mail'];
		$maillink = X1TeamUser::GetMailIcon($mail);
		$weblink = (empty($info[$player_ext.'website'])) ? '' : "<a href='".$info[$player_ext.'website']."' target='_blank'>	<img src='".X1_imgpath."/home.gif' width='21' height='17' border='0' title=".$info[$player_ext.'website']."></a>";
		$icqlink  = (empty($info[$player_ext.'icq'])) ? '' : "<a href='http://wwp.icq.com/".$info[$player_ext.'icq']."#pager'>
		<img src='".X1_imgpath."/icq.gif' title='".$info[$player_ext.'icq']."' width='21' height='17' border='0'></a>";
		$msnlink = (empty($info[$player_ext.'msn'])) ? '' : "<a href='mailto:".$info[$player_ext.'msn']."'>
		<img src='".X1_imgpath."/msn.gif' title='".$info[$player_ext.'msn']."' width='21' height='17' border='0'></a>";
		$aimlink = (empty($info[$player_ext.'aim'])) ? '' : "<a href='aim:GoIM?screenname=".$info[$player_ext.'aim']."'>
		<img src='".X1_imgpath."/aim.gif' title='".$info[$player_ext.'aim']."' width='17' height='17' border='0'></a>";
		$yimlink = (empty($info[$player_ext.'yim'])) ? '' : "<a href='ymsgr:addfriend?+".$info[$player_ext.'yim']."'>
		<img src='".X1_imgpath."/yahoo.gif' title='".$info[$player_ext.'yim']."' width='21' height='17' border='0'></a>";
		$xfirelink = (empty($info[$player_ext.'xfire'])) ? '' : "<a href='http://profile.xfire.com/".$info[$player_ext.'xfire']."'>
		<img src='http://miniprofile.xfire.com/bg/sh/type/4/".$info[$player_ext.'xfire'].".gif' title='$info[xfire]' width='16' height='16' /></a>";
		return array($maillink,$msnlink,$icqlink,$aimlink,$yimlink,$weblink,$xfirelink);
	}
	




}
	
?>