<?php
/*
+---------------------------------------------------------------+
|        e107bb 3.1
|        DIPOrg (suporte@diporg.com)
|        http://www.diporg.com
|
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

$eplug_name = "e107bb";
$eplug_version = "3.1.t1";
$eplug_author = "DIPOrg";
$eplug_url = "http://www.diporgos.com/e107bb/";
$eplug_email = "suporte@diporg.com";
$eplug_description = "e107bb is a plugin that allows you to integrate and use phpBB in e107.";
$eplug_compatible = "e107 0.7.6+";
$eplug_compliant = false;
$eplug_readme = "readme.txt";

$eplug_folder = "e107bb";
$eplug_menu_name = "";
$eplug_conffile = "admin_index.php";
$eplug_module = true;
$eplug_logo =  $eplug_folder."/images/icon_32.png";
$eplug_icon = $eplug_folder."/images/icon_32.png";
$eplug_icon_small = $eplug_folder."/images/icon_16.png";
$eplug_caption = "phpBB integration for e107";

$eplug_prefs = array(
    "e107bb_url" => $_POST['bbpath'],
    "e107bb_bburl" => $_POST['bbpathi'],
    "e107bb_theme" => 0,
    "e107bb_tborder" => 1,
    "e107bb_version" => $eplug_version
);

$upgrade_add_prefs = array (
    "e107bb_version" => $eplug_version
);

$eplug_link = TRUE;
$eplug_link_name = "phpBB Forum";
$eplug_link_url = e_BASE.$_POST['bbpath'].'/';

$eplug_done = "";


if(!function_exists('e107bb_install')) {

	require_once (e_PLUGIN."e107bb/bbfunctions.php");


	function e107bb_install() {
		global $ns, $phpBBsqlprefix;

		//Debug Purposes
		//print_r($_POST);

		//Stage 1 Submit
		if(isset($_POST['bbsubmit1'])) {

			if(trim($_POST['bbpath']) == "") {
				e107bb_install_stage1("Insert something on the phpBB Path field.");
			}

			if(!file_exists(e_BASE.$_POST['bbpath']."/config.php")) {
				e107bb_install_stage1("phpBB not found at ".e_HTTP.$_POST['bbpath']."/<br/>You must install phpBB before installing e107bb.");
			}

			/* Get phpBB3 database prefix */
			require(e_BASE.$_POST['bbpath']."/config.php");
			require(e_BASE."e107_config.php");

			if($dbname != $mySQLdefaultdb) {
				e107bb_install_stage1("phpBB and e107 must share the same MySQL database!");
			}

			$phpBBsqlprefix = $table_prefix;

			//this is not unset, but it works
			$phpBBpath = "";
			$dbpasswd = "";
			$table_prefix = "";

			e107bb_install_stage2();

		} else if(isset($_POST['bbsubmit2'])) {
			e107bb_setupfiles();
			e107bb_install_finish();

		//Default Stage
		} else {
			e107bb_install_stage1();
		}
	


	}

	function e107bb_uninstall() {
		global $ns, $sql, $pref;

		/* To prevent cached results */
		clearstatcache();
				
		if(is_writable(PHPBB_PATH."/common.php") && is_writable(PHPBB_PATH.$pref['e107bb_url']."/"."includes/auth/")) {

			unlink(PHPBB_PATH.$pref['e107bb_url']."/includes/auth/auth_e107.php");

			// Edit common.php

			$common = PHPBB_PATH."/common.php";
			$common2 = fopen($common, "r");
			$common3 = @fread($common2, filesize($common));
			fclose($common2);

			// delete old e107bb stuff
			$common3 = preg_replace('%/\\* e107bb Integration - Start \\*/(.*?)/\\* e107bb Integration - End \\*/%si', '', $common3);

			// Save changes to common.php
			$save = fopen($common, 'w');
			if(!fwrite($save, $common3))
			{ echo "Error editing common.php!"; exit; }
			fclose($save);

			// Set auth to e107_db.php
			$msql = 'UPDATE `'.PHPBB_PREFIX.'config` SET `config_value` = \'db\' WHERE `config_name` = \'auth_method\' LIMIT 1 ;';
			if(!$sql->db_Select_gen($msql)){//mysql_query($sql) or
                die("Could not set auth method:  " . $sql .  "error: " . mysql_error());
            }

			//Enable phpBB registrations
			$msql = 'UPDATE `'.PHPBB_PREFIX.'config` SET `config_value` = \'1\' WHERE `config_name` = \'require_activation\' LIMIT 1 ;';
            if(!$sql->db_Select_gen($msql)){// or
                die("Could not set auth method:  " . $sql .  "error: " . mysql_error());
            }
			
			//Clear Cache
			destroy(PHPBB_PATH."/cache/");
			
		} else {
			e107bb_install_stage2();
		}
			


	}
	/* Upgrade Information */
	function e107bb_upgrade() {
		global $ns, $pref;
		if(E107BB_VERSION == "3.0.1") {
			if(is_writable(PHPBB_PATH."/common.php") && is_writable(PHPBB_PATH."/"."includes/auth/")) {

				//First uninstall, the old way

				unlink(PHPBB_PATH.$pref['e107bb_url']."/includes/auth/auth_e107.php");

				/* Edit common.php */

				$common = PHPBB_PATH."/common.php";
				$common2 = fopen($common, "r");
				$common3 = @fread($common2, filesize($common));
				fclose($common2);

				/* delete old e107bb stuff */
				$common3 = preg_replace('%/\\* e107bb Integration - Start \\*/(.*?)/\\* e107bb Integration - End \\*/%si', '', $common3);

				/* Save changes to common.php */
				$save = fopen($common, 'w');
				if(!fwrite($save, $common3))
				{ echo "Error editing common.php!"; exit; }
				fclose($save);

				/* Set auth to e107_auth.php */
				$sqlo = 'UPDATE `'.PHPBB_PREFIX.'config` SET `config_value` = \'db\' WHERE `config_name` = \'auth_method\' LIMIT 1 ;';
				mysql_query($sqlo) or die("Could not set auth method:  " . $sqlo .  "error: " . mysql_error());

				/* Disable phpBB registrations*/
				$sqlo = 'UPDATE `'.PHPBB_PREFIX.'config` SET `config_value` = \'1\' WHERE `config_name` = \'require_activation\' LIMIT 1 ;';
				mysql_query($sqlo) or die("Could not set auth method:  " . $sqlo .  "error: " . mysql_error());
				
				destroy(PHPBB_PATH."/cache/");

				$pref['auth_method'] = "e107";
				save_prefs();

				// Good! If you are here, the uninstallation was sucessful! Now install
				$_POST['phpBBsqlprefix'] = PHPBB_PREFIX;
				$_POST['bbpath'] = $pref['e107bb_url'];

				e107bb_setupfiles();

			} else {
				e107bb_install_stage2();
			}
		} else {
			$text = "<strong><u>Upgrading e107bb</u></strong><br/>Update is only supported from e107bb 3.0.1<br/><br/>";
			$ns->tablerender( "e107bb Upgrade Information", $text);
			require_once(e_ADMIN."footer.php");
			exit;
		}
	}
	

}

?>