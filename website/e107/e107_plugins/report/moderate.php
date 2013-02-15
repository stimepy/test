<?php

require_once("../../class2.php");
include_once("languages/".e_LANGUAGE.".php");

global $gen,$pref;
$gen = new convert;

$sql->db_select_gen("SELECT userclass_name FROM `".MPREFIX."userclass_classes` WHERE `".MPREFIX."userclass_classes`.`userclass_id` = '".intval($pref['reports']['mod_class'])."'");
$c = $sql->db_fetch();

if(!check_class($c[0])) {

	header("location: ".e_BASE."index.php") and exit;

}

define(e_PAGETITLE,LAN_REPORT);
require_once(HEADERF);
$text = null;
$qry = explode(".",e_QUERY);

if($_SERVER['REQUEST_METHOD'] != "POST") {

	$stati = array("0" => LAN_REPORT_OPEN,"1" => LAN_REPORT_UNDER_INVESTIGATION, "2" => LAN_REPORT_CLOSED);
	
	switch($qry[0]) {
	
		default:

			$sql->db_select_gen("SELECT r . * , u.user_name, f.forum_name, t.thread_user FROM `".MPREFIX."reports` AS `r` LEFT JOIN `".MPREFIX."user` AS `u` ON `u`.`user_id` = `r`.`userid` LEFT JOIN `".MPREFIX."forum` AS `f` ON `f`.`forum_id` = `r`.`forum` LEFT JOIN `".MPREFIX."forum_t` AS `t` ON `t`.`thread_id` = `r`.`post` WHERE `r`.`status` != '2' AND `r`.`report_parent` = '0' ORDER BY `r`.`timestamp` DESC");

		break;

		case "1":

		
			$sql->db_select_gen("SELECT r . * , u.user_name, f.forum_name, t.thread_user FROM `".MPREFIX."reports` AS `r` LEFT JOIN `".MPREFIX."user` AS `u` ON `u`.`user_id` = `r`.`userid` LEFT JOIN `".MPREFIX."forum` AS `f` ON `f`.`forum_id` = `r`.`forum` LEFT JOIN `".MPREFIX."forum_t` AS `t` ON `t`.`thread_id` = `r`.`post` WHERE `r`.`status` = '2' AND `r`.`report_parent` = '0' ORDER BY `r`.`timestamp` DESC");
		break;

	}
	
	while($row = $sql->db_fetch()) {

		$date = $gen->convert_date($row['timestamp'],"short");
		$userinfo = explode(".",$row['thread_user']);

		$text .= "

			<table cellspacing='0' border='0' cellpadding='5' style='width:700px;'>

				<tr>

					<td valign='top' colspan='2' class='forumheader'>

						".e107UserUrl($row['userid'],$row['user_name']) .LAN_REPORT_REPORTED.e107UserUrl($userinfo[0], $userinfo[1]) ."s <a href='".e_PLUGIN."forum/forum_viewtopic.php?".$row['post'].".post'>".LAN_REPORT_POST."</a> ".LAN_REPORT_ON." ".$date." - ".LAN_REPORT_IN_FORUM." <a href='".e_PLUGIN."forum/forum_viewforum.php?".$row['forum']."'>".$row['forum_name']."</a>

					</td>

				</tr>
				<tr>

					<td valign='top' class='forumheader2'>

						<a href='javascript:' onClick=\"expandit('".$row['id']."');\">".LAN_REPORT_OPEN_CONTENT."</a><br><br>

						<div style='display:none;' id='".$row['id']."'>

							".$tp->toHTML($row['reported_content'])."

						</div>

					</td>
					<td valign='top' class='forumheader2'>

						".LAN_REPORT_MESSAGE." ".$row['message']."

					</td>

				</tr>
				<tr>

					<td colspan='2' class='forumheader2'>

		";

		$mydb = new db;
		$mydb->db_select_gen("SELECT r.*,u.user_name FROM `".MPREFIX."reports` AS `r` LEFT JOIN `".MPREFIX."user` AS `u` ON `r`.`userid` = `u`.`user_id` WHERE `r`.`report_parent` = '".$row['id']."' ORDER BY `r`.`timestamp` ASC");

		while($tmp = $mydb->db_fetch()) {

			$datestamp = $gen->convert_date($tmp['timestamp'],"short");
			$status = $stati[$tmp['status']];

			$text .= "
				
				<table cellspacing='5' border='0' cellpadding='0' style='width:100%;'>

					<tr>

						<td class='forumheader2' valign='top' style='width:30%;'>

							".$tmp['user_name']." @ ".$datestamp.":<br>
							&quot;<span style='font-size:10;'>".$status."</span>&quot;

						</td>
						<td class='forumheader2' valign='top' style='width:70%;'>

							".$tp->toHTML($tmp['message'])."

						</td>

					</tr>

				</table>

			";

		}

		$text .= "
					</td>
			
				<tr>

					<form method='post' action='".e_SELF."?".$row['id']."'>
					<td valign='top' class='forumheader'>
			
						<select name='status' class='tbox'>


		";

		foreach($stati AS $key => $value) {
			
			$selected = ($key == $row['status'] ? "selected" : "");
			
			$text .= "

				<option {$selected} value='".$key."'>".$value."</option>

			";

		}

		$text .= "

						</select>

					</td>
					<td valign='top' class='forumheader'

							<textarea name='modcomment' class='tbox' rows='2' cols='50'></textarea><br>

					</td>

				</tr>
				<tr>
				
			 
					<td colspan='2' class='forumheader' style='text-align:center;'>

						<input type='submit' value='".LAN_SUBMIT."' class='button'>

					</td>

				</tr>
				</form>


			</table>

			<br><br>

		";

	}

} else {

	if(!isset($qry[0])) {

		header("location: moderate.php") and exit;

	} else {

		$time = time();
		$_POST = $tp->toDB($_POST);
		$message = htmlentities($_POST['modcomment']);
		$parent = intval($qry[0]);
		$userid = USERID;
		$status = intval($_POST['status']);

		$sql->db_select_gen("INSERT INTO `".MPREFIX."reports` (message,timestamp,report_parent,userid,status) VALUES ('$message','$time','$parent','$userid','$status');");
		$sql->db_select_gen("UPDATE `".MPREFIX."reports` SET `".MPREFIX."reports`.`status` = '".intval($_POST['status'])."' WHERE `".MPREFIX."reports`.`id` = '".$parent."' LIMIT 1");

		header("location: moderate.php?".e_QUERY."") and exit;

	}

}

$ns->tablerender(LAN_CAPTION_MODCOMMENT,$text);
require_once(FOOTERF);

?>

		