<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2009 (2.6.0+)
##Version 2.6.4
###############################################################

#Use external adodb abstraction layer
define('X1_useadodblite', true);
###########################################
# DATABASE MAPPING OPTIONS
# The following tables define which prefixes and which database tables to use.
# If you have a default setup, most of these should remain as is.
# Some nuke users may need to change the prefix options
###########################################
#Table Mapping
#Plugin Maps Table
define('X1_DB_maps', 'laddermaplist');
#Plugin Games Table
define('X1_DB_games', 'games');
#Plugin Teams Table
define('X1_DB_teams', 'teams');
#Plugin Events Table
define('X1_DB_events', 'ladders');
#Plugin Challenges Table
define('X1_DB_teamchallenges', 'challengeteam');
#Plugin Invites Table
define('X1_DB_teaminvites', 'confirminvites');
#Plugin Disputes Table
define('X1_DB_teamdisputes', 'ladderdisputes');
#Plugin Team's Events Table
define('X1_DB_teamsevents', 'ladderteams');
#Plugin Matches Table
define('X1_DB_teamhistory', 'playedgames');
#Plugin Joined Teams Table
define('X1_DB_teamroster', 'userteams');
#Plugin Team User table
define('X1_DB_userinfo', 'userinfo');
#Plugin Mapgroups Table
define('X1_DB_mapgroups','mapgroups');
#Plugin Staff Table
define('X1_DB_nukstaff','nukladstaff');
#Plugin Message table
define('X1_DB_messages', 'messages');

###########################################
# LINKBACK OPTIONS
# Linking back is nice.
###########################################

#Version Number
define('X1_release', '2.6.44');

###########################################
# Css Options and Menu 
# Some systems may have conflicting or conforming css style classes, 
# define them here or leave as is for main style.
###########################################
define('X1plugin_title', 'title');
define('X1_formstyle', 'margin:0;');

if(X1_alternativesyle==false)
{
define('X1_teamlistclass', 'tborder');
define('X1_teamreportclass', 'tborder');
define('X1plugin_gamecontainer', 'tborder');
define('X1plugin_newmatchestable', 'tborder');
define('X1plugin_pastmatchestable', 'tborder');
define('X1plugin_matchdetailstable', 'tborder');
define('X1plugin_standingstable', 'tborder');
define('X1plugin_mapslist', 'tborder');
define('X1plugin_teamprofiletable', 'tborder');
define('X1plugin_createteamtable', 'tborder');
define('X1plugin_quitteamtable', 'tborder');
define('X1plugin_jointeamtable', 'tborder');
define('X1plugin_teamadmintable', 'tborder');
define('X1plugin_playerprofiletable', 'tborder');
define('X1plugin_challengeteamtable', 'tborder');
define('X1plugin_admintable', 'tborder');
define('X1plugin_disputestable', 'tborder');
define('X1plugin_rulestable', 'tborder');
define('X1plugin_ladderhometable', 'tborder');
define('X1plugin_tablehead', 'thead');
define('X1plugin_tablebody', 'tbody');
define('X1plugin_tablefoot', 'tfoot');
}
///////////////////////////////////
//////////////// Secondary layout settings
//////////////////////////////////
if(X1_alternativesyle==true)
{
define('X1_teamlistclass', 'forumheader2');
define('X1_teamreportclass', 'forumheader2');
define('X1plugin_gamecontainer', 'forumheader2');
define('X1plugin_newmatchestable', 'forumheader2');
define('X1plugin_pastmatchestable', 'forumheader2');
define('X1plugin_matchdetailstable', 'forumheader2');
define('X1plugin_standingstable', 'forumheader2');
define('X1plugin_mapslist', 'forumheader2');
define('X1plugin_teamprofiletable', 'forumheader2');
define('X1plugin_createteamtable', 'forumheader2');
define('X1plugin_quitteamtable', 'forumheader2');
define('X1plugin_jointeamtable', 'forumheader2');
define('X1plugin_teamadmintable', 'forumheader2');
define('X1plugin_playerprofiletable', 'forumheader2');
define('X1plugin_challengeteamtable', 'forumheader2');
define('X1plugin_admintable', 'forumheader2');
define('X1plugin_disputestable', 'forumheader2');
define('X1plugin_rulestable', 'forumheader2');
define('X1plugin_ladderhometable', 'forumheader2');
define('X1plugin_tablehead', 'forumheader');
define('X1plugin_tablebody', 'forumheader2');
define('X1plugin_tablefoot', 'forumheader');
}


###########################################
# Admin Options
###########################################

#help file, liad in admin panel 
define('X1_helpfile', '');//http://www.aodhome.com/help.html');

$gx_message_param = "0";

?>