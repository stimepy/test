<?php
###############################################################
##X1plugin Competition Management
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2011
##Version 2.6.4
##File: X1LadderT2.php
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################


/*######################################################
#Class: X1LadderT2  (Version 1.0.0)   
#implements: X1Eventmods
#What and why: This is a TWL inspired ladder event.  Ladder is defined as follows:
Teams are put in to postions, each position is a "rung"   Teams can only challenge up a % of the "rung".  Points won mean nothing, only positions matters.  (there are no ties!)
Winning a match means:
If you were challenged: Nothing, you defended your spot on the ladder and will keep it as a reward.
If you were challenger: You move up to the challenged teams spot.
Losing a match means:
If you were challenged: You will be knocked down a to the winners rung + 1 (default) or as many spots as defined by the ladder admin.
If you were challenger: Nothing, your stuck in your rung, moving neither up nor down.
######################################################*/
class X1LadderT2 implements X1EventMods{
	
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
	
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, " SET challenged =".MakeItemString($team_names[$challenge['winner']]. ' <- vs ' . $team_names[$challenge['loser']])." WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));
	
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, " SET challenged =".MakeItemString($team_names[$challenge['loser']]. ' vs -> ' . $team_names[$challenge['winner']])." WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));
	
	//"$challenge['map1']).",
		$success[$suc_count]=ModifySql("update", X1_DB_teamchallenges, "Set
		ctemp=".MakeItemString(0).",
		date=".MakeItemString(time()).", 
		map1=".MakeItemString($challenge['map1']).",
		map2=".MakeItemString($maps2).",
		matchdate=".MakeItemString($_POST['matchdate'])."
		where randid=".MakeItemString($challenge['randid']));
		
		//$success[$suc_count++]=ModifySql("DELETE FROM", X1_DB_teamtempchallenges, "WHERE randid = ".MakeItemString($challenge['randid']));
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
What does it do:Takes the inforation provided and inserts the event into
the tempchallenge table, this means a team has been challenged but has yet to accept or decline.
#######################################*/	     
	public function X1SetChallenge($maps, $dates, $randid, $challenger, $challenged, $event, $rchallenged='', $rchallenger='')				
	{
	 	$suc_count=0;
		//Check to see if rung is lower
		if ($rchallenged['rung'] > $rchallenger['rung']){
		 	return DispFunc::X1PluginOutput($c .= DispFunc::X1PluginTitle(laddermod_lowerrung));
		}
		//Check to see if challenged is too many rungs ahead
		if($rchallenger['rung']>$event['c_top']){
			echo 'boo';
			/*If challenged rung is < then challenger rung - a percent of challenged rungs*/
			if ($rchallenged['rung'] < ($rchallenger['rung'] - round(($event['score']/100)* $rchallenger['rung']))){
				$c .= laddermod_toomanyrungs." ".laddermod_toomanyrungs2.($rchallenger['rung'] - round(($event['score']/100)* $rchallenger['rung']))."<br />";
				$c .= laddermod_chalerrung.$rchallenger['rung']."<br />";
				$c .= $challenged['name'].laddermod_chaledrung.$rchallenged['rung']."<br />";
				return DispFunc::X1PluginOutput($c);
			}
		}
		//Implode For Storage in Database
		$mapentry=implode(",",$maps);
		$dateentry=implode(",",$dates);
		//Update the database.                      
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged=".MakeItemString(laddermod_challengedby.$challenger['name'])." WHERE team_id=".MakeItemString($challenged['team_id'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, "	SET challenged=".MakeItemString(laddermod_challenged.$challenged['name'])." WHERE team_id=".MakeItemString($challenger['team_id'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++]=ModifySql("INSERT INTO", X1_DB_teamchallenges, "
		(ctemp,winner, loser, date, randid, ladder_id, map1, matchdate) 
		VALUES(
		".MakeItemString(1).",
		".MakeItemString($challenged['team_id']).", 
		".MakeItemString($challenger['team_id']).", 
		".MakeItemString(time()).", 
		".MakeItemString($randid).", 
		".MakeItemString($event['sid']).",
		".MakeItemString($mapentry).",
		".MakeItemString($dateentry)."
		)");

		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
			 	echo $success[$i];
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
	public function X1JoinEvent($numteamsonladder, $team_id, $lad, $teaminfo, $extainto=0){ // join.php
		$result=ModifySql("INSERT INTO ",X1_DB_teamsevents," (ladder_id, team_id, rung)
		VALUES(
		".MakeItemString($lad['sid']).", 
		".MakeItemString($team_id).", 
		".MakeItemString($numteamsonladder+1).")");	
	
		
		if($result){
			return true;
		}
		return false;
	}
	
/*######################################
Name:X1DeclineChallenge
Needs:int $newpoints, databaseinfo $challenge, databaseinfo $event
Returns: bool $success
What does it do:Takes the information provided, and removes the challenge from the tempchallenge database. 
#######################################*/		
	public function X1DeclineChallenge($newpoints, $newtotalpoints, $challenge, $event){
		$suc_count=0;
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, "SET challyesno ='No', challenged ='".laddermod_openchall."', points = '$newpoints' WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, "SET challyesno ='No', challenged ='".laddermod_openchall."' WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++]=ModifySql("DELETE FROM", X1_DB_teamchallenges, " WHERE randid = ".MakeItemString($challenge['randid']));
		
		$success[$suc_count++]=ModifySql("UPDATE", "teams", " SET totalpoints = ".MakeItemString($newtotalpoints)." WHERE team_id=".MakeItemString($challenge['winner']));
		
		for($i=0;$i<$suc_count;$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;
		//$c .= laddermod_challengedeclined;

	}
	
/*######################################
Name:X1ModInfo
Needs:N/A
Returns: string $outpout
What does it do:Displays the info about the mod.
#######################################*/			
	public function X1ModInfo(){
		$output = DispFunc::X1PluginTitle(laddermod_modinfotitle);
		$output .= "
			<table class='".X1plugin_mapslist."' width='100%'>
		    	<thead class='".X1plugin_tablehead."'>
					<tr>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody class='".X1plugin_tablebody."'>
					<tr>
						<td class='alt1'>".laddermod_modinfodesc."</td>
					</tr>
				</tbody>
				<tfoot class='".X1plugin_tablefoot."'>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</tfoot>
			</table>";
			return $output;
	}
	
/*######################################
Name:X1QuitEvent
Needs:databaseinfo $lad
Returns: bool $success
What does it do:Removed the team from the event
#######################################*/
	public function X1QuitEvent($lad, $team_id){
		$mod=SqlGetRow("rung",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($lad['sid'])." AND team_id=".MakeItemString($team_id));
		
		$rungs= SqlGetAll("rung",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($lad['sid'])." order by rung");
		
		if($rungs){
		 	$error=0;
			foreach($rungs AS $row){
				if($row['rung'] > $mod['rung']){
					$result = ModifySql("UPDATE ",X1_DB_teamsevents," set rung=rung-1 where rung=$row[rung] AND ladder_id=".MakeItemString($lad['sid']));
					if(!result){
						$error++;
					}
				}
			}
		}
		else{
			$error=-1;
		}

		$del= ModifySql("DELETE FROM ",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($lad['sid'])." AND team_id=".MakeItemString($team_id));
		
		if($error!=0 && $del){
			return false;
		}
		return true;
	}
	
/*######################################
Name:X1ReportDraw
Needs:array $ids(string $winner, string $loser_id, string $loser, string $winner_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a draw for said event
#######################################*/		
	public function X1ReportDraw($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event){
	 
		$winner=$ids['winner'];
	 	$winner_id=$ids['winner_id'];
		$loser=$ids['loser'];
	 	$loser_id=$ids['loser_id'];
	 	$suc_count=0;
		//$success[$suc_count++]=$this->DeleteFromChallenge($challenge);
		
		//Update losing teams record in the datebase for the ladder
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, 
		"SET draws=draws+1, 
		challenged ='".laddermod_drawvs."$winner', 
		points=points+$event[pointsdraw], 
		games=games+1, 
		streakwins=0, 
		streaklosses=0 
		WHERE team_id=".MakeItemString($loser_id)."	AND ladder_id=".MakeItemString($event['sid']));
		
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teamsevents, 
		"SET draws=draws+1, 
		challenged ='".laddermod_drawvs."$loser', 
		points=points+$event[pointsdraw], 
		games=games+1, 
		streakwins=0, 
		streaklosses=0 
		WHERE team_id=".MakeItemString($winner_id)." AND ladder_id=".MakeItemString($event['sid']));
			
		//Update overal teams record in the database. (loser)
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teams, 
		"SET totaldraws=totaldraws+1, 
		totalpoints=totalpoints+$event[pointsdraw], 
		totalgames=totalgames+1 
		WHERE team_id=".MakeItemString($loser_id));
		
		//Update overal teams record in the database. (winner)
		$success[$suc_count++]=ModifySql("UPDATE", X1_DB_teams, 
		"SET totaldraws=totaldraws+1, 
		totalpoints=totalpoints+$event[pointsdraw],  
		totalgames=totalgames+1 
		WHERE team_id=".MakeItemString($winner_id));
		
		for($i=0;$i<sizeof($success);$i++)
		{
			if(!$success[$i]){
				return false;
			}
		}
		return true;
	}

/*######################################
Name:X1ReportLoss
Needs:array $ids(string $winner, string $loser_id, string $loser, string $winner_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a loss for said event
#######################################*/	
	public function X1ReportLoss($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event)
	{
	 	$suc_count=0;
	 	
		$winner=$ids['winner'];
	 	$winner_id=$ids['winner_id'];
	 	$loser=$ids['loser'];
	 	$loser_id=$ids['loser_id'];
	 	
		$rungs_up = $event['score'];
		$rungs_down = $event['ratings'];
		//Get the current rung of the winner.
		$winner_rung=$this->GetRungInfo(MakeItemString($winner_id), $event);
		//Get the current rung of the loser.
		$loser_rung=$this->GetRungInfo(MakeItemString($loser_id), $event);
		
		//If the winners rung is greater than the losers rung then we have to adjust the ranks.

		if ($winner_rung > $loser_rung){
			//The new rung of the winner will be the rung of the loser.
			$new_winner_rung = $loser_rung;
			//The new rung of the loser will be thier original rung minus the number of rungs selected to drop for each ladder
			$new_loser_rung = $loser_rung+$rungs_down;
			/*We know the winner rung and the loser rung and the names of the teams, we want to set the winner and loser rung,then we want to 
				lower all those ladders that are less then or = to the new loser rung as rung-1 up to the whole (but no further)
			
			If the new loser rung is larger than the number of team on the ladder then there will be gaps unexplainable gaps.
			This will check for that and set it to the last rung  */
			$teams_on_ladder = GetTotalCountOf("*",X1_DB_teamsevents,"WHERE ladder_id=".MakeItemString($event['sid']));
			if ($new_loser_rung > $teams_on_ladder){
				$new_loser_rung=$teams_on_ladder;
			}
			$rung_diff=$new_loser_rung-$winner_rung;
			
			//Takes and replaces the current rung of the winner and loser
			$success[$suc_count++] = ModifySql("Update ",X1_DB_teamsevents," set rung = $new_loser_rung where team_id=".MakeItemString($loser_id)."  AND ladder_id=".MakeItemString($event['sid'])); 
			$success[$suc_count++] = ModifySql("Update ",X1_DB_teamsevents," set rung = $new_winner_rung where team_id=".MakeItemString($winner_id)." AND ladder_id=".MakeItemString($event['sid'])); 

			/*if newloser-oldwinner=-
			+1 between old winner  and new loser
			if newloser-oldwinner=+
			-1 between new loser and old winner	
			if newloser-oldwinner=0
			Do nothing*/
			if($rung_diff>0){
				$rungs="rung-1 where rung <= ".$new_loser_rung." and rung >= ".$winner_rung;
				$success[$suc_count++]=ModifySql("Update",X1_DB_teamsevents," set rung = ".$rungs." and team_id not in(".$winner_id.",".$loser_id.") and ladder_id=".MakeItemString($event['sid']));
			}
			elseif($rung_diff<0){
				$rungs="rung+1 where rung >=".$new_loser_rung." and rung <= ".$winner_rung;
				$success[$suc_count++]=ModifySql("Update",X1_DB_teamsevents," set rung = ".$rungs." and team_id not in(".$winner_id.",".$loser_id.") and ladder_id=".MakeItemString($event['sid']));
			}
		}
		// Else no change in rungs
		
		#Remove Challenge from databade
		//$success[$suc_count++]=$this->DeleteFromChallenge($challenge);
			
		//Update losing teams record in the datebase for the ladder
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET 
		challenged ='".laddermod_defeateddby."$winner', 
		wins=wins, 
		losses=losses+1, 
		points=points+$event[pointsloss], 
		games=games+1,
		streakwins=0, streaklosses=streaklosses+1 
		WHERE team_id=".MakeItemString($loser_id)." AND ladder_id=".MakeItemString($event['sid']));
		
		
		//Update winning teams record in the datebase for the ladder
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET 
		challenged ='".laddermod_defeated." $loser', 
		wins=wins+1, 
		losses=losses, 
		points=points+$event[pointswin], 
		games=games+1, 
		streakwins=streakwins+1, streaklosses=0 
		WHERE team_id=".MakeItemString($winner_id)." AND ladder_id=".MakeItemString($event['sid']));
		
		//Update overal teams record in the database. (loser)
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teams, "SET 
		totalwins=totalwins, 
		totallosses=totallosses+1, 
		totalpoints=totalpoints+$event[pointsloss], 
		totalgames=totalgames+1 
		WHERE team_id=".MakeItemString($loser_id));
		
		//Update overal teams record in the database. (winner)
		$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teams, "SET 
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
	
	}
	
/*######################################
Name:X1ResetEvent
Needs:int $ladder_id
Returns: bool $success
What does it do:resets stats for all teams on said event, does NOT remove points from a teams total points (located out side said event). 
#######################################*/		
	public function X1ResetEvent($ladder_id){
		$suc_count=0;
		$i=1;
		$error=0;
		//first get all the teams
		$teams = SqlGetAll("team_id",X1_DB_teamsevents," WHERE ladder_id=".MakeItemString($ladder_id)."Order by team_id");
		$total_teams=GetTotalCountOf("team_id", X1_DB_teamsevents,"WHERE ladder_id=".MakeItemString($ladder_id));
		//We are going to reset the rung to n based on how many teams are on the ladder, inorder of team id.
		foreach($teams as $modifiedteam){
			$success[$suc_count] = ModifySql("UPDATE", X1_DB_teamsevents, "SET 
			challenged ='".laddermod_openchall."', wins=0, losses=0, draws=0,points=0, games=0, streakwins=0, streaklosses=0, penalties=0, rung=$i WHERE team_id=".MakeItemString($modifiedteam[0])." AND ladder_id= $ladder_id");
			$i++;
			if(!$success[$suc_count++]){
			 	$error++;
			}
		}
		if($error){
			return false;
		}
		return true;  //Database updated successfully.
	}

/*######################################
Name:X1Standings
Needs:int $sid=0, string $limit="", int $start=0
Returns: string
What does it do:creates the standings for players on the ladder
#######################################*/		
	public function X1Standings($event, $game, $sid, $limit="", $start=0, $numberofplayersin=0){
		$span = 11;
		$output = DispFunc::X1PluginTitle(laddermod_leaderboard.$event['title']);
		$output .= "
		<table class='".X1plugin_standingstable."' width='100%'>
	    <thead class='".X1plugin_tablehead."'>
	    	<tr>
	    		<th class='alt3'>".laddermod_rank."</th>
	    		<th class='alt3'>".laddermod_tags." </th>
	    		<th class='alt3'>".laddermod_team."</th>
	    		<th class='alt3'>".laddermod_status."</th>
	    		<th class='alt3'>".laddermod_wins."</th>
	    		<th class='alt3'>".laddermod_losses."</th>
	    		<th class='alt3'>".laddermod_draws."</th>
	    		<th class='alt3'>".laddermod_points."</th>
	    		<th class='alt3'>".laddermod_percentage."</th>
				<th class='alt3'>".laddermod_streak."</th>
	    		<th class='alt3'>".laddermod_country."</th>
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
		$op=DispFunc::X1Clean($_REQUEST[X1_actionoperator]);
		$limit = ($op == "standings") ? "" :" LIMIT $start, $limit";
		
		$team_info = SqlGetAll(X1_prefix.X1_DB_teamsevents.".*, ".X1_prefix.X1_DB_teams.".country, ".X1_prefix.X1_DB_teams.".team_id, ".X1_prefix.X1_DB_teams.".name, ".X1_prefix.X1_DB_teams.".clantags",X1_DB_teamsevents.",".X1_prefix.X1_DB_teams," WHERE ".X1_prefix.X1_DB_teamsevents.".ladder_id=".MakeItemString($sid)." and ".X1_prefix.X1_DB_teams.".team_id=".X1_prefix.X1_DB_teamsevents.".team_id"," ORDER BY rung asc ".$limit);
		if($team_info){
			$rank = 1+$start;
			foreach($team_info AS $team){
				$rating=sprintf("%.0f", $team['points']); 
				$played = $team["wins"]+$team["losses"]+$team['draws'];
				$percentage = ($played <= 0)?0.00: round($team["wins"]/$played, 2)*100;
				switch($team["streakwins"]){
					case 4:
						$streak = "<img src='".X1_imgpath."/stars/stars-4.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
					case 3:
						$streak = "<img src='".X1_imgpath."/stars/stars-3.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
					case 2:
						$streak = "<img src='".X1_imgpath."/stars/stars-2.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
					case 1:
						$streak = "<img src='".X1_imgpath."/stars/stars-1.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
					case 0:
						$streak = "<img src='".X1_imgpath."/stars/stars-0.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
					default:
						$streak = "<img src='".X1_imgpath."/stars/stars-5.gif' title='$team[streakwins] ".laddermod_winsinarow."'>";
						break;
				}
				//$name2 = str_replace(' ', "+", $team["name"]);
				$output .=  "
				<tr>
					<td class='alt1'>$rank($team[rung])</td>
					<td class='alt2'>$team[clantags]</td>
					<td class='alt1'>
					<a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=$team[team_id]'>$team[name]</a></td>
					<td class='alt2'>$team[challenged]</td>
					<td class='alt1'>$team[wins]</td>
					<td class='alt2'>$team[losses]</td>
					<td class='alt1'>$team[draws]</td>
					<td class='alt2'>$team[points]</td>
					<td class='alt1'>$percentage%</td>
					<td class='alt2'>$streak</td>
					<td class='alt1'><img src='".X1_imgpath."/flags/$team[country].bmp' align='absmiddle'></td>
				</tr>";
				$rank++;
			}
		}
		else{
			$output .="<tr>
						<td colspan='$span' class='alt1'>".laddermod_noteams."</td>
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
	#update Database
	$suc_count=0;
	$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".laddermod_openchall."' WHERE team_id=".MakeItemString($challenge['winner'])." AND ladder_id=".MakeItemString($event['sid']));
	
	$success[$suc_count++] = ModifySql("UPDATE", X1_DB_teamsevents, "SET challenged ='".laddermod_openchall."' WHERE team_id=".MakeItemString($challenge['loser'])." AND ladder_id=".MakeItemString($event['sid']));
	
	$success[$suc_count++] = ModifySql("DELETE FROM", X1_DB_teamchallenges, "WHERE randid=".MakeItemString($challenge['randid']));
	
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
Needs:N/a
Returns: String $output
What does it do:If there are specail requirements for an event will display them in the event creation page
#######################################*/		
	public function X1DisplaySpecialFeatures($edit=false,$event=0){
		if(!$edit){
			return $output ="<tr>
    			<td class='alt1'>".XL_aevents_lex1."</td>
    			<td class='alt1'><input type='text' name='score' size='20' value='1'>% </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_lex2."</td>
    			<td class='alt2'><input type='text' name='ratings' size='20' value='1'> </td>
    		</tr>
			<tr>
    			<td class='alt2'>".XL_aevents_lex3."</td>
    			<td class='alt2'><input type='text' name='c_top' size='20' value='10'> </td>
    		</tr>";
		}
		else{
			return $output ="<tr>
    			<td class='alt1'>".XL_aevents_lex1."</td>
    			<td class='alt1'><input type='text' name='score' size='20' value='".$event['score']."'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_lex2."</td>
    			<td class='alt2'><input type='text' name='ratings' size='20' value='".$event['ratings']."'> </td>
    		</tr>
			<tr>
    			<td class='alt2'>".XL_aevents_lex2."</td>
    			<td class='alt2'><input type='text' name='ratings' size='20' value='".$event['c_top']."'> </td>
    		</tr>";
		}
	}
	
/*######################################
Name:X1HasSpecialFeatures
Needs:boolean $edit=false, databaseinfo $event
Returns: bool $hasspecial
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
			$db_string1="score,ratings,c_top";
			$db_string2=MakeItemString($_POST['score']).",	".MakeItemString($_POST['ratings']).", ".MakeItemString($_POST['c_top']);
			return array($db_string1,$db_string2);
		}
		else{
			$db_string1="score=".MakeItemString($_POST['score']).", ratings=".MakeItemString($_POST['ratings'])." c_top=".MakeItemString($_POST['c_top']);
			return array($db_string1);
		}
	}

/*####################################
Name:DeleteFromChallenge  (Private!)
Needs:databaseinfo challenge)
Returns:string $rung
What does it do:Deletes the team after the challenge has been complete, and deletes the messages as well.
#####################################*/	
	private function D2eleteFromChallenge($challenge){
		//Remove Challenge from databade
		$success[$suc_count++]=ModifySql("DELETE FROM ", X1_DB_teamchallenges, "WHERE randid = ".MakeItemString($challenge['randid']));
		//get rid of the messages written for the challenge.
		$success[$suc_count++]=ModifySql("Delete from ", X1_DB_messages, "where randid=".MakeItemString($challenge['randid']));
		
		if($success[0] && $success[1]){
			return true;
		}
		else{
			return false;
		}
	}

/*####################################
Name:GetRungInfo  (Private!)
Needs:String $the_id, databaseinfo $event
Returns:string $rung
What does it do:Returns the rung that the team is on.
#####################################*/
	private function GetRungInfo($the_id, $event){
		$row = SqlGetRow("rung",X1_DB_teamsevents," WHERE team_id=$the_id AND ladder_id=".MakeItemString($event['sid']));	
		return $row['rung'];
	}

}
?>
