<?php


/*
##########################
# AACGC Advaced Roster   #
# M@CH!N3                #
# www.aacgc.com          #
# admin@aacgc.com        #
##########################
*/



require_once("../../class2.php");
if (!defined('e107_INIT'))
{exit;}
if (!getperms("P"))
{header("location:" . e_HTTP . "index.php");
exit;}
require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{define(ADMIN_WIDTH, "width:100%;");}

if (e_QUERY == "update")
{
    $pref['advrank_profile_img'] = $_POST['advrank_profile_img'];
    $pref['advrank_forum_img'] = $_POST['advrank_forum_img'];
    $pref['advrankmenu_title'] = $_POST['advrankmenu_title'];
    $pref['advrankmenu_height'] = $_POST['advrankmenu_height'];
    $pref['advrankmenu_speed'] = $_POST['advrankmenu_speed'];
    $pref['advrankmenu_mouseoverspeed'] = $_POST['advrankmenu_mouseoverspeed'];
    $pref['advrankmenu_mouseoutspeed'] = $_POST['advrankmenu_mouseoutspeed'];
    $pref['advrankmenu_img'] = $_POST['advrankmenu_img'];
    $pref['advrank_flag_img'] = $_POST['advrank_flag_img'];
    $pref['advrank_main_img'] = $_POST['advrank_main_img'];
    $pref['advrankfont_title'] = $_POST['advrankfont_title'];
    $pref['advcatfont_title'] = $_POST['advcatfont_title'];
    $pref['advrankfont_detail'] = $_POST['advrankfont_detail'];
    $pref['advuserfont_detail'] = $_POST['advuserfont_detail'];
    $pref['advrank_main_title'] = $_POST['advrank_main_title'];
    $pref['advnumrank'] = $_POST['advnumrank'];
    $pref['advrank_avatar_size'] = $_POST['advrank_avatar_size'];
    $pref['advrankmenu_avatar_size'] = $_POST['advrankmenu_avatar_size'];
    $pref['advrank_rankorderby'] = $_POST['advrank_rankorderby'];
    $pref['advrank_rankorder'] = $_POST['advrank_rankorder'];
    $pref['advrank_catrankorderby'] = $_POST['advrank_catrankorderby'];
    $pref['advrank_catrankorder'] = $_POST['advrank_catrankorder'];
    $pref['advclandetailstitle'] = $_POST['advclandetailstitle'];
    $pref['advclandetails'] = $_POST['advclandetails'];
    $pref['advappdetails'] = $_POST['advappdetails'];
    $pref['advappdetailstitle'] = $_POST['advappdetailstitle'];
    $pref['advrxf_skin'] = $_POST['advrxf_skin'];

$pref['advroster_questiona'] = $_POST['advroster_questiona'];
$pref['advroster_questionb'] = $_POST['advroster_questionb'];
$pref['advroster_questionc'] = $_POST['advroster_questionc'];
$pref['advroster_questiond'] = $_POST['advroster_questiond'];
$pref['advroster_questione'] = $_POST['advroster_questione'];


if (isset($_POST['advrank_enable_gold'])) 
{$pref['advrank_enable_gold'] = 1;}
else
{$pref['advrank_enable_gold'] = 0;}


if (isset($_POST['advrank_enable_forum'])) 
{$pref['advrank_enable_forum'] = 1;}
else
{$pref['advrank_enable_forum'] = 0;}

if (isset($_POST['advrank_enable_profile'])) 
{$pref['advrank_enable_profile'] = 1;}
else
{$pref['advrank_enable_profile'] = 0;}

if (isset($_POST['advrank_enable_game'])) 
{$pref['advrank_enable_game'] = 1;}
else
{$pref['advrank_enable_game'] = 0;}

if (isset($_POST['advrank_enable_age'])) 
{$pref['advrank_enable_age'] = 1;}
else
{$pref['advrank_enable_age'] = 0;}

if (isset($_POST['advrank_enable_status'])) 
{$pref['advrank_enable_status'] = 1;}
else
{$pref['advrank_enable_status'] = 0;}

if (isset($_POST['advrank_enable_loccol'])) 
{$pref['advrank_enable_loccol'] = 1;}
else
{$pref['advrank_enable_loccol'] = 0;}

if (isset($_POST['advrank_enable_locflag'])) 
{$pref['advrank_enable_locflag'] = 1;}
else
{$pref['advrank_enable_locflag'] = 0;}

if (isset($_POST['advrank_enable_location'])) 
{$pref['advrank_enable_location'] = 1;}
else
{$pref['advrank_enable_location'] = 0;}

if (isset($_POST['advrank_enable_djoined'])) 
{$pref['advrank_enable_djoined'] = 1;}
else
{$pref['advrank_enable_djoined'] = 0;}

if (isset($_POST['advrank_enable_bday'])) 
{$pref['advrank_enable_bday'] = 1;}
else
{$pref['advrank_enable_bday'] = 0;}

if (isset($_POST['advrank_enable_xfire'])) 
{$pref['advrank_enable_xfire'] = 1;}
else
{$pref['advrank_enable_xfire'] = 0;}

if (isset($_POST['advrank_enable_xfirename'])) 
{$pref['advrank_enable_xfirename'] = 1;}
else
{$pref['advrank_enable_xfirename'] = 0;}

if (isset($_POST['advrank_enable_xfireimg'])) 
{$pref['advrank_enable_xfireimg'] = 1;}
else
{$pref['advrank_enable_xfireimg'] = 0;}

if (isset($_POST['advrank_enable_avatar'])) 
{$pref['advrank_enable_avatar'] = 1;}
else
{$pref['advrank_enable_avatar'] = 0;}

if (isset($_POST['advrankmenu_enable_avatar'])) 
{$pref['advrankmenu_enable_avatar'] = 1;}
else
{$pref['advrankmenu_enable_avatar'] = 0;}

if (isset($_POST['advrank_enable_theme'])) 
{$pref['advrank_enable_theme'] = 1;}
else
{$pref['advrank_enable_theme'] = 0;}

if (isset($_POST['advrank_enable_application'])) 
{$pref['advrank_enable_application'] = 1;}
else
{$pref['advrank_enable_application'] = 0;}

if (isset($_POST['advrank_enable_autoscroll'])) 
{$pref['advrank_enable_autoscroll'] = 1;}
else
{$pref['advrank_enable_autoscroll'] = 0;}

    save_prefs();
    $led_msgtext = "Settings Saved";
}

$admin_title = "AACGC Advaced Roster (Settings)";
//--------------------------------------------------------------------


$text .= "
<form method='post' action='" . e_SELF . "?update' id='confadvmedsys'>
	<table style='" . ADMIN_WIDTH . "' class='fborder'>


<tr>
</tr>
		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Main Settings:</b></font></td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Use Alternate Theme:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_theme'] == 1 ? "<input type='checkbox' name='advrank_enable_theme' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_theme' value='0' />")."</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Roster Main Page Title:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='25' name='advrank_main_title' value='" . $tp->toFORM($pref['advrank_main_title' ]) . "' /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Roster Top Detail Title:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='50' name='advclandetailstitle' value='" . $tp->toFORM($pref['advclandetailstitle']) . "' /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Roster Top Detail Information:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='50' name='advclandetails' value='" . $tp->toFORM($pref['advclandetails']) . "' /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Category Title Font Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advcatfont_title' value='" . $tp->toFORM($pref['advcatfont_title']) . "' />px  (pixles)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Order Categories By:</td>
                        <td style='width:' class=''>
                        <select name='advrank_catrankorderby' size='1' class='tbox' style='width:50%'>
                        <option name='advrank_catrankorderby' value='cat_id'>Cat ID</option>
                        <option name='advrank_catrankorderby' value='cat_name'>Cat Name</option>
                        </td>
			<td style='width:' class=''>
                        <select name='advrank_catrankorder' size='1' class='tbox' style='width:50%'>
                        <option name='advrank_catrankorder' value='ASC'>ASC</option>
                        <option name='advrank_catrankorder' value='DESC'>DESC</option>
                        </td>
                </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Image Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrank_main_img' value='" . $tp->toFORM($pref['advrank_main_img']) . "' />px  (pixles)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Title Font Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankfont_title' value='" . $tp->toFORM($pref['advrankfont_title']) . "' />px  (pixles)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Order Ranks By:</td>
                        <td style='width:' class=''>
                        <select name='advrank_rankorderby' size='1' class='tbox' style='width:50%'>
                        <option name='advrank_rankorderby' value='rank_id'>Rank ID</option>
                        <option name='advrank_rankorderby' value='rank_name'>Rank Name</option>
                        </td>
			<td style='width:' class=''>
                        <select name='advrank_rankorder' size='1' class='tbox' style='width:50%'>
                        <option name='advrank_rankorder' value='ASC'>ASC</option>
                        <option name='advrank_rankorder' value='DESC'>DESC</option>
                        </td>
                </tr>


		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Enable / Disable Columns: (Extended User Fields Required On Some Columns)</b></font></td>
		</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Enable Application:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_application'] == 1 ? "<input type='checkbox' name='advrank_enable_application' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_application' value='0' />")."</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show User's Avatar:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_avatar'] == 1 ? "<input type='checkbox' name='advrank_enable_avatar' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_avatar' value='0' />")."</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>User Avatar Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrank_avatar_size' value='" . $tp->toFORM($pref['advrank_avatar_size'])."' />px  (If enabled above)</td>
		</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Game:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_game'] == 1 ? "<input type='checkbox' name='advrank_enable_game' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_game' value='0' />")."(If your Clan Plays More Than 1 Game)</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Age:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_age'] == 1 ? "<input type='checkbox' name='advrank_enable_age' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_age' value='0' />")." (Age Entered Manually When Given Rank, Disable If Birth Date Column Is Enabled)</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Status:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_status'] == 1 ? "<input type='checkbox' name='advrank_enable_status' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_status' value='0' />")."</td>
	        </tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Location:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_loccol'] == 1 ? "<input type='checkbox' name='advrank_enable_loccol' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_loccol' value='0' />")."</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Location Flag:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_locflag'] == 1 ? "<input type='checkbox' name='advrank_enable_locflag' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_locflag' value='0' />")." (Flag Chosen Manually When Given Rank)</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Location Flag Image Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrank_flag_img' value='" . $tp->toFORM($pref['advrank_flag_img']) . "' />px  (If Enabled)</td>
		</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Location:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_location'] == 1 ? "<input type='checkbox' name='advrank_enable_location' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_location' value='0' />")." (Extended Location Field)</td>
	        </tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Join Date:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_djoined'] == 1 ? "<input type='checkbox' name='advrank_enable_djoined' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_djoined' value='0' />")." (Date Entered Manually When Given Rank)</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Birth Date With Age:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_bday'] == 1 ? "<input type='checkbox' name='advrank_enable_bday' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_bday' value='0' />")." (Extended Birthday Field)</td>
	        </tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Xfire:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_xfire'] == 1 ? "<input type='checkbox' name='advrank_enable_xfire' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_xfire' value='0' />")." (Extended Field user_xfire)</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Xfire:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_xfirename'] == 1 ? "<input type='checkbox' name='advrank_enable_xfirename' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_xfirename' value='0' />")." (If Xfire Column Enabled)</td>
	        </tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Xfire Online Image:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_xfireimg'] == 1 ? "<input type='checkbox' name='advrank_enable_xfireimg' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_xfireimg' value='0' />")." (If Xfire Column Enabled)</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Choose Xfire Skin:</td>
                        <td style='width:' class=''>
                        <select name='advrxf_skin' size='1' class='tbox' style='width:50%'>
                        <option name='advrxf_skin' value='".$pref['advrxf_skin']."'> ".$pref['advrxf_skin']."</option>
                        <option name='advrxf_skin' value='Xfire Default'>Xfire Default</option>
                        <option name='advrxf_skin' value='Sci-fi'>Sci-fi</option>
                        <option name='advrxf_skin' value='Shadow'>Shadow</option>
                        <option name='advrxf_skin' value='Combat'>Combat</option>
                        <option name='advrxf_skin' value='Fantasy'>Fantasy</option>
                        </td>
		<tr>



		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Application Settings:</b></font></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Title:</td>
			<td colspan='2'  class='forumheader3'>
                        <input class='tbox' type='text' size='50' name='advappdetailstitle' value='" . $tp->toFORM($pref['advappdetailstitle']) . "' />
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Detail Information:</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='10' cols='100' name='advappdetails'>" . $tp->toFORM($pref['advappdetails']) . "</textarea>
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Custom Question 1 (optional):</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='3' cols='100' name='advroster_questiona'>" . $tp->toFORM($pref['advroster_questiona']) . "</textarea>
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Custom Question 2 (optional):</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='3' cols='100' name='advroster_questionb'>" . $tp->toFORM($pref['advroster_questionb']) . "</textarea>
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Custom Question 3 (optional):</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='3' cols='100' name='advroster_questionc'>" . $tp->toFORM($pref['advroster_questionc']) . "</textarea>
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Custom Question 4 (optional):</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='3' cols='100' name='advroster_questiond'>" . $tp->toFORM($pref['advroster_questiond']) . "</textarea>
                        </td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Application Custom Question 5 (optional):</td>
			<td colspan='2'  class='forumheader3'>
                        <textarea class='tbox' rows='3' cols='100' name='advroster_questione'>" . $tp->toFORM($pref['advroster_questione']) . "</textarea>
                        </td>
		</tr>



		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Menu Settings:</b></font></td>
		</tr>
<tr>
</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Menu Title:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='50' name='advrankmenu_title' value='" . $tp->toFORM($pref['advrankmenu_title']) . "' /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Menu Height:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_height' value='" . $tp->toFORM($pref['advrankmenu_height']) . "' />px  (pixles)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Rank Menu Image size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_img' value='" . $tp->toFORM($pref['advrankmenu_img']) . "' />px  (pixles)</td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show User's Avatar:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrankmenu_enable_avatar'] == 1 ? "<input type='checkbox' name='advrankmenu_enable_avatar' value='1' checked='checked' />" : "<input type='checkbox' name='advrankmenu_enable_avatar' value='0' />")."</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>User Avatar Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_avatar_size' value='" . $tp->toFORM($pref['advrankmenu_avatar_size'])."' />px  (If enabled)</td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Enable Auto Scroll:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_autoscroll'] == 1 ? "<input type='checkbox' name='advrank_enable_autoscroll' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_autoscroll' value='0' />")."</td>
	        </tr>

		<tr>
			<td style='width:30%' class='forumheader3'>Scroll Speed On Start:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_speed' value='" . $tp->toFORM($pref['advrankmenu_speed']) . "' />  (1 for slow, 10 for fast)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Scroll Speed On Mouseover:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_mouseoverspeed' value='" . $tp->toFORM($pref['advrankmenu_mouseoverspeed']) . "' />  (1 for slow, 10 for fast, 0 for it to stop)</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Scroll Speed On Mouseout:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrankmenu_mouseoutspeed' value='" . $tp->toFORM($pref['advrankmenu_mouseoutspeed']) . "' />  (1 for slow, 10 for fast)</td>
		</tr>








<tr>
</tr>
		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Forums Settings:</b></font></td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Rank In Forums:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_forum'] == 1 ? "<input type='checkbox' name='advrank_enable_forum' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_forum' value='0' />")."</td>
	        </tr>
                <tr>
			<td style='width:30%' class='forumheader3'>Number of Ranks To Show:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advnumrank' value='".$tp->toFORM($pref['advnumrank'])."' /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Forum Rank Image Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrank_forum_img' value='".$tp->toFORM($pref['advrank_forum_img'])."' />px</td>
		</tr>




<tr>
</tr>
		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Profile Settings:</b></font></td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Show Rank In Profiles:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_profile'] == 1 ? "<input type='checkbox' name='advrank_enable_profile' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_profile' value='0' />")."</td>
	        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>Profile Rank Image Size:</td>
			<td colspan='2'  class='forumheader3'><input class='tbox' type='text' size='10' name='advrank_profile_img' value='".$tp->toFORM($pref['advrank_profile_img'])."' />px</td>
		</tr>





<tr>
</tr>
		<tr>
			<td colspan='3' class='fcaption'><font size='2'><b>Gold System Support:</b></font></td>
		</tr>
<tr>
</tr>
                <tr>
		        <td style='width:30%' class='forumheader3'>Enable Gold Orbs:</td>
		        <td colspan=2 class='forumheader3'>".($pref['advrank_enable_gold'] == 1 ? "<input type='checkbox' name='advrank_enable_gold' value='1' checked='checked' />" : "<input type='checkbox' name='advrank_enable_gold' value='0' />")."(shows orbs, must have gold sytem 4.x and gold orbs 1.x installed)</td>
	        </tr>








<tr>
</tr>

                <tr>
			<td colspan='3' class='fcaption' style='text-align: left;'><center><input type='submit' name='update' value='Save Settings' class='button' /></td>
		</tr>





</table>
</form>";





$ns->tablerender($admin_title, $text);
require_once(e_ADMIN . "footer.php");
?>
