<?php


//eplug info--------------------------
$eplug_name = "Extreme Tournament System";
$eplug_version = "Beta 2.6.4";
$eplug_author = "Kris Sherrerd";
$eplug_url = "http://www.aodhome.com";
$eplug_email = "stimepy@aodhome.com";


//eplug folder--------------------------------
$eplug_folder = "extremetournamentsystem";

//Todo , Make Favicons
$eplug_icon = $eplug_folder."/images/icon_32.png";
$eplug_icon_small = $eplug_folder."/images/icon_16.png";



$eplug_description = "Gaming Leagues and Ladders";
$eplug_compatible = "e107v0.7+";
//$eplug_readme = "admin_readme.php";


//Todo, Make compliant.
$eplug_compliant = TRUE;

$eplug_menu_name = "Nuke Ladder - XTS";
$eplug_conffile = "Kompete.php?op=admin";
$eplug_caption = "Kompete Admin";


$eplug_link = TRUE;
$eplug_link_name = "XTS";
$eplug_link_url = "e107_plugins/extremetournamentsystem/Kompete.php";
$eplug_link_perms = "Everyone"; // Optional: Guest, Member, Admin, Everyone    

$eplug_done = "Installation Successful...";
$eplug_upgrade_done = "Upgrade successful...";

$eplug_prefs = "";
$upgrade_add_prefs = "";

$eplug_table_names = array(
	'xts_challengeteam',  
	'xts_confirminvites', 
	'xts_games', 
	'xts_ladderdisputes', 
	'xts_laddermaplist', 
	'xts_ladders', 
	'xts_ladderteams', 
	'xts_mapgroups',
	'xts_messages', 
	'xts_nukladstaff',
	'xts_playedgames', 
	'xts_teams',
	'xts_userteams',
	'xts_userinfo'
	
);


$eplug_tables = array(

"CREATE TABLE ".MPREFIX."xts_challengeteam (
	ctemp tinyint(1) NOT NULL DEFAULT '1',
	winner varchar(255) NOT NULL DEFAULT '',
	loser varchar(255) NOT NULL DEFAULT '',
	date varchar(255) NOT NULL DEFAULT '',
	randid varchar(10) NOT NULL DEFAULT '0',
	ladder_id varchar(10) NOT NULL DEFAULT '',
	map1 varchar(255) NOT NULL DEFAULT 'None',
	map2 varchar(255) NOT NULL DEFAULT 'None',
	matchdate varchar(255) NOT NULL DEFAULT 'None',
	PRIMARY KEY (randid)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_confirminvites (
	invite_id int(10) NOT NULL AUTO_INCREMENT,
	team_id varchar(40) NOT NULL DEFAULT '',
	randid varchar(10) NOT NULL DEFAULT '0',
	uid varchar(40) NOT NULL DEFAULT '0',
	PRIMARY KEY (invite_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_games (
	gameid int(10) NOT NULL AUTO_INCREMENT,
	gamename varchar(32) DEFAULT NULL,
	gameimage varchar(20) DEFAULT NULL,
	gametext varchar(40) DEFAULT NULL,
	PRIMARY KEY (gameid)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_ladderdisputes (
	dispute_id int(10) NOT NULL AUTO_INCREMENT,
	sender varchar(40) NOT NULL DEFAULT '',
	offender varchar(40) NOT NULL DEFAULT '',
	ladder_id int(5) NOT NULL DEFAULT '0',
	date varchar(40) NOT NULL DEFAULT '',
	info varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (dispute_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_laddermaplist (
	mapid int(10) NOT NULL AUTO_INCREMENT,
	mapname varchar(40) NOT NULL DEFAULT 'default map',
	mappic varchar(40) NOT NULL DEFAULT 'none',
	mapdl varchar(255) NOT NULL DEFAULT 'none',
	gpmp_cnt int(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (mapid)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_ladders (
	sid int(11) NOT NULL AUTO_INCREMENT,
	title varchar(80) DEFAULT NULL,
	hometext text,
	bodytext text NOT NULL,
	game int(3) NOT NULL DEFAULT '1',
	notes text NOT NULL,
	allow_rest int(1) NOT NULL DEFAULT '0',
	score int(10) NOT NULL DEFAULT '0',
	ratings int(10) NOT NULL DEFAULT '0',
	pointswin int(5) NOT NULL DEFAULT '2',
	pointsloss int(5) NOT NULL DEFAULT '0',
	pointsdraw int(5) NOT NULL DEFAULT '1',
	gamesmaxday int(5) NOT NULL DEFAULT '1',
	declinepoints int(5) NOT NULL DEFAULT '1',
	active int(11) NOT NULL DEFAULT '1',
	enabled int(11) NOT NULL DEFAULT '1',
	challengelimit int(5) NOT NULL DEFAULT '1',
	challengedays int(40) NOT NULL DEFAULT '7',
	restrictdates int(1) NOT NULL DEFAULT '0',
	numdates int(5) NOT NULL DEFAULT '3',
	restrictmaps int(5) NOT NULL DEFAULT '0',
	nummaps1 int(5) NOT NULL DEFAULT '3',
	nummaps2 int(5) NOT NULL DEFAULT '2',
	standingstype varchar(255) NOT NULL DEFAULT '',
	maxteams int(10) NOT NULL DEFAULT '0',
	minplayers int(10) NOT NULL DEFAULT '0',
	maxplayers int(10) NOT NULL DEFAULT '500',
	type varchar(255) NOT NULL DEFAULT 'league',
	expirechalls tinyint(1) NOT NULL DEFAULT '0',
	expirehours int(10) NOT NULL DEFAULT '120',
	expirepen int(10) NOT NULL DEFAULT '1',
	expirebon int(10) NOT NULL DEFAULT '1',
	whoreports varchar(10) NOT NULL DEFAULT 'loser',
	mapgroups text NOT NULL,
	PRIMARY KEY (sid)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_ladderteams (
	ladder_id int(10) NOT NULL DEFAULT '0',
	team_id int(10) NOT NULL DEFAULT '0',
	games int(10) NOT NULL DEFAULT '0',
	wins int(10) NOT NULL DEFAULT '0',
	losses int(10) NOT NULL DEFAULT '0',
	draws int(10) NOT NULL DEFAULT '0',
	points int(100) NOT NULL DEFAULT '0',
	penalties int(10) NOT NULL DEFAULT '0',
	streakwins int(10) NOT NULL DEFAULT '0',
	streaklosses int(10) NOT NULL DEFAULT '0',
	rest int(10) NOT NULL DEFAULT '0',
	challenged varchar(255) NOT NULL DEFAULT 'New Team',
	challyesno char(3) NOT NULL DEFAULT 'No',
	rung int(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (ladder_id,team_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_mapgroups (
	id int(255) NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,
	maps text NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_messages (
	randid int(10) NOT NULL,
	messid int(10) NOT NULL DEFAULT '0',
	message text NOT NULL,
	hasread int(1) NOT NULL DEFAULT '0',
	steam_id int(10) NOT NULL,
	sender varchar(255) NOT NULL,
	rteam_id int(10) NOT NULL,
	tstamp varchar(255) NOT NULL,
	PRIMARY KEY (randid,messid)
) ENGINE=MyISAM;",



"CREATE TABLE ".MPREFIX."xts_nukladstaff (
	mod_id int(10) NOT NULL AUTO_INCREMENT,
	mod_name varchar(40) NOT NULL DEFAULT '',
	mod_pswd varchar(40) DEFAULT NULL,
	PRIMARY KEY (mod_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_playedgames (
	game_id int(10) NOT NULL AUTO_INCREMENT,
	winner_id int(255) NOT NULL,
	winner varchar(40) DEFAULT NULL,
	loser_id int(255) NOT NULL,
	loser varchar(40) DEFAULT NULL,
	date varchar(40) DEFAULT NULL,
	map1 varchar(255) NOT NULL DEFAULT 'n/a',
	map2 varchar(255) NOT NULL DEFAULT 'n/a',
	mapsettotal varchar(255) NOT NULL DEFAULT 'n/a',
	map1t1 varchar(255) NOT NULL DEFAULT 'n/a',
	map1t2 varchar(255) NOT NULL DEFAULT 'n/a',
	map2t1 varchar(255) NOT NULL DEFAULT 'n/a',
	map2t2 varchar(255) NOT NULL DEFAULT 'n/a',
	scrnsht1 varchar(255) NOT NULL DEFAULT 'n/a',
	scrnsht2 varchar(255) NOT NULL DEFAULT 'n/a',
	comments varchar(255) NOT NULL DEFAULT '',
	laddername int(11) NOT NULL DEFAULT '0',
	draw tinyint(1) NOT NULL DEFAULT '0',
	demo varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (game_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_teams (
	team_id int(10) NOT NULL AUTO_INCREMENT,
	name varchar(40) NOT NULL DEFAULT '',
	totalnmessag int(10) NOT NULL DEFAULT '0',
	mail varchar(50) DEFAULT NULL,
	aim varchar(40) DEFAULT NULL,
	icq varchar(15) DEFAULT NULL,
	msn varchar(40) DEFAULT NULL,
	xfire varchar(40) DEFAULT NULL,
	yim varchar(40) DEFAULT NULL,
	country varchar(40) DEFAULT '',
	totalwins int(10) DEFAULT '0',
	totallosses int(10) DEFAULT '0',
	totaldraws int(10) DEFAULT '0',
	totalpoints int(10) DEFAULT '0',
	totalgames int(10) DEFAULT '0',
	penalties int(10) DEFAULT '0',
	playerone int(10) NOT NULL DEFAULT '0',
	playerone2 varchar(255) NOT NULL DEFAULT '',
	clantags varchar(10) NOT NULL DEFAULT '',
	challenged varchar(10) NOT NULL DEFAULT 'No',
	website varchar(200) NOT NULL,
	clanlogo varchar(200) NOT NULL DEFAULT '',
	ircserver varchar(40) NOT NULL DEFAULT '''',
	ircchannel varchar(40) NOT NULL DEFAULT '',
	joinpassword varchar(40) NOT NULL DEFAULT '',
	recruiting int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (team_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_userteams (
	uid int(10) NOT NULL DEFAULT '0',
	team_id int(10) NOT NULL DEFAULT '0',
	extra1 varchar(255) NOT NULL DEFAULT 'none',
	extra2 varchar(255) NOT NULL DEFAULT 'none',
	extra3 varchar(255) NOT NULL DEFAULT 'none',
	joindate varchar(10) NOT NULL DEFAULT 'n/a',
	cocaptain tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (uid,team_id)
) ENGINE=MyISAM;",

"CREATE TABLE ".MPREFIX."xts_userinfo (
	uid int(10) NOT NULL,
	gam_name varchar(255) NOT NULL DEFAULT 'N/A',
	p_country varchar(255) NOT NULL,
	p_mail varchar(255) NOT NULL,
	faux_email varchar(255) NOT NULL,
	use_faux int(10) NOT NULL DEFAULT '0',
	p_aim varchar(40) NOT NULL,
	p_icq varchar(40) NOT NULL,
	p_msn varchar(255) NOT NULL,
	p_xfire varchar(40) NOT NULL,
	p_yim varchar(255) NOT NULL,
	p_website varchar(256) NOT NULL,
	PRIMARY KEY (uid)
) ENGINE=MyISAM;"

);

$upgrade_alter_tables = "";


if(!function_exists("Kompete_install")){
	 function Kompete_install(){
	 
	 }
}

if(!function_exists("Kompete_upgrade")){
	 function Kompete_upgrade(){
	 
	 }
}

if(!function_exists("Kompete_uninstall")){
	 function Kompete_uninstall(){
		
	 }
}