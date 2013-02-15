<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4 Alpha v2
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################


class ChallengeMessageSystem
{
		private $myteam_id, $myteam_name, $myteam_id_l, $myteam_name_l, $myunreadmessages;	 
		
		//tables: X1_DB_message  (has team_id and the name total new messages)
/*###########################
Name: __construct
Needs: int $team_id, sting $team_name
Returns: N/A
What does it do: Default constructor for message system of the end user
############################*/	
	public function __construct($team_id,$team_name, $team_id_l="n|a" ,$team_name_l="n|a") {
		$this->myteam_id=$team_id;
		$this->myteam_name=$team_name;
		$this->myteam_id_l=$team_id_l;
		$this->myteam_name_l=$team_name_l;
		$unreadmessages=SqlGetRow("totalnmessag",X1_DB_teams,"where team_id =".MakeItemString($team_id));
		$this->myunreadmessages=$unreadmessages['totalnmessag'];
	}
	
/*############################
Name: GetAndDisplayTotalNewMesage
Needs:N/A
returns: string $output
What does it do: Determines how many new messages you have in all challenges
##############################*/
	public function GetAndDisplayTotalNewMessage(){
		return "(".$this->myunreadmessages.")";
	}

/*#############################
Name: GetAndDisplayTotalNewChallengeMess
Needs:int rand_id
returns:string $output
What does it do: It defines a single line that out puts 
##############################*/
	public function GetAndDisplayTotalNewChallengeMess($rand_id){
		$total_new=0;
		$challenge=SqlGetRow("winner,loser,ladder_id",X1_DB_teamchallenges,"where randid=".MakeItemString($rand_id));
		if($this->myunreadmessages!=0)
		{
			$messages=SqlGetAll("hasread",X1_DB_messages,"where randid =".MakeItemString($rand_id));	
		
			foreach($messages As $message){
				if($message['hasread']=='0'){
					$total_new++;
				}
	
			}
		}
		$name=X1TeamUser::SetTeamName(array($challenge['winner'], $challenge['loser']));
		$ladder_name=SqlGetRow("title",X1_DB_events,"where sid=".MakeItemString($challenge['ladder_id']));
		return "<tr>
		<td class='alt2'><a href='".X1_publicpostfile.X1_linkactionoperator."messages&randid=$rand_id'>".$ladder_name['title'].":".$name[$challenge['winner']]." vs ".$name[$challenge['loser']].": New Messages:(".$total_new.")</a> </td>
		</tr>";
	}

/*################################
Name:ViewMessageMenu
Needs: N/A
Returns:string $output
What does it do: This is the main link from the menu in display team, it gets and displays links to each Challenge Message.
#################################*/	
	public function ViewMessageMenu(){
		global $gx_message_param;
		$span=8;
		$challenges=SqlGetAll('randid',X1_DB_teamchallenges,"where (winner = ".MakeItemString($this->myteam_id)." or loser =".MakeItemString($this->myteam_id).") and ctemp=0");
		
		$output ="
		<table class='".X1plugin_teamadmintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teammess_title.":$this->myteam_name</th>
			</tr>
    	 </thead>
		<tbody class='".X1plugin_tablebody."'>";
			
		if($challenges){
			foreach($challenges as $challenge){
				$output.=$this->GetAndDisplayTotalNewChallengeMess($challenge['randid']);	
			}
		}
		else{
			$output .="
				<tr>
					<td colspan='$span'>".XL_teamadmin_messnone."</td>
				</tr> ";
		}

		switch($gx_message_param){
			case "view":
				$output.= $this->ViewChallMessages(htmlspecialchars($_GET['randid']));
				break;
			case "send":
				$output.= $this->SendMessage();
				break;
			default:
				break;
		}
		$output.= "</tbody>".DispFunc::DisplaySpecialFooter($span);
		return $output;
	}

/*#################################
Name: ViewChallMesssages
needs:int $rand_id
retuns:string $output
what does it do: The message screen where you are gonig to be viewing all your messages.
##################################*/
	public function ViewChallMessages($rand_id){
		global $gx_message_param;
		$user=X1_userdetails();
		$output ='';
		$theteams=SqlGetRow("winner,loser", X1_DB_teamchallenges, "where randid = ".MakeItemString($rand_id));
		if($theteams['winner']!=$this->myteam_id && $theteams['loser']!=$this->myteam_id){
			//Security, keeps other teams/people from seeing your teams messages.
			return XL_teamadmin_invalidteammes;
		}

		$messages_read = 0;
		//First now that we are reading messages we make sure to say that we have read them.
		
		if($this->myunreadmessages!=0){
			$result=ModifySql("update", X1_DB_messages, "set hasread = 1 where randid =".MakeItemString($rand_id)." and hasread=0");
			//Get what rows have been affected.
			$messages_read = GetAffectedRows();
			if($gx_message_param=="send"){
				$messages_read=$messages_read-1;
			}
			if(!$result){
				$output .=XL_achallenges_databaseopps;
			}
		}
		
		//Make sure to update the total messages if it needs updating
		if($messages_read>0 && $this->myunreadmessages!=0){
			if($messages_read>$this->myunreadmessages){
				$messages_read=$this->myunreadmessages;
			}
			$result=ModifySql("update", X1_DB_teams, "set totalnmessag = totalnmessag-".MakeItemString($messages_read)." where team_id = ".MakeItemString($this->myteam_id));
			
			if(!$result){
				$output .=XL_achallenges_databaseopps;
			}
		}

		$messagecount=1;
		//formating the mesage 2 parts information (sender name, team name, message)
		//reply part  text box, with a reply/send button
		$output .="<table class='".X1plugin_teamadmintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
			<table class='".X1plugin_title."' width='100%' border='0' cellspacing='1' cellpadding='6'>
				<td colspan='2'><hr /></td>";

		$output.= $this->GetMessages($rand_id, &$messagecount, &$rteam, $theteams);
	
		
		$output.= $this->CreateSendBox($rand_id,$rteam,$user[1],$messagecount);
			$output.= "</thread>
			</table>";
		return $output;
	}


/*################################
Name: GetMessages
Needs:int $rand_id, int &$totalmessages, int &$rteam, array $theteams
returns: string $output
what does it do?:Gets all the messages, and gets it set to displays the message, the team that send the message, the user that sent the message.  There is no way to edit the message once it has been sent (to keep the record from being corrupt if a dispute should arise)
#################################*/		
	public function GetMessages($rand_id, &$totalmessages, &$rteam, $theteam, $admin=false){
		$messages=SqlGetAll("*",X1_DB_messages,"where randid =".MakeItemString($rand_id)." order by messid");		
		if(!$admin){
			if($theteam['winner']==$this->myteam_id){
				//sql query for the loser
				$team=SqlGetRow("team_id, name", X1_DB_teams, "where team_id=".MakeItemString($theteam['loser']));
				$senderteam = array($this->myteam_id=>$this->myteam_name, $theteam['loser']=>$team['name']);
			}
			else{
				//sql query for the winner
				$team=SqlGetRow("team_id, name", X1_DB_teams, "where team_id=".MakeItemString($theteam['winner']));
				$senderteam = array($this->myteam_id=>$this->myteam_name, $theteam['winner']=>$team['name']);
			}
		}
		else{
			$senderteam = array($this->myteam_id=>$this->myteam_name, $this->myteam_id_l=>$this->myteam_name_l);			
		}
		//Inorder to show the administration team it must be
		$senderteam[0] = XL_adminmess_modteam;
		
		$rteam=$team['team_id'];
		if($messages){
			$output='';
 			foreach($messages as $message){//1
			 	$output.="<tr>
	        		<td width='15%' align='left' valign='top' class='row1'>
	         		<span><a name='$message[messid]'></a>
	         			<strong>".XL_adminmess_team.": ".$senderteam[$message['steam_id']]."</strong><br />
						<strong>".XL_adminmess_user.": ".$message['sender']."</strong><br />
						".XL_adminmess_sent.": ".date(X1_extendeddateformat,$message['tstamp'])."
					</span><br />
	                	<td width='100%' height='100%' valign='top'>
						<table width='100%' border='0' cellspacing='0' cellpadding='0'>
	            			<tr>
	                			<td width='100%'><span class='postdetails'>".XL_adminmess_message.": ".$message['messid']."</span></td>
	                		</tr>
	            			<tr>
	                			<td colspan='2'><hr /></td>
	            			</tr>
	            			<tr>
	                			<td colspan='2' height='100%' valign='top'><span> ".DispFunc::X1_HTMLReady($message['message'])." </span></td>
							</tr>
	                	</table>
					</td>
				</td>	
			    </tr>
	    		<tr>
	            	<td colspan='2'><hr /></td>
	    		</tr>";                
	    		$totalmessages++;
			}//foreach 1
		}
		else{
			$output = XL_teamadmin_nomessages;
		}
		return $output;
	}
	
	/*################################
	Name: CreateSendBox
	Needs: int $rand_id, int &$totalmessages, int &$rteam, array $theteams, boolean admin=false;
	returns: string $output
	what does it do?: Create a message box.
	#################################*/	
	
	public function CreateSendBox($randid,$rteam,$username,$messagecount,$admin=false){
		$output ="<tr>
		<td class='alt2'>
		<tbody class='tbody'>
			<tr>
				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
				<td class='alt2' colspan='2'><textarea wrap='virtual' cols='100' rows='12' name='x1_hometext'></textarea></td>
			</tr>
		</tbody>

		</td>
		<td class='alt1'>
		
	    		<input name='randid' type='hidden' value='".$randid."' />
	    		<input name='receiveteam' type='hidden' value='$rteam' />
	    		<input name='messagecount' type='hidden' value='$messagecount' />
	    		<input name='sender' type='hidden' value='$username' />";
	    		if((X1Moderator::CheckStaff(true) || check_admin()) && $admin){
					$output .="
					<input name='sendteam' type='hidden' value='0' />
	    			<input name='team_id_w' type='hidden' value='$this->myteam_id'>
					<input name='team_id_l' type='hidden' value='$this->myteam_id_l'>
					<input name='team_name_w' type='hidden' value='$this->myteam_name'>
					<input name='team_name_l' type='hidden' value='$this->myteam_name_l'>
					<input name='ladder_id' type='hidden' value='".DispFunc::X1Clean($_POST['ladder_id'])."'>";
					if(X1Cookie::CheckLogin(X1_cookiemod)){
						$output .="<input name='".X1_actionoperator."' type='hidden' value='x1_modreplymessage' />";
					}
					else{
						$output .="<input name='".X1_actionoperator."' type='hidden' value='x1_adminreplymessage' />";	
					}
					
	    			$output .="<input type='submit' value='".XL_teamadmin_reply."' />";
				}
				else{
	    			$output .="
	    			<input name='sendteam' type='hidden' value='".$this->myteam_id."' />
	    			<input name='".X1_actionoperator."' type='hidden' value='x1_replymessage' />
	    			<input type='submit' value='".XL_teamadmin_reply."' />";
	    		}
    		$output .="</form>
    	</td>
		</tr>";
		return $output;
	}
	
	
	/*###################################################
	Name: SendMessages
	Needs:N/A
	Returns: N/A
	What does it do: Takes the message as recorded and put it into the database to be retreived.
	###################################################*/
	public function SendMessage(){
		$receive_team=MakeItemString(DispFunc::X1Clean($_POST['receiveteam']));
		ModifySql("insert into", X1_DB_messages, 
		"(randid, messid, message, hasread, steam_id, sender, rteam_id, tstamp) 
		values (".MakeItemString(DispFunc::X1Clean($_POST['randid'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['messagecount'])).", 
		".MakeItemString(trim(DispFunc::X1Clean($_POST['x1_hometext'],$cleantype=3))).", 
		".MakeItemString(0).", 
		".MakeItemString(DispFunc::X1Clean($_POST['sendteam'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['sender'])).", 
		".$receive_team.", 
		".MakeItemString(time()).")");
	
		ModifySql("update", X1_DB_teams, "set totalnmessag=totalnmessag+1 where team_id = ".$receive_team);	
	}
	
	/*###################################################
	Name: AdminSendMessages
	Needs:N/A
	Returns: N/A
	What does it do: Takes the message as recorded and put it into the database to be retreived.
	###################################################*/	
	public function AdminSendMessage(){
		$send_team=MakeItemString(DispFunc::X1Clean($_POST['sendteam']));
		$receive_team=MakeItemString(DispFunc::X1Clean($_POST['receiveteam']));
		
		ModifySql("insert into", X1_DB_messages, "(randid,messid,message,hasread,steam_id,sender,rteam_id,tstamp) 
		values (".MakeItemString(DispFunc::X1Clean($_POST['randid'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['messagecount'])).", 
		".MakeItemString(DispFunc::X1Clean($_POST['x1_hometext'],$cleantype=3)).", 
		0,
		".$send_team.", 
		".MakeItemString(DispFunc::X1Clean($_POST['sender'])).", 
		".$receive_team.",  
		".time().")");
		
		ModifySql("update", X1_DB_teams, "set totalnmessag=totalnmessag+1 where team_id = ".$receive_team." or team_id = ".$send_team);
	}

	/*###################################################
	Name: AdminViewMess
	Needs:int randid
	Returns: N/A
	What does it do: admin view of the messages
	###################################################*/
	public function AdminViewMess($rand_id){
		if(!check_admin()&&!X1Moderator::isMod()){
			return "No comith";
		}
	
		$user=X1_userdetails();
		if(empty($user[1])){
			$user[1]="Moderator";
		}

		$messagecount=1;
		//formating the mesage 2 parts information (sender name, team name, message)
		//reply part  text box, with a reply/send button
		$output .="<table class='".X1plugin_teamadmintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
			<table class='".X1plugin_title."' width='100%' border='0' cellspacing='1' cellpadding='6'>
				<td colspan='2'><hr /></td>";
		
		$output .= $this->GetMessages($rand_id, &$messagecount, &$forgetme, "n/a", $admin=true);
		
		$output .= $this->CreateSendBox($rand_id,$this->myteam_id_l,$user[1],$messagecount, $admin=true);
		$output .= "</thread>
			</table>";
		return $output;
	}	

		
}


?>