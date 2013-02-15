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

if(defined("PHPBB_PREFIX") && USER) {
	$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
	require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

	/* This isn't necessary, but for some reason, it is better to use this system */
	$getconfig="SELECT user_loginname from ".MPREFIX."user where user_id='".USERID."' LIMIT 1";
	$getconfig2=$sql->db_Select_gen($getconfig);//mysql_query($getconfig) or die(mysql_error());
	$getconfig3=$sql->db_Fetch();//mysql_fetch_array($getconfig2);
	$userlogin = $getconfig3['user_loginname'];

	//e107bb 3.1
	if($sql->db_Select_gen("SHOW COLUMNS FROM ".PHPBB_PREFIX."users LIKE 'loginname'")===0) {
	$modtext = "username";
	} else {
	$modtext = "loginname";
	}

	/* Get privmsg user info */
	$getpm="SELECT user_new_privmsg, user_unread_privmsg from ".PHPBB_PREFIX."users where ".$modtext."='".$userlogin."' LIMIT 1";
	$getpm2=$sql->db_Select_gen($getpm);//mysql_query($getpm) or die(mysql_error());
	$getpm3=$sql->db_Fetch();//mysql_fetch_array($getpm2);
 //if(!isset($getpm3['user_new_privmsg'])) { $bigmessage = "<h1><img src='".e_IMAGE."admin_images/arrow_over_16.png' />ERROR!</h1><br/>"; }
	$phpBBpmnew = $getpm3['user_new_privmsg'];
	$phpBBpmunread = $getpm3['user_unread_privmsg'];

	/* Set PM info Message */
	//if($phpBBpmnew == $phpBBpmunread) {
	//	$phpBBpmtext = sprintf(LAN_E107BB_PMUN, $phpBBpmunread);
	//} else {
		$phpBBpmtext = sprintf(LAN_E107BB_PMUNEW, $phpBBpmnew, $phpBBpmunread);

	//}

	/* Are you admin?*/
	if(ADMIN) {
		$adminlink = $bullet." <a href='".PHPBB_PATH."adm/index.php'>".LAN_E107BB_ADMIN."</a><br/>";
	} else {
		$adminlink = "";
	}

	/* Is this working correctly? Show a huge message if unread messages */
	if($phpBBpmunread >= "1") {
		$bigmessage = "<h1><img src='".e_IMAGE."admin_images/arrow_over_16.png' />".LAN_E107BB_NEW_PM."</h1><br/>";
	}

	/* And the menu creation part */
	$caption = LAN_E107BB_TITLE;
	$text = $bigmessage.$adminlink.$bullet." <a href='".PHPBB_PATH."ucp.php?i=164'>".LAN_E107BB_EPROF."</a><br/>".$bullet." <a href='".PHPBB_PATH."ucp.php?i=pm&amp;folder=inbox'>".$phpBBpmtext."</a>";

	$ns -> tablerender($caption, $text);

	}
?>