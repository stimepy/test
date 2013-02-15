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
$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

$gen = new convert;

$text = "";

if(defined("PHPBB_PREFIX")) {

	$lastsql="SELECT * FROM `".PHPBB_PREFIX."posts` ORDER BY `post_time` DESC LIMIT 0, 10";
	$lastsql2=$sql->db_Select_gen($lastsql);//mysql_query($lastsql) or die("could not select posts");
	while($lastsql3=$sql->db_Fetch()){//mysql_fetch_array($lastsql2)) {

        $usersql="SELECT username FROM `".PHPBB_PREFIX."users` WHERE user_id='".$lastsql3['poster_id']."' LIMIT 0, 1";
        if(!$usersql2=$sql->db_Select_gen($usersql)){//mysql_query($usersql) or
            die("could not select users");
        }
         $usersql3=$sql->db_Fetch();//mysql_fetch_array($usersql2);

        //From newforumposts_menu.php
        $datestamp = $gen->convert_date($lastsql3['post_time'], "short");

        $text .= "<img src='".THEME."images/".(defined("BULLET") ? BULLET : "bullet2.gif")."' alt='' /> <a href='".PHPBB_PATH."viewtopic.php?p=".$lastsql3['post_id']."'>Post: ".$lastsql3['post_subject']."</a><br /> Posted by ".$usersql3['username']."<br />".$datestamp."<br />";

	}


	/* And the menu creation part */
	$caption = LAN_E107BB_NPOSTS;
	$ns -> tablerender($caption, $text);

}
?>