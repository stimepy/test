<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

	X1File::X1LoadMultiFiles(array("my_config.php", "systemconfig.php","xts_config.php"),parent_path);

	# Load AdodbLite if Requested
	if(X1_useadodblite) {
	 	X1File::X1LoadFile("adodb.inc.php", X1_plugpath."includes/adodb/");

	 }

	# Load  Language Definitions
	X1File::X1LoadFile("core_lang.php", X1_langpath."/".X1_corelang."/");
//	X1File::X1LoadFile("admin_lang.php", X1_langpath."/".X1_adminlang."/");
	

	# Load Integration Functions
	X1File::X1LoadFile("X1EventMod.php", X1_modpath."/");
	X1File::X1LoadFile("integrate.php",X1_plugpath."");
		
	# Load System Functions
	X1File::X1LoadMultiFiles(array("system_selectboxes.php","system_sql.php"), X1_plugpath."core/system/");
	X1File::X1LoadMultiFiles(array("DispFunc.class.php", "X1ChallengeMessenger.class.php", "X1Cookie.class.php","X1Log.class.php", "X1Misc.class.php", "X1Moderator.class.php", "X1TeamUser.class.php"),X1_plugpath."includes/");

	# Load Admin Functions
	function X1_require_admin(){
		if(check_admin()){
			X1File::X1LoadMultiFiles(array("admin_disputes.php","admin_config.php", "admin_index.php", "admin_games.php", "admin_events.php", "admin_functions.php", "admin_matches.php", "admin_teams.php", "admin_maps.php", "admin_mapgroups.php", "admin_challenges.php", "admin_player.php","admin_moderator.php"),X1_plugpath."core/admin/");
		}else{
			die("Go Away");
		}
	}
	# Load Moderator Functions
	function X1_require_moderator(){
		if(X1Moderator::CheckStaff()){
			X1File::X1LoadMultiFiles(array("admin_disputes.php", "admin_functions.php", "admin_matches.php", "admin_teams.php", "admin_player.php", "admin_challenges.php"),X1_plugpath."core/admin/");
			X1File::X1LoadFile("mod_index.php",X1_plugpath."core/moderator/");
		}
		else{
			die("Go Away");
		}	
	}
	 	
	X1Misc::ExpireChallenges();
	if(!defined('X1_cookiename')){
		//error
	 	define('X1_cookiename', "team");
	 }
	 
	if(X1_custommenu){
	 	X1File::X1LoadFile(X1_custommenu_inc,X1_plugpath."core/system/");
	}
	X1File::X1LoadFile("system_cases.php",X1_plugpath."core/system/");
	
	DispFunc::X1PluginOutput(DispFunc::X1PluginLinkback());

	 
	 
	 
?>