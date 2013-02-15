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

/* Default variables of phpbb */
if(isset($pref['e107bb_url'])) {
/* e107bb 3.0.t3 - Updated old bad way to get url*/
/* e107bb 3.0.0 - Updated path to avoid input errors.*/
$phpBBpath = "./".$pref['e107bb_url']."/";
define('PHPBB_PATH', e_BASE.$phpBBpath);

if(file_exists(PHPBB_PATH)) {

/* Get phpBB3 database prefix */
require(PHPBB_PATH."config.php");
$phpBBsqlprefix = $table_prefix;
define('PHPBB_PREFIX', $phpBBsqlprefix);

define('E107BB_VERSION', $pref['e107bb_version']);

//define('E107BB_AUTHTYPE', $pref['e107bb_bbauthtype']);

/*this is not unset, but it works*/
//$phpBBpath = "";
$dbpasswd = "";
$table_prefix = "";
$phpBBsqlprefix = "";
} else {
echo "<h1>phpBB could not be found at ".PHPBB_PATH."!</h1> Please fix this error.<br/>";
}
}

// e107bb 3.1: For phpBB only users
/*
if (isset($_POST['userlogin']) || isset($_POST['userlogin_x'])) {
	if($sql->db_Select("user", "user_loginname", "LOWER(user_loginname) = LOWER('".$tp -> toDB(trim($_POST['username']))."') AND user_password = 'phpbb'")) {
		require_once (e_PLUGIN."e107bb/bbfunctions.php");
		$sql->db_Select_gen("SELECT user_password FROM ".PHPBB_PREFIX."users  WHERE LOWER(username)=LOWER('".$tp -> toDB(trim($_POST['username']))."') LIMIT 1");
		while($row = $sql -> db_Fetch()) {
			if(e107_phpbb_check_hash(trim($_POST['userpass']), $row['user_password']) === true) {
				$sql->db_Update("user", "user_password='".md5(trim($_POST['userpass']))."' WHERE user_loginname='".$tp -> toDB(trim($_POST['username']))."' LIMIT 1");
			}
		}
	}
}*/


//e107bb 3.1: Crazy Stuff for phpBB
//e107bb Admin CP: Theme Integration Preview
//phpBB Hook: Stuff for Theme Integration
if(defined('IN_PHPBB')) {

	if(ADMIN && isset($_GET['e107bb']) && $_GET['e107bb'] === "preview") {
		if($_GET['e107bb_theme'] === "ok") {
			$pref['e107bb_theme'] = "ok";
		} else {
			$pref['e107bb_theme'] = "0";
		}
		if($_GET['e107bb_tborder'] === "ok") {
			$pref['e107bb_tborder'] = "1";
		} else {
			$pref['e107bb_tborder'] = "0";
		}
	}


	if($pref["e107bb_theme"] !== "0") {
		function bb_e107_header(&$hook,$handle, $include_once = true) {
			global $sql, $CUSTOMPAGES, $HEADER, $FOOTER, $pref, $tp, $ns, $template, $e107ParseHeaderFlag, $e107_Clean_Exit;
			if($handle === "body") {
				include(e_BASE.'/e107_config.php'); 

				$sql = new db;
				$sql2 = new db;

				$sql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);

				ob_start();
				require_once(HEADERF);
				$e107header = ob_get_contents();
				ob_end_clean();

				if($pref["e107bb_tborder"] === "1") {
					ob_start();
					$ns->tablerender("phpBB Forum", "---CONTENT---");
					$tablerender1 = ob_get_contents();
					ob_end_clean();
					preg_match('/(.*?)---CONTENT---(.*)/s', $tablerender1, $matches);
					$bbpre = $matches[1];
					$bbpos = $matches[2];
				} else {
					$bbpre = "";
					$bbpos = "";
				}

				preg_match('%<head\b[^>]*>(.*?)</head>%is',$e107header, $matches);
				$e107headtag = $matches[1];
				preg_match('%<body\b[^>]*>(.*)%is',$e107header, $matches);
				$e107bodytag = $matches[1];

				ob_start();
				$e107_Clean_Exit = true;
				parseheader(($e107ParseHeaderFlag ? $e107CustomFooter : $FOOTER));
				$e107footer = ob_get_contents();
				ob_end_clean();

				$template->assign_vars(array(
				"E107HEADER" => $e107headtag,
				"E107BODY" => $e107bodytag.$bbpre,
				"E107FOOTER" => $bbpos.$e107footer
				));

			}
		}

	} else {

		function bb_e107_header(&$hook,$handle, $include_once = true)
		{
		//Absolutely Nothing
		}

	}

}

?>