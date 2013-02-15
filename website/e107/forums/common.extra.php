/* e107bb Integration - Start */
/* From version: 3.1 */
global $user, $auth, $template, $db, $phpbb_root_path, $phpEx, $cache;

if(!defined("e107_INIT"))
{
	$e107path = '../';
	while(!file_exists($e107path.'class2.php')) {
		$e107path .= '../';
	}
	
	if(!defined('PHPBB_ROOT_PATH')){
		define("PHPBB_ROOT_PATH", $phpbb_root_path);
	}
	require($e107path . '/class2.' . $phpEx);

	$phpbb_root_path = PHPBB_ROOT_PATH;
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
}
elseif(defined('PHPBB_ROOT_PATH')){
	$phpbb_root_path=PHPBB_ROOT_PATH;
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
}

/* End of e107bb 3.1 Trash */
/* e107bb Integration - End */