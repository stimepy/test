<?php
/* Let's do what administrators want to know */

$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

/* select and get number of posts */
$result = $sql->db_Select_gen("SELECT * FROM ".PHPBB_PREFIX."posts");//mysql_query("SELECT * FROM ".PHPBB_PREFIX."posts");
$num_rows = $sql->db_Fetch();//mysql_num_rows($result);


$text .= "
<div style='padding-bottom: 2px;'><img src=\"".e_PLUGIN."e107bb/images/icon_16.png\" alt=\"\" style=\"border: 0px none ; vertical-align: bottom; width: 16px; height: 16px;\" \> ".LAN_E107BB_POSTN.": ".$num_rows."</div>";
?>