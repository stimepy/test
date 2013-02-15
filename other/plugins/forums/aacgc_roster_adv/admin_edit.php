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
if (isset($_POST['update_rank'])) {
        $message = ($sql->db_Update("aacgc_roster_adv", "rank_name='".$_POST['rank_name']."', rank_pic='".$_POST['rank_pic']."', rank_cat='".$_POST['rank_cat']."' WHERE rank_id='".$_POST['id']."' ")) ? "Successful updated" : "Update failed";
}

if (isset($_POST['main_delete'])) {
        $delete_id = array_keys($_POST['main_delete']);
	$sql2 = new db;
    $sql2->db_Delete("aacgc_roster_adv", "rank_id='".$delete_id[0]."'");
	
}

if (isset($message)) {
        $ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}
//-----------------------------------------------------------------------------------------------------------+
if ($action == "") {
        $text .= $rs->form_open("post", e_SELF, "myform_".$row['rank_id']."", "", "");
        $text .= "
        <div style='text-align:center'>
        <table style='width:95%' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:' class='forumheader3'>Rank ID</td>
        <td style='width:25%' class='forumheader3'>Rank Name</td>
        <td style='width:25%' class='forumheader3'>Rank Category</td>
        <td style='width:25%' class='forumheader3'>Rank Image</td>
        <td style='width:' class='forumheader3'>Options</td>
       </tr>";
        $sql->db_Select("aacgc_roster_adv", "*", "ORDER BY rank_cat ASC","");
        while($row = $sql->db_Fetch()){
        $sql2 = new db;
        $sql2->db_Select("aacgc_roster_adv_cat", "*", "WHERE cat_id=".$row['rank_cat']."","");
        $row2 = $sql2->db_Fetch();
        $text .= "
        <tr>
        <td style='width:' class='forumheader3'>".$row['rank_id']."</td>
        <td style='width:25%' class='forumheader3'>".$row['rank_name']."</td>
        <td style='width:25%' class='forumheader3'>".$row2['cat_name']."</td>
        <td style='width:25%' class='forumheader3'><img src='ranks/".$row['rank_pic']."'></img></td>
        <td style='width:' class='forumheader3'>
        
		<a href='".e_SELF."?edit.{$row['rank_id']}'>".ADMIN_EDIT_ICON."</a>
		<input type='image' title='".LAN_DELETE."' name='main_delete[".$row['rank_id']."]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".LAN_CONFIRMDEL." [ID: {$row['rank_id']} ]')\"/>
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
                $sql->db_Select("aacgc_roster_adv", "*", "rank_id = $id");
                $row = $sql->db_Fetch();
$sql2 = new db;
$sql2->db_Select("aacgc_roster_adv_cat", "*");
$rows = $sql2->db_Rows();
for ($i=0; $i < $rows; $i++) {
$option = $sql2->db_Fetch();
$options .= "<option name='rank_cat' value='".$option['cat_id']."'>".$option['cat_name']."</option>";}


        $width = "width:100%";
        $text = "
        <div style='text-align:center'>
        ".$rs -> form_open("post", e_SELF, "MyForm", "", "enctype='multipart/form-data'", "")."
        <table style='".$width."' class='fborder' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>Rank Name:</td>
        <td style='width:70%' class='forumheader3'>
            ".$rs -> form_text("rank_name", 100, $row['rank_name'], 500)."
        </td>
        </tr>
       <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>Category:</td>
        <td style='width:70%' class='forumheader3'>
		<select name='rank_cat' size='1' class='tbox' style='width:100%'>
		".$options."
        </td>
        </tr>";

        $rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*', 'blank*');
        $iconpath = e_PLUGIN."aacgc_roster_adv/ranks/";
        $iconlist = $fl->get_files($iconpath,"",$rejectlist);

        $text .= "
        <tr>
        <td style='width:40%; text-align:right' class='forumheader3'>Rank Image:</td>
        <td style='width:60%' class='forumheader3'>
        ".$rs -> form_text("rank_pic", 50, $row['rank_pic'], 100)."
        ".$rs -> form_button("button", '', "Show Ranks", "onclick=\"expandit('plcico')\"")."
            <div id='plcico' style='{head}; display:none'>";
            foreach($iconlist as $icon){
            $text .= "<a href=\"javascript:insertext('".$icon['fname']."','rank_pic','plcico')\"><img src='".$icon['path'].$icon['fname']."' style='border:0' alt='' /></a> ";
            }



        $text .= "</div>
        </td></tr>
        <tr style='vertical-align:top'>
        <td colspan='2' style='text-align:center' class='forumheader'>
        ".$rs->form_hidden("id", "".$row['rank_id']."")."
        ".$rs -> form_button("submit", "update_rank", "Update")."
        </td>
        </tr>
        </table>
        ".$rs -> form_close()."
        </div>";
	      $ns -> tablerender("", $text);
	      require_once(e_ADMIN."footer.php");
}
?>

