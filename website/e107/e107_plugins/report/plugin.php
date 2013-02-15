<?php

$eplug_name = "Advanced Report System";
$eplug_version = "1.0";
$eplug_author = "VeN0m";
$eplug_folder = "report";
$eplug_icon = $eplug_folder."/images/reports32.png";
$eplug_icon_small = $eplug_folder."/images/reports16.png";
$eplug_url = "";
$eplug_email = "scharfer.senf@googlemail.com";
$eplug_description = "An advanced report system.";
$eplug_compatible = "e107v0.7+";
$eplug_caption = false;;
$eplug_link = FALSE;
$eplug_done = "Done";
$eplug_upgrade_done = "Upgrade successful...";
$eplug_menu_name = false;
$eplug_conffile = "admin_config.php";
$eplug_table_names = array("reports");

$eplug_tables = array(

   "CREATE TABLE ".MPREFIX."reports (
  `id` int(11) NOT NULL auto_increment,
  `message` text NOT NULL,
  `reported_content` text NOT NULL,
  `timestamp` int(15) NOT NULL,
  `report_parent` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `forum` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY  (`id`)

   ) TYPE=MyISAM;",

);



?>