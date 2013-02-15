<?php
###############################################################
##X1plugin Competition Management
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2009
##Introductioned Versision 2.6.3
##File: X1leagueMod.php
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################


/*######################################################
#Class: X1LeagueMod  (Version 1.0.2)   
#implements: X1Eventmods
#What and why: This is the league event.  League is defined as follows:
Teams are places on a board.  On this board there can be challenges either up the board or down the board.  Positions are defined by the admin.  Going up in position means that criteria are met as specified by admin.  The default criteria are the points.  From here on out I will assume points are the defining criteria for position in the league
Winning a match means:
If you were challenged: You gain a set number of points.  Your new position is made by comparing points to teams above, and below you and placing you where the team above you has more points or is = to you.
If you were challenger: You gain a set number of points.  Your new position is made by comparing points to teams above, and below you and placing you where the team above you has more points or is = to you.
example of custom usage with Games won:
If you were challenged: You gain a game win.  Your new position is made by comparing games won to teams above, and below you and placing you where the team above you has more won games or is = to you.
etc...
Losing a match means:
If you were challenged: You lose a set number of points.  Your new position is made by comparing points to teams above, and below you and placing you where the team above you has more points or is = to you.
If you were challenger: You lose a set number of points.  Your new position is made by comparing points to teams above, and below you and placing you where the team above you has more points or is = to you.
etc...
######################################################*/
class X1LeagueMod implements X1EventMods{

/*######################################
Name:X1AcceptChallenge
Needs:array $maps, databaseinfo $challenge, datbaseinfo $event
Returns: bool $success
What does it do:Takes the inforation provided and inserts the event into
the challenge team table making the challenge an official challenge.
#######################################*/	 
    public function X1AcceptChallenge($challenge, $event, $maps, $team_names){
		$suc_count=0;
		$maps1=explode(",",$challenge['map1']);
		$maps2=implode(",",$maps);
		
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, " SET challenged =".MakeItemString($team_names[$challenge['winner']]. ' <- vs ' . $team_names[$challenge['loser']])." WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, " SET challenged =".MakeItemString($team_names[$challenge['loser']]. ' vs -> ' . $team_names[$challenge['winner']])." WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));
		
		
		$success[$suc_count++] = ModifySql("Update", X1_DB_teamchallenges, "set 
		ctemp=".MakeItemString(0).",
		date=".MakeItemString(time()).",
		map1=".MakeItemString($challenge['map1']).",
		map2=".MakeItemString($maps2).",
		matchdate=".MakeItemString(DispFunc::X1Clean($_POST['matchdate']))."
		where randid=".MakeItemString($challenge['randid'])); 
		
		//$success[$suc_count++] = ModifySql("DELETE FROM", X1_DB_teamtempchallenges, "WHERE randid = ".MakeItemString($challenge['randid']));
		
		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;

	}

/*######################################
Name:X1SetChallenge
Needs:array maps, array dates, int $randid, databaseinfo $rchallenged, databaseinfo $rchallenger databaseinfo $challenger, databaseinfo $challenged, databaseinfo $event  
Returns: bool $success
What does it do:Takes the information provided and inserts the event into
the tempchallenge table, this means a team has been challenged but has yet to accept or decline.
#######################################*/	 	
    
	public function X1SetChallenge($maps, $dates, $randid, $challenger, $challenged, $event, $rchallenged='', $rchallenger=''){
		#Implode For Storage in Database
		$mapentry=implode(",",$maps);
		$dateentry=implode(",",$dates);
		$suc_count = 0;
		
		#Update the database.
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged=".MakeItemString(leaguemod_challengedby.$challenger['name'])." WHERE team_id=".MakeItemString($challenged['team_id'])." AND ladder_id=".MakeItemString($event['sid']));
	
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged=".MakeItemString(leaguemod_challenged.$challenged['name'])." WHERE team_id=".MakeItemString($challenger['team_id'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++] = ModifySql("INSERT INTO", X1_DB_teamchallenges, "(ctemp, winner, loser, date, randid, ladder_id, map1, matchdate) VALUES (
		".MakeItemString(1).",
		".MakeItemString($challenged['team_id']).", 
		".MakeItemString($challenger['team_id']).", 
		".MakeItemString(time()).", 
		".MakeItemString($randid).", 
		".MakeItemString($event['sid']).",
		".MakeItemString($mapentry).",
		".MakeItemString($dateentry).")");
		
		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
			die("die $i");
				return false;
			}
		}
		return true;
	}
	
/*######################################
Name:X1JoinEvent
Needs:int $numteamsonladder, int $team_id, databaseinfo $lad, databaseinfo $teaminfo, array extainto
Returns: bool $success
What does it do:Takes the inforation provided puts the team into said event.
#######################################*/	
	public function X1JoinEvent($numteamsonladder, $team_id, $lad, $teaminfo, $extainto=0){
		
		#Update database
		$success = ModifySql("INSERT INTO ",X1_DB_teamsevents,"(ladder_id, team_id) VALUES(
		".MakeItemString($lad['sid']).", 
		".MakeItemString($team_id).")");
		
		if($success){
			return true;
		}
		return false;
//		$c .= X1_leaguemod_joinedevent;
	}
	
/*######################################
Name:X1DeclineChallenge
Needs:int $newpoints, databaseinfo $challenge, databaseinfo $event
Returns: bool $success
What does it do:Takes the information provided, and removes the challenge from the tempchallenge database. 
#######################################*/		
	public function X1DeclineChallenge($newpoints, $newtotalpoints, $challenge, $event){
		$suc_count=0;
		#Update the database
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, " SET challyesno ='No', challenged ='".leaguemod_openchall."', points = '$newpoints' WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));

		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, " SET challyesno ='No', challenged ='".leaguemod_openchall."' WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));

		$success[$suc_count++] = ModifySql("DELETE FROM", X1_DB_teamchallenges, " WHERE randid = ".MakeItemString($challenge['randid']));

		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teams, "	SET totalpoints = ".MakeItemString($newtotalpoints)." WHERE name=".MakeItemString($challenge['winner']));

		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;

}
	
/*######################################
Name:X1ModInfo
Needs:N/A
Returns: string $outpout
What does it do:Displays the info about the mod.
#######################################*/			
	public function X1ModInfo(){
		$output = DispFunc::X1PluginTitle(leaguemod_modinfotitle);
		return $output .= "
			<table class='".X1plugin_mapslist."' width='100%'>
				<thead class='".X1plugin_tablehead."'>
					<tr>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody class='".X1plugin_tablebody."'>
					<tr>
						<td class='alt1'>".leaguemod_modinfodesc."</td>
					</tr>
				</tbody>
				<tfoot class='".X1plugin_tablefoot."'>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</tfoot>
			</table>";
		}
	
/*######################################
Name:X1QuitEvent
Needs:databaseinfo $lad
Returns: bool $success
What does it do:Removed the team from the event
#######################################*/
	public function X1QuitEvent($lad,$team_id){
		//first see if they are in any events
		$del= ModifySql("Delete from ",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($lad['sid'])." AND team_id=".MakeItemString($team_id));

		if($del){
			return $del;
			//$c .=leaguemod_teamremoved;
		}
		return falss;
	}
	
/*######################################
Name:X1ReportDraw
Needs:array $ids(string $winner, string $winner_id, string $loser, string $loser_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a draw for said event
#######################################*/		
	public function X1ReportDraw($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event){
		$suc_count=0;

		#Update losing teams record in the datebase for the ladder
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET draws=draws+1,
		challenged ='".leaguemod_drawvs."$ids[winner]', 
		points=points+$event[pointsdraw], 
		games=games+1, 
		streakwins=0, streaklosses=streaklosses=0 
		WHERE team_id=".MakeItemString($ids['loser_id'])." AND ladder_id=".MakeItemString($event['sid']));

		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET draws=draws+1,
		challenged ='".leaguemod_drawvs."$ids[loser]', 
		points=points+$event[pointsdraw],  
		games=games+1, 
		streakwins=0, streaklosses=streaklosses=0 
		WHERE team_id=".MakeItemString($ids['winner_id'])." AND ladder_id=".MakeItemString($event['sid']));


		#Update overal teams record in the database. (loser)
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teams, "SET 
		totaldraws=totaldraws+1,
		totalpoints=totalpoints+$event[pointsloss], 
		totalgames=totalgames+1
		WHERE team_id=".MakeItemString($ids['loser_id']));

		#Update overal teams record in the database. (winner)
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teams, "SET 
		totaldraws=totaldraws+1,
		totalpoints=totalpoints+$event[pointsdraw], 
		totalgames=totalgames+1 
		WHERE team_id=".MakeItemString($ids['winner_id']));
	
		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
	//			echo $i."<br />";
				return false;
			}
		}
		return true;
		#Send successful report message
		//$c .= DispFunc::X1PluginTitle(leaguemod_drawreported);

		}

/*######################################
Name:X1ReportLoss
Needs:array $ids(string $winner, string $winner_id, string $loser, string $loser_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a loss for said event
#######################################*/	
	public function X1ReportLoss($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event){
		$winner=$ids['winner'];
		$winner_id=$ids['winner_id'];
		$loser=$ids['loser'];
		$loser_id=$ids['loser_id'];	 	
		$suc_count=0;

		#Update losing teams record in the datebase for the ladder
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".leaguemod_defeateddby."$winner', 
		wins=wins, 
		losses=losses+1, 
		points=points-$event[pointsloss], 
		games=games+1, 
		streakwins=0, streaklosses=streaklosses+1 
		WHERE team_id=".MakeItemString($loser_id)." AND ladder_id=".MakeItemString($event['sid']));


		#Update winning teams record in the datebase for the ladder
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".leaguemod_defeated." $loser', 
		wins=wins+1, 
		losses=losses, 
		points=points+$event[pointswin], 
		games=games+1, 
		streakwins=streakwins+1, streaklosses=0 
		WHERE team_id=".MakeItemString($winner_id)." AND ladder_id=".MakeItemString($event['sid']));

		#Update overal teams record in the database. (loser)
		$success[$suc_count++] = modifysql("UPDATE", X1_DB_teams, "SET 
		totalwins=totalwins, 
		totallosses=totallosses+1, 
		totalpoints=totalpoints+$event[pointsloss], 
		totalgames=totalgames+1 
		WHERE team_id=".MakeItemString($loser_id));

		#Update overal teams record in the database. (winner)
		$success[$suc_count++] = modifysql("UPDATE", X1_DB_teams, "SET 
		totalwins=totalwins+1, 
		totallosses=totallosses, 
		totalpoints=totalpoints+$event[pointswin], 
		totalgames=totalgames+1
		WHERE team_id=".MakeItemString($winner_id));
		
		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;
		#Send successful report message
		//$c .= DispFunc::X1PluginTitle(X1_leaguemod_lossreported);
	}
	
/*######################################
Name:X1ResetEvent
Needs:int $ladder_id
Returns: bool $success
What does it do:resets stats for all teams on said event. 
#######################################*/		
	public function X1ResetEvent($ladder_id){
		$success = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".leaguemod_openchall."', 
		wins=0, 
		losses=0, 
		points=0, 
		games=0,
		streakwins=0, streaklosses=0 
		WHERE ladder_id=".MakeItemString($ladder_id));
		
		if($success){
			return true;
		}
		return false;
	}
	
/*######################################
Name:X1Standings
Needs:int $sid=0, string $limit="", int $start=0
Returns: string
What does it do:creates the standings for players on the ladder
#######################################*/		
	public function X1Standings($event, $game, $sid, $limit="", $start=0, $numberofplayersin=0){
		$span=12;
		$output = DispFunc::X1PluginTitle(leaguemod_leaderboard.$event['title']);
		$output .= "
		<table class='".X1plugin_standingstable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th class='alt3'>".leaguemod_rank."</th>
					<th class='alt3'>".leaguemod_tags." </th>
					<th class='alt3'>".leaguemod_team."</th>
					<th class='alt3'>".leaguemod_status."</th>
					<th class='alt3'>".leaguemod_wins."</th>
					<th class='alt3'>".leaguemod_losses."</th>
					<th class='alt3'>".leaguemod_draws."</th>
					<th class='alt3'>".leaguemod_points."</th>
					<th class='alt3'>".leaguemod_percentage."</th>
					<th class='alt3'>".leaguemod_rating."</th>
					<th class='alt3'>".leaguemod_streak."</th>
					<th class='alt3'>".leaguemod_country."</th>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>";
	  if(isset($_REQUEST['page'])){
	  	$cur_page=DispFunc::X1Clean($_REQUEST['page']);
	  }
	  else{
	  	$cur_page=1;
	  }
	  
	  if(isset($_REQUEST['limit'])){
	  	$limit=DispFunc::X1Clean($_REQUEST['limit']);
	  } 
	 else{
	 	$limit=X1_topteamlimit;
 	 }
		
	$start = $cur_page * $limit - $limit;
	if(empty($start)){
	  	$start = 0;
	}
		if(empty($event['standingstype'])){
			$sortby = 'games/2 + wins - losses + draws/2 + streakwins/2 - streaklosses/2 - 4 * penalties + 100 DESC ';
		}else{
			$sortby = $event['standingstype'];
		}
		$op=DispFunc::X1Clean($_REQUEST[X1_actionoperator]);
		$limit = ($op == "standings") ? "" :" LIMIT $start, $limit";
		$team_info = SqlGetAll(X1_prefix.X1_DB_teamsevents.".*, ".X1_prefix.X1_DB_teams.".country, ".X1_prefix.X1_DB_teams.".team_id, ".X1_prefix.X1_DB_teams.".name, ".X1_prefix.X1_DB_teams.".clantags",X1_DB_teamsevents.",".X1_prefix.X1_DB_teams," WHERE ".X1_prefix.X1_DB_teamsevents.".ladder_id=".MakeItemString($sid)." and ".X1_prefix.X1_DB_teams.".team_id=".X1_prefix.X1_DB_teamsevents.".team_id"," ORDER BY ".$sortby.$limit);
		
		$rank = 1+$start;
		if($team_info){
			foreach($team_info AS $row){
				$rating = $row['games']/2 + $row["wins"] - $row["losses"] + $row["draws"]/2 + $row["streakwins"]/2 - $row["streaklosses"]/2 - 4 * $row["penalties"]  + 100;
				$rating=sprintf("%.0f", $rating); 
				$played = $row["wins"]+$row["losses"]+$row['draws'];
				$percentage = ($played <= 0)?0.00: round($row["wins"]/$played, 2)*100;
				switch($row["streakwins"]){
					case 4:
						$streak = "<img src='".X1_imgpath."/stars/stars-4.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
					case 3:
						$streak = "<img src='".X1_imgpath."/stars/stars-3.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
					case 2:
						$streak = "<img src='".X1_imgpath."/stars/stars-2.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
					case 1:
						$streak = "<img src='".X1_imgpath."/stars/stars-1.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
					case 0:
						$streak = "<img src='".X1_imgpath."/stars/stars-0.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
					default:
						$streak = "<img src='".X1_imgpath."/stars/stars-5.gif' title='$row[streakwins] ".leaguemod_winsinarow."'>";
						break;
				}
				//$name2 = str_replace(' ', "+", $row["name"]);
				$output .=  "
				<tr>
					<td class='alt1'>$rank</td>
					<td class='alt2'>$row[clantags]</td>
					<td class='alt1'>
					<a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=$row[team_id]'>$row[name]</a></td>
					<td class='alt2'>$row[challenged]</td>
					<td class='alt1'>$row[wins]</td>
					<td class='alt2'>$row[losses]</td>
					<td class='alt1'>$row[draws]</td>
					<td class='alt2'>$row[points]</td>
					<td class='alt1'>$percentage%</td>
					<td class='alt2'>$rating</td>
					<td class='alt2'>$streak</td>
					<td class='alt1'><img src='".X1_imgpath."/flags/$row[country].bmp' align='absmiddle'></td>
				</tr>";
				$rank++;
			}
		}else{
			$output .="<tr>
						<td colspan='$span'  class='alt2'>".leaguemod_noteams."</td>
					</tr>";
		}
				
		$pages = DispFunc::X1Pagination($numberofplayersin, X1_topteamlimit, 'limit', $cur_page, 'page', X1_publicpostfile.X1_linkactionoperator."ladderhome&sid=$sid");
		$pages = ($op != "standings") ? $pages : "&nbsp;";
		$output .=  DispFunc::DisplaySpecialFooter($span,true,$pages);
		
		return $output;
		}
	
/*######################################
Name:X1WithdrawChallenge
Needs:databaseinfo $challenge, databaseinfo $event
Returns: bool $success
What does it do:Withdrawls a challenge that was out forth  (The assuming the other team has NOT yet accepted.)
#######################################*/		
	public function X1WithdrawChallenge($challenge, $event){
		$suc_count=0;
		#update Database
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, " SET challenged ='".leaguemod_openchall."' WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));

		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".leaguemod_openchall."' WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));

		$success[$suc_count++] = ModifySql("DELETE FROM", X1_DB_teamchallenges, " WHERE randid=".MakeItemString($challenge['randid']));

		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;
	}
	
/*######################################
Name:X1DisplaySpecialFeatures
Needs:boolean $edit=false, databaseinfo $event
Returns: String $output
What does it do:If there are specail requirements for an event will display them in the event creation page
#######################################*/		
	public function X1DisplaySpecialFeatures($edit=false,$event=0){
		if(!$edit){
			return $output = "<tr>
			<td class='alt2'>".XL_aevents_sort."</td>
			<td class='alt2'><input type='text' name='standingstype' size='20' value='points'></td>
		</tr>";
		}
		else{
		return $output = "<tr>
			<td class='alt2'>".XL_aevents_sort."</td>
			<td class='alt2'><input type='text' name='standingstype' size='20' value='".$event['standingstype']."'></td>
		</tr>";
		}
		
		//more to come including setting up league times and how matches are made....
	}
	
/*######################################
Name:X1HasSpecialFeatures
Needs:N/a
Returns: bool hasspecial
What does it do:If there are special features to be seen it returns true other wise it returns false.
#######################################*/			
	public function X1HasSpecialFeatures(){
		return true;	
	}
	
/*######################################
Name:X1DataInsert
Needs:boolean $edit=false
Returns: array of 2 strings.
What does it do:Takes the information needed for a special event, both the needed col names of the
database and the $_POST of said special features, and puts it in the form required to run the db 
function.
#######################################*/	
	public function X1DataInsert($edit=false){
		if(!$edit){
			$db_string1="standingstype";
			$db_string2=MakeItemString($_POST['standingstype']);
			return array($db_string1,$db_string2);
		}
		else{
			$db_string1="standingstype=".MakeItemString($_POST['standingstype'])."";
			return array($db_string1);
		}
	}
}
?>
