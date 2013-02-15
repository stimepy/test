<?php

/*
#######################################
#     e107 website system plguin      #
#     AACGC Advanced Roster           #
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
$selecteduserid = $_POST['user_sel_id'];
function getrankcount($rankcuid) {
$rankcount = 0;
    $sql0000001 = new db;
    $sql0000001->db_Select("aacgc_roster_adv_members","*", "WHERE user_id='".$rankcuid."'", "");
    while($row0000001 = $sql0000001->db_Fetch()) {
        $rankcount++;
	}
    return $rankcount;
}
//-----------------------------------------------------------------------------------------------------------+
if (isset($_POST['rank_delete'])) {
        $delete_id = array_keys($_POST['rank_delete']);
        $message = ($sql->db_Delete("aacgc_roster_adv_members", "awarded_id ='".$delete_id[0]."' ")) ? AMS_ADMIN_S23 : AMS_ADMIN_S24 ;
}
if (isset($message)) {
        $ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}
//-----------------------------------------------------------------------------------------------------------+
//-----------------------------------------------------------------------------------------------------------+
$text ="
<form method='POST' action='admin_take.php'>
<br>
<center>
<div style='width:100%'>
<table style='width:60%' class='fborder' cellspacing='0' cellpadding='0'>
	<tr>
		<td style='width:30%; text-align:right' class='forumheader3'>".AMS_ADMIN_S2.":</td>
		<td style='width:70%' class='forumheader3'>
		<select name='user_sel_id' size='1' class='tbox' style='width:100%'>";
	        $sql->db_Select("user", "user_id, user_name", "ORDER BY user_name ASC","");
    		    while($row = $sql->db_Fetch()){
    		    $usern = $row[user_name];
    		    $userid = $row[user_id];
    		    $urankc = getrankcount($userid);
    		    If ($urankc > 0) {
    		    if ($userid == $selecteduserid) {
			        $text .= "<option name='user_sel_id' value='".$userid."' selected>".$usern."</option>";
				} else {
			        $text .= "<option name='user_sel_id' value='".$userid."'>".$usern."</option>";					
				}
				}
	        	}
        $text .= "
		</td>
    </tr>
    <tr>
    <td colspan='2' 'style='width:70%' class='forumheader3'>
        <input type='hidden' name='ranktake' value='1'>
		<center><input class='button' type='submit' value='".AMS_ADMIN_S3."' style='width:150px'></center>
	</td>
	</tr>
</table>
</div>
</center>
<br>
</form>
<br>
";
//-----------------------------------------------------------------------------------------------------------+
//-----------------------------------------------------------------------------------------------------------+
If ($_POST['ranktake'] == "1") {
$text .= "	
<form method='POST' action='admin_take.php'>
<div style='text-align:center'>
<table style='width:90%' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:50px' class='forumheader3'>ID</td>
        <td style='width:80px' class='forumheader3'>Rank Picture</td>
        <td style='width:25%' class='forumheader3'>Rank Name</td>
        <td style='width:50px' class='forumheader3'>Delete Rank</td>
        </tr>";
        $sql->db_Select("aacgc_roster_adv_members", "*", "WHERE user_id = '".$selecteduserid."'","");
        while($row = $sql->db_Fetch()){
        $sql2->db_Select("aacgc_roster_adv", "*", "WHERE rank_id = '".$row['awarded_rank_id']."'","");
        $row2 = $sql2->db_Fetch();
        $text .= "
        <tr>
        <td style='width:50px' class='forumheader3'>".$row['awarded_rank_id']."</td>
        <td style='width:80px' class='forumheader3'><center><img src='ranks/".$row2['rank_pic']."' alt=''></img></center></td>
        <td style='width:25%' class='forumheader3'>".$row2['rank_name']."</td>";
        $sql2->db_Select("user", "*", "WHERE user_id = '".$row['user_id']."'","");
        $row2 = $sql2->db_Fetch();
        $text .= "
        
        <td style='width:50px; text-align:center; white-space: nowrap' class='forumheader3'>
		<input type='image' title='".LAN_DELETE."' name='rank_delete[".$row['awarded_id']."]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".LAN_CONFIRMDEL." [ID: {$row['rank_id']} ]')\"/>
		</td>
        </tr>";
                }
        $text .= "
        </table>
        </div>";
        $text .= $rs->form_close();	
}
	      $ns -> tablerender("Take Rank", $text);
	      require_once(e_ADMIN."footer.php");
?>
