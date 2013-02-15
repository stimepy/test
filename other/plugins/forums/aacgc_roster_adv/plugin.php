<?php

/*
#######################################
#     e107 website system plguin      
#     AACGC Advanced Roster                 
#     by M@CH!N3
#     http://www.AACGC.com       
#######################################
*/



$eplug_name = "AACGC Advanced Roster";
$eplug_version = "1.8";
$eplug_author = "M@CH!N3";
$eplug_url = "http://www.aacgc.com";
$eplug_email = "admin@aacgc.com";
$eplug_description = "Roster to show members of your community. Create categories with ranks for staff, members, units, squads, etc.";
$eplug_compatible = "";
$eplug_readme = "";
$eplug_compliant = FALSE;
$eplug_module = FALSE;
$eplug_status = TRUE;
$eplug_latest = TRUE;


$eplug_folder      = "aacgc_roster_adv";

$eplug_menu_name   = "AACGC_Advanced_Roster";

$eplug_conffile    = "admin_main.php";

$eplug_logo        = "";
$eplug_icon        = e_PLUGIN."aacgc_roster_adv/images/icon_32.png";
$eplug_icon_small  = e_PLUGIN."aacgc_roster_adv/images/icon_16.png";
$eplug_icon_custom  = e_PLUGIN."aacgc_roster_adv/images/icon_64.png";

$eplug_caption     = "AACGC Advanced Roster";  

$eplug_table_names = array("aacgc_roster_adv", "aacgc_roster_adv_cat", "aacgc_roster_adv_members", "aacgc_roster_adv_apps");

$eplug_tables = array(

"CREATE TABLE ".MPREFIX."aacgc_roster_adv(rank_id int(11) NOT NULL auto_increment,rank_name varchar(50) NOT NULL,rank_pic varchar(120) NOT NULL,rank_cat int(10) unsigned NOT NULL, PRIMARY KEY  (rank_id)) TYPE=MyISAM;",

"CREATE TABLE ".MPREFIX."aacgc_roster_adv_cat(cat_id int(11) NOT NULL auto_increment,cat_name varchar(50) NOT NULL, PRIMARY KEY  (cat_id)) TYPE=MyISAM;",

"CREATE TABLE ".MPREFIX."aacgc_roster_adv_members(awarded_id int(11) NOT NULL auto_increment,awarded_rank_id int(11) NOT NULL,user_id varchar(11) NOT NULL,user_location varchar(120) NOT NULL,user_age text NOT NULL,user_game text NOT NULL,user_status text NOT NULL,join_date text NOT NULL,user_duties text NOT NULL, PRIMARY KEY  (awarded_id)) TYPE=MyISAM;",

"CREATE TABLE ".MPREFIX."aacgc_roster_adv_apps(app_id int(11) NOT NULL auto_increment,user varchar(11) NOT NULL,age text NOT NULL,location text NOT NULL,contact text NOT NULL,bio text NOT NULL,gamename text NOT NULL,questiona text NOT NULL,questionb text NOT NULL,questionc text NOT NULL,questiond text NOT NULL,questione text NOT NULL, PRIMARY KEY  (app_id)) TYPE=MyISAM;");

$eplug_link      = TRUE;
$eplug_link_name = "AdvRoster";
$eplug_link_url  = e_PLUGIN."aacgc_roster_adv/AdvRoster.php";

$eplug_done = "Install Complete";
$eplug_upgrade_done = "Upgrade Complete - Go to Databases and scan plugin directories! New Settings Available!";

$upgrade_alter_tables = array (
"ALTER TABLE " . MPREFIX . "aacgc_roster_adv_apps ADD COLUMN questiona text NOT NULL AFTER gamename;",

"ALTER TABLE " . MPREFIX . "aacgc_roster_adv_apps ADD COLUMN questionb text NOT NULL AFTER questiona;",

"ALTER TABLE " . MPREFIX . "aacgc_roster_adv_apps ADD COLUMN questionc text NOT NULL AFTER questionb;",

"ALTER TABLE " . MPREFIX . "aacgc_roster_adv_apps ADD COLUMN questiond text NOT NULL AFTER questionc;",

"ALTER TABLE " . MPREFIX . "aacgc_roster_adv_apps ADD COLUMN questione text NOT NULL AFTER questiond;"

);

$upgrade_remove_prefs = "";
$upgrade_add_prefs = "";

?>
