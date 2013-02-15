<?php
###############################################################
##X1plugin Competition Management
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2009
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################


/*######################################################
#Interface:X1EventMods
#What and why: This interface is built on the strategy design pattern which will on the fly switch between the 
#types of events.
######################################################*/
interface X1EventMods{
	
/*######################################
Name:X1AcceptChallenge
Needs:array $maps, databaseinfo $challenge, datbaseinfo $event
Returns: bool $success
What does it do:Takes the inforation provided and inserts the event into
the challenge team table making the challenge an official challenge.
#######################################*/	 
    public function X1AcceptChallenge($challenge, $event, $maps, $team_names);

/*######################################
Name:X1SetChallenge
Needs:array maps, array dates, int $randid, databaseinfo $rchallenged, databaseinfo $rchallenger databaseinfo $challenger, databaseinfo $challenged, databaseinfo $event  
Returns: bool $success
What does it do:Takes the inforation provided and inserts the event into
the tempchallenge table, this means a team has been challenged but has yet to accept or decline.
#######################################*/	     
	public function X1SetChallenge($maps, $dates, $randid, $challenger, $challenged, $event, $rchallenged='', $rchallenger=''); // challenge.php
	
/*######################################
Name:X1JoinEvent
Needs:int $numteamsonladder, int $team_id, databaseinfo $lad, databaseinfo $teaminfo, array extainto
Returns: bool $success
What does it do:Takes the inforation provided puts the team into said event.
#######################################*/	
	public function X1JoinEvent($numteamsonladder, $team_id, $lad, $teaminfo, $extainto=0); // join.php
	
/*######################################
Name:X1DeclineChallenge
Needs:int $newpoints, databaseinfo $challenge, databaseinfo $event
Returns: bool $success
What does it do:Takes the information provided, and removes the challenge from the tempchallenge database. 
#######################################*/		
	public function X1DeclineChallenge($newpoints, $totalnewpoints, $challenge, $event);	//4
	
/*######################################
Name:X1ModInfo
Needs:N/A
Returns: string $outpout
What does it do:Displays the info about the mod.
#######################################*/			
	public function X1ModInfo();
	
/*######################################
Name:X1QuitEvent
Needs:databaseinfo $lad
Returns: bool $success
What does it do:Removed the team from the event
#######################################*/
	public function X1QuitEvent($lad, $team_id);  // Quit.php
	
/*######################################
Name:X1ReportDraw
Needs:array $ids(string $winner, string $winner_id, string $loser, string $loser_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a draw for said event
#######################################*/		
	public function X1ReportDraw($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event);

/*######################################
Name:X1ReportLoss
Needs:array $ids(string $winner, string $winner_id, string $loser, string $loser_id), array $mapnumarray, array $m1winnerarray, array $m1loserarray, array $m2winnerarray, array $m2loserarray, databaseinfo $challenge,databaseinfo $event
Returns: bool $success
What does it do:Reports a loss for said event
#######################################*/	
	public function X1ReportLoss($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event);
	
/*######################################
Name:X1ResetEvent
Needs:int $ladder_id
Returns: bool $success
What does it do:resets stats for all teams on said event. 
#######################################*/		
	public function X1ResetEvent($ladder_id);
	
/*######################################
Name:X1Standings
Needs:int $sid=0, string $limit="", int $start=0
Returns: string
What does it do:creates the standings for players on the ladder
#######################################*/		
	public function X1Standings($event, $game, $sid, $limit="", $start=0, $numberofplayersin=0);
	
/*######################################
Name:X1WithdrawChallenge
Needs:databaseinfo $challenge, databaseinfo $event
Returns: bool $success
What does it do:Withdrawls a challenge that was out forth  (The assuming the other team has NOT yet accepted.)
#######################################*/		
	public function X1WithdrawChallenge($challenge, $event);
	
/*######################################
Name:X1DisplaySpecialFeatures
Needs:boolean $edit=false, databaseinfo $event
Returns: String $output
What does it do:If there are specail requirements for an event will display them in the event creation page
#######################################*/		
	public function X1DisplaySpecialFeatures($edit=true,$event=0);
	
/*######################################
Name:X1HasSpecialFeatures
Needs:N/a
Returns: bool hasspecial
What does it do:If there are special features to be seen it returns true other wise it returns false.
#######################################*/			
	public function X1HasSpecialFeatures();
	
/*######################################
Name:X1DataInsert
Needs:boolean $edit=false
Returns: array of 2 strings.
What does it do:Takes the information needed for a special event, both the needed col names of the
database and the $_POST of said special features, and puts it in the form required to run the db 
function.
########################################*/	
	public function X1DataInsert($edit=false);
	
/*####################################
Name:DeleteFromChallenge  (Private!)
Needs:databaseinfo challenge)
Returns:string $rung
What does it do:Deletes the team after the challenge has been complete, and deletes the messages as well.
#####################################*/	
	//************************************************
	//public function DeleteFromChallenge($challenge);
	//************************************************
	//Note of the bove, this MUST be included in the mod however inorder to apply to PHP laws this will NOT be included as an interfare and must be used for an example.
}
?>
