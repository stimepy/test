<?php
// e_meta.php

if (!defined('e107_INIT')) { exit; }

/* Show user posts on user page */ 
if (e_PAGE == "user.php") {
	$qs = explode(".", e_QUERY);
	$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
	require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

	/* Get user_posts info */
	/* This isn't necessary, but for some reason, it is better to use this system */
	$getconfig="SELECT user_loginname from ".MPREFIX."user where user_id='".$qs[1]."' LIMIT 1";
	$getconfig2=$sql->db_Select($getconfig);//@mysql_query($getconfig) or die(mysql_error());
	$getconfig3=$sql->db_Fetch(); //@mysql_fetch_array($getconfig2);
	$userlogin = $getconfig3['user_loginname'];
	$getpm="SELECT user_posts from ".PHPBB_PREFIX."users where username='".$userlogin."' LIMIT 1";
	$getpm2=$sql->db_Select_gen($getpm);//mysql_query($getpm) or die(mysql_error());
	$getpm3=$sql->db_Fetch();//mysql_fetch_array($getpm2);
	$phpBBposts = $getpm3['user_posts'];

	/* from gold system 2.2 */
	$profile_old = "{USER_FORUM_LINK}";
	$profile_new = "<tr>
	<td style='width:30%' class='forumheader3'>".LAN_E107BB_POSTN."</td>
	<td style='width:70%' class='forumheader3'>$phpBBposts</td>
	</tr>";
	$USER_FULL_TEMPLATE = str_replace($profile_old, $profile_old.$profile_new, $USER_FULL_TEMPLATE);
}
?>