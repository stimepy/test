<?php

/*
#######################################
#     e107 website system plguin      #
#     AACGC Roster                    #
#     by M@CH!N3                      #
#     http://www.AACGC.com            #
#######################################
*/


require_once("../../class2.php");
require_once(HEADERF);



if ($pref['advrank_enable_gold'] == "1")
{$gold_obj = new gold();}

if ($pref['advrank_enable_theme'] == "1")
{$themea = "forumheader3";
$themeb = "indent";}
else
{$themea = "";
$themeb = "";}
//---------------------------------------------------------------------------------

$title .= "".$pref['advrank_main_title'].""; 

//-----------------------------------------------------------------------------------

$text .= "    
        <table style='width:75%' class='' border=1 bordercolor='#808080'>
        <tr>
        <td><center><font size='3'><b><u>".$pref['advclandetailstitle']."</u></b></font>
        <br><br>
        ".$pref['advclandetails']."</td>
        </tr>";

if ($pref['advrank_enable_application'] == "1"){
$text .= "<tr><td class='button'><a href='Application.php'>[ Submit Application ]</a></td></tr>";}


$text .= "</table><br><br>";

//-----------------------------------------------------------------------------------

        $sql ->db_Select("aacgc_roster_adv_cat", "*", "ORDER BY cat_id ASC","");
        while($row = $sql ->db_Fetch()){

$text .= "<table style='width:100%' class='' cellspacing='0' cellpadding='0'>
        <tr>
        <td style='width:75%' class='".$themea."' colspan=3>
        <center><font size='".$pref['advcatfont_title']."'><b><u>".$row['cat_name']."</u></b></font></a>
        </td>
        </tr>
        <tr>
        <td style='width:75%' class='".$themea."' colspan=3><center>";

        $sql7 = new db;
        $sql7 ->db_Select("aacgc_roster_adv", "*", "WHERE rank_cat=".$row['cat_id']." ORDER BY ".$pref['advrank_rankorderby']." ".$pref['advrank_rankorder']."","");
        while($row7 = $sql7 ->db_Fetch()){
$text .= " <img height='20px' src='".e_PLUGIN."/aacgc_roster_adv/ranks/".$row7['rank_pic']."' alt = '".$row7['rank_name']."'></img> , ";}


$text .= "</center></td>
          </tr>
          ";

        $sql7 = new db;
        $sql7 ->db_Select("aacgc_roster_adv", "*", "WHERE rank_cat=".$row['cat_id']." ORDER BY ".$pref['advrank_rankorderby']." ".$pref['advrank_rankorder']."","");
        while($row7 = $sql7 ->db_Fetch()){
        $sql3 = new db;
        $sql3 ->db_Select("aacgc_roster_adv_members", "*", "WHERE awarded_rank_id= '".$row7['rank_id']."'","");
        while($row3 = $sql3 ->db_Fetch()){
        $sql2 ->db_Select("user", "*", "WHERE user_id='".$row3['user_id']."' ORDER BY user_name ASC","");
        while($row2 = $sql2 ->db_Fetch()){
        $sql5 = new db;
        $sql5 ->db_Select("user_extended", "*", "WHERE user_extended_id = '".$row2['user_id']."'","");
        $row5 = $sql5 ->db_Fetch();
        $sql6 = new db;
        $sql6 ->db_Select("user_extended_country", "*", "WHERE country_iso='".$row5['user_country']."'","");
        $row6 = $sql6 ->db_Fetch();

if ($pref['advrank_enable_avatar'] == "1"){
if ($row2['user_image'] == "")
{$avatar = "";}
else
{$useravatar = $row2[user_image];
require_once(e_HANDLER."avatar_handler.php");
$useravatar = avatar($useravatar);
$avatar = "<img src='".$useravatar."' width=".$pref['advrank_avatar_size']."px></img>";}}
if ($pref['advrank_enable_gold'] == "1")
{$userorb = "<font color='#00FF00'>".$gold_obj->show_orb($row2['user_id'])."</font>";}
else
{$userorb = "".$row2['user_name']."";}


$text .= "<tr>
          <td style='width:' class='".$themeb."'><a href='".e_BASE."user.php?id.".$row3['user_id']."'>".$avatar." ".$userorb."</a></td>
          <td style='width:' class='".$themeb."'>";

//--#location
if ($pref['advrank_enable_loccol'] == "1"){
$locflag = "<img width='".$pref['advrank_flag_img']."' src='flags/".$row3['user_location']."'></img>";
if ($row5['user_location'] == "")
{$location = "";}
else
{$location = "".$row5['user_country']."/".$row5['user_location']."";}
$text .= "Location: ".$locflag."".$location."<br>";}}
//--#Age
if ($pref['advrank_enable_age'] == "1"){
$text .= "Age: ".$row3['user_age']."<br>";}
//--#Game
if ($pref['advrank_enable_game'] == "1"){
$text .= "Game: ".$row3['user_game']."<br>";}
//--#status
if ($pref['advrank_enable_status'] == "1"){
$text .= "Status: ".$row3['user_status']."<br>";}
//--#datejoined
if ($pref['advrank_enable_djoined'] == "1"){
$text .= "Date Joined: ".$row3['join_date']."<br>";}
//--#birthday
if ($pref['advrank_enable_bday'] == "1"){
if($row5['user_birthday'] == "0000-00-00"){
$bday = "<i>not set</i>";}
else
if($row5['user_birthday'] == ""){
$bday = "<i>not set</i>";}
else
{$BDAY_now = time();
$BDAY_age = date("Y-m-d", $BDAY_now) - $row5['user_birthday'];
$bday = "".$row5['user_birthday']." (".$BDAY_age.")";}
$text .= "Birthday (age): ".$bday."<br>";}
//--#Duties
if ($row3['user_duties'] == ""){}
else
{$text .= "Duties: ".$row3['user_duties']."<br>";}
//--#xfire
if ($pref['advrank_enable_xfire'] == "1"){
if($row5['user_xfire'] == ""){
$xfire = "<i>none</i>";}
else
{$xfire = "".$row5['user_xfire']."";}}
if ($pref['advrank_enable_xfireimg'] == "1"){
if($row5['user_xfire'] == ""){
$xfireimg = "";}
else
{
if ($pref['advrxf_skin'] == "Xfire Default"){
$xfireimg = "<a href='http://profile.xfire.com/".$row5['user_xfire']."' target='_blank'><img src='http://miniprofile.xfire.com/bg/bg/type/3/".$row5['user_xfire'].".png' width='149' height='29' /></a>";}

if ($pref['advrxf_skin'] == "Sci-fi"){
$xfireimg = "<a href='http://profile.xfire.com/".$row5['user_xfire']."' target='_blank'><img src='http://miniprofile.xfire.com/bg/sf/type/3/".$row5['user_xfire'].".png' width='149' height='29' /></a>";}

if ($pref['advrxf_skin'] == "Shadow"){
$xfireimg = "<a href='http://profile.xfire.com/".$row5['user_xfire']."' target='_blank'><img src='http://miniprofile.xfire.com/bg/sh/type/3/".$row5['user_xfire'].".png' width='149' height='29' /></a>";}

if ($pref['advrxf_skin'] == "Combat"){
$xfireimg = "<a href='http://profile.xfire.com/".$row5['user_xfire']."' target='_blank'><img src='http://miniprofile.xfire.com/bg/co/type/3/".$row5['user_xfire'].".png' width='149' height='29' /></a>";}

if ($pref['advrxf_skin'] == "Fantasy"){
$xfireimg = "<a href='http://profile.xfire.com/".$row5['user_xfire']."' target='_blank'><img src='http://miniprofile.xfire.com/bg/os/type/3/".$row5['user_xfire'].".png' width='149' height='29' /></a>";}}

$text .= "Xfire:<br>".$xfireimg."";}


$text .= "</td>";


$text .= "<td style='width:' class='".$themeb."'><center>
          <font size='".$pref['advrankfont_title']."'>".$row7['rank_name']."</font>
          <br>
          <img width='".$pref['advrank_main_img']."' src='".e_PLUGIN."/aacgc_roster_adv/ranks/".$row7['rank_pic']."' alt = '".$row7['rank_name']."'></img>
          </center></td>";



$text .= "</tr>";}}

$text .= "</table><br><br>";}



//----#AACGC Plugin Copyright&reg; - DO NOT REMOVE BELOW THIS LINE! - #-------+
require(e_PLUGIN . 'aacgc_roster_adv/plugin.php');
$text .= "<br><br><br><br><br><br><br>
<a href='http://www.aacgc.com' target='_blank'>
<font color='808080' size='1'>".$eplug_name." V".$eplug_version."  &reg;</font>
</a>";
//------------------------------------------------------------------------+




$ns -> tablerender($title, $text);











//----------------------------------------------------------------------------------

require_once(FOOTERF);



?>