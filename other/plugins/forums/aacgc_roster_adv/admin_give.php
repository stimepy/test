<?php

/*
#######################################
#     e107 website system plguin      #
#     AACGC Advanced Roster           #
#     by M@CH!N3                      #
#     http://www.AACGC.com            #
#######################################
*/
require_once("../../class2.php");
if (!getperms('P'))
{header('location:' . e_HTTP . 'index.php');
exit;}
require_once(e_ADMIN."auth.php");
if (!defined('ADMIN_WIDTH'))
{define(ADMIN_WIDTH, 'width:100%;');}
require_once(e_HANDLER."form_handler.php"); 
require_once(e_HANDLER."file_class.php");
$rs = new form;
$fl = new e_file;

//-----------------------------------------------------------------------------------------------------------+
if ($_POST['ranktodb'] == "1") {
$rankid = $_POST['rank'];
$uid = $_POST['user'];
$newuserloc = $_POST['user_location'];
$newuserage = $_POST['user_age'];
$newusergame = $_POST['user_game'];
$newuserstatus = $_POST['user_status'];
$newuserjoin = $_POST['join_date'];
$newuserduties = $_POST['user_duties'];

$sql->db_Select("user", "*", "WHERE user_id = '".$uid."'","");
while($row = $sql->db_Fetch())
{$usern2 = $row[user_name];}

$sql->db_Insert("aacgc_roster_adv_members", "NULL,'".$rankid."' , '".$uid."', '".$newuserloc."', '".$newuserage."', '".$newusergame."', '".$newuserstatus."', '".$newuserjoin."', '".$newuserduties."'");



$txt .= "<center><b>Rank Given To:".$usern2."</b><center>";

$ns -> tablerender("", $txt);}

//-----------------------------------------------------------------------------------------------------------+


//-----------------------------------------------------------------------------------------------------------------------------


$text .= "*NOTE* - Make sure you go to settings and enable which columns you want shown before giving ranks, some columns may not be showing here*
<form method='POST' action='admin_give.php'>
<br>
<center>
<table style='width:100%' class='fborder' cellspacing='0' cellpadding='0'>
	<tr>
		<td style='width:30%; text-align:right' class='forumheader3'>Member:</td>
		<td style='width:70%' class='forumheader3'>
		<select name='user' size='1' class='tbox' style='width:100%'>";
	        $sql->db_Select("user", "user_id, user_name", "ORDER BY user_name ASC","");
    		    while($row = $sql->db_Fetch()){
    		    $usern = $row[user_name];
    		    $userid = $row[user_id];
		    $text .= "<option name='user' value='".$userid."'>".$usern."</option>";
        	}
        $text .= "
		</td>
        </tr>
        <tr>
        <td style='width:30%; text-align:right' class='forumheader3'>Rank:</td>
        <td style='width:70%' class='forumheader3'>
		<select name='rank' size='1' class='tbox' style='width:100%'>";
		$sql->db_Select("aacgc_roster_adv", "rank_id, rank_name", "ORDER BY rank_id ASC","");
        while($row = $sql->db_Fetch()){
         $text .= "<option name='rank' value='".$row['rank_id']."'>".$row['rank_name']."</option>";}
        

$text .= "</td>
	</tr>";




if ($pref['advrank_enable_age'] == "1"){
$text .= "<tr>
        <td style='width:40%; text-align:right' class='forumheader3'>User Age:</td>
        <td style='width:60%' class='forumheader3'>
        <input class='tbox' type='text' name='user_age' size='50'>
        </td>
        </tr>";}



if ($pref['advrank_enable_game'] == "1"){
$text .= "<tr>
        <td style='width:40%; text-align:right' class='forumheader3'>User Game:</td>
        <td style='width:60%' class='forumheader3'>
        <input class='tbox' type='text' name='user_game' size='50'>
        </td>
        </tr>";}




if ($pref['advrank_enable_djoined'] == "1"){
$text .= "<tr>
        <td style='width:40%; text-align:right' class='forumheader3'>Date Joined:</td>
        <td style='width:60%' class='forumheader3'>
        <input class='tbox' type='text' name='join_date' size='50'>
        </td>
        </tr>";}


$text .= "<tr>
        <td style='width:40%; text-align:right' class='forumheader3'>User Duties:</td>
        <td style='width:60%' class='forumheader3'>
        <input class='tbox' type='text' name='user_duties' size='100'>
        </td>
        </tr>";

if ($pref['advrank_enable_status'] == "1"){
$text .= "<tr>
        <td style='width:30%; text-align:right' class='forumheader3'>User Status:</td>
        <td style='width:70%' class='forumheader3'>
		<select name='user_status' size='1' class='tbox' style='width:100%'>
		<option name=''user_status' value='Active'>Active</option>
		<option name='user_status' value='Inactive'>Inactive</option>
        </td>
        </tr>";}



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
		</tr>
		
        <tr>
        <td colspan='2' style='text-align:center' class='forumheader'>
		<input type='hidden' name='ranktodb' value='1'>
		<input class='button' type='submit' value='Give Rank To:' style='width:150px'>
		</td>
        </tr>
        </table>
        </form>";

$ns -> tablerender("AACGC Advanced Roster (Give Rank)", $text);
require_once(e_ADMIN."footer.php");

?>