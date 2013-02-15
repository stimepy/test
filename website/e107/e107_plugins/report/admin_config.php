<?php

require_once("../../class2.php");
require_once(e_ADMIN."auth.php");
include_once("languages/".e_LANGUAGE."_admin.php");
global $pref;

$text = null;

if($_SERVER['REQUEST_METHOD'] != "POST") {
		
	$modclass = $pref['reports']['mod_class'];

	$text = "

		<fieldset style='border:1px dashed #000; width:400px;'>

			<legend>".LAN_SETTINGS."</legend>

		<form method='post' action='".e_SELF."'>
		<table cellspacing='15' border='0' cellpadding='0' style='width:400px;'>

			<colgroup>

				<col style='width:50%;'>
				<col style='width:50%;'>

			</colgroup>

			<tr>

				<td>

					<label for='reports_mod_class'>".LAN_REPORTS_MODCLASS."</label>

				</td>
				<td>
				
					<select name='reports_mod_class' class='tbox'>

	";

					$sql->db_select_gen("SELECT userclass_id,userclass_name FROM `".MPREFIX."userclass_classes` WHERE `".MPREFIX."userclass_classes`.`userclass_editclass` IN (".USERCLASS_LIST.") union select '0','".LAN_ADMIN_ONLY."'");

					while($row = $sql->db_fetch()) {

						$class_selected = ($row[0] == $modclass ? "selected" : "");

						$text .= "<option value='".$row[0]."' $class_selected>".$row[1]."</option>";

					}

	$text .= "

					</select>

				</td>

			</tr>
			<tr>
	
				<td colspan='2'>

					<input type='submit' value='".LAN_SUBMIT."' class='button'>

				</td>

			</tr>

		</table>
		</form>
		</fieldset>

	";

} else {

	$pref['reports'] = array("mod_class" => intval($_POST['reports_mod_class']));
	save_prefs();

	$text = LAN_PREFS_SAVED."<br><br>

		Reports_mod_class: ".$pref['reports']['mod_class'];

	header("refresh:4;url=admin_config.php");

}

$ns->tablerender(LAN_CAPTION,$text);

require_once(e_ADMIN."footer.php");

?>