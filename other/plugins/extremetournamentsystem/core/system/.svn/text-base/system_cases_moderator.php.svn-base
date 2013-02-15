<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008 (2.6.0)
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))die("Not Valid Include");
function modswitch($op)
{
	switch($op){
	
		case "mod_modifyladderTeam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				modifyladderTeam(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
		case "mod_updateteam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				adminupdateteam(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
	   	case "mod_updateladderTeam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				updateladderTeam(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
	    case "mod_delTeam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1_removeteam(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	    
		case "mod_delladderTeam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1_removeladderteam(true); 
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	    case "mod_modifyTeam":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				modifyTeam(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	    case "mod_createplayedgame":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				createplayedgame(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	    case "mod_insertplayedgame":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				insertplayedgame(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	    case "mod_modifymatch":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				modifymatch(true); 
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_delmatch":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1_removematch(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
	   case "mod_updatematch":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				updatematch(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_deldispute":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1_removedispute(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_insertchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				insertchallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_editchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				editchallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_updatechallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				updatechallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_edittempchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				edittempchallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_updatetempchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				updatetempchallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_deletechallenge":
	   case "mod_deletetempchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				deletechallenge(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;

	   case "mod_createchallenge":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				listchallenges(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	
	   case "mod_message":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				global $gx_message_param;
				$gx_message_param = "admin_mess";
				listchallenges(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
		case "x1_modreplymessage":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
	        				global $gx_message_param;
				$gx_message_param = "admin_send";
				$admin_mess = new ChallengeMessageSystem($_POST["team_id_w"],$_POST["team_name_w"],$_POST["team_id_l"],$_POST["team_name_l"]);
				$admin_mess->AdminSendMessage();
				echo XL_adminmess_sent.".<br />";
				listchallenges(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
	   case "mod_displayeventteams":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				DisplayTeamFromEvent(true);
	        }else{
	          echo XL_moderatoronly;
	        }
	        break;
	        
	    case "mod_ad_plsearch":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1FindPlayer(true);
	    }
			else{
	    	echo XL_moderatoronly;
	    }
	    break;

	    case "mod_ad_modify":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				DisplayEditableProfile(true);
	    }
			else{
	    	echo XL_moderatoronly;
	    }
	    break;

	    case "mod_plyupdate":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1UpdatePlayer(true);
	    }
			else{
	    	echo XL_moderatoronly;
	    }
	    break;

			case "mod_remove_pl_fr_team":
			case "mod_plytremov_all":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
				X1DeletePlayer(true);
	    }
			else{
	    	echo XL_moderatoronly;
	    }
	    break;

		default:
			return true; 
	    
	}
}
    
?>