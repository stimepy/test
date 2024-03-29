<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/class2.php $
|     $Revision: 12422 $
|     $Id: class2.php 12422 2011-11-29 23:36:57Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
//
// *** Code sequence for startup ***
// IMPORTANT: These items are in a carefully constructed order. DO NOT REARRANGE
// without checking with experienced devs! Various subtle things WILL break.
//
// A Get the current CPU time so we know how long all of this takes
// B Remove output buffering so we are in control of text sent to user
// C Remove registered globals (SECURITY for all following code)
// D Setup PHP error handling (now we can see php errors ;))
// E Setup other PHP essentials
// F Grab e107_config to get directory paths
// G Retrieve Query from URI (i.e. what are the request parameters?!)
// H Initialize debug handling (NOTE: A-G cannot use debug tools!)
// I: Sanity check to ensure e107_config is ok
// J: MYSQL setup (NOTE: A-I cannot use database!)
// K: Compatibility mode
// L: Retrieve core prefs
// M: Subdomain and language selection
// N: Other misc setups (NOTE: Put most 'random' things here that don't require user session or theme
// O: Start user session
// P: Load theme
// Q: Other setups

//
// A: Honest global beginning point for processing time
//
$eTimingStart = microtime();					// preserve these when destroying globals in step C
$oblev_before_start = ob_get_level();

// Block common bad agents / queries / php issues. 
array_walk($_SERVER,  'e107_filter', '_SERVER');
if (isset($_GET)) array_walk($_GET,     'e107_filter', '_GET');
if (isset($_POST)) array_walk($_POST,    'e107_filter', '_POST');
if (isset($_COOKIE)) array_walk($_COOKIE,  'e107_filter', '_COOKIE');

//
// B: Remove all output buffering
//
while (@ob_end_clean());  // destroy all ouput buffering
ob_start();             // start our own.
$oblev_at_start = ob_get_level(); 	// preserve when destroying globals in step C

//
// C: Find out if register globals is enabled and destroy them if so
// (DO NOT use the value of any variables before this point! They could have been set by the user)
//
$register_globals = true;
if(function_exists('ini_get')) {
	$register_globals = ini_get('register_globals');
}

// Destroy! (if we need to)
if($register_globals == true){
	while (list($global) = each($GLOBALS)) {
		if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*|_REQUEST|retrieve_prefs|eplug_admin|eTimingStart)|oblev_.*$/', $global)) {
			unset($$global);
		}
	}
	unset($global);
}


if(($pos = strpos(strtolower($_SERVER['PHP_SELF']), ".php/")) !== false) // redirect bad URLs to the correct one.
{
	$new_url = substr($_SERVER['PHP_SELF'], 0, $pos+4);
	$new_loc = ($_SERVER['QUERY_STRING']) ? $new_url."?".$_SERVER['QUERY_STRING'] : $new_url;
	header("Location: ".$new_loc);
	exit();
}
// If url contains a .php in it, PHP_SELF is set wrong (imho), affecting all paths.  We need to 'fix' it if it does.
$_SERVER['PHP_SELF'] = (($pos = strpos(strtolower($_SERVER['PHP_SELF']), ".php")) !== false ? substr($_SERVER['PHP_SELF'], 0, $pos+4) : $_SERVER['PHP_SELF']);
unset($pos);
//
// D: Setup PHP error handling
//    (Now we can see PHP errors) -- but note that DEBUG is not yet enabled!
//
$error_handler = new error_handler();
set_error_handler(array(&$error_handler, "handle_error"));

//
// E: Setup other essential PHP parameters
//
define("e107_INIT", TRUE);

require_once('e107_handlers/e107_functions.php');

// setup some php options
e107_ini_set('magic_quotes_runtime',     0);
e107_ini_set('magic_quotes_sybase',      0);
e107_ini_set('arg_separator.output',     '&amp;');
e107_ini_set('session.use_only_cookies', 1);
e107_ini_set('session.use_trans_sid',    0);




if(isset($retrieve_prefs) && is_array($retrieve_prefs)) {
	foreach ($retrieve_prefs as $key => $pref_name) {
		 $retrieve_prefs[$key] = preg_replace("/\W/", '', $pref_name);
	}
} else {
	unset($retrieve_prefs);
}

define("MAGIC_QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// Define the domain name and subdomain name.
if(is_numeric(str_replace(".","",$_SERVER['HTTP_HOST'])))
{
	$domain = FALSE;
	$subdomain = FALSE;
}
else
{	
	if(preg_match("/\.?([a-z0-9-]+)(\.(com|net|org|co|me|ltd|plc|gov)\.[a-z]{2})$/i",$_SERVER['HTTP_HOST'],$m)) //eg. mysite.co.uk
	{
        $domain = $m[1].$m[2];
    }
	elseif(preg_match("/\.?([a-z0-9-]+)(\.[a-z]{2,})$/i",$_SERVER['HTTP_HOST'],$m))//  eg. .com/net/org/ws/biz/info
	{       
        $domain = $m[1].$m[2];		
    }
	else
	{
		$domain = FALSE; //invalid domain
	}
	
	$replace = array(".".$domain,"www.","www",$domain);
	$subdomain = str_replace($replace,'',$_SERVER['HTTP_HOST']);
}

define("e_DOMAIN",$domain);
define("e_SUBDOMAIN",($subdomain) ? $subdomain : FALSE);
unset($domain,$subdomain,$replace,$m);

// ---------------------------

//  Ensure thet '.' is the first part of the include path
$inc_path = explode(PATH_SEPARATOR, ini_get('include_path'));
if($inc_path[0] != ".") {
	array_unshift($inc_path, ".");
	$inc_path = implode(PATH_SEPARATOR, $inc_path);
	e107_ini_set("include_path", $inc_path);
}
unset($inc_path);

//
// F: Grab e107_config, get directory paths and create $e107 object
//
@include_once(realpath(dirname(__FILE__).'/e107_config.php'));

// set debug mode in e107_config.php when admin access is unavailable
if(defset('e_DEBUG')==TRUE) 
{
	$error_handler->debug = true;
	error_reporting(E_ALL | E_STRICT);
}

if(isset($CLASS2_INCLUDE) && ($CLASS2_INCLUDE!=''))
{ 
	 require_once(realpath(dirname(__FILE__).'/'.$CLASS2_INCLUDE)); 
}

if(!isset($ADMIN_DIRECTORY))
{
  // e107_config.php is either empty, not valid or doesn't exist so redirect to installer..
  header("Location: install.php");
  exit();
}

//
// clever stuff that figures out where the paths are on the fly.. no more need fo hard-coded e_HTTP :)
//
e107_require_once(realpath(dirname(__FILE__).'/'.$HANDLERS_DIRECTORY).'/e107_class.php');
$e107_paths = compact('ADMIN_DIRECTORY', 'FILES_DIRECTORY', 'IMAGES_DIRECTORY', 'THEMES_DIRECTORY', 'PLUGINS_DIRECTORY', 'HANDLERS_DIRECTORY', 'LANGUAGES_DIRECTORY', 'HELP_DIRECTORY', 'DOWNLOADS_DIRECTORY');
$e107 = new e107($e107_paths, realpath(dirname(__FILE__)));

$inArray = array("'", ";", "/**/", "/UNION/", "/SELECT/", "AS ");
if (strpos($_SERVER['PHP_SELF'], "trackback") === false) {
	foreach($inArray as $res) {
		if(stristr($_SERVER['QUERY_STRING'], $res)) {
			//if string has any works or characters in the inArray, except amp;
			//TODO: Rewrite for better security
			if(!stristr($_SERVER['QUERY_STRING'],'&amp;&amp;')){
				die("Access denied.");
			}
		}
	}
}
unset($inArray);

/**
 * NEW - system security levels
 * Could be overridden by e107_config.php OR $CLASS2_INCLUDE script (if not set earlier)
 * 
 * 0 (disabled)
 * 5 (balanced) - token value once per session
 * 8 (high) - token value regenerated on every page load
 * 10 (insane) - #8 + regenerate SID on every page load
 * default is 5
 */
if(!defined('e_SECURITY_LEVEL')) 
{
	define('e_SECURITY_LEVEL', 5);
}

/**
 * G: Retrieve Query data from URI
 * (Until this point, we have no idea what the user wants to do)
 */

if (preg_match("#\[(.*?)](.*)#", $_SERVER['QUERY_STRING'], $matches)) {
	define("e_MENU", $matches[1]);
	$e_QUERY = $matches[2];
	unset($matches);
}
else
{
	define("e_MENU", "");
	$e_QUERY = $_SERVER['QUERY_STRING'];
}

//
// Start the parser; use it to grab the full query string
//

e107_require_once(e_HANDLER.'e_parse_class.php');
$tp = new e_parse;

$e_QUERY = str_replace(array('{', '}', '%7B', '%7b', '%7D', '%7d'), '', rawurldecode($e_QUERY));
$e_QUERY = str_replace('&', '&amp;', $tp->post_toForm($e_QUERY));

/**
 * e_QUERY notes:
 * It seems _GET / _POST / _COOKIE are doing pre-urldecode on their data.
 * There is no official documentation/php.ini setting to confirm this.
 * We could add rawurlencode() after the replacement above if problems are reported.
 *
 * @var string
 */
define('e_QUERY', $e_QUERY);

//$e_QUERY = e_QUERY;

define("e_TBQS", $_SERVER['QUERY_STRING']);
$_SERVER['QUERY_STRING'] = e_QUERY;

define("e_UC_PUBLIC", 0);
define("e_UC_MAINADMIN", 250);
define("e_UC_READONLY", 251);
define("e_UC_GUEST", 252);
define("e_UC_MEMBER", 253);
define("e_UC_ADMIN", 254);
define("e_UC_NOBODY", 255);
define("ADMINDIR", $ADMIN_DIRECTORY);

/**
 * H: Initialize debug handling
 * (NO E107 DEBUG CONSTANTS OR CODE ARE AVAILABLE BEFORE THIS POINT)
 * All debug objects and constants are defined in the debug handler
 * i.e. from here on you can use E107_DEBUG_LEVEL or any
 * E107_DBG_* constant for debug testing.
 */

require_once(e_HANDLER.'debug_handler.php');

if(E107_DEBUG_LEVEL && isset($db_debug) && is_object($db_debug)) {
	$db_debug->Mark_Time('Start: Init ErrHandler');
}

//
// I: Sanity check on e107_config.php
//     e107_config.php upgrade check
if (!$ADMIN_DIRECTORY && !$DOWNLOADS_DIRECTORY) {
	message_handler("CRITICAL_ERROR", 8, ": generic, ", "e107_config.php");
	exit;
}

//
// J: MYSQL INITIALIZATION
//
@require_once(e_HANDLER.'traffic_class.php');
$eTraffic=new e107_traffic; // We start traffic counting ASAP
$eTraffic->Calibrate($eTraffic);

define("MPREFIX", $mySQLprefix);

e107_require_once(e_HANDLER."mysql_class.php");

$sql = new db;
$sql2 = new db;

$sql->db_SetErrorReporting(FALSE);

$sql->db_Mark_Time('Start: SQL Connect');
$merror=$sql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);
$sql->db_Mark_Time('Start: Prefs, misc tables');

require_once(e_HANDLER.'admin_log_class.php');
$admin_log = new e_admin_log();

if ($merror == "e1") {
	message_handler("CRITICAL_ERROR", 6, ": generic, ", "class2.php");
	exit;
}
else if ($merror == "e2") {
	message_handler("CRITICAL_ERROR", 7, ": generic, ", "class2.php");
	exit;
}

//
// K: Load compatability mode.
//
/* At a later date add a check to load e107 compat mode by $pref
PHP Compatabilty should *always* be on. */
e107_require_once(e_HANDLER."php_compatibility_handler.php");
e107_require_once(e_HANDLER."e107_Compat_handler.php");
$aj = new textparse; // required for backwards compatibility with 0.6 plugins.

//
// L: Extract core prefs from the database
//
$sql->db_Mark_Time('Start: Extract Core Prefs');
e107_require_once(e_HANDLER."pref_class.php");
$sysprefs = new prefs;

e107_require_once(e_HANDLER.'cache_handler.php');
e107_require_once(e_HANDLER.'arraystorage_class.php');
$eArrayStorage = new ArrayData();

$PrefCache = ecache::retrieve('SitePrefs', 24 * 60, true);
if(!$PrefCache){
	// No cache of the prefs array, going for the db copy..
	$retrieve_prefs[] = 'SitePrefs';
	$sysprefs->ExtractPrefs($retrieve_prefs, TRUE);
	$PrefData = $sysprefs->get('SitePrefs');
	$pref = $eArrayStorage->ReadArray($PrefData);
	if(!$pref){
		$admin_log->log_event("CORE_LAN8", "CORE_LAN7", E_LOG_WARNING); // Core prefs error, core is attempting to
		// Try for the automatic backup..
		$PrefData = $sysprefs->get('SitePrefs_Backup');
		$pref = $eArrayStorage->ReadArray($PrefData);
		if(!$pref){
			// No auto backup, try for the 'old' prefs system.
			$PrefData = $sysprefs->get('pref');
			$pref = unserialize($PrefData);
			if(!is_array($pref)){
				message_handler("CRITICAL_ERROR", 3, __LINE__, __FILE__);
				// No old system, so point in the direction of resetcore :(
				message_handler("CRITICAL_ERROR", 4, __LINE__, __FILE__);
				$admin_log->log_event("CORE_LAN8", "CORE_LAN9", E_LOG_FATAL); // Core could not restore from automatic backup. Execution halted.
				exit;
			} else {
				// old prefs found, remove old system, and update core with new system
				$PrefOutput = $eArrayStorage->WriteArray($pref);
				if(!$sql->db_Update('core', "e107_value='{$PrefOutput}' WHERE e107_name='SitePrefs'")){
					$sql->db_Insert('core', "'SitePrefs', '{$PrefOutput}'");
				}
				if(!$sql->db_Update('core', "e107_value='{$PrefOutput}' WHERE e107_name='SitePrefs_Backup'")){
					$sql->db_Insert('core', "'SitePrefs_Backup', '{$PrefOutput}'");
				}
				$sql->db_Delete('core', "`e107_name` = 'pref'");
			}
		} else {
			message_handler("CRITICAL_ERROR", 3, __LINE__, __FILE__);
			// auto backup found, use backup to restore the core
			if(!$sql->db_Update('core', "`e107_value` = '".addslashes($PrefData)."' WHERE `e107_name` = 'SitePrefs'")){
				$sql->db_Insert('core', "'SitePrefs', '".addslashes($PrefData)."'");
			}
		}
	}
	// write pref cache array
	$PrefCache = $eArrayStorage->WriteArray($pref, false);
	// store the prefs in cache if cache is enabled
	ecache::set('SitePrefs', $PrefCache);
} else {
	// cache of core prefs was found, so grab all the useful core rows we need
	if(!isset($sysprefs->DefaultIgnoreRows)){
    	$sysprefs->DefaultIgnoreRows = "";
	}
	$sysprefs->DefaultIgnoreRows .= '|SitePrefs';
	$sysprefs->prefVals['core']['SitePrefs'] = $PrefCache;
	if(isset($retrieve_prefs))
	{
		$sysprefs->ExtractPrefs($retrieve_prefs, TRUE);
	}
	$pref = $eArrayStorage->ReadArray($PrefCache);
}

$e107->set_base_path();

// extract menu prefs
$menu_pref = unserialize(stripslashes($sysprefs->get('menu_pref')));

$sql->db_Mark_Time('(Extracting Core Prefs Done)');


//
// M: Subdomain and Language Selection
//
define("SITEURLBASE", ($pref['ssl_enabled'] == '1' ? "https://" : "http://").$_SERVER['HTTP_HOST']);
define("SITEURL", SITEURLBASE.e_HTTP);

if(!defined('e_SELF')) // user override option 
{
	define("e_SELF", ($pref['ssl_enabled'] == '1' ? "https://".$_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST']) . ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']));	
}

$page = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
define("e_PAGE", $page);
	  
/**
 * Detect if we are in the Admin Area. 
 * The following files are assumed to be admin areas:
 * 1. Any file in the admin directory (check for non-plugin added to avoid mismatches)
 * 2. any plugin file starting with 'admin_'
 * 3. any plugin file in a folder called admin/
 * 4. any file that specifies $eplug_admin = TRUE before class2.php is included.
 */
$inAdminDir = FALSE;
$isPluginDir = strpos(e_SELF,'/'.$PLUGINS_DIRECTORY) !== FALSE;		// True if we're in a plugin
$e107Path = str_replace($e107->base_path, "", e_SELF);				// Knock off the initial bits
if	(
		 (!$isPluginDir && strpos($e107Path, $ADMIN_DIRECTORY) === 0 ) 								// Core admin directory
	  || ($isPluginDir && (strpos(e_PAGE,"admin_") === 0 || strpos($e107Path, "admin/") !== FALSE)) // Plugin admin file or directory
	  || (varsettrue($eplug_admin))																	// Admin forced
	)
{
	$inAdminDir = TRUE;
}

// -----------------------------------------

// Ensure $pref['sitelanguage'] is set if upgrading from 0.6
$pref['sitelanguage'] = (isset($pref['sitelanguage']) ? $pref['sitelanguage'] : 'English');

// if a cookie name pref isn't set, make one :)
if (!$pref['cookie_name']) {
	$pref['cookie_name'] = "e107cookie";
}

$sql->db_Mark_Time('Start: Init Language and detect changes');
require_once(e_HANDLER."language_class.php");
$lng = new language;
$lng->detect(); // Must be before session_start(). Requires $pref, e_DOMAIN, e_MENU;

// e-Token START
$sql->db_Mark_Time('Start: e-Token creation');

/**
 * Set Cache Headers
 * Must be set before session_start()
 */
if($inAdminDir || defined('e_NOCACHE'))
{
	 session_cache_limiter('nocache'); // don't cache the html. (Should fix back-button issues)
}
else
{
	header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: post-check=0, pre-check=0",false);
	session_cache_limiter("must-revalidate");	
}

$SESS_NAME = strtoupper(preg_replace("/[\W_]/","",$pref['cookie_name'])); // clean-up characters.  
session_name('SESS'.$SESS_NAME); // avoid session conflicts with separate sites within subdomains
unset($SESS_NAME);

// Start session after $prefs are available.
session_start(); // Needs to be started after language detection (session.cookie_domain) to avoid multi-language 'access-denied' issues. 
header("Cache-Control: must-revalidate");	
// TODO - maybe add IP as well?
define('e_TOKEN_NAME', 'e107_token_'.md5($_SERVER['HTTP_HOST'].e_HTTP));

// Ajax calls should be handled manual at this time (set e_TOKEN_FREEZE in Ajax scripts before the API is loaded)
if(e_SECURITY_LEVEL > 0 && session_id() && isset($_POST['e-token']) && ($_POST['e-token'] != varset($_SESSION[e_TOKEN_NAME]))/* && $_POST['ajax_used']!=1*/)
{
	if(defsettrue('e_DEBUG'))
	{		
		$details = "HOST: ".$_SERVER['HTTP_HOST']."\n";
		$details .= "REQUEST_URI: ".$_SERVER['REQUEST_URI']."\n";		
		$details .= "_SESSION:\n";
		$details .= print_r($_SESSION,true);
		$details .= "\n_POST:\n";
		$details .= print_r($_POST,true);
		$details .= "\nPlugins:\n";
		$details .= print_r($pref['plug_installed'],true);
			
		$admin_log->log_event("Access denied", $details, E_LOG_WARNING);				
	}	
		// do not redirect, prevent dead loop, save server resources
	die('Access denied');
}

// Create new token only if not exists or session id is regenerated (footer.php), respect security level
if(!isset($_SESSION[e_TOKEN_NAME]) || (e_SECURITY_LEVEL >= 8 && isset($_SESSION['regenerate_'.e_TOKEN_NAME]) && !defsettrue('e_TOKEN_FREEZE')))
{
	// we should not break ajax calls this way
	$_SESSION[e_TOKEN_NAME] = uniqid(md5(rand()), true);
	// this will be reset in the end of the script (footer) if needed
	unset($_SESSION['regenerate_'.e_TOKEN_NAME]);
}

// use it now
define('e_TOKEN', $_SESSION[e_TOKEN_NAME]);

// Debug
//header('X-etoken-name: '.e_TOKEN_NAME);
//header('X-etoken-value: '.e_TOKEN);
//header('X-etoken-freeze: '.(defsettrue('e_TOKEN_FREEZE') ? 1 : 0));

// e-Token END
// start a session if session based login is enabled

// if ($pref['user_tracking'] == "session")
//{
//	session_start(); // start the session, even if it won't be used for login-tracking. 
//}


// if the option to force users to use a particular url for the site is enabled, redirect users there as needed
// Now matches RFC 2616 (sec 3.2): case insensitive, https/:443 and http/:80 are equivalent.
// And, this is robust against hack attacks. Malignant users can put **anything** in HTTP_HOST!
if($pref['redirectsiteurl'] && $pref['siteurl'])
{

	if(isset($pref['multilanguage_subdomain']) && $pref['multilanguage_subdomain'])
	{
   		if(substr(e_SELF,7,4)=="www." || substr(e_SELF,8,4)=="www.")
		{
			$self = e_SELF;
			if(e_QUERY){ $self .= "?".e_QUERY; }
			$location = str_replace("://www.","://",$self);
			header("Location: {$location}", true, 301); // send 301 header, not 302
			exit();
		}
	}
    else
	{
		// Find domain and port from user and from pref
		list($urlbase,$urlport) = explode(':',$_SERVER['HTTP_HOST'].':');
		if (!$urlport) { $urlport = $_SERVER['SERVER_PORT']; }
		if (!$urlport) { $urlport = 80; }
		$aPrefURL = explode('/',$pref['siteurl'],4);
		if (count($aPrefURL) > 2) { // we can do this -- there's at least http[s]://dom.ain/whatever
			$PrefRoot = $aPrefURL[2];
			list($PrefSiteBase,$PrefSitePort) = explode(':',$PrefRoot.':');
			if (!$PrefSitePort) {
				$PrefSitePort = ( $aPrefURL[0] == "https:" ) ? 443 : 80;	// no port so set port based on 'scheme'
			}

			// Redirect only if
			// -- ports do not match (http <==> https)
			// -- base domain does not match (case-insensitive)
			// -- NOT admin area
			if (($urlport != $PrefSitePort || stripos($PrefSiteBase, $urlbase) === FALSE) && strpos(e_SELF, ADMINDIR) === FALSE) 		{
				$aeSELF = explode('/',e_SELF,4);
				$aeSELF[0] = $aPrefURL[0];	// Swap in correct type of query (http, https)
				$aeSELF[1] = '';						// Defensive code: ensure http:// not http:/<garbage>/
				$aeSELF[2] = $aPrefURL[2];  // Swap in correct domain and possibly port
				$location = implode('/',$aeSELF).(e_QUERY ? "?".e_QUERY : "");

			header("Location: {$location}", true, 301); // send 301 header, not 302
			exit();
		}

		}
	}
}

/**
 * Set the User's Language
 */
$sql->db_Mark_Time('Start: Set User Language');

$lng->set(); // set e_LANGUAGE, USERLAN, Language Session / Cookies etc. requires $pref; 

header('Content-Language: '.e_LAN);

if(varset($pref['multilanguage']) && (e_LANGUAGE != $pref['sitelanguage']))
{
	$sql->mySQLlanguage  = e_LANGUAGE;
	$sql2->mySQLlanguage = e_LANGUAGE;
}

//TODO do it only once and with the proper function
include_lan(e_LANGUAGEDIR.e_LANGUAGE."/".e_LANGUAGE.".php");
include_lan(e_LANGUAGEDIR.e_LANGUAGE."/".e_LANGUAGE."_custom.php");

define('e_LOCALE',(strtolower(CORE_LC)."-".strtoupper(CORE_LC2)));
//
// N: misc setups: online user tracking, cache
//
$sql -> db_Mark_Time('Start: Misc resources. Online user tracking, cache');
$e_online = new e_online();

// cache class
$e107cache = new ecache;


if (isset($pref['del_unv']) && $pref['del_unv'] && $pref['user_reg_veri'] != 2) 
{
	$threshold = intval(time() - ($pref['del_unv'] * 60));
	$sql->db_Delete('user', "user_ban = 2 AND user_join < ".$threshold);
}

e107_require_once(e_HANDLER.'override_class.php');
$override=new override;

e107_require_once(e_HANDLER.'event_class.php');
$e_event=new e107_event;

if (isset($pref['notify']) && $pref['notify'] == true) 
{
	e107_require_once(e_HANDLER.'notify_class.php');
}

//
// O: Start user session
//
$sql -> db_Mark_Time('Start: Init session');
init_session();

// for multi-language these definitions needs to come after the language loaded.
define("SITENAME", trim($tp->toHTML($pref['sitename'], "", 'USER_TITLE,defs')));
define("SITEBUTTON", $pref['sitebutton']);
define("SITETAG", $tp->toHTML($pref['sitetag'], FALSE, "emotes_off, defs"));
define("SITEDESCRIPTION", $tp->toHTML($pref['sitedescription'], "", "emotes_off,defs"));
define("SITEADMIN", $pref['siteadmin']);
define("SITEADMINEMAIL", $pref['siteadminemail']);
define("SITEDISCLAIMER", $tp->toHTML($pref['sitedisclaimer'], "", "emotes_off,defs"));
define("SITECONTACTINFO", $tp->toHTML($pref['sitecontactinfo'], TRUE, "emotes_off,defs"));

// legacy module.php file loading.
if (isset($pref['modules']) && $pref['modules']) {
	$mods=explode(",", $pref['modules']);
	foreach ($mods as $mod) {
		if (is_readable(e_PLUGIN."{$mod}/module.php")) {
			require_once(e_PLUGIN."{$mod}/module.php");
		}
	}
}


$js_body_onload = array();			// Initialise this array in case a module wants to add to it


// Load e_modules after all the constants, but before the themes, so they can be put to use.

if(isset($pref['e_module_list']) && $pref['e_module_list']){
	foreach ($pref['e_module_list'] as $mod){
		if (is_readable(e_PLUGIN."{$mod}/e_module.php")) {
			require_once(e_PLUGIN."{$mod}/e_module.php");
 		}
	}
}

//
// P: THEME LOADING
//

$sql->db_Mark_Time('Start: Load Theme');

//###########  Module redefinable functions ###############
if (!function_exists('checkvalidtheme'))
{
	// arg1 = theme to check
	function checkvalidtheme($theme_check)
	{
	  global $ADMIN_DIRECTORY, $tp, $e107;

	  if (ADMIN && strpos(e_QUERY, "themepreview") !== FALSE)
	  {	// Theme preview
			list($action, $id) = explode('.', e_QUERY);
			require_once(e_HANDLER."theme_handler.php");
			$themeArray = themeHandler :: getThemes("id");
			define("PREVIEWTHEME", e_THEME.$themeArray[$id]."/");
			define("PREVIEWTHEMENAME", $themeArray[$id]);
			define("THEME", e_THEME.$themeArray[$id]."/");
			define("THEME_ABS", e_THEME_ABS.$themeArray[$id]."/");
			return;
	  }
	  if (@fopen(e_THEME.$theme_check."/theme.php", "r"))
	  {  // 'normal' theme load
		define("THEME", e_THEME.$theme_check."/");
		define("THEME_ABS", e_THEME_ABS.$theme_check."/");
		$e107->site_theme = $theme_check;
	  }
	  else
	  {
		function search_validtheme()
		{
		  global $e107;
		  $th=substr(e_THEME, 0, -1);
		  $handle=opendir($th);
		  while ($file = readdir($handle))
		  {
			if (is_dir(e_THEME.$file) && is_readable(e_THEME.$file.'/theme.php'))
			{
			  closedir($handle);
			  $e107->site_theme = $file;
			  return $file;
			}
		  }
		  closedir($handle);
		}

		$e107tmp_theme = search_validtheme();
		define("THEME", e_THEME.$e107tmp_theme."/");
		define("THEME_ABS", e_THEME_ABS.$e107tmp_theme."/");
		if (ADMIN && strpos(e_SELF, $ADMIN_DIRECTORY) === FALSE)
		{
		  echo '<script>alert("'.$tp->toJS(CORE_LAN1).'")</script>';
		}
	  }
	  $themes_dir = $e107->e107_dirs["THEMES_DIRECTORY"];
	  $e107->http_theme_dir = "{$e107->server_path}{$themes_dir}{$e107->site_theme}/";
	}
}

//
// Q: ALL OTHER SETUP CODE
//
$sql->db_Mark_Time('Start: Misc Setup');

//------------------------------------------------------------------------------------------------------------------------------------//
if (!class_exists('e107table')){
	class e107table
	{
		function tablerender($caption, $text, $mode = "default", $return = false) {
			/*
			# Render style table
			# - parameter #1:                string $caption, caption text
			# - parameter #2:                string $text, body text
			# - return                                null
			# - scope                                        public
			*/
			global $override;

			if ($override_tablerender = $override->override_check('tablerender')) {
				$result=call_user_func($override_tablerender, $caption, $text, $mode, $return);

				if ($result == "return") {
					return;
				}
				extract($result);
			}

			if ($return) {
				ob_start();
				tablestyle($caption, $text, $mode);
				$ret=ob_get_contents();
				ob_end_clean();
				return $ret;
			} else {
				tablestyle($caption, $text, $mode);
			}
		}
	}
}
//#############################################################

$ns=new e107table;

$e107->ban();

if(varset($pref['force_userupdate']) && USER)
{
  if(force_userupdate())
  {
	header("Location: ".e_BASE."usersettings.php?update");
	exit();
  }
}

$sql->db_Mark_Time('Start: Signup/splash/admin');

define("e_SIGNUP", e_BASE.(file_exists(e_BASE."customsignup.php") ? "customsignup.php" : "signup.php"));

if(!defined('e_LOGIN')) // customizable via e107_config.php
{
	define("e_LOGIN", e_BASE.(file_exists(e_BASE."customlogin.php") ? "customlogin.php" : "login.php"));	
}


if ($pref['membersonly_enabled'] && !USER && e_SELF != SITEURL.e_SIGNUP && e_SELF != SITEURL."index.php" && e_SELF != SITEURL."fpw.php" && e_SELF != SITEURL.e_LOGIN && strpos(e_PAGE, "admin") === FALSE && e_SELF != SITEURL.'membersonly.php' && e_SELF != SITEURL.'sitedown.php')
{
	header("Location: ".e_HTTP."membersonly.php");
	exit();
}

$sql->db_Delete("tmp", "tmp_time < ".(time() - 300)." AND tmp_ip!='data' AND tmp_ip!='submitted_link'");



if (varset($pref['maintainance_flag'])
 && strpos(e_SELF, 'admin.php') === FALSE && strpos(e_SELF, 'sitedown.php') === FALSE && strpos(e_SELF, '/secure_img_render.php') === FALSE)
{
	if(!ADMIN || ($pref['maintainance_flag'] == e_UC_MAINADMIN && !getperms('0')))
	{
		// 307 Temporary Redirect
		header('Location: '.SITEURL.'sitedown.php', TRUE, 307);
		exit();
	}
}

$sql->db_Mark_Time('(Start: Login/logout/ban/tz)');

if (isset($_POST['userlogin']) || isset($_POST['userlogin_x'])) {
	e107_require_once(e_HANDLER."login.php");
	$usr = new userlogin($_POST['username'], $_POST['userpass'], $_POST['autologin']);
}

if (e_QUERY == 'logout')
{
	$ip = $e107->getip();
	$udata=(USER === TRUE) ? USERID.".".USERNAME : "0";
	if (isset($pref['track_online']) && $pref['track_online'])
	{
		$sql->db_Update("online", "online_user_id = '0', online_pagecount=online_pagecount+1 WHERE online_user_id = '{$udata}' LIMIT 1");
	}

	//if ($pref['user_tracking'] == 'session')
	{
		$_SESSION[$pref['cookie_name']]='';
		session_destroy();
	}

	cookie($pref['cookie_name'], '', (time() - 2592000));
	$e_event->trigger('logout');
	header('location:'.e_BASE.'index.php');
	exit();
}


/*
* Calculate time zone offset, based on session cookie set in e107.js.
* (Buyer beware: this may be wrong for the first pageview in a session,
* which is while the user is logged out, so not a problem...)
*
* Time offset is SECONDS. Seconds is much better than hours as a base,
* as some places have 30 and 45 minute time zones.
* It matches user clock time, instead of only time zones.
* Add the offset to MySQL/server time to get user time.
* Subtract the offset from user time to get server time.
*
*/

$e_deltaTime=0;

if (isset($_COOKIE['e107_tdOffset'])) {
	// Actual seconds of delay. See e107.js and footer_default.php
	$e_deltaTime = (15*floor((($_COOKIE['e107_tdOffset'] + 450)/60)/15))*60; // Delay in seconds rounded to the nearest quarter hour
}

if (isset($_COOKIE['e107_tzOffset'])) {
	// Relative client-to-server time zone offset in seconds.
	$e_deltaTime += (-($_COOKIE['e107_tzOffset'] * 60 + date("Z")));
}

define("TIMEOFFSET", $e_deltaTime);

$sql->db_Mark_Time('Start: Get menus');

$menu_data = $e107cache->retrieve("menus_".USERCLASS_LIST."_".md5(e_LANGUAGE));
$menu_data = $eArrayStorage->ReadArray($menu_data);
$eMenuList=array();
$eMenuActive=array();
if(!is_array($menu_data)) {
	if ($sql->db_Select('menus', '*', "menu_location > 0 AND menu_class IN (".USERCLASS_LIST.") ORDER BY menu_order")) {
		while ($row = $sql->db_Fetch()) {
			$eMenuList[$row['menu_location']][]=$row;
			$eMenuActive[]=$row['menu_name'];
		}
	}
	$menu_data['menu_list'] = $eMenuList;
	$menu_data['menu_active'] = $eMenuActive;
	$menu_data = $eArrayStorage->WriteArray($menu_data, false);
	$e107cache->set("menus_".USERCLASS_LIST."_".md5(e_LANGUAGE), $menu_data);
	unset($menu_data);
} else {
	$eMenuList = $menu_data['menu_list'];
	$eMenuActive = $menu_data['menu_active'];
	unset($menu_data);
}

$sql->db_Mark_Time('(Start: Find/Load Theme)');

// Load admin Language File. 
if($inAdminDir == TRUE)
{
	include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_admin.php');	
}

if(!defined("THEME"))
{
	if ($inAdminDir && varsettrue($pref['admintheme'])&& (strpos(e_SELF.'?'.e_QUERY, 'menus.php?configure') === FALSE))
	{
/*	  if (strpos(e_SELF, "newspost.php") !== FALSE)
	  {
		define("MAINTHEME", e_THEME.$pref['sitetheme']."/");		MAINTHEME no longer used in core distribution
	  }  */
		checkvalidtheme($pref['admintheme']);
	}
	elseif (USERTHEME !== FALSE && USERTHEME != "USERTHEME" && !$inAdminDir)
	{
		checkvalidtheme(USERTHEME);
	}
	else
	{
		checkvalidtheme($pref['sitetheme']);
	}
}


// --------------------------------------------------------------


// here we USE the theme
if ($inAdminDir)
{
  if (file_exists(THEME.'admin_theme.php'))
  {
	require_once(THEME.'admin_theme.php');
  }
  else
  {
	require_once(THEME."theme.php");
  }
}
else
{
  require_once(THEME."theme.php");
}




$exclude_lan = array("lan_signup.php");  // required for multi-language.

//TODO remove autoload
if ($inAdminDir)
{
  include_lan(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_".e_PAGE);
}
elseif (!in_array("lan_".e_PAGE,$exclude_lan) && !$isPluginDir)
{
  include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
}




if(!defined("IMODE")) define("IMODE", "lite");

if ($pref['anon_post'] ? define("ANON", TRUE) : define("ANON", FALSE));

if (Empty($pref['newsposts']) ? define("ITEMVIEW", 15) : define("ITEMVIEW", $pref['newsposts']));

if ($pref['antiflood1'] == 1)
{
  define('FLOODPROTECT', TRUE);
  define('FLOODTIMEOUT', max(varset($pref['antiflood_timeout'],10),3));
}
else
{
  define('FLOODPROTECT', FALSE);
}

$layout = isset($layout) ? $layout : '_default';
define("HEADERF", e_THEME."templates/header{$layout}.php");
define("FOOTERF", e_THEME."templates/footer{$layout}.php");

if (!file_exists(HEADERF)) {
	message_handler("CRITICAL_ERROR", "Unable to find file: ".HEADERF, __LINE__ - 2, __FILE__);
}

if (!file_exists(FOOTERF)) {
	message_handler("CRITICAL_ERROR", "Unable to find file: ".FOOTERF, __LINE__ - 2, __FILE__);
}

define("LOGINMESSAGE", "");
define("OPEN_BASEDIR", (ini_get('open_basedir') ? TRUE : FALSE));
define("SAFE_MODE", (ini_get('safe_mode') ? TRUE : FALSE));
define("FILE_UPLOADS", (ini_get('file_uploads') ? TRUE : FALSE));
define("INIT", TRUE);
if(isset($_SERVER['HTTP_REFERER'])) {
	$tmp = explode("?", $_SERVER['HTTP_REFERER']);
	define("e_REFERER_SELF",($tmp[0] == e_SELF));
} else {
	define('e_REFERER_SELF', FALSE);
}

if (!class_exists('convert'))
{
	require_once(e_HANDLER."date_handler.php");
}





//@require_once(e_HANDLER."IPB_int.php");
//@require_once(e_HANDLER."debug_handler.php");
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

class e_online {
	function online($online_tracking = false, $flood_control = false) {
		if($online_tracking == true || $flood_control == true)
		{
			global $online_timeout, $online_warncount, $online_bancount;
			if(!isset($online_timeout)) {
				$online_timeout = 300;
			}
			if(!isset($online_warncount)) {
				$online_warncount = 90;
			}
			if(!isset($online_bancount)) {
				$online_bancount = 100;
			}
			global $sql, $pref, $e107, $listuserson, $e_event, $tp;
			$page = (strpos(e_SELF, "forum_") !== FALSE) ? e_SELF.".".e_QUERY : e_SELF;
			$page = (strpos(e_SELF, "comment") !== FALSE) ? e_SELF.".".e_QUERY : $page;
			$page = (strpos(e_SELF, "content") !== FALSE) ? e_SELF.".".e_QUERY : $page;
			$page = $tp -> toDB($page, true);

			$ip = $e107->getip();
			$udata = (USER === true ? USERID.".".USERNAME : "0");

			if (USER)
			{
				// Find record that matches IP or visitor, or matches user info
				if ($sql->db_Select("online", "*", "(`online_ip` = '{$ip}' AND `online_user_id` = '0') OR `online_user_id` = '{$udata}'")) {
					$row = $sql->db_Fetch();

					if ($row['online_user_id'] == $udata) {
						//Matching user record
						if ($row['online_timestamp'] < (time() - $online_timeout)) {
							//It has been at least 'timeout' seconds since this user has connected
							//Update user record with timestamp, current IP, current page and set pagecount to 1
							$query = "online_timestamp='".time()."', online_ip='{$ip}', online_location='{$page}', online_pagecount=1 WHERE online_user_id='{$row['online_user_id']}' LIMIT 1";
						} else {
							if (!ADMIN) {
								$row['online_pagecount'] ++;
							}
							// Update user record with current IP, current page and increment pagecount
							$query = "online_ip='{$ip}', `online_location` = '{$page}', `online_pagecount` = '".intval($row['online_pagecount'])."' WHERE `online_user_id` = '{$row['online_user_id']}' LIMIT 1";
						}
					} else {
						//Found matching visitor record (ip only) for this user
						if ($row['online_timestamp'] < (time() - $online_timeout)) {
							// It has been at least 'timeout' seconds since this user has connected
							// Update record with timestamp, current IP, current page and set pagecount to 1
							$query = "`online_timestamp` = '".time()."', `online_user_id` = '{$udata}', `online_location` = '{$page}', `online_pagecount` = 1 WHERE `online_ip` = '{$ip}' AND `online_user_id` = '0' LIMIT 1";
						} else {
							if (!ADMIN) {
								$row['online_pagecount'] ++;
							}
							//Update record with current IP, current page and increment pagecount
							$query = "`online_user_id` = '{$udata}', `online_location` = '{$page}', `online_pagecount` = ".intval($row['online_pagecount'])." WHERE `online_ip` = '{$ip}' AND `online_user_id` = '0' LIMIT 1";
						}
					}
					$sql->db_Update("online", $query);
				} else {
					$sql->db_Insert("online", " '".time()."', '0', '{$udata}', '{$ip}', '{$page}', 1, 0");
				}
			}
			else
			{
				//Current page request is from a visitor
				if ($sql->db_Select("online", "*", "`online_ip` = '{$ip}' AND `online_user_id` = '0'")) {
					$row = $sql->db_Fetch();

					if ($row['online_timestamp'] < (time() - $online_timeout)) //It has been at least 'timeout' seconds since this ip has connected
					{
						//Update record with timestamp, current page, and set pagecount to 1
						$query = "`online_timestamp` = '".time()."', `online_location` = '{$page}', `online_pagecount` = 1 WHERE `online_ip` = '{$ip}' AND `online_user_id` = '0' LIMIT 1";
					} else {
						//Update record with current page and increment pagecount
						$row['online_pagecount'] ++;
						//   echo "here {$online_pagecount}";
						$query="`online_location` = '{$page}', `online_pagecount` = {$row['online_pagecount']} WHERE `online_ip` = '{$ip}' AND `online_user_id` = '0' LIMIT 1";
					}
					$sql->db_Update("online", $query);
				} else {
					$sql->db_Insert("online", " '".time()."', '0', '0', '{$ip}', '{$page}', 1, 0");
				}
			}

		if (ADMIN || ($pref['autoban'] != 1 && $pref['autoban'] != 2) || (!isset($row['online_pagecount']))) // Auto-Ban is switched off. (0 or 3)
			{
				$row['online_pagecount'] = 1;
			}

			if ($row['online_pagecount'] > $online_bancount && ($row['online_ip'] != "127.0.0.1"))
			{
				include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_banlist.php');
				$sql->db_Insert('banlist', "'{$ip}', '0', '".str_replace('--HITS--',$row['online_pagecount'],BANLAN_78)."' ");
				$e_event->trigger("flood", $ip);
				exit();
			}
			if ($row['online_pagecount'] >= $online_warncount && $row['online_ip'] != "127.0.0.1") {
				echo "<div style='text-align:center; font: 11px verdana, tahoma, arial, helvetica, sans-serif;'><b>".LAN_WARNING."</b><br /><br />".CORE_LAN6."<br /></div>";
				exit();
			}

			$sql->db_Delete("online", "`online_timestamp` < ".(time() - $online_timeout));

			global $members_online, $total_online, $member_list, $listuserson;
			$total_online = $sql->db_Count("online");
			if ($members_online = $sql->db_Select("online", "*", "online_user_id != '0' ")) {
				$member_list = '';
				$listuserson = array();
				while ($row = $sql->db_Fetch()) {
					$vals = explode(".", $row['online_user_id'], 2);
					$member_list .= "<a href='".e_BASE."user.php?id.{$vals[0]}'>{$vals[1]}</a> ";
					$listuserson[$row['online_user_id']] = $row['online_location'];
				}
			}
			define("TOTAL_ONLINE", $total_online);
			define("MEMBERS_ONLINE", $members_online);
			define("GUESTS_ONLINE", $total_online - $members_online);
			define("ON_PAGE", $sql->db_Count("online", "(*)", "WHERE `online_location` = '{$page}' "));
			define("MEMBER_LIST", $member_list);
		}
		else
		{
			define("e_TRACKING_DISABLED", true);
			define("TOTAL_ONLINE", "");
			define("MEMBERS_ONLINE", "");
			define("GUESTS_ONLINE", "");
			define("ON_PAGE", "");
			define("MEMBER_LIST", ""); //
		}
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class floodprotect {
	function flood($table, $orderfield) {
		/*
		# Test for possible flood
		#
		# - parameter #1                string $table, table being affected
		# - parameter #2                string $orderfield, date entry in respective table
		# - return                                boolean
		# - scope                                        public
		*/
		$sql=new db;

		if (FLOODPROTECT == TRUE) {
			$sql->db_Select($table, "*", "ORDER BY ".$orderfield." DESC LIMIT 1", "no_where");
			$row=$sql->db_Fetch();
			return ($row[$orderfield] > (time() - FLOODTIMEOUT) ? FALSE : TRUE);
		} else {
			return TRUE;
		}
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//


$sql->db_Mark_Time('Start: Go online');
if(isset($pref['track_online']) && $pref['track_online']) {
	$e_online->online($pref['track_online'], $pref['flood_protect']);
}

//
// Use these to combine isset() and use of the set value. or defined and use of a constant
// i.e. to fix  if($pref['foo']) ==> if ( varset($pref['foo']) ) will use the pref, or ''.
// Can set 2nd param to any other default value you like (e.g. false, 0, or whatever)
// $testvalue adds additional test of the value (not just isset())
// Examples:
// $something = pref;  // Bug if pref not set         ==> $something = varset(pref);
// $something = isset(pref) ? pref : "";              ==> $something = varset(pref);
// $something = isset(pref) ? pref : default;         ==> $something = varset(pref,default);
// $something = isset(pref) && pref ? pref : default; ==> use varsettrue(pref,default)
//



// ---------------------------------------------------------------------------

function e107_filter($input,$key,$type,$base64=FALSE)
{
	if(is_string($input) && trim($input)=="")
	{
		return;
	}
		
	if(is_array($input))
	{
		return array_walk($input, 'e107_filter', $type);	
	} 
			
	if($type == "_POST" || ($type == "_SERVER" && ($key == "QUERY_STRING")))
	{
		if($type == "_POST" && ($base64 == FALSE))
		{
			$input = preg_replace("/(\[code\])(.*?)(\[\/code\])/is","",$input);
		}
	
		$regex = "/(document\.location|document\.write|base64_decode|chr|php_uname|fwrite|fopen|fputs|passthru|popen|proc_open|shell_exec|exec|proc_nice|proc_terminate|proc_get_status|proc_close|pfsockopen|apache_child_terminate|posix_kill|posix_mkfifo|posix_setpgid|posix_setsid|posix_setuid|phpinfo) *?\((.*) ?\;?/i";
		if(preg_match($regex,$input))
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			exit();
		}
		
		if(preg_match("/system *?\((.*);.*\)/i",$input))
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			exit();	
		}
		
		$regex = "/(wget |curl -o |fetch |lwp-download|onmouse)/i";
		if(preg_match($regex,$input))
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			exit();
		}
	
	}
	
	if($type == "_SERVER")
	{
		if(($key == "QUERY_STRING") && (
			strpos(strtolower($input),"../../")!==FALSE 
			|| strpos(strtolower($input),"=http")!==FALSE 
			|| strpos(strtolower($input),strtolower("http%3A%2F%2F"))!==FALSE
			|| strpos(strtolower($input),"php:")!==FALSE  
			|| strpos(strtolower($input),"data:")!==FALSE
			|| strpos(strtolower($input),strtolower("%3Cscript"))!==FALSE
			))
		{

			header('HTTP/1.0 400 Bad Request', true, 400);
			exit();
		}
					
		if(($key == "HTTP_USER_AGENT") && strpos($input,"libwww-perl")!==FALSE)
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			exit();	
		}
		
						
	}
		
	if(strpos(str_replace('.', '', $input), '22250738585072011') !== FALSE) // php-bug 53632
	{
		header('HTTP/1.0 400 Bad Request', true, 400);
		exit();
	} 
	
	if($base64 != TRUE)
	{
		e107_filter(base64_decode($input),$key,$type,TRUE);
	}
	
}






if(!function_exists("print_a"))
{
  function print_a($var, $return = false)
  {
	$charset = "utf-8";
	if(defined("CHARSET"))
	{
	  $charset = CHARSET;
	}
	if(!$return)
	{
	  echo '<pre>'.htmlspecialchars(print_r($var, true), ENT_QUOTES, $charset).'</pre>';
	  return true;
	}
	else
	{
	  return '<pre>'.htmlspecialchars(print_r($var, true), ENT_QUOTES, $charset).'</pre>';
	}
  }
}



class error_handler {

	var $errors;
	var $debug = false;

	function error_handler() {
		//
		// This is initialized before the current debug level is known
		//
		if ((isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'debug=') !== FALSE) || isset($_COOKIE['e107_debug_level'])) {
			$this->debug = true;
			error_reporting(E_ALL | E_STRICT);
		} else {
			error_reporting(E_ERROR | E_PARSE | E_STRICT);
		}
	}

	function handle_error($type, $message, $file, $line, $context) {
		$startup_error = (!defined('E107_DEBUG_LEVEL')); // Error before debug system initialized
		switch($type) {
			case E_NOTICE:
			if ($startup_error || E107_DBG_ALLERRORS) {
				$error['short'] = "Notice: {$message}, Line {$line} of {$file}<br />\n";
				$trace = debug_backtrace();
				$backtrace[0] = (isset($trace[1]) ? $trace[1] : "");
				$backtrace[1] = (isset($trace[2]) ? $trace[2] : "");
				$error['trace'] = $backtrace;
				$this->errors[] = $error;
			}
			break;
			case E_WARNING:
			if ($startup_error || E107_DBG_BASIC) {
				$error['short'] = "Warning: {$message}, Line {$line} of {$file}<br />\n";
				$trace = debug_backtrace();
				$backtrace[0] = (isset($trace[1]) ? $trace[1] : "");
				$backtrace[1] = (isset($trace[2]) ? $trace[2] : "");
				$error['trace'] = $backtrace;
				$this->errors[] = $error;
			}
			break;
			case E_USER_ERROR:
			if ($this->debug == true) {
				$error['short'] = "&nbsp;&nbsp;&nbsp;&nbsp;Internal Error Message: {$message}, Line {$line} of {$file}<br />\n";
				$trace = debug_backtrace();
				$backtrace[0] = (isset($trace[1]) ? $trace[1] : "");
				$backtrace[1] = (isset($trace[2]) ? $trace[2] : "");
				$error['trace'] = $backtrace;
				$this->errors[] = $error;
			}
			default:
			return true;
			break;
		}
	}

	function return_errors() {
		$index = 0; $colours[0] = "#C1C1C1"; $colours[1] = "#B6B6B6";
		$ret = "" ;
		if (E107_DBG_ERRBACKTRACE)
		{
			foreach ($this->errors as $key => $value) {
				$ret .= "\t<tr>\n\t\t<td class='forumheader3' >{$value['short']}</td><td><input class='button' type ='button' style='cursor: hand; cursor: pointer;' size='30' value='Back Trace' onclick=\"expandit('bt_{$key}')\" /></td>\n\t</tr>\n";
				$ret .= "\t<tr>\n<td style='display: none;' colspan='2' id='bt_{$key}'>".print_a($value['trace'], true)."</td></tr>\n";
				if($index == 0) { $index = 1; } else { $index = 0; }
			}
		} else {
			foreach ($this->errors as $key => $value)
			{
				$ret .= "<tr class='forumheader3'><td>{$value['short']}</td></tr>\n";
			}
		}

		return ($ret) ? "<table class='fborder'>\n".$ret."</table>" : "";
	}

	function trigger_error($information, $level) {
		trigger_error($information);
	}
}


$sql->db_Mark_Time('(After class2)');




?>