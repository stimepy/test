<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function ladderhome() {
	global $gx_event_manager;
	$team_info = X1Cookie::CookieRead(X1_cookiename);
	$event = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString(DispFunc::X1Clean($_REQUEST['sid'])));
	$event['sid'] = DispFunc::X1Clean($event['sid']);
	$event['type'] = DispFunc::X1Clean($event['type']);
	$rungsup=$event['score'];
	$rungsdown=$event['ratings'];
	$numberofplayersin = GetTotalCountOf("ladder_id",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($_REQUEST['sid']));
	$colspan = (!empty($team_info[0] )) ? 5 : 4 ;
	$c  = DispFunc::X1PluginStyle();
	$page = (empty($_REQUEST['page'])) ? 1 : intval($_REQUEST['page']);
	$start = (empty($_REQUEST['start'])) ? 0 : intval($_REQUEST['start']);
	
	$c .= standings($event['sid'], X1_topteamlimit, $start);
    $c .= DispFunc::X1PluginTitle(XL_eventhome_viewtitle);
    
	$c .=  "
	<table class='".X1plugin_newmatchestable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th colspan='$colspan'>&nbsp;</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'><tr>";
		
		if(!empty($team_info[0] )){
			$c .="
				<td align='center'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='Join' value='Join Event' type='submit'>
						<input name='ladder' type='hidden' value='$event[sid]'>
						<input type='hidden' name='sid' value='$event[sid]'>
						<input name='".X1_actionoperator."' type='hidden' value='joinladderpre'>
					</form>
				</td>";
		}
		$c .="
				<td align='center'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='Maps List' value='".XL_eventhome_mapsbutton."' type='submit'>
						<input name='id' type='hidden' value='$event[sid]'>
						<input name='".X1_actionoperator."' type='hidden' value='listmaps'>
					</form>
				</td>
				<td align='center'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='standings' value='".XL_eventhome_standingsbutton."' type='submit'>
						<input name='sid' type='hidden' value='$event[sid]'>
						<input name='".X1_actionoperator."' type='hidden' value='standings'>
					</form>
				</td>
				<td align='center'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='submit' value='".XL_eventhome_viewhistory."' type='submit'>
						<input name='sid' type='hidden' value='$event[sid]'>
						<input name='".X1_actionoperator."' type='hidden' value='pastmatches'>
					</form>
				</td>
				<td align='center'>
					<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
						<input name='submit' value='".XL_eventhome_viewrules."' type='submit'>
						<input name='sid' type='hidden' value='$event[sid]'>
						<input name='".X1_actionoperator."' type='hidden' value='eventrules'>
					</form>
				</td>
			</tr>";
	$c .= DispFunc::DisplaySpecialFooter($colspan);
	$c .= DispFunc::X1PluginTitle(XL_eventhome_newmatches);
	$c .= newmatches($event['sid'], X1_newmatchlimit,1);

	$c .= DispFunc::X1PluginTitle(XL_matchhistory_title);
	$c .= pastmatches($event['sid'],X1_resultslimit,1);

	$c .= DispFunc::X1PluginTitle(XL_eventhome_settings);
    $c .= laddersettings($event['sid']);
    $c .= "<br/>";
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
    $c .=$gx_event_manager->X1ModInfo();
	
	return DispFunc::X1PluginOutput($c);
}

/*###########################
Function: laddersettings
Needs: string $sid
Returns: String $output
What does it do: Displayes the information about the event.
##############################*/
function laddersettings($sid){
	$span=2;
	$eventinfo = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString($sid));
	if($eventinfo){
		$active = ( $eventinfo['active'] ) ? XL_yes : XL_no; 
		$enabled = ( $eventinfo['enabled'] ) ? XL_yes : XL_no;
		$restrictdates = ( $eventinfo['restrictdates'] ) ? XL_yes : XL_no;
		$restrictmaps = ( $eventinfo['restrictmaps'] ) ? XL_yes : XL_no;
		$output = "
		<table class='".X1plugin_ladderhometable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>	
				<th colspan='$span'>&nbsp;</td>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td align='left' width='50%' class='alt1'>
					<ul>
						<li>".XL_eventhome_active."  $active
						<li>".XL_eventhome_enabled."  $enabled
						<li>".XL_eventhome_timezone." ".X1_timezone."
						<li>".XL_eventhome_numdates."  $eventinfo[numdates]
						<li>".XL_eventhome_dupedates."  $restrictdates
						<li>".XL_eventhome_maps1."  $eventinfo[nummaps1]
						<li>".XL_eventhome_maps2."  $eventinfo[nummaps2]
						<li>".XL_eventhome_dupemaps."  $restrictmaps
					</ul>
				</td>
				<td align='left' width='50%' class='alt2'>
					<ul>
						<li>".XL_eventhome_pointswin."  $eventinfo[pointswin]
						<li>".XL_eventhome_pointsloss."  $eventinfo[pointsloss]
						<li>".XL_eventhome_pointsdecline."  -$eventinfo[declinepoints]
						<li>".XL_eventhome_gamesday."  $eventinfo[gamesmaxday]
						<li>".XL_eventhome_challlimit."  $eventinfo[challengelimit]
						<li>".XL_eventhome_timeout."  $eventinfo[challengedays]
						<li>".XL_eventhome_maxteams."  $eventinfo[maxteams]
						<li>".XL_eventhome_rostermin."  $eventinfo[minplayers]
					</ul>
				</td>
			</tr>";
			$output .= DispFunc::DisplaySpecialFooter($span,$break=false);
			return $output;
	}
	else{ 
		return DispFunc::X1PluginOutput("Error 10. No event has been found");
	}
}

/*############################
Function: eventrule
Needs: N/A
Returns: N/A
What does it do: Displays the event rules.
##############################*/
function eventrules() {
	$event = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString(DispFunc::X1Clean($_POST['sid'])));
	$output = DispFunc::X1PluginStyle();
	$output .= "
	<table class='".X1plugin_rulestable."' width='100%'>
    	<thead class='".X1plugin_tablehead."'>
        	<tr>";
        	if($event){
        		$output .="<th>".XL_eventrules_title.":".$event['title']."</th>";
			}
        	else{
        		$output .="<th>".XL_eventrules_title.":No Name</th>";
        	}
        	$output .="</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";

	//$row = $xdb->GetRow("SELECT * FROM ".X1_prefix.X1_DB_events." WHERE sid=".MakeItemString(DispFunc::X1Clean($_POST['sid']))); 
	if ($event){
		$output .="
		<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
			<tr>
				<td class='alt1'>".Dispfunc::X1_HTMLReady($event['hometext'])." 
				<hr noshade><br />".Dispfunc::X1_HTMLReady($event['bodytext'])."</td>
			</tr>
		</form>";
	}
	else{
		$output .= "
		<tr>
			<td>".XL_eventrules_none."</td>
		</tr>";
	}
	$output .= "</tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>";
	return DispFunc::X1PluginOutput($output);
}

function standings($sid=0, $limit="", $start=0) {
	global $gx_event_manager;
	$span = 11;
	$output  = DispFunc::X1PluginStyle();
	$post_sid=DispFunc::X1Clean($_REQUEST['sid']);
	if(!isset($post_sid)){
		$post_sid = $sid;
	}
	;
	$e_sid=MakeItemString($post_sid);
	# Count total teams
	$numberofplayersin = GetTotalCountOf("team_id",X1_DB_teamsevents," WHERE ladder_id=".$e_sid);
	$event = SqlGetRow("*",X1_DB_events," WHERE sid=".$e_sid);
	
	#Get Game Data
	$game  = SqlGetRow("*",X1_DB_games," WHERE gameid=".MakeItemString($event['game']));
	# Return error if mod  type is missing
	if (empty($event['type'])){
		return DispFunc::X1PluginOutput($c .= XL_missingfile);
	}
	echo $event['type'];
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	
	$output .=$gx_event_manager->X1Standings($event, $game, $post_sid, $limit, $start, $numberofplayersin);
	
	return DispFunc::X1PluginOutput($output);
}



/*#######################################
Function: joinladderpre
Needs: N/A
Returns: Output $c
What does it do: Sets up and displayes the info screen to join an event.
########################################*/
function joinladderpre() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if (!X1Cookie::CheckLogin(X1_cookiename)){
		DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));	
		if(isset($_POST['sid'])){
			X1File::X1LoadMultiFiles(array("user_match.php"),X1_plugpath."/core/user/");
			return ladderhome();
		}
		return;
	}

	$eventinfo=DispFunc::X1Clean($_POST['ladder']);
	if (!isset($eventinfo)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_teamadmina_noeventsel));	
	}

	list ($team_id, $team) = X1Cookie::CookieRead(X1_cookiename);
	$team_info=$team_id;
	PrePostJoinReq($team_info, $eventinfo, $failed);
	if($failed){
		return false;
	}
	unset($team_info);
	
	$c .= DispFunc::X1PluginTitle(XL_teamadmina_joinevent."::$eventinfo[title]");
	/*$title = stripslashes($eventinfo['title']);
	$hometext = stripslashes($eventinfo['hometext']);
	$bodytext = stripslashes($eventinfo['bodytext']);
	$notes = stripslashes($eventinfo['notes']);
	
	$notes =(!empty($notes)) ? "<br /><br />"._NOTE." <i>$notes</i>" : "";
	$bodytext = (!empty($bodytext)) ? "$hometext$notes<br />" : "$hometext<br /><br />$bodytext$notes";
*/
    $gameinfo=SqlGetRow("*",X1_DB_games, "WHERE gameid=".MakeItemString(DispFunc::X1Clean($eventinfo['game'])));

    if($gameinfo){
        $gameid = $gameinfo['gameid'];
        $gamename = $gameinfo['gamename'];
        $gameimage = $gameinfo['gameimage'];
    }
    else{
		return DispFunc::X1PluginOutput("Error 9 No game Found!");
	}
	$c .= laddersettings($eventinfo['sid'])."<br />";
	
	if (!isset($eventinfo['type'])){
		return DispFunc::X1PluginOutput(DispFunc::X1PluginTitle(XL_missingfile));
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$eventinfo[type]/");	
	$c .= $gx_event_manager->X1ModInfo();
		
	$c .= "
	<br />
	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
		<input name='Submit' type='Submit' value='".XL_teamadmina_joinevent."' >
		<input name='".X1_actionoperator."' type='hidden' value='joinladder'>
		<input name='ladder_id' type='hidden' value='$eventinfo[sid]'>
	</form>";
	return DispFunc::X1PluginOutput($c);
}

/*###################################
Function joinladder
Needs:N/A
Returns: N/A
What does it do:Makes sure everything is ok for a team to join a ladder and then calls the function to add them to the
 ladder.  If they are not ok to join the ladder a message will display, along with why they can't join the ladder.
#####################################*/
function joinladder() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	
	list ($team_id, $team) = X1Cookie::CookieRead(X1_cookiename);
	
	$lad=DispFunc::X1Clean($_POST['ladder_id']);
	$teaminfo=$team_id;
	$numteamsonladder = PrePostJoinReq($teaminfo, $lad, $failed=false);
	if($failed){
		return false;
	}
	if (!isset($lad['type'])){
		return DispFunc::X1PluginOutput(displayteam("events", $c .= XL_missingfile));
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$lad[type]/");
	$gx_event_manager->X1JoinEvent($numteamsonladder, $team_id, $lad, $teaminfo);
	
	$c.= XL_teamadmin_joinedevent;
	return DispFunc::X1PluginOutput(displayteam("events", $c));
}

/*###################################
Function PrePostJoinReq
Needs:int $team_id. int &$event, boolean $fail=false
Returns: the eventinformation, and everything passed $fail has a value of true, else returns a display to the event area of team profile.
What does it do:Makes sure everything is ok for a team to join a ladder.  If they are not ok to join the ladder a message will display, 
along with why they can't join the ladder.
#####################################*/

function PrePostJoinReq(&$teaminfo, &$event,  &$fail=false){
	if(isset($event) && isset($teaminfo)){
		$ladder_id=$event;
		$team_id=$teaminfo;
	}
	else{
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle(XL_failed_value)));
	}
	
	$teamonladder =GetTotalCountOf("team_id",X1_DB_teamsevents, "WHERE team_id = ".MakeItemString(DispFunc::X1Clean($team_id))." AND ladder_id = ".MakeItemString($ladder_id));
	#Check if they are on the ladder allready
	if($teamonladder!=0){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle(X1_event_allreadyjoined)));
	}
	
	$teaminfo = SqlGetRow("*",X1_DB_teams," WHERE team_id = ".MakeItemString(DispFunc::X1Clean($team_id)));	
	//Is the team there?
	if(!$teaminfo){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle($team.':'.XL_ateams_none)));
	}
	
	$event = SqlGetRow("*",X1_DB_events, " WHERE sid=".MakeItemString($ladder_id));
	if (!$event){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle(XL_teamadmina_noevent)));
	}
	if (!$event['active']){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle(XL_challenges_notactive)));
	}
	
	//Checks to see if your team has the correct number of players.
	$numonroster = GetTotalCountOf('uid', X1_DB_teamroster, "WHERE team_id = ".MakeItemString($team_id));
	if($numonroster > $event['maxplayers']){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", $c .= XL_teamadmina_joinmaxplayers));
	}
	elseif($numonroster < $event['minplayers']){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", $c .= XL_teamadmina_joinminplayers));
	}
	
	$numteamsonladder = GetTotalCountOf('team_id', X1_DB_teamsevents, "WHERE ladder_id = ".MakeItemString($ladder_id));
	#Check to see if the ladder is full
	if ($numteamsonladder >= $event['maxteams']){
		$fail=true;
		return DispFunc::X1PluginOutput(displayteam("events", DispFunc::X1PluginTitle(X1_laddermod_eventfull)));
	}

	//all is well, return
	return $numteamsonladder;
}

/*####################################
Function: quitladder
Needs: N/A
Returns: N/A
What does it do: Checks to ensure the team in on the ladder and then calls the remove function.
#####################################*/
function quitladder() {
	global $gx_event_manager;
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(XL_notlogggedin));
	}
	$team_id=DispFunc::X1Clean($_POST['team_id']);
	
	//checks to ensure that the user is not just chickening out of a challenge already accepted.
	$check =SqlGetAll("ladder_id", X1_DB_teamchallenges, "where winner=".MakeItemString($team_id)." or loser=".MakeItemString($team_id)." and ctemp=0");
	if($check){
		return $output = XL_teamadmin_nochallenges;
	}
	 //Gets rid of any challenges that have been posed but not accepted.
	ModifySql("DELETE FROM", X1_DB_teamchallenges, "where winner=".MakeItemString($team_id)." or loser=".MakeItemString($team_id)." and ctemp=1");
	
	$lad = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	if($lad['type']){
		X1File::X1LoadFile("event.php",X1_modpath."/$lad[type]/");
		$gx_event_manager->X1QuitEvent($lad,$team_id);
    }
	else{
	 	return DispFunc::X1PluginOutput(DispFunc::X1PluginTitle(XL_missingfile));
    }
    $c .= XL_teamadmin_eventteamremoved;
	return DispFunc::X1PluginOutput(displayteam("events", $c));
}






?>
