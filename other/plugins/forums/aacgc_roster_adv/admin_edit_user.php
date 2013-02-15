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
$fl = new e_file;
if (e_QUERY) {
        $tmp = explode('.', e_QUERY);
        $action = $tmp[0];
        $id = $tmp[1];
        unset($tmp);
}
//-----------------------------------------------------------------------------------------------------------+
if (isset($_POST['update_user'])) {
        $message = ($sql->db_Update("aacgc_roster_adv_members", "awarded_rank_id='".$_POST['awarded_rank_id']."', user_id='".$_POST['user_id']."', user_location='".$_POST['user_location']."', user_age='".$_POST['user_age']."', user_game='".$_POST['user_game']."', user_status='".$_POST['user_status']."', join_date='".$_POST['join_date']."', user_duties='".$_POST['user_duties']."' WHERE awarded_id='".$_POST['id']."' ")) ? "Successful updated" : "Update failed";
}

if (isset($_POST['main_delete'])) {
        $delete_id = array_keys($_POST['main_delete']);
	$sql2 = new db;
    $sql2->db_Delete("aacgc_roster_adv_members", "awarded_id='".$delete_id[0]."'");
	
}

if (isset($message)) {
        $ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}
//-----------------------------------------------------------------------------------------------------------+
if ($action == "") {
        $text .= $rs->form_open("post", e_SELF, "myform_".$row['awarded_id']."", "", "");
        $text .= "
        <div style='text-align:center'>
        <table style='width:95%' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        </tr>";
        $sql->db_Select("aacgc_roster_adv_members", "*", "ORDER BY awarded_id ASC","");
        while($row = $sql->db_Fetch()){
        $sql2 = new db;
        $sql2->db_Select("aacgc_roster_adv", "*", "WHERE rank_id='".$row['awarded_rank_id']."'","");
        $row2 = $sql2->db_Fetch();
        $sql3 = new db;
        $sql3->db_Select("user", "*", "WHERE user_id='".$row['user_id']."'","");
        $row3 = $sql3->db_Fetch();


        $text .= "<tr>";
$text .= "<td style='width:' class='forumheader3'>".$row['awarded_id']."</td>";

$text .= "<td style='width:' class='forumheader3'>".$row3['user_name']."</td>";

if ($pref['rank_enable_locflag'] == "1"){
$text .= "<td style='width:' class='forumheader3'>".$row['user_location']."</td>";}

if ($pref['rank_enable_age'] == "1"){
$text .= "<td style='width:' class='forumheader3'>".$row['user_age']."</td>";}

if ($pref['rank_enable_game'] == "1"){
$text .= "<td style='width:' class='forumheader3'>".$row['user_game']."</td>";}

if ($pref['rank_enable_status'] == "1"){
$text .= "<td style='width:' class='forumheader3'>".$row['user_status']."</td>";}

if ($pref['rank_enable_djoined'] == "1"){
$text .= "<td style='width:' class='forumheader3'>".$row['join_date']."</td>";}

$text .= "<td style='width:' class='forumheader3'>".$row['user_duties']."</td>";

$text .= "<td style='width:' class='forumheader3'><img src='".e_PLUGIN."aacgc_roster_adv/ranks/".$row2['rank_pic']."'></img></td>";

$text .= "<td style='width:' class='forumheader3'>
        
		<a href='".e_SELF."?edit.{$row['awarded_id']}'>".ADMIN_EDIT_ICON."</a>
		<input type='image' title='".LAN_DELETE."' name='main_delete[".$row['awarded_id']."]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".LAN_CONFIRMDEL." [ID: {$row['awarded_id']} ]')\"/>
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

//-----------------------------------------------------------------------------------------------------------+

if ($action == "edit")
{
                $sql->db_Select("aacgc_roster_adv_members", "*", "awarded_id = $id");
                $row = $sql->db_Fetch();
                $sql2 = new db;
                $sql2->db_Select("aacgc_roster_adv", "*", "WHERE rank_id='".$row['awarded_rank_id']."'","");
                $row2 = $sql2->db_Fetch();
                $sql3 = new db;
                $sql3->db_Select("user", "*", "WHERE user_id='".$row['user_id']."'","");
                $row3 = $sql3->db_Fetch();


        $width = "width:100%";
        $text .= "
        <div style='text-align:center'>
        ".$rs -> form_open("post", e_SELF, "MyForm", "", "enctype='multipart/form-data'", "")."
        <table style='".$width."' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Name:</td>
        <td style='width:70%' class='forumheader3'>
        <input type='hidden' name='user_id' value='".$row['user_id']."'>
        ".$row3['user_name']."
        </td>
        </tr>";


if ($pref['advrank_enable_locflag'] == "1"){
        $rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*', 'blank*');
        $iconpath = e_PLUGIN."/aacgc_roster_adv/flags/";
        $iconlist = $fl->get_files($iconpath,"",$rejectlist);

        $text .= "
        <tr>
        <td style='width:40%; text-align:right' class='forumheader3'>User Location:</td>
        <td style='width:60%' class='forumheader3'>
        ".$rs -> form_text("user_location", 50, $row['user_location'], 100)."
        ".$rs -> form_button("button", '', "Show Flags", "onclick=\"expandit('plcico')\"")."
        <div id='plcico' style='{head}; display:none'>";
        foreach($iconlist as $icon){
        $text .= "<a href=\"javascript:insertext('".$icon['fname']."','user_location','plcico')\"><img src='".$icon['path'].$icon['fname']."' style='border:0' alt='".$icon['fname']."' /></a> ";}

      $text .= "</div>
		</td>
		</tr>";}


if ($pref['advrank_enable_age'] == "1"){
$text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Age:</td>
        <td style='width:70%' class='forumheader3'>
            ".$rs -> form_text("user_age", 100, $row['user_age'], 500)."
        </td>
        </tr>";}

if ($pref['advrank_enable_game'] == "1"){
$text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Game:</td>
        <td style='width:70%' class='forumheader3'>
            ".$rs -> form_text("user_game", 100, $row['user_game'], 500)."
        </td>
        </tr>";}

if ($pref['advrank_enable_djoined'] == "1"){
$text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>Join Date:</td>
        <td style='width:70%' class='forumheader3'>
            ".$rs -> form_text("join_date", 100, $row['join_date'], 500)."
        </td>
        </tr>";}

$text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Duties:</td>
        <td style='width:70%' class='forumheader3'>
            ".$rs -> form_text("user_duties", 100, $row['user_duties'], 500)."
        </td>
        </tr>";

if ($pref['advrank_enable_status'] == "1"){
$text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Status:</td>
        <td style='width:70%' class='forumheader3'>
		<select name='user_status' size='1' class='tbox' style='width:100%'>
		<option name='user_status' value='Active'>Active</option>
		<option name='user_status' value='Inactive'>Inactive</option>
        </td>
        </tr>";}



        $text .= "
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>Rank Image:</td>
        <td style='width:70%' class='forumheader3'>
        <select name='awarded_rank_id' size='1' class='tbox' style='width:100%'>";
        $sql5 = new db;
	$sql5->db_Select("aacgc_roster_adv", "rank_id, rank_name", "ORDER BY rank_id ASC","");
        while($row5 = $sql5->db_Fetch()){
        $text .= "<option name='rank' value='".$row5['rank_id']."'>".$row5['rank_name']."</option>";}




        $text .= "</div>
        </td></tr>
        <tr style='vertical-align:top'>
        <td colspan='2' style='text-align:center' class='forumheader'>
        ".$rs->form_hidden("id", "".$row['awarded_id']."")."
        ".$rs -> form_button("submit", "update_user", "Update")."
        </td>
        </tr>
        </table>
        ".$rs -> form_close()."
        </div>";
	      $ns -> tablerender("", $text);
	      require_once(e_ADMIN."footer.php");
}
?>


