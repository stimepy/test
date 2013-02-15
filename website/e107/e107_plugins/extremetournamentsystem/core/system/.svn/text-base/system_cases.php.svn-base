<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))die("Not Valid Include");
###############################################################
if(isset($_REQUEST[X1_actionoperator])){
	$op = Dispfunc::X1Clean($_REQUEST[X1_actionoperator]);
}
else{
	$op=NULL;
}

if(check_admin())
{
	X1File::X1LoadFile("system_cases_admin.php",X1_plugpath."core/system/");
	if(!adminswitch($op))
	{
		return;	
	}
}
if(X1Cookie::CheckLogin(X1_cookiemod))
{
	X1File::X1LoadFile("system_cases_moderator.php",X1_plugpath."core/system/");
	if(!modswitch($op))
	{
		return;
	}
}

$user_path=X1_plugpath."core/user/";
X1File::X1LoadFile("user_functions.php",$user_path);

switch($op){
	
 	case "modindex":
			if(X1Moderator::CheckStaff()){
				X1_require_moderator();
	            X1_moderator('home');
	        }
	        break;
	
	case "loginmod":
		X1Moderator::ModDoLogin();
		break;

	######################################################
	########################## User end ###
	######################################################
	
	case "eventrules":
		X1File::X1LoadFile("user_eventhome.php",$user_path);
		eventrules();
	break;
	
	case "disputeform":
		X1File::X1LoadMultiFiles(array("user_displayteam.php", "user_disputes.php"),$user_path);
				disputeform();
	break;
	
	case "dispute":
		X1File::X1LoadMultiFiles(array("user_disputes.php","user_displayteam.php"),$user_path);
		dispute();
	break;
	
	case "endteam":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		endteam();
	break;
	
	case "activate_team":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		X1_activate_team();
	break;
	
	case "displayteam":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		displayteam();
	break;
	
	case "loginteam";
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		loginteam();
	break;
	
	case "logoutteam";
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		logoutteam();
	break;
	
	case "joinladderpre":
		X1File::X1LoadmultiFiles(array("user_eventhome.php","user_displayteam.php"),$user_path);
		joinladderpre();
	break;
	
	case "joinladder":
		X1File::X1LoadmultiFiles(array("user_eventhome.php","user_displayteam.php"),$user_path);
		joinladder();
	break;
	
	case "quitladder":
	X1File::X1LoadmultiFiles(array("user_eventhome.php","user_displayteam.php"),$user_path);
		quitladder();
	break;
	
	case "coreupdateteam":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		coreupdateteam();
	break;
	
	case "removemember":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		removemember();
	break;
	
	case "updatemember":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		updatemember();
	break;
	
	case "mailteammatch";
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		mailteam();
	break;
	
	case "challengeteamform":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_eventhome.php","user_challenges.php"),$user_path);
		challengeteamform();
	break;
	
	case "cookiechallenge":
		X1File::X1LoadFile("user_cookiechallenge.php",$user_path);
		cookiechallenge();
	break;
	
	case "sendchallenge":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_challenges.php"),$user_path);
		sendchallenge();
	break;
	
	case "confirmchallform":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_eventhome.php","user_challenges.php"),$user_path);
		confirmchallform();
	break;
	
	case "acceptchall":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_challenges.php"),$user_path);
		acceptchall();
	break;
	
	case "declinechall":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_challenges.php"),$user_path);
		declinechall();
	break;
	
	case "withdrawchall";
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_challenges.php"),$user_path);
		withdrawchall();
	break;

	case "pastmatches":
		X1File::X1LoadFile("user_match.php",$user_path);
		pastmatches();
	break;
	
	case "newmatches":
		X1File::X1LoadFile("user_match.php",$user_path);
		newmatches();
	break;
	
	case "matchdetails":
		X1File::X1LoadFile("user_match.php",$user_path);
		matchdetails();
	break;
	
	case "standings":
		X1File::X1LoadFile("user_eventhome.php",$user_path);
		standings();
	break;
	
	case "playerprofile":
		X1File::X1LoadMultiFiles(array("user_playerprofile.php","user_myteams.php"),$user_path);
		playerprofile();
	break;
	
	case "jointeamform":
		X1File::X1LoadFile("user_jointeam.php",$user_path);
		jointeamform();
	break;
	
	case "jointeam":
		X1File::X1LoadFile("user_jointeam.php",$user_path);
		jointeam();
	break;
	
	case "quitteamform":
		X1File::X1LoadFile("user_quitteam.php",$user_path);
		quitteamform();
	break;

	case "quitteam":
		X1File::X1LoadFile("user_quitteam.php",$user_path);
		quitteam();
	break;
	
	case "teamprofile":
		X1File::X1LoadFile("user_teamprofile.php",$user_path);
		teamprofile();
	break;
	
	case "teamlist":
		X1File::X1LoadFile("user_myteams.php",$user_path);
		teamlist();
	break;
	
	case "reportform":
		X1File::X1LoadMultiFiles(array("user_eventhome.php", "user_report.php"),$user_path);
		X1_reportform();
	break;
	
	case "X1_reportdraw":
	case "X1_reportloss":
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_report.php"),$user_path);
		X1_reportloss($op);
	break;
	
	case "listmaps":
		X1File::X1LoadFile("user_index.php",$user_path);
		ListMaps();
	break;
	
	case "ladderhome";
		X1File::X1LoadMultiFiles(array("user_eventhome.php","user_match.php"),$user_path);
		ladderhome();
	break;
	
	case "sendinvite";
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_invites.php"),$user_path);
		sendinvite();
	break;
	
	case "confirminvite";
		X1File::X1LoadMultiFiles(array("user_invites.php","user_jointeam.php"),$user_path);
		confirminvite();
	break;
	
	case "acceptinvite":
		$accept=1;
	case "declineinvite":
		if(!isset($accept)){
			$accept=2;
		}
		X1File::X1LoadMultiFiles(array("user_invites.php","user_jointeam.php"),$user_path);
		if(isset($_POST['code'])){
			AcceptInvite(DispFunc::X1Clean($_POST['code'],$accept));
		}
		else{
			//error
		}
	break;
	
	case "removeinvite";
		X1File::X1LoadMultiFiles(array("user_displayteam.php","user_invites.php"),$user_path);
		removeuserinvite();
	break;
	
	case "createteam":
		X1File::X1LoadFile("user_teamcreate.php",$user_path);
		createteam();
	break;
	
	case "newteam":
		X1File::X1LoadFile("user_teamcreate.php",$user_path);
		newteam();
	break;
	
	case "transferteam":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		X1TransferLeadership();
	break;

	case "myteams":
		X1File::X1LoadFile("user_myteams.php",$user_path);
		X1_myteams();
	break;

	case "messages":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		global $gx_message_param;
		$gx_message_param = "view";
		displayteam("messages");
	break;

	case "x1_replymessage":
		X1File::X1LoadFile("user_displayteam.php",$user_path);
		global $gx_message_param;
		$gx_message_param = "send";
		displayteam("messages");
	break;
	
	case "updateplayerprofile":
		X1File::X1LoadMultiFiles(array("user_playerprofile.php","user_myteams.php"),$user_path);
		UpdatePlayerProfile();
		playerprofile();
	break;
	
	case "getdemo":
		X1File::X1LoadFile("user_index.php",$user_path);
		X1GetFile(DispFunc::X1Clean($_GET['id']));
		X1plugin_index();
	break;
		
	default:
		X1File::X1LoadFile("user_index.php",$user_path);
		X1plugin_index();
	break;
}
?>
