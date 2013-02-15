<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/usersettings.php $
|     $Revision: 12188 $
|     $Id: usersettings.php 12188 2011-05-04 20:38:01Z e107steved $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
// Experimental e-token
if(!empty($_POST) && !isset($_POST['e-token']))
{
	// set e-token so it can be processed by class2
	$_POST['e-token'] = '';
}
define("e_NOCACHE",TRUE);
require_once("class2.php");
require_once(e_HANDLER."ren_help.php");
require_once(e_HANDLER."user_extended_class.php");
require_once(e_HANDLER."usersetting_handler.php");
$ue = new e107_user_extended;

//define("US_DEBUG",TRUE);
define("US_DEBUG",FALSE);

if (!USER) {
    header("location:".e_BASE."index.php");
    exit;
}

if ((!ADMIN || !getperms('4')) && e_QUERY && e_QUERY != 'update' )
{
    header("location:".e_BASE."usersettings.php");
    exit;
}

require_once(e_HANDLER."ren_help.php");
/*
if(is_readable(THEME."usersettings_template.php"))
{
	include_once(THEME."usersettings_template.php");
}
else
{
	include_once(e_THEME."templates/usersettings_template.php");
}
include_once(e_FILE."shortcode/batch/usersettings_shortcodes.php");*/

require_once(e_HANDLER."calendar/calendar_class.php");
$cal = new DHTML_Calendar(true);
$sesschange = '';						// Notice removal
$photo_to_delete = '';
$avatar_to_delete = '';

$inp = USERID;
$_uid = false;
if(is_numeric(e_QUERY))
{
	if(ADMIN)
	{
		$inp = (int)e_QUERY;
		$_uid = $inp;
		$info = get_user_data($inp);
		//Only site admin is able to change setting for other admins
		if(!is_array($info) || ($info['user_admin'] == 1 && (!defined('ADMINPERMS') || ADMINPERMS !== '0')))
		{
			header('location:'.e_BASE.'index.php');
  		exit;
		}
	}
	else
	{
		//Non admin attempting to edit another user's ID
		header('location:'.e_BASE.'index.php');
	  exit;
	}
}

require_once(HEADERF);


// Save user settings (whether or not changed)
//---------------------------------------------
$error = "";

if (isset($_POST['updatesettings']) && varset($_POST['e-token']))
{
	$message=$error=$caption='';
	update_settings($inp,$_uid,$tp,$message,$error,$caption);
}

if ($error)
{
	require_once(e_HANDLER."message_handler.php");
	message_handler("P_ALERT", $error);
	$adref = $_POST['adminreturn'];
}

// --- User data has been update here if appropriate ---

if(isset($message))
{
	$ns->tablerender($caption, $message);
}

// ---------------------


$uuid = ($_uid) ? $_uid : USERID;

//Get all userinfo as well as all extended fields
$qry = "SELECT u.*, ue.* FROM #user AS u
LEFT JOIN #phpbb_profile_fields_data AS ue ON ue.user_id = u.user_id
WHERE u.user_id='".intval($uuid)."'
";

$sql->db_Select_gen($qry);
$curVal=$sql->db_Fetch();
$curVal['userclass_list'] = addCommonClasses($curVal);
//Position of where template is discovered changed.
if(is_readable(THEME."usersettings_template.php"))
{
    include_once(THEME."usersettings_template.php");
}
else
{
    include_once(e_THEME."templates/usersettings_template.php");
}
include_once(e_FILE."shortcode/batch/usersettings_shortcodes.php");


if($_POST && $error)
{     // Fix for all the values being lost when an error occurred.
	foreach($_POST as $key => $val)
	{
		$curVal["user_".$key] = $tp->post_toForm($val);
	}
	foreach($_POST['ue'] as $key => $val)
	{
		$curVal[$key] = $tp->post_toForm($val);
	}
}

require_once(e_HANDLER."extended_fields.php");
$ef= new ExtendedFields;

require_once(e_HANDLER."form_handler.php");
$rs = new form;

$text = (e_QUERY ? $rs->form_open("post", e_SELF."?".e_QUERY, "dataform", "", " enctype='multipart/form-data'") : $rs->form_open("post", e_SELF, "dataform", "", " enctype='multipart/form-data'"));

if(e_QUERY == "update")
{
	$text .= "<div class='fborder' style='text-align:center'><br />".str_replace("*","<span style='color:red'>*</span>",LAN_USET_9)."<br />".LAN_USET_10."<br /><br /></div>";
}


$text .= $tp->parseTemplate($USERSETTINGS_EDIT, TRUE, $usersettings_shortcodes);
$text .= "<div>";

$text .= "
	<input type='hidden' name='_uid' value='{$uuid}' />
	<input type='hidden' name='e-token' value='".e_TOKEN."' style='width:100%' />
	</div>
	</form>
	";

$ns->tablerender(LAN_155, $text);

deleteExpired(ADMIN);			// This will clean up the user and user_extended databases

require_once(FOOTERF);



// Delete 'expired' user records, clean up user_extended DB
function deleteExpired($force = FALSE)
{
	global $pref, $sql;
	$temp1 = 0;
	if (isset($pref['del_unv']) && $pref['del_unv'] && $pref['user_reg_veri'] != 2)
	{
		$threshold= intval(time() - ($pref['del_unv'] * 60));
		if (($temp1 = $sql->db_Delete('user', 'user_ban = 2 AND user_join < '.$threshold)) > 0) { $force = TRUE; }
	}
	if ($force)
	{	// Remove 'orphaned' extended user field records
		$sql->db_Select_gen("DELETE `#user_extended` FROM `#user_extended` LEFT JOIN `#user` ON `#user_extended`.`user_extended_id` = `#user`.`user_id`
				WHERE `#user`.`user_id` IS NULL");
	}
	return $temp1;
}


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function req($field) {
	global $pref;
	if ($field == 2)
	{
		$ret = "<span style='text-align:right;font-size:15px; color:red'> *</span>";
	}
	else
	{
		$ret = "";
	}
	return $ret;
}
//---------------------------------------------------------------------------------

// Delete a file from the public directories. Return TRUE on success, FALSE on failure.
// Also deletes from database if appropriate.
function delete_file($fname, $dir = 'avatars/')
{
  global $sql;
  if (!$fname) return FALSE;

  if (preg_match("#Binary (.*?)/#", $fname, $match))
  {
	return $sql -> db_Delete("rbinary", "binary_id='".$tp -> toDB($match[1])."'");
  }
  elseif (file_exists(e_FILE."public/".$dir.$fname))
  {
	unlink(e_FILE."public/".$dir.$fname);
	return TRUE;
  }
  return FALSE;
}


function headerjs() {
	global $cal;
	$script = "<script type=\"text/javascript\">
		function addtext_us(sc){
		document.getElementById('dataform').image.value = sc;
		}

		</script>\n";

	$script .= $cal->load_files();
	return $script;
}
?>
