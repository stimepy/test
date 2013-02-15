<?php

require_once("../../class2.php");
include_once("languages/".e_LANGUAGE.".php");

global $gen,$pref;
$gen = new convert;

define(e_PAGETITLE,LAN_REPORT);
require_once(HEADERF);
$text = "

	<fieldset style='width:80%;' class='blockquote'>

		<table cellspacing='5px' border='0' cellpadding='0' style='width:100%;'>

			<tr>

				<td>

					".LAN_PERSONAL_REPORTS."

				</td>
				<td>

					<a href='myreports.php'>".LAN_CLICK_HERE."</a>

				</td>

			</tr>

";

$sql->db_select_gen("SELECT userclass_name FROM `".MPREFIX."userclass_classes` WHERE `".MPREFIX."userclass_classes`.`userclass_id` = '".intval($pref['reports']['mod_class'])."'");
$c = $sql->db_fetch();


if(check_class($c[0])) {

	$text .= "


			<tr>

				<td>

					".LAN_OPEN_REPORTS."

				</td>
				<td>

					<a href='moderate.php'>".LAN_CLICK_HERE."</a>

				</td>

			</tr>			
			<tr>

				<td>

					".LAN_CLOSED_REPORTS."

				</td>
				<td>

					<a href='moderate.php?1'>".LAN_CLICK_HERE."</a>

				</td>

			</tr>

	";



}

$text .= "

		</table>

	</fieldset>

";

$ns->tablerender(LAN_REPORT,$text);
require_once(FOOTERF);

?>
			
