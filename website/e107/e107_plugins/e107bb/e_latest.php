<?php

if (!defined('e107_INIT')) { exit; }

/* Yay! I know that you all want to know about reported posts! */

$lan_file = e_PLUGIN."e107bb/languages/".e_LANGUAGE.".php";
require_once(file_exists($lan_file) ? $lan_file :  e_PLUGIN."e107bb/languages/English.php");

/* select and get number of reported posts */
$result = $sql->db_Select_gen("SELECT * FROM ".PHPBB_PREFIX."reports WHERE 'report_closed'=0");//mysql_query("SELECT * FROM ".PHPBB_PREFIX."reports WHERE 'report_closed'=0");
$num_rows2 = $sql->db_Rows()//mysql_num_rows($result);
$sql->freeQuery();


$text .= "
<div style='padding-bottom: 2px;'><img src=\"".e_PLUGIN."e107bb/images/icon_16.png\" alt=\"\" style=\"border: 0px none ; vertical-align: bottom; width: 16px; height: 16px;\" \> ".LAN_E107BB_REPON.": ".$num_rows2."</div>";
?>