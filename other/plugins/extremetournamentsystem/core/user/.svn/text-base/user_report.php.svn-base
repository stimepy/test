<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2011
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include')){die ("You cannot load this file outfile of X1plugin");}
###############################################################
function X1_reportform() {
	global $gx_event_manager;
	
	$c  = DispFunc::X1PluginStyle();
	
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		$c .= XL_notlogggedin;
		return DispFunc::X1PluginOutput($c);
	}
	list ($cookieteamid, $teamname) = X1Cookie::CookieRead(X1_cookiename);

	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString($_POST['randid']));
	if(!$challenge){
		UserLog(XL_failed_retr."(Var: challenge, Table:".X1_DB_teamchallenges.")","X1_reportform","Major Error",ERROR_DIE);
	}

	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	if(!$event){
		UserLog(XL_failed_retr."(Var: event, Table:".X1_DB_events.")","X1_reportform","Major Error",ERROR_DIE);
	}
	
	$names=X1TeamUser::SetTeamName(array($challenge['winner'], $challenge['loser']));
	$otherteam = ($challenge['winner'] == $cookieteamid) ? $names[$challenge['loser']] : $names[$challenge['winner']];

	if($event['whoreports'] == "winner"){
		$temp = $teamname;
		$teamname = $otherteam;
		$otherteam = $temp;
		unset($temp);
	}
	if(isset($_POST['draw'])){
		if(DispFunc::X1Clean($_POST['draw']) == "1"){
			$button = trim(XL_teamreport_draw);
			$func = "X1_reportdraw";
		}
	}
	else{
		$button = ($event['whoreports']=="winner") ? trim(XL_teamreport_win) : trim(XL_teamreport_loss);
		$func = "X1_reportloss";
	}
	
	$c .= "
	<form method='post' enctype='multipart/form-data' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<table class='".X1_teamreportclass."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_teamreport_title."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamreport_event."</td>
			<td class='alt1'><input name='ladder' type='text' readonly size='40' value='$event[title]'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamreport_opponent."</td>
			<td class='alt2'><input name='teamone' type='text' readonly size='40' value='$otherteam'></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_teamreport_you."</td>
			<td class='alt1'><input name='teamtwo' type='text' readonly size='40' value='$teamname'></td>
		</tr>
		<tr>
			<th class='alt2' colspan='2'>".XL_teamreport_mapsandscores."</th>
		</tr>";
		$c .= DisplayMap($challenge['map1'], $event['nummaps1'], $event, $otherteam,$teamname);
	
		$c .= DisplayMap($challenge['map2'], $event['nummaps2'], $event, $otherteam,$teamname, $winnerscore = false);

		$c .= "
			<tr>
				<th class='alt2' colspan='2'>".XL_teamreport_comments."</th>
			</tr>
			<tr>
				<td class='alt1' colspan='2'> 
				<textarea name='comments' cols='60' rows='4'>".XL_teamreport_textarea."</textarea>
				<br />".XL_teamreport_textarea2."
				</td>
			</tr>
			<tr>
				<th class='alt2' colspan='2'>".XL_teamreport_extras."</th>
			</tr>";
			$ignore_break=false;
			switch(X1_fup_demo){
				default:
					$ignore_break=true;
				case 0:
					$c .="<tr>
						<td class='alt2'>".XL_teamreport_demolink."</td>
						<td class='alt2'><input type='text' size='40' name='demo'></td>
					</tr>";		
					if(!$ignore_break){
						break;
					}
				case 1:
					$c .="<tr>
						<td class='alt2'>".XL_teamreport_demolink."</td>
						<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />
						<td class='alt2'><input type='file' name='x1demo' id='x1demo' /></td>
					</tr>";
					break;
			}
			//Screen shots
			$ignore_break=false;
			switch(X1_fup_image){
				default:
					$ignore_break=true;
				case 0:
					$c.="<tr>
						<td class='alt2'>".XL_teamreport_screenlink."</td>
						<td class='alt2'><input type='text' size='40' name='screen1'></td>
					</tr>
					<tr>
						<td class='alt1'>".XL_teamreport_screenlink."</td>
						<td class='alt1'><input type='text' size='40' name='screen2'></td>
					</tr>";				
					if(!$ignore_break){
						break;
					}
				case 1:
					$c.="<tr>
						<td class='alt2'>".XL_teamreport_screenlink."</td>
						<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />
						<td class='alt2'><input type='file' name='x1scrn1' id='x1scrn1' /> </td>
					</tr>
					<tr>
						<td class='alt1'>".XL_teamreport_screenlink."</td>
						<td class='alt1'><input type='file' name='x1scrn2' id='x1scrn2' /></td>
					</tr>";	
					break;
			}
			

		$c.="</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<th colspan='2'>
				<input name='laddername' type='hidden' value='$event[title]'>
				<input name='winnername' type='hidden' value='$otherteam'>
				<input name='losername' type='hidden' value='$teamname'>
				<input name='map1' type='hidden' value='$challenge[map1]'>
				<input name='map2' type='hidden' value='$challenge[map2]'>
				<input name='randid' type='hidden' value='$challenge[randid]'>
				<input name='".X1_actionoperator."' type='hidden' value='$func'>
				<input type='Submit' name='Submit' value='$button'>
				</th>
			</tr>
		</tbody>
	</table>
	</form>";
	if (X1_showsettingschall){
		X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
		$c .= $gx_event_manager->X1ModInfo();       
		$c .= laddersettings($event['sid']);
	}
	if (X1_showruleschall){      
		$event['bodytext'] = stripslashes($event['bodytext']);
		$c .= XL_teamreport_rules."$event[bodytext]";
	}
	return DispFunc::X1PluginOutput($c);
}

/*#####################################
Function: DisplayMap
Needs:dabatbasevar $maps, int $nummaps databaseinfo $event,  Stinrg $otherteam, String, $teanmname, boolean winnerscore
Returns: String $output
What does it do:Sets up and displayes infor on the maps
######################################*/
function DisplayMap($maps, $nummaps, $event,$otherteam,$teamname, $winnerscore=true) {
  $mapsarry=explode(",",$maps);
	$curmap=0;
	$output ='';
	while($curmap < $nummaps){
		$my_map = X1Misc::MapInfo($maps);
		list ($mapname, $mappic, $mapdl) = $my_map[$mapsarry[$curmap]];
		$output .= "
		<tr>
			<td class='alt1' colspan='2'>$mapname</td>
		</tr>
		<tr>
			<td class='alt2'><img src='".X1_imgpath."/maps/$mappic' title='$mapname'></td>
			<td class='alt2'>";
				if($winnerscore){
					$output .="<input type='int' name='m1winnerscore[]' size='5' maxlength='4' value='".XL_na."'>::$otherteam<br />
					<input type='int' name='m1loserscore[]' size='5' maxlength='4' value='".XL_na."'>::$teamname";
				}
				else{
					$output .="<input type='int' name='m2winnerscore[]' size='5' maxlength='4' value='".XL_na."'>::$otherteam<br />
					<input type='int' name='m2loserscore[]' size='5' maxlength='4' value='".XL_na."'>::$teamname";
				}
			$output .="</td>
		</tr>";
		$curmap++;
	}
	return $output;
}


/*#####################################
Function: x1_reportloss
Needs:string $op
Returns: N/A
What does it do:Sets up and records the record for drawl or loss as appropriate.  On error retruns to reportform.
######################################*/
function X1_reportloss($op) {
	global $gx_event_manager;
	
	if(!isset($op)){
		UserLog("No Draw or Loss operator specified","X1_reportloss", "Major Error",ERROR_DIE);
	}
	$c  = DispFunc::X1PluginStyle();
	if(!X1Cookie::CheckLogin(X1_cookiename)){
		$c .= XL_notlogggedin;
		return DispFunc::X1PluginOutput($c);
	}
	list ($cookieteamid, $losername) = X1Cookie::CookieRead(X1_cookiename);
	
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," WHERE randid = ".MakeItemString(DispFunc::X1Clean($_POST['randid'])));
	if(!$challenge){
		UserLog(XL_failed_retr."(Var:challenge, Table:".X1_DB_teamchallenges.")", "X1_reportloss","Major Error",ERROR_DIE);
	}
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	if(!$event){
		UserLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")", "X1_reportloss", "Major Error",ERROR_DIE);
	}
	
	$winner=DispFunc::X1Clean($_POST['winnername']);
	$loser=DispFunc::X1Clean($_POST['losername']);
	
	#if the winner or loser is blank, there is a prob
	if (empty($winner)) {
		UserLog("Empty winnername", "X1_reportloss", "Minor Error",ERROR_RET);
		$c = DispFunc::X1PluginTitle(XL_teamreport_blankwinner);
		return DispFunc::X1PluginOutput($c);
	}
	if (empty($loser)) {
		UserLog("Empty losername", "X1_reportloss", "Minor Error",ERROR_RET);
		$c = DispFunc::X1PluginTitle(XL_teamreport_blankname);
		return DispFunc::X1PluginOutput($c);
	}
	
	#if the winner = the loser then there is a problem
	if (strcasecmp($winner,$loser)==0){
		UserLog("Names are the same", "X1_reportloss", "Minor Error",ERROR_RET);
		$c = DispFunc::X1PluginTitle(XL_teamreport_playwithself);
		 return DispFunc::X1PluginOutput($c);
	}

	$row= SqlGetAll("mail, team_id, name",X1_DB_teams," WHERE name = ".MakeItemString($winner)." or name=".MakeItemString($loser));
	if(!$row){
		UserLog(XL_failed_retr."(Var:row, Table:".X1_DB_teams.")", "X1_reportloss", "Major Error",ERROR_DIE);
	}	

	foreach($row as $team){
		if(strcasecmp($team['name'],$winner)==0){
			$winnermail=$team['mail'];
			$winner_id=$team['team_id'];	
		}
		else{
			$losermail=$team['mail'];
			$loser_id=$team['team_id'];
		}
	}
	unset($row);
	
	$oneway= GetTotalCountOf("winner_id",X1_DB_teamhistory," WHERE winner = ".MakeItemString($winner)." AND loser = ".MakeItemString($loser)." AND date >= ".MakeItemString(time()-3200*24)." AND laddername = ".MakeItemString($event['sid']));
	$otherway= GetTotalCountOf("winner_id",X1_DB_teamhistory," WHERE winner = ".MakeItemString($loser)." AND loser = ".MakeItemString($winner)." AND date >= ".MakeItemString(time()-3200*24)." AND laddername = ".MakeItemString($event['sid']));
	$numgames = $oneway + $otherway;

	#Check to see if X games have allready been played today with this team, if yes error
	if ($numgames >= $event['gamesmaxday']){
		$c = DispFunc::X1PluginTitle(XL_teamreport_gamesmaxday);
		return DispFunc::X1PluginOutput($c);
	}
	
	$mapnumarray=array($event['nummaps1'],$event['nummaps2']);
	
	$m1winner=DispFunc::X1Clean($_POST['m1winnerscore'],5);
	$m2winner=DispFunc::X1Clean($_POST['m2winnerscore'],5);
	$m1loser=DispFunc::X1Clean($_POST['m1loserscore'],5);
	$m2loser=DispFunc::X1Clean($_POST['m2loserscore'],5);

	if($m1winner && $m2winner && $m1loser && $m2loser){
		$m1winnerarray = implode(",", $m1winner);
		$m2winnerarray = implode(",", $m2winner);
		$m1loserarray  = implode(",", $m1loser);
		$m2loserarray  = implode(",", $m2loser);
	}
	else{
		DispFunc::X1PluginOutput(DispFunc::X1PluginTitle(XL_teamreport_blankscores));
		return X1_reportform();
	}
	
	$mapnum  = implode(",", $mapnumarray);

	#Check to see if the ladder is active
	if ($event['active'] != 1){
		$c = DispFunc::X1PluginTitle(XL_teamreport_notactive);
		return DispFunc::X1PluginOutput($c);
	}
	#Check to see if the ladder is active
	if ($event['enabled'] != 1){
		$c = DispFunc::X1PluginTitle( XL_teamreport_disabled);
		 return DispFunc::X1PluginOutput($c);
	}

	DetermineImageUp($event);
	DetermineDemoUp($event);
	
	$ids=array("winner"=>$winner, "winner_id"=>$winner_id, "loser"=>$loser, "loser_id"=>$loser_id);
	if (empty($event['type'])){
		UserLog(XL_missingfile."for event","x1_reportloss","Major error", ERROR_RET);
		$c .= XL_missingfile; 
		return DispFunc::X1PluginOutput($c);
	}
	
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	switch($op){
		case "X1_reportloss":
			if($gx_event_manager->X1ReportLoss($ids,$mapnum,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event)){
				$suc=AddPlayedGame($ids,$mapnum,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event);
				if($suc){
					$del=DeleteFromChallenge($challenge);
				}
			}
			else{
				UserLog("Failed To Successfully modify the Database for a loss.","X1_reportloss","Major Error",ERROR_DIE);
				
			}
			if($suc && $del){
				if (X1_emailon){ #Send off the Email to each team if enabled
					if($event['whoreports']=="loser"){
						$content = array(
							'winner' =>  $winner,
							'loser' => $loser,
							'winnermail' => $winnermail,
							'losermail' => $losermail,
							'event' => $event['title']);
						$c .= X1Misc::X1PluginEmail($losermail, "recievedloss.tpl", $content, XL_teamreport_emailloss);
						$c .= X1Misc::X1PluginEmail($winnermail, "recievedwin.tpl", $content, XL_teamreport_emailwin);	
					}
					else{
						$content = array(
							'winner' => $loser,
							'loser' => $winner,
							'winnermail' => $losermail,
							'losermail' => $winnermail,
							'event' => $event['title']);
						$c .= X1Misc::X1PluginEmail($winnermail, "recievedloss.tpl", $content, XL_teamreport_emailloss);
						$c .= X1Misc::X1PluginEmail($losermail, "recievedwin.tpl", $content, XL_teamreport_emailwin);	
					}
				}
			}
			else{
				UserLog("Failed To Successfully add a played game and/or remove challenge.","X1_reportloss","Major Error",ERROR_DIE);
			}
			break;
		
		case "X1_reportdraw":
			if($gx_event_manager->X1ReportDraw($ids,$mapnum,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event)){
				$suc=AddPlayedGame($ids,$mapnum,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event, $draw=1);
				if($suc){
					$del=DeleteFromChallenge($challenge);
				}
			}
			else{
				UserLog("Failed To Successfully modify the Database for a draw.","X1_reportloss","Major Error",ERROR_DIE);
				
			}
			if($suc && $del){
				if (X1_emailon){ #Send off the Email to each team if enabled
						$content = array(
							'winner' =>  $winner,
							'loser' => $loser,
							'event' => $event['title']);
						$c .= X1Misc::X1PluginEmail($losermail, "recieveddraw.tpl", $content, XL_teamreport_emaildraw);
						$c .= X1Misc::X1PluginEmail($winnermail, "recieveddraw.tpl", $content, XL_teamreport_emaildraw);
				}
			}
			else{
				UserLog("Failed To Successfully add a played game and/or remove challenge.","X1_reportloss","Major Error",ERROR_DIE);
			}
				break;
			default:
				UserLog("Failed to determine if it was a Draw, Loss or Win","X1_reportloss","Major Error",ERROR_DIE);
				break;
		}

	return DispFunc::X1PluginOutput(displayteam("events",$c));
}

/*######################################
Name:AddPlayedGame
Needs:array $ids(string $winner, string $loser_id, string $loser, string $winner_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event, $draw=0
Returns: bool $success  True on success, false on failure.
What does it do:Adds a played game to the database.
#######################################*/	
function AddPlayedGame($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event, $draw=0){
		if($draw==1){
			$dra=",".MakeItemString(1);
		}
		else{
			$dra=",".MakeItemString(0);
		}
		
		#Add the new played game to the datebase
		$success = ModifySql("INSERT INTO",X1_DB_teamhistory, "
		(laddername, winner_id, winner, loser_id, loser, date, comments, map1, map2, mapsettotal, map1t1, map1t2, map2t1, map2t2, scrnsht1, scrnsht2, demo, draw) VALUES (
		".MakeItemString($event['sid']).", 
		".MakeItemString($ids['winner_id']).", 
		".MakeItemString($ids['winner']).",
		".MakeItemString($ids['loser_id']).",  
		".MakeItemString($ids['loser']).", 
		".MakeItemString(time()).", 
		".MakeItemString(DispFunc::X1Clean($_POST['comments'])).", 
		".MakeItemString($challenge['map1']).", 
		".MakeItemString($challenge['map2']).", 
		".MakeItemString($mapnumarray).", 
		".MakeItemString($m1winnerarray).", 
		".MakeItemString($m1loserarray).",  
		".MakeItemString($m2winnerarray).", 
		".MakeItemString($m2loserarray).",  
		".MakeItemString($_POST['screen1']).", 
		".MakeItemString($_POST['screen2']).", 
		".MakeItemString($_POST['demo'])."
		".$dra.")");

		return $success;
}



/*####################################
Name:DeleteFromChallenge  (Private!)
Needs:databaseinfo challenge)
Returns:string $rung
What does it do:Deletes the team after the challenge has been complete, and deletes the messages as well.
#####################################*/	
function DeleteFromChallenge($challenge){
	#Remove Challenge from databade
	$suc_count=0;
	$success[$suc_count++]=ModifySql("DELETE FROM ", X1_DB_teamchallenges, "WHERE randid = ".MakeItemString($challenge['randid']));
	if(GetTotalCountOf("message_id", X1_DB_messages,"where randid=".MakeItemString($challenge['randid']))){
	#get rid of the messages written for the challenge.
		$success[$suc_count++]=ModifySql("Delete from ", X1_DB_messages, "where randid=".MakeItemString($challenge['randid']));
	}
	else{
		$success[$suc_count++]=true;
	}
	
	if($success[0] && $success[1]){
		return true;
	}
	else{
		return false;
	}
}

function DetermineImageUp($event){
	$set=0;
	$img1=DispFunc::X1Clean($_POST['screen1']);
	$img2=DispFunc::X1Clean($_POST['screen2']);
	switch(X1_fup_image){
		case 0://link
			if(!empty($img1)){
				$_POST['screen1']="0::".$img1;
			}
			if(!empty($img2)){
				$_POST['screen2']="0::".$img2;
			}
			break;
		case 1://upload
			$_POST['screen1']=X1File::X1UploadFile("x1scrn1",$event);
			$_POST['screen2']=X1File::X1UploadFile("x1scrn2",$event);
			break;
		case 2://take your pick.
			$set=0;
			if(isset($img1)){
				$set++;
				$_POST['screen1']="0::".$img1;
				$slot1=true;
			}
			if(isset($img2)){
				$set+=2;
				$_POST['screen2']="0::".$img2;
				$slot2=true;
			}
			if(isset($_FILE["x1scrn1"])){
				switch($set){
					case 1:
						$_POST['screen2']=X1File::X1UploadFile("x1scrn1",$event);
						break;
					case 2:
						$_POST['screen1']=X1File::X1UploadFile("x1scrn1",$event);
						break;
					case 3://Both screens in use.
						break;
					default:
						$_POST['screen1']=X1File::X1UploadFile("x1scrn1",$event);
						break;
				}
				$set+=4;	
			}
			if(isset($_FILE["x1scrn2"])){
				switch($set){
					case 1:
						$_POST['screen2']=X1File::X1UploadFile("x1scrn2",$event);
						break;
					case 2:
						$_POST['screen1']=X1File::X1UploadFile("x1scrn2",$event);
						break;
					case 3://Both screens in use.
					case 5:
					case 6:
						break;
					default:
						$_POST['screen2']=X1File::X1UploadFile("x1scrn2",$event);
						break;
					}
			}
			break;
		default:
			UserLog("Failed image retreival from user", "DeterminImageUp", "Major Error",ERROR_DISP);		
			return false;
			break;
	}//end_switch for img
}

function DetermineDemoUp($event){
	$demo=DispFunc::X1Clean($_POST['demo']);
	switch(X1_fup_demo){
		case 0://link
			if(empty($demo)){
				break;
			}
			$_POST['demo']="0::".$demo;
			break;
		case 1://upload
			$_POST['demo']=X1File::X1UploadFile("x1demo",$event);
			break;
		case 2://Take your pick
			if(!empty($demo)){
				$_POST['demo']="0::".$demo;
			}
			else{
				$_POST['demo']=X1File::X1UploadFile("x1demo",$event);
			}
			break;
		default:
			UserLog("Failed demo retrieval from user", "DeterminDemoUp", "Major Error",ERROR_DISP);		
			return false;
			break;
	}//end_switch
	return true;
}

?>
