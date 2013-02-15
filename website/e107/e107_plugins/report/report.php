<?php

require_once("../../class2.php");
include_once("languages/".e_LANGUAGE.".php");
define(e_PAGETITLE,LAN_REPORT);
require_once(HEADERF);
$text = null;
$qry = explode(".",e_QUERY);

if(!USER OR !$qry[0]) {

	header("location: ".e_BASE."index.php") and exit;

} else {

	if($_SERVER['REQUEST_METHOD'] != "POST") {

		$text .= "

			<form method='post' action='".e_SELF."?".e_QUERY."'>
			<fieldset style='border:1px dashed #000; background-color:transparent; width:500px;'>

				<legend>".LAN_REPORT_NEW."</legend>

			<table cellspacing='0' border='0' style='width:500px;'>

				<tr>

					<td valign='top' style='text-align:center;'>

						<textarea name='report' class='tbox' rows='20' cols='80' onClick=\"this.value='';\">Your report text here</textarea>
					
					</td>
					
				</tr>
				<tr>

					<td valign='top' style='text-align:center;'>

						<br>
						<input type='submit' value='".LAN_SEND_REPORT."' class='button'>

					</td>

				</tr>

			</table>
			</fieldset>
			</form>

		";

	} else {

		$_POST = $tp->toDB($_POST);
		$message = htmlentities($_POST['report']);
		$thread_id = intval($qry[0]);
		$time = time();

		$sql->db_select_gen("SELECT thread_thread,forum_id,forum_name FROM `".MPREFIX."forum_t` LEFT JOIN `".MPREFIX."forum` ON `".MPREFIX."forum_t`.`thread_forum_id` = `".MPREFIX."forum`.`forum_id` WHERE `".MPREFIX."forum_t`.`thread_id` = '".$thread_id."'");
		$t = $sql->db_fetch() or die(mysql_error());

		$reported_content = $t[0];
		$forum = $t[1];
		$userid = USERID;

		$sql->db_select_gen("INSERT INTO `".MPREFIX."reports` (message,reported_content,timestamp,report_parent,userid,post,forum,status) VALUES ('$message','$reported_content','$time','0','$userid','$thread_id','$forum','0');");
		header("location: ".e_PLUGIN."forum/forum_viewtopic.php?".$thread_id.".post") and exit;

	}


	$ns->tablerender(LAN_CAPTION,$text);

}

require_once(FOOTERF);

?>