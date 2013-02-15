<?php
/*
+ ----------------------------------------------------------------------------------------------------+
|        e107 website system 
|        Plugin File :  e107_plugins/lightbox/plugin.php
|        Email: support@free-source.net
|        $Revision: 372 $
|        $Date: 2007-02-12 23:22:29 +0200 (Mon, 12 Feb 2007) $
|        $Author: secretr $
|        Copyright Corllete Lab ( http://www.clabteam.com ) under GNU GPL License (http://gnu.org)
|        Support Sites : http://www.free-source.net/ | http://dev.e107bg.org/
+----------------------------------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

include(e_PLUGIN."passchecker/Language/".e_LANGUAGE.".php");

// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = PASSCHK_1;
$eplug_version = "1.0";
$eplug_author = PASSCHK_2;
$eplug_url = "http://e107.ir";
$eplug_email = "sonixax@yahoo.com";
$eplug_description = PASSCHK_3;
$eplug_compatible = "e107 v0.77+";
$eplug_readme = "";       
$eplug_compliant = TRUE;
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "passchecker";

// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/icon.png";
$eplug_icon_small = $eplug_folder."/images/icon_16.png";
$eplug_logo = $eplug_folder."/images/icon.png";

// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array();


// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array(
   "passchkenable"   => "1"
);


// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = FALSE;

//$eplug_module = TRUE;
// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = PASSCHK_4;

$eplug_uninstall_done = PASSCHK_5;

// upgrading ... v. < 1.3 compat//

$upgrade_add_prefs = "";

$upgrade_remove_prefs = "";

$upgrade_alter_tables = "";

$eplug_upgrade_done = "";
?>