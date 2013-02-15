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

//-----------------------------------------------------------------------------------------------------------+
if ($_POST['add_rank'] == '1') {
$newrankname = $_POST['rank_name'];
$newrankpic = $_POST['rank_pic'];
$newrankcat = $_POST['rank_cat'];
$reason = "";
$newok = "";
if (($newrankname == "")){
	$newok = "0";
	$reason = "No name";
} else {
	$newok = "1";
}
if (($newrankpic == "") OR ($newok == "0")){
		If ($newrankpic == "") {
		$reason .= "No Image Selected";	
		}
	$newok = "0";
} else {
	$newok = "1";
}

If ($newok == "0"){
 	$newtext = "
 	<center>
	<b><br><br> ".$reason."
	</center>
 	</b>
	";
	$ns->tablerender("", $newtext);
}
If ($newok == "1"){
$sql->db_Insert("aacgc_roster_adv", "NULL, '".$newrankname."', '".$newrankpic."', '".$newrankcat."'") or die(mysql_error());
$ns->tablerender("", "<center><b>Rank Created</b></center>");
}
}
//-----------------------------------------------------------------------------------------------------------+
$text = "
<form method='POST' action='admin_new.php'>
<br>
<center>
<div style='width:100%'>
<table style='width:80%' class='fborder' cellspacing='0' cellpadding='0'>";


$sql2->db_Select("aacgc_roster_adv_cat", "*");
$rows = $sql2->db_Rows();
for ($i=0; $i < $rows; $i++) {
$option = $sql2->db_Fetch();
$options .= "<option name='rank_cat' value='".$option['cat_id']."'>".$option['cat_name']."</option>";}


$text .= "
        <tr>
        <td style='width:40%; text-align:right' class='forumheader3'>Rank:</td>
        <td style='width:60%' class='forumheader3'>
        <input class='tbox' type='text' name='rank_name' size='50'>
        </td>
        </tr>
";

        $rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*', 'blank*');
        $iconpath = e_PLUGIN."aacgc_roster_adv/ranks";
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
        </td>
	</tr>
        <tr>
        <td style='width:40%; text-align:right' class='forumheader3'>Rank Category:</td>
        <td style='width:70%' class='forumheader3'>
		<select name='rank_cat' size='1' class='tbox' style='width:100%'>
		".$options."
        </td>
        </tr>
		
        <tr style='vertical-align:top'>
        <td colspan='2' style='text-align:center' class='forumheader'>
		<input type='hidden' name='add_rank' value='1'>
		<input class='button' type='submit' value='Create Rank'>
		</td>
        </tr>
</table>
</div>
<br>
</form>";
	      $ns -> tablerender("AACGC Advanced Roster", $text);
	      require_once(e_ADMIN."footer.php");
?>

