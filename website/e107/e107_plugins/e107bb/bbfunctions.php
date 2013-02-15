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

//Language Compatibility with e107 v0.7.6+
$lan_file = "e107bb/languages/".e_LANGUAGE.".php";
include_once(file_exists($lan_file) ? $lan_file : "e107bb/languages/English.php");


/* Misc Functions */
/* Crazy way to destroy cache :P */

function destroy($dir) {
/* function credit:
http://forums.codewalkers.com/php-coding-7/delete-all-files-in-directory-714057.html*/

$mydir = opendir($dir);
while(false !== ($file = readdir($mydir))) {
	if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.htm") {
		chmod($dir.$file, 0777);
if(is_dir($dir.$file)) {
chdir('.');
destroy($dir.$file.'/');
rmdir($dir.$file) or DIE("couldn't delete $dir$file<br />");
} else {
unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
}
}
}
closedir($mydir);
}

/* Installer Functions */
function e107bb_install_header() {
global $ns;

$text = '<style type="text/css">
@import url("'.e_PLUGIN.'e107bb/bbstyle.css");
</style>

<div style="text-align:center; width: 95%; margin-left: auto; margin-right: auto"><div style="text-align:left;">
<div class="phpbb-style">
<div class="theme-headerbar">

<div class="theme-inner"><span class="theme-corners-top"><span></span></span>

<div id="theme-site-description">
<a href="#" title="Board index" id="theme-logo"><img src="'.e_PLUGIN.'e107bb/images/site_logo.gif" alt="" title="" height="52" width="139"></a>
<h1>'.LAN_E107BB_38.'</h1>
<p>'.LAN_E107BB_39.'</p>
</div>

<span class="theme-corners-bottom"><span></span></span></div>
</div></div>';

return $text;
}

function e107bb_install_footer() {
global $ns;

return "</div></div>";

}

function e107bb_install_stage1($errormsg = "") {
global $ns;

$text = e107bb_install_header().'

<form name="e107bb" action="'.e_SELF."?".e_QUERY.'" method="post">';
if($errormsg != "") {
$text .= '<table style="width: 100%;" class="fborder">
<tbody><tr>
<td class="fcaption" title="" style="text-align: left;"><strong>Error</strong></td>
</tr>
<tr> <td class="forumheader3"><strong>'.$errormsg.'</strong></td>
</tr></tbody>
</table><br/>';
}
$text .= '<table style="width: 100%;" class="fborder">
<tbody><tr>
<td class="fcaption" title="" style="text-align: left;" colspan="2">'.LAN_E107BB_40.'</td>
</tr>
<tr> <td colspan="2" class="forumheader3"> '.sprintf(LAN_E107BB_41, '<a href="http://www.phpbb.com/kb/article/transferring-your-board-to-a-new-host-or-domain/">phpBB KB #845</a>').'</td></tr>
<tr>
<td style="width: 50%;" class="forumheader3"><strong>'.LAN_E107BB_42.'</strong><br/><span class="smalltext">'.LAN_E107BB_43.'<br/>'.LAN_E107BB_44.'</span></td>
<td style="width: 50%; text-align: right;" class="forumheader3">
<input name="bbpath" class="tbox" type="text" value="" size="50" style="width: 100%"><br/>
</td>
</tr>
<tr>
<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmit1" value="'.LAN_E107BB_25.'" /></td>
</tr>
</tbody>
</table>
</form>
'.e107bb_install_footer();
		
$ns->tablerender( LAN_E107BB_12, $text);
require_once(e_ADMIN."footer.php");

exit;

}


function e107bb_install_stage2($errormsg = "") {
global $ns, $phpBBsqlprefix;

$text = e107bb_install_header().'<form name="e107bb" action="'.e_SELF."?".e_QUERY.'" method="post">

<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;" colspan="2">'.LAN_E107BB_18.'</td>

	</tr>

<tr>
<td colspan="2" class="forumheader">'.LAN_E107BB_45.'

<ul>';

$writemode = 0;

clearstatcache();

if(is_writable(e_BASE.$_POST['bbpath']."/common.php") ) { $text .= "<li>common.php <strong>".LAN_E107BB_20."</strong></li>"; } else { $text .= "<li>common.php <strong style=\"color:#ff0000;\">".LAN_E107BB_21."</strong></li>";  $writemode=1; }

if(is_writable(e_BASE.$_POST['bbpath']."/includes/auth/")) { $text .= "<li>includes/auth/ <strong>".LAN_E107BB_20."</strong></li>"; } else { $text .= "<li>includes/auth/ <strong style=\"color:#ff0000;\">".LAN_E107BB_21."</strong></li>";  $writemode=1; }

if($writemode == 0) {
$text .= '</ul><br/>

'.LAN_E107BB_22.'<br/>
'.LAN_E107BB_23.'
</tr>

<tr>
<input type="hidden" name="bbpath" value="'.$_POST['bbpath'].'"/>
<input type="hidden" name="phpBBsqlprefix" value="'.$phpBBsqlprefix.'"/>
		<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmit2" value="'.LAN_E107BB_25.'" /></td>
		</tr>
	</tbody>
</table>
</form>';
} else {
$text .= '</ul><br/>

'.LAN_E107BB_24.'<br/>
'.LAN_E107BB_23.'
</tr>

<tr>
<input type="hidden" name="bbpath" value="'.$_POST['bbpath'].'"/>
<input type="hidden" name="phpBBsqlprefix" value="'.$phpBBsqlprefix.'"/>
		<td colspan="2" style="text-align:center" class="forumheader"><input class="button" type="submit" name="bbsubmit1" value="'.LAN_E107BB_26.'" /></td>
		</tr>
	</tbody>
</table>
</form>';
}

$text .= e107bb_install_footer();
		
$ns->tablerender( LAN_E107BB_12, $text);
require_once(e_ADMIN."footer.php");

exit;

}

function e107bb_install_finish() {
global $ns;

$text = e107bb_install_header();

	$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">'.LAN_E107BB_12.'</td>
	</tr>
	<tr> <td class="forumheader3"><strong>'.LAN_E107BB_46.'</strong><br/>
	'.LAN_E107BB_47.'<br/>
	'.LAN_E107BB_48.'<br/><br/>
	<a href="'.e_PLUGIN.'e107bb/admin_index.php">'.LAN_E107BB_49.'</a><br/>';
if(defined("BBCONFIGPLUS")) {
$text .= '<img src="'.e_IMAGE.'admin_images/nopreview.png" alt="Warning" style="vertical-align:middle;"/><strong>'.LAN_E107BB_50.': <a href="'.e_PLUGIN.'e107bb/admin_install.php">Click here to continue</a></strong><br/>';

}
	$text .= '</td>
	</tr></tbody>
	</table><br/>';

$text .= e107bb_install_footer();
$ns->tablerender( LAN_E107BB_INST_PLINST, $text);

}

function e107bb_setupfiles() {
global $sql;
$sqlt = $sql;

$phpBBsqlprefix = $_POST['phpBBsqlprefix'];
if (!copy(e_PLUGIN."e107bb/temp/auth_e107.php", e_BASE.$_POST['bbpath']."/"."includes/auth/auth_e107.php")) {
	echo "error, could not copy auth_e107.php to ".e_BASE.$_POST['bbpath']."/"."includes/auth/auth_e107.php";
	exit;
}


if(is_writable(e_BASE.$_POST['bbpath']."/")) {
	if (!copy(e_BASE.$_POST['bbpath']."/common.php", e_BASE.$_POST['bbpath']."/common.bak.php")) {
		echo "error, could not backup common.php\n";
		exit;
	}
}

/* Edit common.php */

$common = e_BASE.$_POST['bbpath']."/common.php";
$common2 = fopen($common, "r");
$common3 = @fread($common2, filesize($common));
fclose($common2);

/* delete old e107bb stuff */
$common3 = preg_replace('%/\\* e107bb Integration - Start \\*/(.*?)/\\* e107bb Integration - End \\*/%si', '', $common3);

/* and replace it with new e107bb stuff */
				

$replacewith = "
if (!defined('IN_PHPBB'))
{
	exit;
}

/* e107bb Integration - Start */
/* From version: 3.1 */


if(!defined(\"e107_INIT\"))
{
	\$e107path = '../';
	while (!file_exists(\$e107path.'class2.php')) {
		\$e107path .= '../';
	}

	define(\"PHPBB_ROOT_PATH\", \$phpbb_root_path);
	require(\$e107path . '/class2.' . \$phpEx);

	\$phpbb_root_path = PHPBB_ROOT_PATH;
	\$phpEx = substr(strrchr(__FILE__, '.'), 1);
}

/* End of e107bb 3.1 Trash */
/* e107bb Integration - End */
";

echo preg_quote($lookfor);
$common3 = preg_replace('/if \\(\\!defined\\(\'IN_PHPBB\'\\)\\)(.*?)}/si', $replacewith, $common3);

/* Save changes to common.php */
$save = fopen($common, 'w');
if(!fwrite($save, $common3))
{ echo "Error editing common.php!"; exit; }
fclose($save);

/*
$common = e_BASE."/class2.php";
$common2 = fopen($common, "r");
$common3 = @fread($common2, filesize($common));
fclose($common2);
*/
//$common3 = preg_replace('%/\\* e107bb Fix - Start \\*/(.*?)/\\* e107bb Fix - End \\*/%si', '', $common3);
//$common3 = preg_replace('/\$inArray = array\("(.*)"\);/', "/* e107bb Fix - Start */\r\n\$_SERVER['QUERY_STRING'] = str_replace(\"&amp;\", \"&\", \$_SERVER['QUERY_STRING']);\r\n/* e107bb Fix - End */\r\n\r\n\$inArray = array(\"'\", \";\", \"/**/\", \"/UNION/\", \"/SELECT/\", \"AS \");", $common3);

/*
$save = fopen($common, 'w');
if(!fwrite($save, $common3))
{ echo "Error editing class2.php!"; exit; }
fclose($save);
*/

/* Set auth to e107_auth.php */
$msql = 'UPDATE `'.$phpBBsqlprefix.'config` SET `config_value` = \'e107\' WHERE `config_name` = \'auth_method\' LIMIT 1 ;';
if(!$sql->db_QueryFreeForm($msql)){//qmysql_query($sql) or
    die("Could not set auth method:  " . $sql .  "error: " . $sql->getSqlErrorno());
}
/* Only e107 auth stuff */
if($_POST['bbauthtype'] == "e107") {
/* Disable phpBB registrations*/
$msql = 'UPDATE `'.$phpBBsqlprefix.'config` SET `config_value` = \'3\' WHERE `config_name` = \'require_activation\' LIMIT 1 ;';
if(!$sql->db_QueryFreeForm($msql)){//mysql_query($sql) or
    die("Could not set auth method:  " . $sql .  "error: " .$sql->getSqlErrorno());// mysql_error()
}

/* e107bb 3.1: Disable phpBB auto-login*/
$msql = 'UPDATE `'.$phpBBsqlprefix.'config` SET `config_value` = \'0\' WHERE `config_name` = \'allow_autologin\' LIMIT 1 ;';
if(!$sql->db_QueryFreeForm($msql)){//mysql_query($sql) or
    die("Could not set autologin:  " . $sql .  "error: " . $sql->getSqlErrorno());
}

/* Set auth options */
$msql = 'UPDATE `'.$phpBBsqlprefix.'acl_roles_data` SET `auth_setting` = \'0\' WHERE `role_id` = \'6\' AND `auth_option_id` = \'92\' LIMIT 1;';
if(!$sql->db_QueryFreeForm($msql)) { //mysql_query($sql)
    $msql = 'INSERT INTO `'.$phpBBsqlprefix.'acl_roles_data` VALUES (6, 92, 0);';
    if(!$sql->db_QueryFreeForm($msql)){//mysql_query($sql) or
        die("Could not set role data:  " . $sql .  "error: " . mysql_error());
    }
}

$msql = 'UPDATE `'.$phpBBsqlprefix.'acl_roles_data` SET `auth_setting` = \'0\' WHERE `role_id` = \'6\' AND `auth_option_id` = \'89\' LIMIT 1;';
if(!$sql->db_QueryFreeForm($msql)) {
    $msql = 'INSERT INTO `'.$phpBBsqlprefix.'acl_roles_data` VALUES (6, 89, 0);';
    if(!$sql->db_QueryFreeForm($msql)){ //or
        die("Could not set role data:  " . $sql .  "error: " . $sql->getSqlErrorno());
    }
}
}


	if($sqlt->db_Select_gen("SHOW COLUMNS FROM ".PHPBB_PREFIX."users LIKE 'loginname'")===0) {
// Import some Users
$sqlt->db_Select_gen("SELECT b.username, b.user_email, b.user_regdate FROM ".PHPBB_PREFIX."users AS b LEFT JOIN #user AS e ON b.username = e.user_loginname WHERE e.user_loginname IS NULL and b.group_id <>6 AND b.group_id <>1 LIMIT 100");
$i=0;
$textu="";
while($row = $sqlt -> db_Fetch()) {
	$textu .= $row['username']."<br/>";

	$userrow[$i]['user_loginname'] = $row['username'];
	$userrow[$i]['user_name'] = $userrow[$i]['user_loginname'];
	$userrow[$i]['user_password'] = "phpbb";
	$userrow[$i]['user_email'] = $row['user_email'];
	$userrow[$i]['user_join'] = $row['user_regdate'];

	$i++;
}

	} else {
// Import some Users
$sqlt->db_Select_gen("SELECT b.loginname, b.username, b.user_email, b.user_regdate FROM ".PHPBB_PREFIX."users AS b LEFT JOIN #user AS e ON b.loginname = e.user_loginname WHERE e.user_loginname IS NULL and b.group_id <>6 AND b.group_id <>1 LIMIT 100");
$i=0;
$textu="";
while($row = $sqlt -> db_Fetch()) {
	$textu .= $row['username']."<br/>";

	$userrow[$i]['user_loginname'] = $row['loginname'];
	$userrow[$i]['user_name'] = $userrow[$i]['username'];
	$userrow[$i]['user_password'] = "phpbb";
	$userrow[$i]['user_email'] = $row['user_email'];
	$userrow[$i]['user_join'] = $row['user_regdate'];

	$i++;
}

	}

foreach($userrow as $bbuser) {

	$sqlt->db_Insert('user',$bbuser);

}

if($i>95) {
define("BBCONFIGPLUS",true);
}


destroy(e_BASE.$_POST['bbpath']."/cache/");
$sql = $sqlt;
}


function e107bb_install_stage3() {
global $ns;

$text = e107bb_install_header();

$text .= '<table style="width: 100%;" class="fborder">
<tbody><tr>
<td class="fcaption" title="" style="text-align: left;">e107bb</td>
</tr>
<tr> <td class="forumheader3">'.LAN_E107BB_56.' <a href="'.e_SELF.'?stage4">'.LAN_E107BB_25.'</a></td>
</tr></tbody>
</table><br/>';

$text .= e107bb_install_footer();
$ns->tablerender( "e107bb", $text);
require_once(e_ADMIN."footer.php");

}

function e107bb_install_stage4() {
global $ns, $sql;

	if($sql->db_Select_gen("SHOW COLUMNS FROM ".PHPBB_PREFIX."users LIKE 'loginname'")===0) {
// Import some Users
$sql->db_Select_gen("SELECT b.username, b.user_email, b.user_regdate FROM ".PHPBB_PREFIX."users AS b LEFT JOIN #user AS e ON b.username = e.user_loginname WHERE e.user_loginname IS NULL and b.group_id <>6 AND b.group_id <>1 LIMIT 100");
$i=0;
$textu="";
while($row = $sql -> db_Fetch()) {
	$textu .= $row['username']."<br/>";

	$userrow[$i]['user_loginname'] = $row['username'];
	$userrow[$i]['user_name'] = $userrow[$i]['user_loginname'];
	$userrow[$i]['user_password'] = "phpbb";
	$userrow[$i]['user_email'] = $row['user_email'];
	$userrow[$i]['user_join'] = $row['user_regdate'];

	$i++;
}

	} else {
// Import some Users
$sql->db_Select_gen("SELECT b.loginname, b.username, b.user_email, b.user_regdate FROM ".PHPBB_PREFIX."users AS b LEFT JOIN #user AS e ON b.loginname = e.user_loginname WHERE e.user_loginname IS NULL and b.group_id <>6 AND b.group_id <>1 LIMIT 100");
$i=0;
$textu="";
while($row = $sql -> db_Fetch()) {
	$textu .= $row['username']."<br/>";

	$userrow[$i]['user_loginname'] = $row['loginname'];
	$userrow[$i]['user_name'] = $userrow[$i]['username'];
	$userrow[$i]['user_password'] = "phpbb";
	$userrow[$i]['user_email'] = $row['user_email'];
	$userrow[$i]['user_join'] = $row['user_regdate'];

	$i++;
}
	}

if($i==0) {

	e107bb_install_finish();

} else {

	foreach($userrow as $bbuser) {

		$sql->db_Insert('user',$bbuser);

	}

	$text = e107bb_install_header();

	$text .= '<table style="width: 100%;" class="fborder">
	<tbody><tr>
	<td class="fcaption" title="" style="text-align: left;">e107bb Wizard</td>
	</tr>
	<tr> <td class="forumheader3">'.LAN_E107BB_51.' <noscript>'.LAN_E107BB_52.'</noscript><br/>';
	$text .= $textu;
	$text .= '</td>
	</tr></tbody>
	</table><br/>';
	$text .= "<script type='text/javascript'>document.location.reload(true)</script>\n";
	$text .= e107bb_install_footer();
	$ns->tablerender( LAN_E107BB_12, $text);
	require_once(e_ADMIN."footer.php");

}


}

function e107bb_install_stage5() {
global $ns, $sql;

e107bb_install_finish();

}

/* e107bb Admin Menu */

function e107bb_admin_menu() {
global $pageid, $ns;
//The links...
	$menutitle = LAN_E107BB_53;

	$butname[] = LAN_E107BB_3; 
	$butlink[] = "admin_index.php";
	$butid[] = "index";        

	$butname[] = LAN_E107BB_6;  
	$butlink[] = "admin_bbtheme.php";  
	$butid[] = "bbtheme"; 

	$butname[] = LAN_E107BB_14;  
	$butlink[] = "admin_remod.php";  
	$butid[] = "remod";   

//...and the script to show the menu.

	for ($i=0; $i<count($butname); $i++) {
        $var[$butid[$i]]['text'] = $butname[$i];
		$var[$butid[$i]]['link'] = $butlink[$i];
	};

    show_admin_menu($menutitle,$pageid, $var);

//And more stuff...

	$helptitle = "More Stuff";
require(e_PLUGIN.'e107bb/plugin.php');
	$helpcapt[] = LAN_E107BB_54;
	$helptext[] = "Version: ".$eplug_version." <br/><br/><strong>".LAN_E107BB_55."</strong><br/><a href='http://www.diporg.com'>DIPOrg</a>";

//---------------------------------------------------------------

	$text2 = "";
	for ($i=0; $i<count($helpcapt); $i++) {
		$text2 .="<b>".$helpcapt[$i]."</b><br />";
	$text2 .=$helptext[$i]."<br /><br />";
	};

$ns -> tablerender($helptitle, $text2);

}


/* phpBB Password Validation*/
function e107_phpbb_check_hash($password, $hash)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if (strlen($hash) == 34)
	{
		return (e107_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	}

	return (md5($password) === $hash) ? true : false;
}

/**
* Encode hash
*/
function e107_hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}

/**
* The crypt function/replacement
*/
function e107_hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	/**
	* We're kind of forced to use MD5 here since it's the only
	* cryptographic primitive available in all versions of PHP
	* currently in use.  To implement our own low-level crypto
	* in PHP would result in much worse performance and
	* consequently in lower iteration counts and hashes that are
	* quicker to crack (by non-PHP code).
	*/
	if (PHP_VERSION >= 5)
	{
		$hash = md5($salt . $password, true);
		do
		{
			$hash = md5($hash . $password, true);
		}
		while (--$count);
	}
	else
	{
		$hash = pack('H*', md5($salt . $password));
		do
		{
			$hash = pack('H*', md5($hash . $password));
		}
		while (--$count);
	}

	$output = substr($setting, 0, 12);
	$output .= e107_hash_encode64($hash, 16, $itoa64);

	return $output;
}

?>