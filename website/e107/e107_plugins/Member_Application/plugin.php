<?php


//eplug info--------------------------
$eplug_name = "Member Application";
$eplug_version = "2.1.5b";
$eplug_author = "Kris Sherrerd (Version 2.1.5 and up)";
$eplug_url = "http://www.aodhome.com";
$eplug_email = "stimepy@aodhome.com";


//eplug folder--------------------------------
$eplug_folder = "Member_Application";

//Todo , Make Favicons
$eplug_icon = $eplug_folder."/images/MAlogo2.png";
$eplug_icon_small = $eplug_folder."/images/MAlogo2.png";



$eplug_description = "Form and application mod";
$eplug_compatible = "e107v0.7+";
//$eplug_readme = "admin_readme.php";


//Todo, Make compliant.
$eplug_compliant = TRUE;

$eplug_menu_name = "Member Application";
$eplug_conffile = "ma_admin.php";
$eplug_caption = "Member Application Admin";


$eplug_link = TRUE;
$eplug_link_name = "Member Application";
$eplug_link_url = "e107_plugins/Member_Application/mem_app.php";
$eplug_link_perms = "Everyone"; // Optional: Guest, Member, Admin, Everyone    

$eplug_done = "Installation Successful...";
$eplug_upgrade_done = "Upgrade successful...";

$eplug_prefs = "";
$upgrade_add_prefs = "";

$eplug_table_names = array(
	'e107_MA_mapcfg',
	'e107_MA_mapp',
	'e107_MA_mappresp'	
);


$eplug_tables = array(

"CREATE TABLE IF NOT EXISTS ".MPREFIX."MA_mapp(
  `fldnum` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fldord` int(11) NOT NULL DEFAULT '0',
  `subfldord` int(11) NOT NULL DEFAULT '0',
  `fldname` text NOT NULL,
  `requrd` char(1) NOT NULL DEFAULT '',
  `inuse` char(1) NOT NULL DEFAULT '',
  `format` char(1) NOT NULL DEFAULT '',
  `parent` smallint(6) NOT NULL DEFAULT '0',
  `isdel` tinyint(1) NOT NULL DEFAULT '0',
  `formno` int(11) NOT NULL DEFAULT '0',
  `rgextxt` text,
  UNIQUE KEY `fldnum` (`fldnum`)
) ENGINE=MyISAM;",

"CREATE TABLE IF NOT EXISTS ".MPREFIX."MA_mappresp (
  `recno` bigint(11) NOT NULL AUTO_INCREMENT,
  `appnum` bigint(20) NOT NULL DEFAULT '0',
  `userno` bigint(20) NOT NULL DEFAULT '0',
  `qno` bigint(20) NOT NULL DEFAULT '0',
  `response` longtext NOT NULL,
  `adate` text NOT NULL,
  `formno` int(11) NOT NULL DEFAULT '0',
  `appstatus` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recno`)
) ENGINE=MyISAM;",

"CREATE TABLE IF NOT EXISTS ".MPREFIX."MA_mapcfg (
  `keyfld` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL default '1',
  `apptxt` longtext NOT NULL,
  `admaddr` text NOT NULL,
  `emdetail` tinyint(1) NOT NULL DEFAULT '0',
  `fpdetail` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` mediumint(8) NOT NULL DEFAULT '0',
  `forum_id` smallint(5) NOT NULL DEFAULT '0',
  `tytxt` longtext NOT NULL,
  `noapptxt` longtext NOT NULL,
  `appson` tinyint(1) NOT NULL DEFAULT '1',
  `current` tinyint(1) NOT NULL DEFAULT '0',
  `formtitle` varchar(64) NOT NULL DEFAULT '',
  `appslimit` tinyint(1) NOT NULL DEFAULT '0',
  `appslimitno` int(11) NOT NULL DEFAULT '0',
  `appsfull` tinyint(1) NOT NULL DEFAULT '0',
  `group_add` mediumint(8) NOT NULL DEFAULT '0',
  `block_multi_apps` tinyint(1) NOT NULL DEFAULT '1',
  `email_admin` tinyint(1) NOT NULL DEFAULT '1',
  `mailgroup` tinyint(1) NOT NULL DEFAULT '0',
  `topicwatch` tinyint(1) NOT NULL DEFAULT '0',
  `emuser` tinyint(1) NOT NULL DEFAULT '0',
  `formno` int(11) NOT NULL DEFAULT '0',
  `annon` tinyint(1) NOT NULL DEFAULT '0',
  `VertAlign` tinyint(1) NOT NULL DEFAULT '0',
  `auto_group` tinyint(1) NOT NULL DEFAULT '0',
  `approvtxt` longtext NOT NULL,
  `denytxt` longtext NOT NULL,
  `formlist` tinyint(1) NOT NULL DEFAULT '0',
  `compat` tinyint(1) NOT NULL DEFAULT '0',
  `emhtml` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `keyfld` (`keyfld`)
) ENGINE=MyISAM;"


);

$upgrade_alter_tables = "";


if(!function_exists("Member_Application_install")){
	 function Member_Application_install(){
	 
	 }
}

if(!function_exists("Member_Application_upgrade")){
	 function Member_Application_upgrade(){
	 
	 }
}

if(!function_exists("Member_Application_uninstall")){
	 function Member_Application_uninstall(){
		
	 }
}