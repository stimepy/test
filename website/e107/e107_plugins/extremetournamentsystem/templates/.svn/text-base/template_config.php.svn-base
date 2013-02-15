<?php

function updatedtemplate(){
return "<?php
###########################################
# SCRIPT CONFIG FOR E107
# config.php
###########################################
# ERROR REPORTING OPTIONS
# error_reporting(2047);
###########################################
# PATH OPTIONS
# Path options define directorys where files should exsist and what to insert 
# into certain links to trigger certain actions within a cms.
###########################################

#Sets site name
define('X1_sitename', '".stripslashes(DispFunc::X1Clean($_POST['sitename']))."');
#Return url, loggin out ect
define('X1_url', '".DispFunc::X1Clean($_POST['rurl'])."');  

###########################################
# LOCALE OPTIONS
# Locale options define which lanaguage files to use in core and admin areas.
# (only english so far, tanslators needed.)
###########################################

#Core Lang
define('X1_corelang', '".DispFunc::X1Clean($_POST['corelang'])."');

#Normal dateformat
define('X1_dateformat', '".DispFunc::X1Clean($_POST['dateformat'])."');
#Extended dateformat with time
define('X1_extendeddateformat', '".DispFunc::X1Clean($_POST['dateformatext'])."');


###########################################
# LINKBACK OPTIONS
# Linking back is nice.
###########################################

#Show linkback, true or false
define('X1_showlinkback', ".SetTrue(DispFunc::X1Clean($_POST['showlinkback'])).");
#Show text version info under image, true or false
define('X1_showversion', ".SetTrue(DispFunc::X1Clean($_POST['showversion'])).");
#Alignment , right, center, left
define('X1_lbalign', '".DispFunc::X1Clean($_POST['align'])."');
#black, blue, green, grey, orange, red, violet, white, yellow (.png)
define('X1_lbimage', '".DispFunc::X1Clean($_POST['linkbackimg'])."');
#Linkback url
define('X1_lblink', '".$_POST['blink']."');



###########################################
# COOKIE AND LOGIN/OUT OPTIONS
# Define cookie name and time, and refreshtime when logging in and out.
###########################################

#Cookie mode (0=php) (1=javascript) (0 is default, 1 for phpbb)
define('X1_cookiemode','".DispFunc::X1Clean($_POST['howcookie'])."');
#Cookie name (default works fine)
define('X1_cookiename', '".DispFunc::X1Clean($_POST['tcook'])."');
#cookie name staff
define('X1_cookiemod', '".DispFunc::X1Clean($_POST['mcook'])."');
#Time for cookie to last
define('X1_cookietime', '".MakeSec(DispFunc::X1Clean($_POST['tcooktime']))."');
#time for cookie to last for moderator
define('X1_cookietimemod', '".DispFunc::X1Clean(MakeSec($_POST['mcooktime']))."');
#Time to wait to fresh when logging in and out
define('X1_refreshtime', '".DispFunc::X1Clean($_POST['cookref'])."');
#Page to goto when logging out
define('X1_logoutpage', X1_url.'/".DispFunc::X1Clean($_POST['logoutpg'])."');


###########################################
# EMAIL OPTIONS
# Options for sending emails
###########################################

#Turn sending on and off
define('X1_emailon',".SetTrue(DispFunc::X1Clean($_POST['emailon'])).");
#Return mail address
define('X1_returnmail', '".DispFunc::X1Clean($_POST['retadd'])."');
#Timestamp format used when creating dates in emails
define('X1_emailtimestamp', '".DispFunc::X1Clean($_POST['emailstamp'])."');
#Show text when emails are sent, for debugging use.
define('X1_emaildebug',".SetTrue(DispFunc::X1Clean($_POST['emailsendon'])).");


###########################################
# TEAMS OPTIONS
# Options related to teams and team profiles
###########################################

#How many teams to display per page on the team list
define('X1_teamlistlimit', '".DispFunc::X1Clean($_POST['numteam'])."');
#How many teams one user is allowed to create
define('X1_maxcreate', '".DispFunc::X1Clean($_POST['maxteamc'])."');
#How many teams one user is allowed to join
define('X1_maxjoin', '".DispFunc::X1Clean($_POST['maxteamj'])."');
#Team image width
define('X1_teamimagew','".DispFunc::X1Clean($_POST['teamimagew'])."');
#Team image height
define('X1_teamimageh','".DispFunc::X1Clean($_POST['teamimageh'])."');
#determines how many if any extra field you want on the roster page. valid arguments 1, 2, 3
define('X1_extrarosterfields', ".DispFunc::X1Clean($_POST['extrarosterfields']).");
#Extra option on roster
define('X1_extraroster1', \"".DispFunc::X1Clean($_POST['extra1'])."\");
define('X1_extraroster2', \"".DispFunc::X1Clean($_POST['extra2'])."\");
define('X1_extraroster3', \"".DispFunc::X1Clean($_POST['extra3'])."\");
#Mysql Orderby for sortinng rosters
define('X1_rostersort', \"".DispFunc::X1Clean($_POST['rostsort'])."\");
#allow an in game editable name
define('X1_ingamename', ".SetTrue(DispFunc::X1Clean($_POST['ingamename'])).");


###########################################
# LADDER HOME LIMIT OPTIONS
###########################################

#Number of teams to show in top standings table
define('X1_topteamlimit', '".DispFunc::X1Clean($_POST['numteamstand'])."');
#Number of new matches to show
define('X1_newmatchlimit','".DispFunc::X1Clean($_POST['numnewmatch'])."');
#number of past matches to show
define('X1_resultslimit', '".DispFunc::X1Clean($_POST['nummatch'])."');
#Time zone of the competition
define('X1_timezone','GMT ".DispFunc::X1Clean($_POST['timezone'])."');


###########################################
# MOD SETTINGS OPTIONS
###########################################
#Show settings when challenging
define('X1_showsettingschall', ".DispFunc::X1Clean(SetTrue($_POST['showsettingschall']))." );
#Show Rules when challenging
define('X1_showruleschall', ".DispFunc::X1Clean(SetTrue($_POST['showruleschall']))." );


###########################################
# Admin Options
###########################################
#Icon Images
define('X1_addimage', '/submit/".DispFunc::X1Clean($_POST['addimage'])."');
define('X1_delimage', '/submit/".DispFunc::X1Clean($_POST['deleteimage'])."');
define('X1_saveimage', '/submit/".DispFunc::X1Clean($_POST['saveimage'])."');
define('X1_editimage', '/submit/".DispFunc::X1Clean($_POST['editimage'])."');
define('X1_tab_image', '/icons/".DispFunc::X1Clean($_POST['tabimage'])."');
#defualt image preview image
define('X1_defpreviewimage', '/games/".DispFunc::X1Clean($_POST['gamedefimg'])."');

###########################################
# File Uploads  0 no uploads allowed, 1 uploads allowed, 2 can upload or not.
###########################################
#image upload info
define('X1_fup_image', ".DispFunc::X1Clean($_POST['imag_up'])."); //0=no, 1=yes, 2=either or
define('X1_fup_imgext', '".str_replace(",","::",DispFunc::X1Clean($_POST['imgfile']))."');
#
define('X1_fup_demo', ".DispFunc::X1Clean($_POST['demo_up'])."); //0=no, 1=yes, 2=either or
define('X1_fup_demoext', '".str_replace(",","::",DispFunc::X1Clean($_POST['demofile']))."');

###########################################
# More
###########################################
#Tab Size
define('X1_tab_width', '".DispFunc::X1Clean($_POST['tabw'])."');
define('X1_tab_height', '".DispFunc::X1Clean($_POST['tabh'])."');
#Tab Border
define('X1_tab_border', '".DispFunc::X1Clean($_POST['tabbord'])."');
#Log errors to the screen(0) or file(1)
define('X1_logfiles', '".DispFunc::X1Clean($_POST['logfile'])."');  //0=screen(default) 1=logs


##########################################
# Css Options and Menu 
# Some systems may have conflicting or conforming css style classes, 
# define them here or leave as is for main style.
###########################################
// if your are having trouble with the layout on your site set this to true

//Setting to True uses forumheader2
#the css file that will be used (located module/css/)
define('X1_style', '".DispFunc::X1Clean($_POST['stylsheet'])."');
//Setting to false uses tborder
define('X1_alternativesyle',".DispFunc::X1Clean($_POST['altstyle']).");

//Custom Menu
define('X1_custommenu', ".SetTrue(DispFunc::X1Clean($_POST['custommenu'])).");
define('X1_custommenu_inc', \"".DispFunc::X1Clean($_POST['custommenfil'])."\");
#Use a custom stylesheet found in Plugin Css directory
define('X1_customstyle', ".SetTrue(DispFunc::X1Clean($_POST['usestylesheet'])).");
?".">";
}




function SetTrue($item){
	if($item=="1"){
		return "true";
	}
	else{
		return "false";
	}
}

function MakeSec($item){
	return $item*60;
}



function defaulttemp(){
return"<?php
###########################################
# SCRIPT CONFIG FOR E107
# config.php
###########################################
# ERROR REPORTING OPTIONS
# error_reporting(2047);
###########################################
# PATH OPTIONS
# Path options define directorys where files should exsist and what to insert 
# into certain links to trigger certain actions within a cms.
###########################################

#Sets site name
define('X1_sitename', \" Extreme Tournament System Powered Gaming Site\");
#Return url, loggin out ect
define('X1_url', 'http://www.aodhome.com');  


###########################################
# LOCALE OPTIONS
# Locale options define which lanaguage files to use in core and admin areas.
# (only english so far, tanslators needed.)
###########################################

#Core Lang
define('X1_corelang', 'english');

#Normal dateformat
define('X1_dateformat', 'M:d:Y');
#Extended dateformat with time
define('X1_extendeddateformat', 'M:d:Y H:i');


###########################################
# LINKBACK OPTIONS
# Linking back is nice.
###########################################

#Show linkback, true or false
define('X1_showlinkback', true);
#Show text version info under image, true or false
define('X1_showversion', true);
#Alignment , right, center, left
define('X1_lbalign', 'right');
#black, blue, green, grey, orange, red, violet, white, yellow (.png)
define('X1_lbimage', 'blue.png');
#Linkback url
define('X1_lblink', 'http://www.aodhome.com');



###########################################
# COOKIE AND LOGIN/OUT OPTIONS
# Define cookie name and time, and refreshtime when logging in and out.
###########################################

#Cookie mode (0=php) (1=javascript) (0 is default, 1 for phpbb)
define('X1_cookiemode','0');
#Cookie name (default works fine)
define('X1_cookiename', 'team');
#cookie name staff
define('X1_cookiemod', 'nukestaff');
#Time for cookie to last
define('X1_cookietime', '36000');
#time for cookie to last for moderator
define('X1_cookietimemod', '36000');
#Time to wait to fresh when logging in and out
define('X1_refreshtime', '4');
#Page to goto when logging out
define('X1_logoutpage', X1_url.'/index.php');


###########################################
# EMAIL OPTIONS
# Options for sending emails
###########################################

#Turn sending on and off
define('X1_emailon',false);
#Return mail address
define('X1_returnmail', 'noreply@yourdomain.com');
#Timestamp format used when creating dates in emails
define('X1_emailtimestamp', 'M:d:Y H:i');
#Show text when emails are sent, for debugging use.
define('X1_emaildebug',false);


###########################################
# TEAMS OPTIONS
# Options related to teams and team profiles
###########################################

#How many teams to display per page on the team list
define('X1_teamlistlimit',5);
#How many teams one user is allowed to create
define('X1_maxcreate', '5');
#How many teams one user is allowed to join
define('X1_maxjoin', '5');
#Team image width
define('X1_teamimagew','100');
#Team image height
define('X1_teamimageh','100');
#determines how many if any extra field you want on the roster page. valid arguments 1, 2, 3
define('X1_extrarosterfields', 3);
#Extra option on roster
define('X1_extraroster1', 'Extra1');
define('X1_extraroster2', 'Extra2');
define('X1_extraroster3', 'Extra3');
#Mysql Orderby for sortinng rosters
define('X1_rostersort', 'gam_name ASC');
#allow an in game editable name
define('X1_ingamename', false);


###########################################
# LADDER HOME LIMIT OPTIONS
###########################################

#Number of teams to show in top standings table
define('X1_topteamlimit', '5');
#Number of new matches to show
define('X1_newmatchlimit','5');
#number of past matches to show
define('X1_resultslimit', '5');
#Time zone of the competition
define('X1_timezone','GMT -1');


###########################################
# MOD SETTINGS OPTIONS
###########################################
#Show settings when challenging
define('X1_showsettingschall', true );
#Show Rules when challenging
define('X1_showruleschall', true );


###########################################
# Admin Options
###########################################
#Icon Images
define('X1_addimage', '/submit/add.gif');
define('X1_delimage', '/submit/close_red.gif');
define('X1_saveimage', '/submit/disk.gif');
define('X1_editimage', '/submit/edit.gif');
define('X1_tab_image', '/icons/folder.gif');
#defualt image preview image
define('X1_defpreviewimage', '/games/default.png');

###########################################
# File Uploads  0 no uploads allowed, 1 uploads allowed, 2 can upload or not.
###########################################
#image upload info
define('X1_fup_image', 0); //0=no, 1=yes, 2=either or
define('X1_fup_imgext', 'jpg::jpeg::png::gif::tif');
#
define('X1_fup_demo', 0); //0=no, 1=yes, 2=either or
define('X1_fup_demoext', 'zip::7z::rar::tar');

###########################################
# More
###########################################
#Tab Size
define('X1_tab_width', '10');
define('X1_tab_height', '10');
#Tab Border
define('X1_tab_border', '0');
#Log errors to the screen(0) or file(1)
define('X1_logfiles', '1');  //0=screen(default) 1=logs

##########################################
# Css Options and Menu 
# Some systems may have conflicting or conforming css style classes, 
# define them here or leave as is for main style.
###########################################
// if your are having trouble with the layout on your site set this to true

//Setting to True uses forumheader2
#the css file that will be used (located module/css/)
define('X1_style', 'style.css');
//Setting to false uses tborder
define('X1_alternativesyle',true);

//Custom Menu
define('X1_custommenu', false);
define('X1_custommenu_inc', \"system_menu.php\");
#Use a custom stylesheet found in Plugin Css directory
define('X1_customstyle', false);
?>";
}
