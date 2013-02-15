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
require_once(HEADERF);
  
$title .= "Application";

//---------------------------------------------

if ($pref['advrank_enable_application'] == "1"){


if ($_POST['add_user'] == '1') {
$newuser = $_POST['user'];
$newage = $_POST['age'];
$newloc = $_POST['location'];
$newcontact = $_POST['contact'];
$newbio = $_POST['bio'];
$newgamename = $_POST['gamename'];
$newquestiona = $_POST['questiona'];
$newquestionb = $_POST['questionb'];
$newquestionc = $_POST['questionc'];
$newquestiond = $_POST['questiond'];
$newquestione = $_POST['questione'];
$reason = "";
$newok = "";
if (($newuser == "") OR ($newage == "")){
	$newok = "0";
	$reason = "No name or age";
} else {
	$newok = "1";
}
if (($newcontact == "")){
	$newok = "0";
	$reason = "Need a way to contact you";
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
$sql->db_Insert("aacgc_roster_adv_apps", "NULL, '".$newuser."', '".$newage."', '".$newloc."', '".$newcontact."', '".$newbio."', '".$newgamename."', '".$newquestiona."', '".$newquestionb."', '".$newquestionc."', '".$newquestiond."', '".$newquestione."'") or die(mysql_error());
$ns->tablerender("", "<center><b>Application Submitted</b></center>");
}
}
//-----------------------------------------------------------------------------------------------------------+

if (USER){
$text = "

<table style='width:80%' class='fborder' cellspacing='0' cellpadding='0'>
<tr>
<td><center>
<font size='3'><b><u>".$pref['advappdetailstitle']."</u></b></font>
<br><br>
".$pref['advappdetails']."
</td>
</tr></table>



<form method='POST' action='Application.php'>
<br>
<center>
<table style='width:80%' class='fborder' cellspacing='0' cellpadding='0'>";


$text .= "
        <tr>
        <td colspan=2 class='forumheader3'>
        Welcome ".USERNAME.", Please fill in the information below
        <input type='hidden' name='user' value='".USERID."'>
        </td>
        </tr>
        <tr>
        <td style='width:20%; text-align:right' class='forumheader3'>Age:</td>
        <td style='width:' class='forumheader3'>
	<input class='tbox' type='text' name='age' size='100'>
        </td>
        </tr>
        <tr>
        <td style='width:20%; text-align:right' class='forumheader3'>Location (country):</td>
        <td style='width:' class='forumheader3'>
	<input class='tbox' type='text' name='location' size='100'>
        </td>
        </tr>
        <tr>
        <td style='width:20%; text-align:right' class='forumheader3'>Contact:</td>
        <td style='width:' class='forumheader3'>
	<input class='tbox' type='text' name='contact' size='100'>
        </td>
        </tr>
        <tr>
        <td style='width:20%; text-align:right' class='forumheader3'>About You:</td>
        <td style='width:' class='forumheader3'>
	        <textarea class='tbox' rows='5' cols='50' name='bio'></textarea>
        </td>
        </tr>
        <tr>
        <td style='width:20%; text-align:right' class='forumheader3'>Game Name(s) <i>(seperate each with a comma , )</i>:</td>
        <td style='width:' class='forumheader3'>
	        <textarea class='tbox' rows='2' cols='50' name='gamename'></textarea>
        </td>
        </tr>";

if ($pref['advroster_questiona'] == ""){}
else
{$text .= "<tr>
<td style='width:20%; text-align:right' class='forumheader3'>".$pref['advroster_questiona'].":</td>
<td style='width:' class='forumheader3'>
<textarea class='tbox' rows='2' cols='50' name='questiona'></textarea>
</td>
</tr>";}

if ($pref['advroster_questionb'] == ""){}
else
{$text .= "<tr>
<td style='width:20%; text-align:right' class='forumheader3'>".$pref['advroster_questionb'].":</td>
<td style='width:' class='forumheader3'>
<textarea class='tbox' rows='2' cols='50' name='questionb'></textarea>
</td>
</tr>";}

if ($pref['advroster_questionc'] == ""){}
else
{$text .= "<tr>
<td style='width:20%; text-align:right' class='forumheader3'>".$pref['advroster_questionc'].":</td>
<td style='width:' class='forumheader3'>
<textarea class='tbox' rows='2' cols='50' name='questionc'></textarea>
</td>
</tr>";}

if ($pref['advroster_questiond'] == ""){}
else
{$text .= "<tr>
<td style='width:20%; text-align:right' class='forumheader3'>".$pref['advroster_questiond'].":</td>
<td style='width:' class='forumheader3'>
<textarea class='tbox' rows='2' cols='50' name='questiond'></textarea>
</td>
</tr>";}

if ($pref['advroster_questione'] == ""){}
else
{$text .= "<tr>
<td style='width:20%; text-align:right' class='forumheader3'>".$pref['advroster_questione'].":</td>
<td style='width:' class='forumheader3'>
<textarea class='tbox' rows='2' cols='50' name='questione'></textarea>
</td>
</tr>";}


        $text .= "</div>
        </td>
		</tr>
		
        <tr style='vertical-align:top'>
        <td colspan='2' style='text-align:center' class='forumheader'>
		<input type='hidden' name='add_user' value='1'>
		<input class='button' type='submit' value='Submit Application'>
		</td>
        </tr>
</table>
<br>
</form>";

}
else
{$text .= "<i><b>Please Login or Register to fill out an Application</b></i>";}}

else

{$text .= "<i><b>Application Disabled For Advanced Roster</b></i>";}

//-------------



       
     
  $ns -> tablerender($title, $text);


  require_once(FOOTERF);



?>