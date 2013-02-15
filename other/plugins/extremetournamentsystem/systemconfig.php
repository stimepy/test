<?php
###########################################
# SCRIPT CONFIG FOR E107
# systemconfig.php
###########################################
# ERROR REPORTING OPTIONS
# error_reporting(2047);
###########################################
# PATH OPTIONS
# Path options define directorys where files should exsist and what to insert 
# into certain links to trigger certain actions within a cms.
###########################################
#Path to Plugin directory
define('X1_plugpath', "./");
#Remote linking path, best left as is
$path = explode('?', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
define('X1_linkpath', $path[0]);
#Starting op
define('X1_startop', '?');
#Path to css directory
define('X1_csspath', X1_plugpath."css");
#Path to images directory
define('X1_imgpath', X1_plugpath."images");
#Path to javascripts
define('X1_jspath', X1_plugpath."jscript");
#Path to plugin mod files
define('X1_modpath',X1_plugpath."mods");
#Path to language files
define('X1_langpath', X1_plugpath."language");
#Path to email files
define('X1_emailpath', X1_plugpath."templates/emails");
#Default Logo
define('X1_team_image', X1_imgpath.'/deflogo.gif');
#default log path
define('X1_logpath', X1_plugpath.'logs');


#File to use in POST requests in admin
define('X1_adminpostfile', 'Kompete.php');
#File to use in GET requests in admin
define('X1_admingetfile', 'Kompete.php');

#File to use in POST requests in core
define('X1_publicpostfile', 'Kompete.php');
#File to use in GET requests in core
define('X1_publicgetfile', 'Kompete.php');
#Action operators
define('X1_linkactionoperator', '?op=');
define('X1_actionoperator', 'op');
#urlpath needed for emails with external links
define('X1_urlx_path','/e107_plugins/extremetournamentsystem/');

#Which cms the plugin is running in
define('X1_parent', 'e107');
#Output format of the plugin
define('X1_output', "echo");
#configuration console
define('X1_useconfigpanel',true);

define('X1_admin_log','admin');
define('X1_user_log', 'user');


###########################################
# DATABASE MAPPING OPTIONS
# The following tables define which prefixes and which database tables to use.
# If you have a default setup, most of these should remain as is.
# Some nuke users may need to change the prefix options
###########################################

#main tables prefix
define('X1_prefix', 'e107_xts_'); 
#user table prefix
define('X1_userprefix', 'e107_');

#Users Main
#CMS Database table containing users
define('X1_DB_userstable', 'user');
#Key name which contains user's id
define('X1_DB_usersidkey', 'user_id');
#Key name which contains user's name
define('X1_DB_usersnamekey', 'user_name');
#Key name which contains user's email
define('X1_DB_usersemailkey', 'user_email');
#Key name which contains user's fake email
define('X1_DB_usersfakeemailkey', 'user_email');
#Key name which contains user's public email flag
define('X1_DB_usersviewemailkey', 'user_hideemail');
#User extras
#Key name which contains user's icq
define('X1_DB_userseicqkey', 'user_icq');
#Key name which contains user's aim
define('X1_DB_userseaimkey', 'user_aim');
#Key name which contains user's msn
define('X1_DB_usersemsnkey', 'user_msn');
#Key name which contains user's yim
define('X1_DB_userseyimkey', 'user_yim');
#Key name which contains user's homepage
define('X1_DB_usersewebkey', 'user_website');
#Key name which contains user's avatar
define('X1_DB_userseavatarkey', 'user_avatar');
#Key name which contains user's country
define('X1_DB_userslocationkey', 'user_from');
#Key name which contains user's registration date
define('X1_DB_usersregdatekey', 'user_join');

?>