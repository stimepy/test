<?php
/*
+---------------------------------------------------------------+
|        Akismet AntiSpam v6.0
|        coded by aSeptik
|        http://ask.altervista.org
|        aseptik@gmail.com
|        
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License Version 3 (http://gnu.org).
+---------------------------------------------------------------+
*/

//PLUGIN INFO

  $eplug_name        = "Akismet AntiSpam";
  $eplug_version     = "7.0";
  $eplug_author      = "aSeptik";
  $eplug_url         = "http://ask.altervista.org/";
  $eplug_email       = "aseptik@gmail.com";
  $eplug_description = "This plugin detects and deletes SPAM using the Akismet Service.";
  $eplug_compatible  = "e107v7";
  $eplug_readme      = "";
  $eplug_compliant   = FALSE;
  $eplug_module      = TRUE;

  $eplug_folder      = "akismet";

  $eplug_menu_name   = "akismet";

  $eplug_conffile    = "admin_config.php";

  $eplug_logo        = "spam32.png";
  $eplug_icon        = "$eplug_folder/images/spam32.png";
  $eplug_icon_small  = "$eplug_folder/images/spam16.png";
  $eplug_caption     = 'Configure';  

  $eplug_prefs = array(

  "akismet_key"   => "",
  "askimet_spam"	=> "",
  "akismet_redir" => "",
  "akismet_stop_service" => "0",
  "akismet_reuse_spam_message" => "0",
  "akismet_poster_messages" => "
Nice hint! tank\'s for posting! :)|
Very good..|
Nice to see how others get set up! thanks for this.|
really nice... thank you|
GReat ! So simple, so excellent ! Woaaa !|
thank you for your information|
Nice article, really useful !|
Wow, this is great.. Useful post.. Thank you very much!|
great explanation to work php with projects, thank you.|
This is a very helpful tutorial..!|
Great Tutorial, simple and precious.|
I needed to know some of these solutions, thanks!|
Good tutorial, I really should add a few of these to my sites!|
awesome!! this is really good!! |
Great Tutorial,It\’s very useful... Thanks!|
Thanks!! Great article!|
Nice work, I was wonder how to do this|
Nice one Thank you man|
Lookin good! :)|
Nice article with a good information! ;)|
Thank you! :)|
Tip is great, short and very useful.|
Good stuff! :D|
Excellent tip, I already practice most of them though.|
Wow, thanks for this tip.",
"akismet_poster_names" => "
Ella|
Mary|
Alvador|
William|
John|
Benjamin|
Mary|
John|
Sarah|
James|
Kendall|
Elizabeth|
Wesley|
Sarah|
Annabel|
James|
Sarah|
Ella|
Henry|
Syvillia|
William|
Sarah|
James|
ouisa|
Monlla|
John|
Samuel|
George|
Minerva|
Martin|
Robert|
Vincent|
Abraham|
Rebecca|
America|
Joseph"
  );

$eplug_tables = array(
"CREATE TABLE ".MPREFIX."akismet (
	spam_id int(10) unsigned NOT NULL auto_increment,
	spam_comment text NOT NULL default '',
	spam_query varchar(255) NOT NULL default '',
	spam_userid int(10) NOT NULL,
	spam_timedate TIMESTAMP(8),
	spam_username varchar(255) NOT NULL default '',
	spam_userip varchar(255) NOT NULL default '',
	spam_subject varchar(255) NOT NULL default '',
	spam_where varchar(255) NOT NULL default '',
	PRIMARY KEY  (spam_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;");

  $eplug_table_names = array("akismet");

  $eplug_link      = FALSE;

  $eplug_done = "Plugin Succesfull Installed.";


?>	