<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008 (2.6.0)
##Version 2.6.0
###############################################################
if (!defined('X1plugin_include'))die("Not Valid Include");
	function adminswitch($op)
	{
		switch($op){
		
			case "admin":
		        if(check_admin()){
					X1_require_admin();
		            x1_admin('home');
		        }else{
		          echo XL_adminonly;
		        }
			break;
		
			case "addgames":
		        if(check_admin()){
					X1_require_admin();
		            addgames();
		        }else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updategames":
		        if(check_admin()){
					X1_require_admin();
					updategames();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "xadminladder":
		        if(check_admin()){
					X1_require_admin();
					X1plugin_adminladder();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "newevent":
		        if(check_admin()){
					X1_require_admin();
					newcompevent();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "fixladderrungs":
		        if(check_admin()){
					X1_require_admin();
					X1ResetEvents();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "editevent":
		        if(check_admin()){
					X1_require_admin();
					x1_editevent();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "ChangeLadder":
		        if(check_admin()){
					X1_require_admin();
					changeLadder();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "RemoveLadder":
			    if(check_admin()){
					X1_require_admin();
					removeLadder();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "addmaps":
			    if(check_admin()){
					X1_require_admin();
					addmaps();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatemaps":
			   if(check_admin()){
					X1_require_admin();
					updatemaps();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "addmapgroups":
			    if(check_admin()){
					X1_require_admin();
					addmapgroups();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatemapgroups":
			   if(check_admin()){
					X1_require_admin();
					updatemapgroups();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "addmapstogroup":
			    if(check_admin()){
					X1_require_admin();
					addmapstogroup();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "editmapgroup":
			   if(check_admin()){
					X1_require_admin();
					editmapgroup();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "delTeam":
			    if(check_admin()){
					X1_require_admin();
					X1_removeteam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "displayeventteams":
			if(check_admin()){
				X1_require_admin();
				DisplayTeamFromEvent();
		    }else{
		    	echo XL_adminonly;
		    }
		    break;
		
			case "modifyTeam":
			    if(check_admin()){
					X1_require_admin();
					modifyTeam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "adminupdateteam":
			    if(check_admin()){
					X1_require_admin();
					adminupdateteam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "modifyladderTeam":
			    if(check_admin()){
					X1_require_admin();
					modifyladderTeam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updateladderTeam":
			    if(check_admin()){
					X1_require_admin();
					updateladderTeam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "delladderTeam":
			    if(check_admin()){
					X1_require_admin();
					X1_removeladderteam();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "createchallenge":
			    if(check_admin()){
					X1_require_admin();
					listchallenges();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "insertchallenge":
			    if(check_admin()){
					X1_require_admin();
					insertchallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "editchallenge":
			    if(check_admin()){
					X1_require_admin();
					editchallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatechallenge":
			    if(check_admin()){
					X1_require_admin();
					updatechallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "deletechallenge":
			case "deletetempchallenge":
			    if(check_admin()){
					X1_require_admin();
					deletechallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "edittempchallenge":
			    if(check_admin()){
					X1_require_admin();
					edittempchallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatetempchallenge":
			    if(check_admin()){
					X1_require_admin();
					updatetempchallenge();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "insertplayedgame":
			    if(check_admin()){
					X1_require_admin();
					insertplayedgame();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "createplayedgame":
			    if(check_admin()){
					X1_require_admin();
		            createplayedgame();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "modifymatch":
			    if(check_admin()){
					X1_require_admin();
					modifymatch();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "delmatch":
			    if(check_admin()){
					X1_require_admin();
					X1_removematch();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatematch":
			    if(check_admin()){
					X1_require_admin();
					updatematch();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "deldispute":
			    if(check_admin()){
					X1_require_admin();
					X1_removedispute();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "updateconfigfile":
			    if(check_admin()){
					X1_require_admin();
					updateconfigfile();
					updatingconfigfile();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "defaultconfigfile":
			    if(check_admin()){
					X1_require_admin();
					updateconfigfile(true);
					updatingconfigfile();
				}else{
		          echo XL_adminonly;
		        }
			break;
		
			case "updatelangfile":
			    if(check_admin()){
					X1_require_admin();
					updatelangfile();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "assignmod":
			    if(check_admin()){
					X1_require_admin();
					X1AdminAssignMod();
				}else{
		          echo XL_adminonly;
		        }
			break;
			
			case "addmoder":
			    if(check_admin()){
					X1_require_admin();
					X1AddModer();
				}else{
		          echo XL_adminonly;
		        }
			break;	
			
			case "modifymoderator":
				if(check_admin()){
					X1_require_admin();
					X1ModifyModerator();
		        }else{
		          echo XL_adminonly;
		        }
		        break;
		    
		  case "X1_updatemoderator":
				if(check_admin()){
					X1_require_admin();
					X1UpdateMod();
		        }else{
			        echo XL_adminonly;
		        }
		        break;
		    
		  case "delmoderator":
				if(check_admin()){
					X1_require_admin();
					X1DelModerator();
		        }else{
		          echo XL_adminonly;
		        }
		        break;
		  case "adminmessage":
		  	if(check_admin()){
		  	 	X1_require_admin();
				global $gx_message_param;
				$gx_message_param = "admin_mess";
				listchallenges();
			}else{
		      echo XL_adminonly;
		    }
		    break;
		  
			case "x1_adminreplymessage":
		  	if(check_admin()){
		  	 	X1_require_admin();
				global $gx_message_param;
				$gx_message_param = "admin_send";
				$admin_mess = new ChallengeMessageSystem($_POST["team_id_w"],$_POST["team_name_w"],$_POST["team_id_l"],$_POST["team_name_l"]);
				$admin_mess->AdminSendMessage();
				echo XL_adminmess_sent.".<br />";
				listchallenges();
			}else{
		      echo XL_adminonly;
		    }
		    break;
		    
		case "displayeventteams":
			if(check_admin()){
				X1_require_admin();
				DisplayTeamFromEvent();
		    }else{
		    	echo XL_adminonly;
		    }
		    break;
		
		case "ad_plsearch":
			if(check_admin()){
				X1_require_admin();
				X1FindPlayer();
		    }else{
		    	echo XL_adminonly;
		    }
		    break;
		
		case "ad_plmodify":
			if(check_admin()){
				X1_require_admin();
					DisplayEditableProfile();
		    }else{
		    	echo XL_adminonly;
		    }
		    break;
		
		case "admin_plyupdate":
			if(check_admin()){
				X1_require_admin();
					X1UpdatePlayer();
		  }
			else{
		  	echo XL_adminonly;
		  }
		  break;    
		
		case "remove_pl_fr_team":
		case "ad_plytremov_all":
			if(check_admin()){
				X1_require_admin();
				X1DeletePlayer();
		  }
			else{
				echo XL_adminonly;
		  }
		  break;
		    
		default:
		{
			return true;
		}
	}//end switch
	return false;
	}//end function

?>
