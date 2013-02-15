<?php
/**
* e107 auth plug-in for phpBB3
* e107bb 3.0 (http://www.diporg.com)
*
* Based on auth_apache.php file:
* Authentication plug-ins is largely down to Sergey Kanareykin, our thanks to him.
*
* @package login
* @version $Id: auth_apache.php,v 1.18 2007/10/05 12:42:06 acydburn Exp $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Checks whether the user is identified to e107
* Only allow changing authentication to e107 if the user is identified
* Called in acp_board while setting authentication plugins
*
* @return boolean|string false if the user is identified and else an error message
*/ 
function init_e107()
{

	if (!defined('e107_INIT'))
	{
		return "e107 was not loaded. You must use the e107bb plugin to use this page.";
	}
	return false;
}

/**
* Login function
*/
function login_e107($username, $password)
{
	global $db, $sql;

	if (!defined('e107_INIT'))
	{
		return array(
			'status'		=> LOGIN_ERROR,
			'error_msg'		=> "e107 was not loaded.",
			'user_row'		=> array('user_id' => ANONYMOUS),
		);
	}

	//Login user at e107
	if(!USER)
	{
		e107_require_once(e_HANDLER."login.php");
		//Currently there is only a hack for autologin, and we don't want that
		$usr = new userlogin($username, $password, 0);
		if(defined("LOGINMESSAGE")) {
			return array(
				'status'	=> LOGIN_ERROR_USERNAME,
				'error_msg'	=> 'LOGIN_ERROR_USERNAME',
				'user_row'	=> array('user_id' => ANONYMOUS),
			);
		}
	}

	//e107bb 3.0.2: Fetch user details using e107 sql class
	$sql->db_Select('user', 'user_id, user_loginname, user_password', "user_id = '".USERID."'");
	$row = $sql->db_Fetch();
	if ($row)
	{
		$e107_auth_user = $row['user_loginname'];
		$e107_auth_pw = $row['user_password'];
	}
	unset($row);

	//If we have an user and password
	if (!empty($e107_auth_user) && !empty($e107_auth_pw))
	{
		//If this is true, something is really wrong
		if ($e107_auth_user !== $username)
		{
			return array(
				'status'	=> LOGIN_ERROR_USERNAME,
				'error_msg'	=> 'LOGIN_ERROR_USERNAME',
				'user_row'	=> array('user_id' => ANONYMOUS),
			);
		}

		//Fetch phpBB details of the user
		$bbsql = 'SELECT user_id, username, user_password, user_passchg, user_email, user_type
			FROM ' . USERS_TABLE . "
			WHERE ".get_field_e107()." = '" . $db->sql_escape($e107_auth_user) . "'";
		$result = $db->sql_query($bbsql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		//If true, then the user exists
		if ($row)
		{
			// User inactive...
			if ($row['user_type'] == USER_INACTIVE || $row['user_type'] == USER_IGNORE)
			{
				return array(
					'status'		=> LOGIN_ERROR_ACTIVE,
					'error_msg'		=> 'ACTIVE_ERROR',
					'user_row'		=> $row,
				);
			}
	
			// Successful login...
			return array(
				'status'		=> LOGIN_SUCCESS,
				'error_msg'		=> false,
				'user_row'		=> $row,
			);
		}

		// this is the user's first login so create an empty profile
		return array(
			'status'		=> LOGIN_SUCCESS_CREATE_PROFILE,
			'error_msg'		=> false,
			'user_row'		=> user_row_e107($e107_auth_user, $e107_auth_pw),
		);
	}

	// Not logged into e107
	return array(
		'status'		=> LOGIN_ERROR_EXTERNAL_AUTH,
		'error_msg'		=> "GENERAL_ERROR",
		'user_row'		=> array('user_id' => ANONYMOUS),
	);
}

/**
* Autologin function
*
* @return array containing the user row or empty if no auto login should take place
*/
function autologin_e107()
{
	global $db, $sql;

	if(!defined("MISC_E107")) { misc_e107(); }

	if (!USER)
	{
		return array();
	}

	//e107bb 3.0.2: Fetch user details using e107 sql class
	$sql->db_Select('user', '*', "user_id = '".USERID."'");
	$row = $sql->db_Fetch();
	if ($row)
	{
		$e107_auth_user = $row['user_loginname'];
		$dispname = $row['user_name'];
		$e107_auth_pw = $row['user_password'];
		$e107_email = $row['user_email'];
	}
	unset($row);

	if (!empty($e107_auth_user) && !empty($e107_auth_pw))
	{
		set_var($e107_auth_user, $e107_auth_user, 'string', true);
		set_var($e107_auth_pw, $e107_auth_pw, 'string');

		$bbsql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE ".get_field_e107()." = '" . $db->sql_escape($e107_auth_user) . "'";
		$result = $db->sql_query($bbsql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row)
		{

			if(get_field_e107() === "loginname") {
				$bbsql = 'UPDATE ' . USERS_TABLE . " SET `username` = '".$db->sql_escape($dispname)."',
				`username_clean` = '".$db->sql_escape(utf8_clean_string($dispname))."' WHERE `user_id` = ".$row['user_id']." LIMIT 1 ";
				$db->sql_query($bbsql);
			}

			return ($row['user_type'] == USER_INACTIVE || $row['user_type'] == USER_IGNORE) ? array() : $row;
		}

		if (!function_exists('user_add'))
		{
			global $phpbb_root_path, $phpEx;
			
			include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
		}

		// create the user if he does not exist yet
		$phpbb_user_id=user_add(user_row_e107($e107_auth_user, $e107_auth_pw, $e107_email, $dispname));
		//update the e107 takbe
		$phpbb_e107phpbb_id="Update e107_user set phpb_id=".$phpbb_user_id;
		$db->sql_query($phpbb_e107phpbb_id);
		$db->sql_freeresult($phpbb_e107phpbb_id);
		
		$bbsql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE ".get_field_e107()."_clean = '" . $db->sql_escape(utf8_clean_string($e107_auth_user)) . "'";
		$result = $db->sql_query($bbsql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row)
		{
			return $row;
		}
	}

	return array();
}

/**
* The session validation function checks whether the user is still logged in
*
* @return boolean true if the given user is authenticated or false if the session should be closed
*/
function validate_session_e107(&$user)
{
global $db, $sql;

	if(!defined("MISC_E107")) { misc_e107(); }
	
	//If user is off on both systems
	if (!USER && $user['username'] === "Anonymous")
	{
		return true;
	//If user is off only at e107
	} else if (!USER)
	{
		return false;
	}

	$e107_auth_user = '';

	//e107bb 3.0.2: For e107 stuff, use e107 mysql classes
	$sql->db_Select('user', '*', "user_id = '".USERID."'");
	$row = $sql->db_Fetch();
	if ($row)
	{
		$e107_auth_user = $row['user_loginname'];
		$dispname = $row['user_name'];
	}

	if(get_field_e107() === "loginname") {

		if(($dispname !== $user['username']) && ($e107_auth_user === $user['loginname'])) {	-
			$bbsql = 'UPDATE ' . USERS_TABLE . " SET `username` = '".$db->sql_escape($dispname)."',
			`username_clean` = '".$db->sql_escape(utf8_clean_string($dispname))."' WHERE `user_id` = ".$user['user_id']." LIMIT 1 ";
			$db->sql_query($bbsql);
		}
		return ($e107_auth_user === $user['loginname']) ? true : false;

	} else {
		return ($e107_auth_user === $user['username']) ? true : false;
	}


}

function logout_e107()
{
$redir = SITEURL."index.php?logout";
redirect($redir);
}

/**
* This function is called to do misc things on e107.
*/
function misc_e107() {
	global $phpbb_hook;

	//e107bb 3.1: Do not allow remind/register modes:
	if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == "register") {
		$redir = SITEURL."signup.php";
		redirect($redir);
	} elseif (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "sendpassword") {
		$redir = SITEURL."fpw.php";
		redirect($redir);
	}
	//Hook Template
	$phpbb_hook->register(array('template','display'), 'bb_e107_header');
	$phpbb_hook->register('append_sid', 'fix_amp_e107');

	//This function should only be called once
	define("MISC_E107",true);
}

function get_field_e107() {
	global $db;
    static $col=false;
    if(!$col){
        $bbsql = 'SHOW COLUMNS FROM ' . USERS_TABLE . " LIKE 'username'";
        $result = $db->sql_query($bbsql);
        if($db->sql_rowcount($result) == 1){
            return $col="username";
        }
        else {
            return $col="loginname";
        }
    }
    else{
        return $col;
    }


}

function fix_amp_e107(&$hooks, $url, $params = false, $is_amp = true, $session_id = false) {
	global $phpbb_hook;
	//This must be the most ugly hack ever made
	//But hey, it works...
	$hook_temp = $phpbb_hook;
	$phpbb_hook = "";
	$result = append_sid($url, $params, $is_amp, $session_id);
	$phpbb_hook = $hook_temp;
	unset($hook_temp);

	$result = str_replace("?amp;", "?",$result);
	$result = str_replace("&amp;", "&",$result);
	$result = str_replace("amp;", "&",$result);
	return $result;
}

?>