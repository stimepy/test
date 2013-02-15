<?php

require_once("../../class2.php");
include_once("languages/".e_LANGUAGE.".php");

global $gen,$pref;
$gen = new convert;

if(!USER) {

	header("location: ".e_BASE."index.php") and exit;

}

define(e_PAGETITLE,LAN_MY_REPORTS);
require_once(HEADERF);
$text = null;
$qry = explode(".",e_QUERY);
$page = (isset($qry[0]) ? intval($qry[0]) : intval("1"));

$sql->db_select_gen("SELECT COUNT(id) FROM `".MPREFIX."reports` WHERE `".MPREFIX."reports`.`userid` = '".USERID."' AND `".MPREFIX."reports`.`report_parent` = '0' ORDER BY `".MPREFIX."reports`.`timestamp` DESC");
$c = $sql->db_fetch();

$count = $c[0];

if($count < "1") {

	$text = LAN_NO_PERSONAL_REPORTS;

} else {

	$count = ceil($count/"10");

	$start = $page*"10";
	$start = ($start > $count ? "0" : $start);
	$limit = "10";
		
	$stati = array("0" => LAN_REPORT_OPEN,"1" => LAN_REPORT_UNDER_INVESTIGATION, "2" => LAN_REPORT_CLOSED);
	
	$sql->db_select_gen("SELECT r . * , f.forum_name, t.thread_user FROM `".MPREFIX."reports` AS `r` LEFT JOIN `".MPREFIX."forum` AS `f` ON `f`.`forum_id` = `r`.`forum` LEFT JOIN `".MPREFIX."forum_t` AS `t` ON `t`.`thread_id` = `r`.`post` WHERE `r`.`userid` = '".USERID."' AND `r`.`report_parent` = '0' ORDER BY `r`.`timestamp` DESC LIMIT ".$start.",".$limit."");
	
	while($row = $sql->db_fetch()) {
		
		$date = $gen->convert_date($row['timestamp'],"short");
		$userinfo = explode(".",$row['thread_user']);
	
		$text .= "
	
			<table cellspacing='0' border='0' cellpadding='5' style='width:700px;'>
	
				<tr>
	
					<td valign='top' colspan='2' class='forumheader'>
	
						".e107UserUrl(USERID, USERNAME) .LAN_REPORT_REPORTED.e107UserUrl($userinfo[0], $userinfo[1])."s <a href='".e_PLUGIN."forum/forum_viewtopic.php?".$row['post'].".post'>".LAN_REPORT_POST."</a> ".LAN_REPORT_ON." ".$date." - ".LAN_REPORT_IN_FORUM." <a href='".e_PLUGIN."forum/forum_viewforum.php?".$row['forum']."'>".$row['forum_name']."</a>
	
					</td>
	
				</tr>	
				<tr>	
	
					<td valign='top' class='forumheader2' colspan='2'>
	
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
	
				</tr>
	
			</table>
	
			<br><br>
	
		";
	
	}
	
	$text .= LAN_REPORT_GOTO_PAGE." ";
	
	for($i = "1"; $i < ($count+"1"); $i++) {
	
		$text .= ($i == $page ? "<span style='color:red;'>".$i."</span>" : "<a href='".e_SELF."?".($i+"1")."'>".$i."</a>").($i != $count ? "," : "")." ";
	
	}

}

$ns->tablerender(LAN_MY_REPORTS,$text);
require_once(FOOTERF);

?>