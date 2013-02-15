<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.1
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
#####
/* function: Challengeteamform 
// This creates a forum for challenging a team from the "challened" team roster page
//
// First it checks if the team is in the event, if they aren't it says so.  Checks if you are in the event if no says so.
// Checks to ensure the minimal number of players are on the team before the challenge can go through
// Checks to ensure the event is active and enabled.
//
// Creates the form and diplays the proper description fot the mod.
*/
####
function challengeteamform() {
	global $gx_event_manager; 
	if(isset($_POST['challengeid'])){
		$challenge_id=DispFunc::X1Clean($_POST['challengeid']);
	}
	else{
		$challenge_id=NULL;
	}
	$c = DispFunc::X1PluginStyle();
	
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	
	list ($cookieteamid, $teamname) = X1Cookie::CookieRead(X1_cookiename);

	if(isset($challenge_id)){
		$challenging = SqlGetRow("team_id",X1_DB_teamsevents," WHERE team_id = ".MakeItemString(DispFunc::X1Clean($_POST['challengeid']))." AND ladder_id = ".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
		if(!$challenging){
			return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle("The team you challenged must be a member of this event first."));
		}
	}
	
	$challenger = SqlGetRow("team_id",X1_DB_teamsevents," WHERE team_id = ".MakeItemString(DispFunc::X1Clean($cookieteamid))." AND ladder_id = ".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	
	if($challenger){
	
		$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
		
		$numonroster =GetTotalCountOf("team_id",X1_DB_teamroster," WHERE team_id = ".MakeItemString($cookieteamid));
				
		if($numonroster < $event['minplayers']){
			return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamadmina_joinminplayers));
		}
		
		if (!$event['enabled']){
			return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notenabled));
		}
		
		if (!$event['active']){
			return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notactive));
		}
		
		$c .= DispFunc::X1PluginTitle(XL_challenges_challengeteam);
		
		$c .= "<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_challengeteamtable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th class='alt1'>".XL_teamprofile_hevent.":</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt2'>
				<input name='Laddername' type='text' size='40' readonly='true' value='$event[title]'>
			</td>
		</tr>
		<tr>
			<th class='alt1'>".XL_teamreport_you.":</th>
		</tr>
		<tr>
			<td class='alt2'>
				<input type='text' name='yourteamdisplay' value='$teamname' size='40' readonly>
			</td>
		</tr>
		<tr>
			<th class='alt1'>".XL_challenges_otherteam.":</th>
		</tr>
		<tr>
			<td class='alt2'>".SelectBox_ChallLadderTeamDrop("challteam", $event['sid'], $challenge_id, $cookieteamid)."</td>
		</tr>
		<tr>
			<th class='alt1'>".XL_challenges_selectdates."</th>
		</tr>
		<td class='alt2'>";
		$curdate = 1;
		while ($event['numdates'] >= $curdate){
			$c .= DispFunc::X1EditTime(time(), "_$curdate")."<br />";
			$curdate++;
		}
		$c .= "</td>
		</tr>
		<tr>
			<th class='alt1'>".XL_challenges_selectmaps."</th>
		</tr>
		<tr>
		<td class='alt2'>";
		$cm = 1;
		while ($event['nummaps1'] >= $cm){
			$c .= SelectBox_Maplist("maps[]", "$event[sid]");
			$c .= "<br />"; 
			$cm++;
		}
		$c .= "
		</tr>
		<br />"; 
		
		$c .= "
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<td>
					<input type='Submit' name='".X1_actionoperator."' value='sendchallenge'>
					<input type='hidden' name='gamesmaxday' value='$event[gamesmaxday]'>
					<input type='hidden' name='ladder_id' value='$event[sid]'>
				</td>
			</tr>
		</table><br />
		</form>";
		if (X1_showsettingschall){
			if (!isset($event['type']))return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle( XL_missingfile));
			X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
			$gx_event_manager->X1ModInfo();
			
			$c .= laddersettings($event['sid']);
		}
		if (X1_showruleschall){
			$c .= stripslashes(DispFunc::X1Clean($event['bodytext'],3));
		}
	}
	else{
		return DispFunc::X1PluginOutput(displayteam("challenges","You must join the event before you can make challenges"));
	}
	return DispFunc::X1PluginOutput($c);
}

####
/*
// Sendchallenge 
//
// check to ensure that you haven't reached max challanges per day, you have not already cahllange them, number on the 
// roster is with in legal limits (both min and max), the map restictions are in place
//
// Sends out an email to the challanger and challenged. (correctly)
*/
####
function sendchallenge() {
	global $gx_event_manager;
	$c = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}

	list ($teamid, $yourteam) = X1Cookie::CookieRead(X1_cookiename);
	
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	if ($event['type']==""){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle( XL_missingfile));
	}
	#Check to see if the ladder is active
	if (!$event['active']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notactive));
	}
	#Check to see if the ladder is active
	if (!$event['enabled']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_disabled));
	}
	

	
	$numonroster =GetTotalCountOf("team_id",X1_DB_teamroster," WHERE team_id = ".MakeItemString($teamid));
	if($numonroster > $event['maxplayers']){
	 	return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamadmina_joinmaxplayers));
	}
		if($numonroster < $event['minplayers']){
	 	return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamadmina_joinminplayers));
	}
	
	$randid = X1Misc::X1PluginRandid();
	$date=date("U")-(3600*24);
	$chall_team=MakeItemString(DispFunc::X1Clean($_POST['challteam']));
	$challenged_team_id=DispFunc::X1Clean($_POST['challteam']);
	$ladder_id=MakeItemString($event['sid']);
			
	//$challenged = SqlGetRow("*",X1_DB_teams," WHERE team_id = ".$chall_team);
	//$challenger = SqlGetRow("*",X1_DB_teams," WHERE team_id = ".MakeItemString(DispFunc::X1Clean($teamid)));
	$challenge = SqlGetAll("*",X1_DB_teams," WHERE team_id = ".$chall_team." or team_id =".MakeItemString($teamid));
	$challenger=$challenge[0];
	$challenged=$challenge[1];
	if($challenged['team_id']!=$challenged_team_id){
		switcharray($challenger, $challenged);
	}
	if($challenger['name'] == $challenged['name']){
		return DispFunc::X1PluginOutput($c .=DispFunc::X1PluginTitle(XL_teamreport_playwithself));
	}
	
	//$rchallenged = SqlGetRow("*",X1_DB_teamsevents," WHERE team_id = ".$chall_team." AND ladder_id=".$ladder_id);
	//$rchallenger = SqlGetRow("*",X1_DB_teamsevents," WHERE team_id = ".MakeItemString($teamid)." AND ladder_id=".$ladder_id);
	$rchallenge = SqlGetAll("*",X1_DB_teamsevents," WHERE (team_id = ".MakeItemString($teamid)." or team_id=".$chall_team.") AND ladder_id=".$ladder_id);
	$rchallenger =$rchallenge[0];
	$rchallenged =$rchallenge[1];
	if($rchallenged['team_id']!==$challenged_team_id){
		switcharray($rchallenger, $rchallenged);
	}
	
	$cout_team_plays = GetTotalCountOf("ladder_id", X1_DB_teamchallenges," WHERE winner IN(".MakeItemString($challenged['team_id']).",".MakeItemString($challenger['team_id']).") and loser IN(".MakeItemString($challenged['team_id']).",".MakeItemString($challenger['team_id']).") and date >= ".MakeItemString($date)." and ladder_id = ".$ladder_id);
	#Check to see if team has played more than alloted number of games with that team perday
	if($cout_team_plays >= $event['gamesmaxday']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_gamesmaxday));
	}
	
	$count_challw= GetTotalCountOf("ladder_id ",X1_DB_teamchallenges," WHERE ladder_id = ".$ladder_id." and winner = ".MakeItemString($challenged['team_id'])." or loser = ".MakeItemString($challenged['team_id']));
	#Checks team for being challenged or not
	if($count_challw >= $event['challengelimit']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_allreadychallenged));
	}
	
	$count_chall= GetTotalCountOf("ladder_id",X1_DB_teamchallenges," WHERE ladder_id = ".$ladder_id." and winner = ".MakeItemString($challenger['team_id'])." or loser = ".MakeItemString($challenger['team_id'])); 
	#Checks team for being challenged or not
	if($count_chall >= $event['challengelimit']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_allreadychallenged));
	}
	
	$curdate = 1;
	$dates = array();
	while ($event['numdates'] >= $curdate){
		$dates[] = DispFunc::X1ReadTime("_$curdate");
		$curdate++;
	}
	if ($event['restrictdates'])if(count($dates) > count(array_unique($dates))){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_datesrestricted));
	}

	$p_maps=DispFunc::X1Clean($_POST['maps'],5);
	$maps=(isset($p_maps)) ?$p_maps:array();
	if ($event['restrictmaps']!=0){
		if(count($maps) > count(array_unique($maps))){
			return DispFunc::X1PluginOutput($c .=  DispFunc::X1PluginTitle(XL_mapsrestricted));
		}
	}
	
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");	
	$result=$gx_event_manager->X1SetChallenge($maps, $dates, $randid, $challenger, $challenged, $event, $rchallenged, $rchallenger);
	if($result){
		$c .= XL_challenges_challengesuccess;	
	}
	//put out success or failure;
	if (X1_emailon){
		$content = array(
				'team' =>  DispFunc::X1Clean($challenged['name']),
				'team2' =>  DispFunc::X1Clean($challenger['name']),
				'event' =>  DispFunc::X1Clean($event['title']),
				'teammail' => $challenged['mail'],
				'teammail2' => $challenger['mail'],
				//make a link to send messages
				);
		$c .= X1Misc::X1PluginEmail($challenger['mail'], "challengesend.tpl", $content);	
		$c .= X1Misc::X1PluginEmail($challenged['mail'], "challengerecv.tpl", $content);
	}
	return DispFunc::X1PluginOutput(displayteam("challenges",$c));
}
####
/* Confirmchallform
//
//The form fot hte challenged to accept or decline.
*/
####
function confirmchallform() {
	$c = DispFunc::X1PluginStyle();
	list ($cookieteamid, $teamname, $teampass) = X1Cookie::CookieRead(X1_cookiename);
	if(!X1Cookie::CheckLogin(X1_cookiename))return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));

	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString(DispFunc::X1Clean($_POST['randid']))." and ctemp=1");
	
	$team_names=X1TeamUser::SetTeamName(array($challenge['winner'],$challenge['loser']));
	
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));

	$mapsarray=explode(",",$challenge['map1']);
	$datesarray=explode(",",$challenge['matchdate']);

	$c .= DispFunc::X1PluginTitle(XL_teamadmin_ChallengeTitle)."

	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	".DispFunc::X1PluginTitle($team_names[$challenge['winner']]." ".XL_challenges_vs." ".$team_names[$challenge['loser']])."
	<table class='".X1plugin_challengeteamtable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_challenges_challengeteam."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_challenges_selectdate.":</td>
			<td class='alt1'>
				<select name='matchdate' id='matchdate'>";
				$cd=0;
				while($cd < $event['numdates']){
					$c .= "<option value='$datesarray[$cd]'>".date(X1_extendeddateformat,$datesarray[$cd])."</option>";
					$cd++;
				}
				$c .= "
				</select>
			</td>
		</tr>
		<tr>
			<td class='alt2'>".XL_challenges_challengermaps."</td>
			<td class='alt2'>";
			$cm=0;
			$map_info=X1Misc::MapInfo($challenge['map1']);
			while($cm < $event['nummaps1']){
				list($map_name)=$map_info[$mapsarray[$cm]];
				$c .= ($cm+1).":<input name='map$cm' readonly type='text' id='map$cm' value='$map_name' size='40'/><br />";
				$cm++;
			}
			$c .= "
			</td>
		</tr>
		<tr>
			<td class='alt1'>".XL_challenges_yourmaps.":</td>
			<td class='alt1'>";
			$cm = 1;
			while ($event['nummaps2'] >= $cm){
				$c .= SelectBox_Maplist("maps[]", $event['sid']);
				$cm++;
			}
			$c .= "
			</td>
		</tr>";
		$c.="
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<td colspan='2'>
					<input type='submit' name='accept' value='".XL_challenges_acceptchalenge."'>
					<input name='randid' type='hidden' value='$challenge[randid]'>
					<input name='".X1_actionoperator."' type='hidden' value='acceptchall'>
				</td>
			</tr>
		</table>
		</form>
		<br/>
		<table class='".X1plugin_challengeteamtable."' width='100%'>

		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td class='alt1'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
					<input type='submit' name='decline' value='".XL_challenges_declinechall."'><br />
					$event[declinepoints] ".XL_challenges_warning."
					<input name='randid' type='hidden' value='$challenge[randid]'>
					<input name='".X1_actionoperator."' type='hidden' value='declinechall'>
					</form>
				<td>
			</tr>
			</tbody>
			<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		";
	if (X1_showsettingschall){
		$c .= laddersettings($challenge['ladder_id']);
	}
	if (X1_showruleschall){
		$c .= stripslashes(DispFunc::X1Clean($event['bodytext']));
	}
	return DispFunc::X1PluginOutput($c);
}
####
/* acceptchall 
//
// Updates the db for the challange
//
// Sends the correct mail.
*/
####
function acceptchall() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	list ($cookieteamid, $teamname, $teampass) = X1Cookie::CookieRead(X1_cookiename);
	//winner = challenged  loser = challenger
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString(DispFunc::X1Clean($_POST['randid'])));
	if (!$challenge || $challenge['ctemp']!=1){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notfound));
	}
	$team_names=X1TeamUser::SetTeamName(array($challenge['winner'],$challenge['loser']));
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	if ($event['type']==""){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_missingfile));
	}
	
	$p_maps=DispFunc::X1Clean($_POST['maps'],5);
	$maps = (isset($p_maps)) ? $p_maps : array();
	
	if ($event['restrictmaps']!=0){
		if(count($maps) > count(array_unique($maps))){
			return DispFunc::X1PluginOutput($c .=  DispFunc::X1PluginTitle(XL_mapsrestricted));
		}
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	$result=$gx_event_manager->X1AcceptChallenge($challenge, $event, $maps, $team_names);
	if($result){
		$c .= XL_challenges_challaccepted;
	}

	
	if (X1_emailon){
	 	$rows = SqlGetAll("team_id, mail",X1_DB_teams," WHERE team_id in(".MakeItemString($challenge['winner']).",".MakeItemString($challenge['loser']));
		//$row2= SqlGetRow("team_id,mail",X1_DB_teams," WHERE team_id = ".MakeItemString($challenge['loser']));
		// challanged
		$team1=$rows[0];
		//challanger
		$team2=$rows[1];
		if($team2['team_id']!=$challenge['loser']){
			switcharray($team1, $team2);
		}
		$content = array(
			'team1' =>  $team1["name"],
			'team2' =>  $team2["name"],
			'team1mail' =>  $team1["mail"],
			'team2mail' =>  $team2["mail"],
			'date' =>  date(X1_extendeddateformat, DispFunc::X1Clean($_POST['matchdate'])));
		//link to messages
		$c .= X1Misc::X1PluginEmail($row["mail"], "challengeaccept1.tpl", $content);	
		$c .= X1Misc::X1PluginEmail($row2["mail"], "challengeaccept2.tpl", $content);
	}
	return DispFunc::X1PluginOutput(displayteam("challenges",$c));
}


/* declinechall
//updates the db for a declined challange.
*/
function declinechall() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename))return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	list ($cookieteamid, $teamname) = X1Cookie::CookieRead(X1_cookiename);
	
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString($_POST['randid']));
	if (!$challenge || $challenge['ctemp']!=1){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notfound));
	}	
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	
	$rows = SqlGetAll("*",X1_DB_teams," WHERE team_id in(".MakeItemString($challenge['loser']).",".MakeItemString($challenge['winner']).")");
	//$winner_row = SqlGetRow("*",X1_DB_teams," WHERE team_id = ".MakeItemString($challenge['winner'])); 
	$loser_row = $rows[0];
	$winner_row = $rows[1];
	if($challenge['loser']!=$loser_row['team_id']){
		switcharray($winner_row,$loser_row);
	}
	$newtotalpoints = $winner_row["totalpoints"]-$event['declinepoints'];
	
	$row = SqlGetRow("*",X1_DB_teamsevents," WHERE team_id = ".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($challenge['ladder_id'])); 
	
	$newpoints = $row["points"]-$event['declinepoints'];
	
	if (!$event['active']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notactive));
	}
	if (!$event['enabled']){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_disabled));
	}
	if ($event['type']==""){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_missingfile));
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	$result = $gx_event_manager->X1DeclineChallenge($newpoints, $newtotalpoints, $challenge, $event);
	if($result){
		$c .= XL_challenges_challengedeclined;
	}
	
	if (X1_emailon){
		$content = array('time'=>date(X1_extendeddateformat));
		$c .= X1Misc::X1PluginEmail($winner_row['mail'], "challengedecline.tpl", $content, 'Challenge Declined');	
		$c .= X1Misc::X1PluginEmail($loser_row['mail'], "challengedecline.tpl", $content, 'Challenge Declined');
	}
	return DispFunc::X1PluginOutput(displayteam("challenges",$c));
}

/*############################# 
// name:withdrawchall
//updates the db for a withdrawn challange.
//
#####################################*/
function withdrawchall() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	list ($cookieteamid, $team) = X1Cookie::CookieRead(X1_cookiename);
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString(DispFunc::X1Clean($_POST['randid'])));

	
	if (!$challenge || $challenge['ctemp']!=1){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_challenges_notfound));
	}
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	
	if (!isset($event['type'])){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_missingfile));
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	$result= $gx_event_manager->X1WithdrawChallenge($challenge, $event);
	if($result){
		$c .= XL_challenges_challengewithdrawn;
	}
	
	if (X1_emailon){
		$row  = SqlGetAll("name ,mail",X1_DB_teams," WHERE team_id = ".MakeItemString($challenge['winner'])." or team_id=".MakeItemString($challenge['loser']));
		$team1=$row[0];
		$team2=$row[1];
		if($team1['team_id']==$cookieteamid){
			$content = array('team' =>  $team1['name']);
			$mail=$team2['mail'];
		}
		else{
			$content = array('team' =>  $team2['name']);
			$mail=$team1['mail'];
		}
		$c .= X1Misc::X1PluginEmail($mail, "challengewith.tpl", $content);	
	}
	return DispFunc::X1PluginOutput(displayteam("challenges",$c));
}

/*********************************
Name:switcharray(&$array1, &$array2){
Requires:array &$array1, array &$array2
Does:Takes two arrays and switches them.
Returns: N/A
***********************************/
function switcharray(&$array1, &$array2){
		$temp=$array1;
		$array1=$array2;
		$array2=$temp;
}
?>
