<?php

/*
#######################################
#     e107 website system plguin      #
#     AACGC Advaced Roster            #
#     by M@CH!N3                      #
#     http://www.aacgc.com            #
#######################################
*/
require_once("../../class2.php");
if(!getperms("P")) {
echo "";
exit;
}
require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."form_handler.php"); 
require_once(e_HANDLER."file_class.php");
$rs = new form;
$fl = new e_file;
if (e_QUERY) {
        $tmp = explode('.', e_QUERY);
        $action = $tmp[0];
        $id = $tmp[1];
        unset($tmp);
}
//-----------------------------------------------------------------------------------------------------------+
if (isset($_POST['main_delete'])) {
        $delete_id = array_keys($_POST['main_delete']);
	$sql2 = new db;
    $sql2->db_Delete("aacgc_roster_adv_apps", "app_id='".$delete_id[0]."'");
	
}

//-----------------------------------------------------------------------------------------------------------+
if ($action == "") {




        $text .= $rs->form_open("post", e_SELF, "myform_".$row['app_id']."", "", "");
        $text .= "
        <div style='text-align:center'>
        <table style='width:95%' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:5%' class='forumheader3'>App ID</td>
        <td style='width:20%' class='forumheader3'>Name</td>
        <td style='width:5%' class='forumheader3'>Age</td>
        <td style='width:5%' class='forumheader3'>Location</td>
        <td style='width:20%' class='forumheader3'>Contact</td>
        <td style='width:20%' class='forumheader3'>Bio</td>
        <td style='width:20%' class='forumheader3'>Game Name(s)</td>
        <td style='width:20%' class='forumheader3'>Questions</td>
        <td style='width:5%' class='forumheader3'>Options</td>
       </tr>";
        $sql->db_Select("aacgc_roster_adv_apps", "*", "ORDER BY app_id ASC","");
        while($row = $sql->db_Fetch()){
        $sql2->db_Select("user", "*", "WHERE user_id=".$row['user']."","");
        $row2 = $sql2->db_Fetch();

        $text .= "
        <tr>
        <td style='width:' class='forumheader3'>".$row['app_id']."</td>
        <td style='width:' class='forumheader3'>".$row2['user_name']."</td>
        <td style='width:' class='forumheader3'>".$row['age']."</td>
        <td style='width:' class='forumheader3'>".$row['location']."</td>
        <td style='width:' class='forumheader3'>".$row['contact']."</td>
        <td style='width:' class='forumheader3'>".$row['bio']."</td>
        <td style='width:' class='forumheader3'>".$row['gamename']."</td>
        <td style='width:' class='forumheader3'>1. ".$row['questiona']."<br>-----<br>2. ".$row['questionb']."<br>-----<br>3. ".$row['questionc']."<br>-----<br>4. ".$row['questiond']."<br>-----<br>5. ".$row['questione']."<br>-----<br></td>
        <td style='width:' class='forumheader3'>
	<input type='image' title='Delete' name='main_delete[".$row['app_id']."]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".LAN_CONFIRMDEL." [ID: {$row['app_id']} ]')\"/>
		</td>
        </tr>";
		}
        $text .= "
        </table>
        </div>";
        $text .= $rs->form_close();
	      $ns -> tablerender("", $text);
	      require_once(e_ADMIN."footer.php");
}
//-----------------------------------------------------------------------------------------------------------+


?>


