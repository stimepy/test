<?php
###############################################################
##Nukeladder Extreme Tournament System
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006  Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1_parent')) exit();
###############################################################
global $gx_event_manager, $xdb;

#May need to add your db details here. PHP and VB USERS
$xdb_host = 'localhost';
$xdb_db = 'phpbb';
$xdb_user = 'username';
$xdb_pass = 'password';


switch(X1_parent){
###############################################################
# PHP-NUKE, Raven, PNC AND VARIANTS
# They may change at some point but for now.
###############################################################
	case "raven":
	case "pnc":
	case "phpnuke":
		global $cookie,$dbhost,$dbuname,$dbpass,$dbname;
		$xdb = ADONewConnection('mysql');
		$result = $xdb->Connect($dbhost,$dbuname,$dbpass,$dbname);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		$xdb->debug =false;
		function X1_userdetails()
		{
			global $user, $admin, $cookie;
			cookiedecode($user);
			$cookie[0] = (isset( $cookie[0]) ) ? $cookie[0] : "" ;
			$cookie[1] = (isset( $cookie[1]) ) ? $cookie[1] : "" ;
			return array($cookie[0], $cookie[1]);
		}
		function check_admin()
		{
			global $admin;
			if(is_admin($admin)){
				return true;
			}else{
				return false;
			}
		}
		function X1_userprofilelink($user_id){
			return "<a href='modules.php?name=Forums&file=profile&mode=viewprofile&u=".$user_id."' target='_blank'>";
		}
		
	break;
###############################################################
# PHPBB FORUM SYSTEM
###############################################################
	case "phpbb":
		$xdb = ADONewConnection('mysql');
		$result = $xdb->Connect($xdb_host , $xdb_user, $xdb_pass, $xdb_db);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		$xdb->debug =false;
		function X1_userdetails()
		{
			global $userdata;
			if($userdata['session_logged_in']=="1"){
				return array($userdata['user_id'],$userdata['username']);
			}
		}
		function check_admin()
		{
			global $userdata;
			if( ($userdata['session_logged_in']=="1") && ($userdata['session_admin']=="1") ){
				return true;
			}else{
				return false;
			}
		}
	break;
###############################################################
# E107 WEBSITE SYSTEM
###############################################################
	case "e107":
		global $mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb;
		$xdb = ADONewConnection('mysql');
		$result = $xdb->Connect($mySQLserver,$mySQLuser,$mySQLpassword,$mySQLdefaultdb);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		function check_admin()
		{
			if(ADMIN){
				return true;
			}else{
				return false;
			}
		}
		function X1_userdetails()
		{
			if (USER)return array(USERID,USERNAME);
		}
		function X1_userprofilelink($user_id){
			return "<a href='../../user.php?id.".$user_id."' target='_blank'>";
		}
	break;

###############################################################
# Php-Fusion Website System
###############################################################		
		case "fusion":
		global  $userdata, $db_host, $db_user, $db_pass, $db_name;
		$xdb = ADONewConnection('mysql');
		include("../../config.php");
		$result = $xdb->Connect($db_host,$db_user,$db_pass,$db_name);
		unset($db_host, $db_user, $db_pass);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		
		$xdb->debug = false;
		
		function check_admin()
		{
			if(iADMIN){
				return true;
			}else{
				return false;
			}
		}

		function X1_userdetails()
		{
			global $userdata;
			if(iMEMBER){
				return array($userdata['user_id'],$userdata['user_name']);
			}else{
				return false;
			}
		}
		
		function X1_userprofilelink($user_id){
			return "<a href='../../profile.php?lookup=".$user_id."' target='_blank'>";
		}

	break;
###############################################################
# Dragonfly cms system
###############################################################
	case "dragonfly":
		global $cookie,$dbhost,$dbuname,$dbpass,$dbname;
		$xdb = ADONewConnection('mysql');
		$result = $xdb->Connect($dbhost,$dbuname,$dbpass,$dbname);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		$xdb->debug =false;
		function X1_userdetails()
		{	
			global $userinfo;
			$cookie[0] = (isset( $userinfo['user_id']) ) ? $userinfo['user_id'] : "" ;
			$cookie[1] = (isset( $userinfo['username']) ) ? $userinfo['username'] : "" ;
			if($cookie[0]==1 || strtolower($cookie[1])=='anonymous'){
				return array(NULL,NULL);
			}
			return array($cookie[0], $cookie[1]);
		}
		function check_admin()
		{
			global $admin;
			if(is_admin()){
				return true;
			}else{
				return false;
			}
		}
		function X1_userprofilelink($user_id){
			return "<a href='Your_Account/profile=".$user_id."/' target='_blank'>";
		}
	break;
	
###############################################################
# Aditional Systems 
# Please see the above 

###############################################################
}#End Switch
###############################################################
?>