<?php

/*
####################################
#  AACGC Roster                    #
#  M@CH!N3 admin@aacgc.com         # 
####################################
*/



global $sc_style;


//-------------------------Menu Title--------------------------------+

$advrankmenu_title .= "".$pref['advrankmenu_title']."";

//-------------------------------------------------------------------+



if ($pref['advrank_enable_gold'] == "1")
{$gold_obj = new gold();}

//-------------------------Menu News & Info Section-------------------+
$advrankmenu_text .= "<table style='width:95%' class=''>
<tr><td class='forumheader3'><center>[ <a href='".e_PLUGIN."aacgc_roster_adv/AdvRoster.php'>View Roster</a> ]</center></td></tr>";

if ($pref['advrank_enable_application'] == "1"){
$advrankmenu_text .= "<tr><td class='forumheader3'><center>[ <a href='".e_PLUGIN."aacgc_roster_adv/Application.php'>Application</a> ]</center></td></tr>";}

$advrankmenu_text .= "</table><br>";


if ($pref['advrank_enable_autoscroll'] == "1")
{$advrankmenu_text .= "
<script type=\"text/javascript\">
function advrostermenuup(){advrostermenu.direction = \"up\";}
function advrostermenudown(){advrostermenu.direction = \"down\";}
function advrostermenustop(){advrostermenu.stop();}
function advrostermenustart(){advrostermenu.start();}
</script>
<marquee height='".$pref['advrankmenu_height']."px' id='advrostermenu' scrollamount='".$pref['advrankmenu_speed']."' onMouseover='this.scrollAmount=".$pref['advrankmenu_mouseoverspeed']."' onMouseout='this.scrollAmount=".$pref['advrankmenu_mouseoutspeed']."' direction='down' loop='true'>";}
else
{$advrankmenu_text .= "<div style='border : 0; padding : 4px; width : auto; height : ".$pref['advrankmenu_height']."px; overflow : auto; '>";}



$advrankmenu_text .= "<table style='width:95%' class=''>";

        $sql ->db_Select("aacgc_roster_adv_cat", "*", "ORDER BY cat_id ASC","");
        while($row = $sql ->db_Fetch()){

$advrankmenu_text .= "<tr><td class='forumheader3'><center><b><u>".$row['cat_name']."</u></b></center></td></tr>";

        $sql7 = new db;
        $sql7 ->db_Select("aacgc_roster_adv", "*", "WHERE rank_cat=".$row['cat_id']." ORDER BY ".$pref['advrank_rankorderby']." ".$pref['advrank_rankorder']."","");
        while($row7 = $sql7 ->db_Fetch()){
        $sql3 = new db;
        $sql3 ->db_Select("aacgc_roster_adv_members", "*", "WHERE awarded_rank_id= '".$row7['rank_id']."'","");
        while($row3 = $sql3 ->db_Fetch()){
        $sql2 = new db;
        $sql2 ->db_Select("user", "*", "WHERE user_id='".$row3['user_id']."' ORDER BY user_name ASC","");
        while($row2 = $sql2 ->db_Fetch()){

        if ($pref['advrank_enable_gold'] == "1")
        {$userorb = "<font color='#00FF00'>".$gold_obj->show_orb($row2['user_id'])."</font>";}
        else
        {$userorb = "".$row2['user_name']."";}

        if ($pref['advrankmenu_enable_avatar'] == "1"){
        if ($row2['user_image'] == "")
        {$advavatar = "";}
        else
        {$advuseravatar = $row2[user_image];
        require_once(e_HANDLER."avatar_handler.php");
        $advuseravatar = avatar($advuseravatar);
        $advavatar = "<img src='".$advuseravatar."' width=".$pref['advrankmenu_avatar_size']."px></img>";}}


$advrankmenu_text .= "
        <tr>
        <td style='width:' class='indent'><a href='".e_BASE."user.php?id.".$row3['user_id']."'>".$advavatar." ".$userorb." <img width='".$pref['advrankmenu_img']."' src='".e_PLUGIN."/aacgc_roster_adv/ranks/".$row7['rank_pic']."' alt = '".$row7['rank_name']."'></img>
</a></td>
        </tr>";}}}}




$advrankmenu_text .= "</table>";

if ($pref['advrank_enable_autoscroll'] == "1")
{$advrankmenu_text .= "</marquee>
<br><br>
<table style='width:100%' class=''><tr><td>
<center>
<input class=\"button\" value=\"Start\" onClick=\"advrostermenustart();\" type=\"button\">
<input class=\"button\" value=\"Stop\" onClick=\"advrostermenustop();\" type=\"button\">
<input class=\"button\" value=\"Up\" onClick=\"advrostermenuup();\" type=\"button\">
<input class=\"button\" value=\"Down\" onClick=\"advrostermenudown();\" type=\"button\">
</center>
</td></tr></table>
<br>
";}
else
{$advrankmenu_text .= "</div>";}


//--------------------------------------------------------------------+








$ns -> tablerender($advrankmenu_title, $advrankmenu_text);


?>