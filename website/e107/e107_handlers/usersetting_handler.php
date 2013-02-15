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
Additional coding by Kris Sherrerd. Copyright (C) 2011

*/
if (!defined('e107_INIT'))
{
	exit("e107 is not loaded!");
}



function update_settings($inp,$_uid,$tp,&$message='',&$error='',&$caption=''){
	global $sql,$e_event, $pref;

    IncludePHPBB(true);

	if(!varsettrue($pref['auth_method']) || $pref['auth_method'] == '>e107')
	{
		$pref['auth_method'] = 'e107';
	}

	if($pref['auth_method'] != 'e107')
	{
		$_POST['password1'] = '';
		$_POST['password2'] = '';
	}

	$udata = get_user_data($inp);				// Get all the user data, including any extended fields
	$peer = ($inp == USERID ? false : true);
	$udata['user_classlist'] = addCommonClasses($udata);


	// Check external avatar 1
  /*  $avName = isset($_POST['uploadfile']) ||	isset($_POST['uploadurl']) || isset($_POST['remotelink'])? true:false;
	if ($avName)
	{
        include_once(PHPBB_ROOT_PATH.'includes/functions_user.php');
        //Not the last fasle is because uploading is NOT allowed.  Will work a better avatar system into place.
        if(avatar_process_user($error, false, false)){
            //$error = LAN_USET_20;
            echo 'bob';
            print_r($error);
            die();
        }
        print_r($error);

        /*
		$avName = strip_if_magic($avName);
		$avName = str_replace(array('\'', '"', '(', ')'), '', $avName);   // these are invalid anyway, so why allow them? (XSS Fix)
		if (strpos($avName, '/') !== FALSE)
		{	// Assume an off-site image
			$avName = checkRemoteImage($avName);
			if ($avName === FALSE)
			{
				$avmsg = LAN_USET_18;
			}
			$avFullName = $avName;
		}
		else
		{	// Its one of the standard choices
			$avName = $tp -> toDB($avName);
			if (strpos($avName, '-upload-') === 0)
			{	// Uploaded avatar
				$avFullName = e_FILE.'public/avatars/'.str_replace('-upload-', '', $avName);
			}
			else
			{	// Site-provided avatar
				$avFullName = e_IMAGE.'avatars/'.$avName;
			}
			if (!is_readable($avFullName))
			{
				$avmsg = LAN_USET_19.': '.$avFullName;			// Error accessing avatar
				$avName = FALSE;
			}
		}
		if ($avmsg)
		{
			$error = $avmsg;
		}
		elseif (FALSE !== ($size = getimagesize($avFullName)))
		{
			$avwidth = $size[0];
			$avheight = $size[1];
			$avmsg = '';

			$pref['im_width'] = ($pref['im_width']) ? $pref['im_width'] : 120;
			$pref['im_height'] = ($pref['im_height']) ? $pref['im_height'] : 100;
			if ($avwidth > $pref['im_width']) 
			{
				$avmsg .= LAN_USET_1." ($avwidth)<br />".LAN_USET_2.": {$pref['im_width']}<br /><br />";
			}
			if ($avheight > $pref['im_height']) 
			{
				$avmsg .= LAN_USET_3." ($avheight)<br />".LAN_USET_4.": {$pref['im_height']}";
			}
			if ($avmsg) 
			{
				$error = $avmsg;
				$avName = '';
			}
			else
			{
				$_POST['image'] = $avName;
			}
		}
		else
		{
			$error = LAN_USET_20.': '.$avFullName;
			$avName = FALSE;
		}
	}*/  //end avatar 1

	$signup_option_title = array(LAN_308, LAN_120, LAN_121, LAN_122, LAN_USET_6);
	$signup_option_names = array("realname", "signature", "image", "timezone", "class");

	foreach($signup_option_names as $key => $value)
	{  // Check required signup fields
		if ($pref['signup_option_'.$value] == 2 && !$_POST[$value] && !$_uid)
		{
			$error .= LAN_SIGNUP_6.$signup_option_title[$key].LAN_SIGNUP_7."\\n";
		}
    }

    $_POST['loginname']=request_var('loginname', false);
// Login Name checks
	if (isset($_POST['loginname']) && $_POST['loginname']!=false)
	{  // Only check if its been edited %*|/|&nbsp;|\#|\=|\$%
		// another option would be /[^\w\pL\.]/u (non latin words)
	//	$temp_name = trim(preg_replace('#[^a-z0-9_\.]#i', "", strip_tags($_POST['loginname'])));
	// The above preg_replace will break any non-latin login and should not be used. 
	
		$temp_name = str_replace('--', '', trim(preg_replace("/[\^\*\|\/;:#=\$'\"!#`\s\(\)%\?<>\\{}]/", '', strip_tags($_POST['loginname']))));
		if ($temp_name != $_POST['loginname'])
		{
			$error .= LAN_USET_13."\\n";
		}
		// Check if login name exceeds maximum allowed length
		if (strlen($temp_name) > varset($pref['loginname_maxlength'],30))
		{
			$error .= LAN_USET_14."\\n";
		}
		if ((strcasecmp($_POST['loginname'],"Anonymous") == 0) || (strcasecmp($_POST['loginname'],LAN_ANONYMOUS) == 0))
		{
			$error .= LAN_USET_11."\\n";
		}
		$_POST['loginname'] = $temp_name;
	}


// Password checks
	$pwreset = '';
	if ($_POST['password1'] != $_POST['password2']) {
		$error .= LAN_105."\\n";
	}
	else
	{
		if(trim($_POST['password1']) != "")
		{
			$pwreset = "user_password = '".md5(trim($_POST['password1']))."', ";
		}
	}

	if(isset($pref['signup_disallow_text']))
	{
	  $tmp = explode(",", $pref['signup_disallow_text']);
	  foreach($tmp as $disallow)
	  {
		if (($disallow != '') && strstr($_POST['username'], $disallow))
		{
		  $error .= LAN_USET_11."\\n";
		}
	  }
	}

	if (strlen(trim($_POST['password1'])) < $pref['signup_pass_len'] && trim($_POST['password1']) != "") {
		$error .= LAN_SIGNUP_4.$pref['signup_pass_len'].LAN_SIGNUP_5."\\n";
		$password1 = "";
		$password2 = "";
	}


	// Always validate an email address if entered. If its blank, that's OK if checking disabled
	$_POST['email'] = $tp->toDB(trim(varset($_POST['email'],'')));
	$do_email_validate = (!varset($pref['disable_emailcheck'],FALSE)) || ($_POST['email'] !='');
	if ($do_email_validate)
	{
		if  (!check_email($_POST['email']))
		{
			$error .= LAN_106."\\n";
		}

		// Check Email address against banlist.
		$wc = make_email_query($_POST['email']);
		if ($wc) $wc = ' OR '.$wc;

		if (($wc === FALSE) || ($do_email_validate && $sql->db_Select("banlist", "*", "banlist_ip='".$_POST['email']."'".$wc)))
		{
			$error .= LAN_106."\\n";
		}


		// Check for duplicate of email address (always)
		if ($sql->db_Select("user", "user_name, user_email", "user_email='".$_POST['email']."' AND user_id !='".intval($inp)."' "))
		{
			$error .= LAN_408."\\n";
		}
	}


// Display name checks
	if (check_class($pref['displayname_class']) && isset($_POST['username']))
	{
	  // Impose a minimum length on display name
	  $username = trim(strip_tags($_POST['username']));
	  if (strlen($username) < 2)
	  {
		$error .= LAN_USET_12."\\n";
	  }
	  if (strlen($username) > varset($pref['displayname_maxlength'],15))
	  {
		$error .= LAN_USET_15."\\n";
	  }

	// Display Name exists.
	  if ($sql->db_Count("user", "(*)", "WHERE `user_name`='".$username."' AND `user_id` != '".intval($inp)."' "))
	  {
		$error .= LAN_USET_17;
	  }
	}


// Uploaded avatar and/or photo
	$user_sess = "";
    //begin avatar 2
    $avatar_to_delete=fasle;
	/*if ($file_userfile['error'] != 4)
	{
		require_once(e_HANDLER."upload_handler.php");
		require_once(e_HANDLER."resize_handler.php");

		if ($uploaded = file_upload(e_FILE."public/avatars/", "avatar=".$udata['user_id']))
		{
		  foreach ($uploaded as $upload)
		  {	// Needs the latest upload handler (with legacy and 'future' interfaces) to work
			if ($upload['name'] && ($upload['index'] == 'avatar') && $pref['avatar_upload'])
			{
				// avatar uploaded - give it a reference which identifies it as server-stored
				$_POST['image'] = "-upload-".$upload['name'];
				if ($_POST['image'] != $udata['user_image'])
				{
				  $avatar_to_delete = str_replace("-upload-", "", $udata['user_image']);
//				  echo "Avatar change; deleting {$avatar_to_delete}<br />";
				}
				if (!resize_image(e_FILE."public/avatars/".$upload['name'], e_FILE."public/avatars/".$upload['name'], "avatar"))
				{
					unset($message);
					$error .= RESIZE_NOT_SUPPORTED."\\n";
					@unlink(e_FILE."public/avatars/".$upload['name']);
					$_POST['image'] = '';
				}
			}

			if ($upload['name'] && ($upload['index'] == 'photo') && $pref['photo_upload'] )
			{
				// photograph uploaded
				$user_sess = $upload['name'];
				if (!resize_image(e_FILE."public/avatars/".$user_sess, e_FILE."public/avatars/".$user_sess, 180))
				{
					unset($message);
					$error .= RESIZE_NOT_SUPPORTED."\\n";
					@unlink(e_FILE."public/avatars/".$user_sess);
					$user_sess = '';
				}
			}
		  }
		}
	}*/  // end avatar 2

// See if user just wants to delete existing photo
	if (isset($_POST['user_delete_photo']))
	{
	  $photo_to_delete = $udata['user_sess'];
	  $sesschange = "user_sess = '', ";
//	  echo "Just delete old photo: {$photo_to_delete}<br />";
	}
	elseif ($user_sess != "")
	{	// Update DB with photo
	  $sesschange = "user_sess = '".$tp->toDB($user_sess)."', ";
	  if ($udata['user_sess'] == $tp->toDB($user_sess))
	  {
		$sesschange = '';			// Same photo - do nothing
//		echo "Photo not changed<br />";
	  }
	  else
	  {
		$photo_to_delete = $udata['user_sess'];
//		echo "New photo: {$user_sess} Delete old photo: {$photo_to_delete}<br />";
	  }
	}


    // Validate Extended User Fields.

/*	$ue_fields = "";
	if($_POST['ue'])
	{
		if ($sql->db_Select('user_extended_struct', '*', 'order by user_extended_struct_type', 'order'))		// Get both field and category definitions
		{
			$skipCat = array();
			while($row = $sql->db_Fetch())
			{
				if($row['user_extended_struct_type']) 
				{	// Its a field
					$extList["user_".$row['user_extended_struct_name']] = $row;
				}
				// else its a category
				elseif(!check_class($row['user_extended_struct_applicable']) || !check_class($row['user_extended_struct_write'])) 
				{
					$skipCat[] = $row['user_extended_struct_id'];
				}
			}
		}

		foreach ($extList as $key => $settings)
		{	// Only process field if its in a category relevant to this user, and this user should be able to change it
			if (!in_array($settings['user_extended_struct_parent'],$skipCat) && check_class($settings['user_extended_struct_applicable']) && check_class($settings['user_extended_struct_write']))
			{
				$val = '';
				if (isset($_POST['ue'][$key])) $val = $_POST['ue'][$key]; 
				$err = $ue->user_extended_validate_entry($val,$settings);
				if($err === TRUE && !$_uid)
				{  // General error - usually empty field; could be unacceptable value, or regex fail and no error message defined
					$error .= LAN_SIGNUP_6.($tp->toHtml($settings['user_extended_struct_text'],FALSE,'defs')).' '.LAN_SIGNUP_7."\\n";
				}
				elseif ($err)
				{	// Specific error message returned - usually regex fail
					$error .= $err."\\n";
					$err = TRUE;
				}
				if(!$err)
				{
					$val = $tp->toDB($val);
					$ue_fields .= ($ue_fields) ? ", " : "";
					$ue_fields .= $key."='".$val."'";
				}
			}
		}

		$ueHide = array();
		foreach (array_keys($_POST['hide']) as $key)
		{
			if (isset($extList[$key]))
			{
				$ueHide[] = $tp->toDB($key);
			}
		}
    }*/


// All validated here
// ------------------
    /*debug
    echo 'Are we saving?';
    echo $error; //*/
// $inp - UID of user whose data is being changed (may not be the currently logged in user)
	if ($error)
	{
	  unset($_POST['password1']);
	  unset($_POST['password2']);
	  
      $_POST['user_id'] = intval($inp);
	  $ret = $e_event->trigger("preuserset", $_POST);

	  if(trim($_POST['user_xup']) != "")
	  {
		if($sql->db_Select('user', 'user_xup', "user_id = '".intval($inp)."'"))
		{
		  $row = $sql->db_Fetch();
		  $update_xup = ($row['user_xup'] != $_POST['user_xup']) ? TRUE : FALSE;
		}
	  }

	  if ($ret == '')
	  {
		$loginname = strip_tags($_POST['loginname']);
		if (!$loginname)
		{
		  $loginname = $udata['user_loginname'];
		}
		else
		{
		  if(!check_class($pref['displayname_class'], $udata['user_classlist'], $peer))
		  {
			$new_username = "user_name = '{$loginname}', ";
			$username = $loginname;
		  }
		}

//			if (isset($_POST['username']) && check_class($pref['displayname_class']))
		if (isset($_POST['username']) && check_class($pref['displayname_class'], $udata['user_classlist'], $peer))
		{	// Allow change of display name if in right class
		  $username = trim(strip_tags($_POST['username']));
		  $username = $tp->toDB(substr($username, 0, $pref['displayname_maxlength']));
		  $new_username = "user_name = '{$username}', ";
		}


		$_POST['signature'] = $tp->toDB($_POST['signature']);
		$_POST['realname'] = $tp->toDB($_POST['realname']);

		$new_customtitle = "";
		if(isset($_POST['customtitle']) && ($pref['forum_user_customtitle'] || ADMIN))
		{   //todo: Delete as now using PHPBB ranking instead of a custom tag.  Though possiblity for this still possible wih some modification.
			$new_customtitle = ", user_customtitle = '".$tp->toDB($_POST['customtitle'])."' ";
		}


		// Extended fields - handle any hidden fields
	/*	if($ue_fields)
		{
			$hiddenFields = implode("^", $ueHide);
			if($hiddenFields != "")
			{
				$hiddenFields = "^".$hiddenFields."^";
			}
			$ue_fields .= ", user_hidden_fields = '".$hiddenFields."'";
		}*/


		// We can update the basic user record now
		$sql->db_Update("user", "{$new_username} {$pwreset} {$sesschange} user_email='".$tp -> toDB($_POST['email'])."', user_signature='".$_POST['signature']."', user_image='".$tp -> toDB($_POST['image'])."', user_timezone='".$tp -> toDB($_POST['timezone'])."', user_hideemail='".intval($tp -> toDB($_POST['hideemail']))."', user_login='".$_POST['realname']."', user_xup='".$tp -> toDB($_POST['user_xup'])."' WHERE user_id='".intval($inp)."' ");

		
		if ($photo_to_delete)
		{	// Photo may be a flat file, or in the database
		  delete_file($photo_to_delete);
		}
		if ($avatar_to_delete)
		{	// Avatar may be a flat file, or in the database
		  delete_file($avatar_to_delete);
		}


		// If user has changed display name, update the record in the online table
		if(isset($username) && ($username != USERNAME) && !$_uid)
		{
		  $sql->db_Update("online", "online_user_id = '".USERID.".".$username."' WHERE online_user_id = '".USERID.".".USERNAME."'");
		}


		// Only admins can update login name
		if(ADMIN && getperms("4"))
		{
		  $sql -> db_Update("user", "user_loginname='".$tp -> toDB($loginname)."' WHERE user_id='".intval($inp)."' ");
		}


		// Gather and Save extended field values
        $cp_data=array();
        GatherCusFields($cp_data, 'profile', 1);
		/*if($ue_fields)
		{
// ***** Next line creates a record which presumably should be there anyway, so could generate an error
		  $sql->db_Select_gen("INSERT INTO #user_extended (user_extended_id, user_hidden_fields) values ('".intval($inp)."', '')");
		  $sql->db_Update("user_extended", $ue_fields." WHERE user_extended_id = '".intval($inp)."'");
		}*/


		// Update Userclass - only if its the user changing their own data (admins can do it another way)
		if (!$_uid && $sql->db_Select("userclass_classes", "userclass_id", "userclass_editclass IN (".USERCLASS_LIST.")") && isset($_POST['class']))
		{
            global $admin_log;
		  $ucList = $sql->db_getList();			// List of classes which this user can edit
		  if (US_DEBUG) $admin_log->e_log_event(10,debug_backtrace(),"DEBUG","Usersettings test","Read editable list. Current user classes: ".$udata['user_class'],FALSE,LOG_TO_ROLLING);
			$cur_classes = explode(",", $udata['user_class']);			// Current class membership
			$newclist = array_flip($cur_classes);						// Array keys are now the class IDs
            print_r($_POST);
			// Update class list - we must take care to only change those classes a user can edit themselves
			foreach ($ucList as $c)
			{
			  $cid = $c['userclass_id'];
			  if(!in_array($cid, $_POST['class']))
			  {
				unset($newclist[$cid]);
			  }
			  else
			  {
				$newclist[$cid] = 1;
			  }
			}
			$newclist = array_keys($newclist);
			$nid = implode(',', array_diff($newclist, array('')));
			if ($nid != $udata['user_class'])
			{
			  if (US_DEBUG) $admin_log->e_log_event(10,debug_backtrace(),"DEBUG","Usersettings test","Write back classes; new list: ".$nid,FALSE,LOG_TO_ROLLING);
			  $sql->db_Update("user", "user_class='".$nid."' WHERE user_id=".intval($inp));
			}
		}


		if($update_xup == TRUE)
		{
		  require_once(e_HANDLER."login.php");
		  userlogin::update_xup($inp, $_POST['user_xup']);
		}

		$e_event->trigger("postuserset", $_POST);


		if(e_QUERY == "update")
		{
          header("Location: index.php");
		}
		$message = "<div style='text-align:center'>".LAN_150."</div>";
		$caption = LAN_151;
	  }
	  else
	  {	// Invalid data
		$message = "<div style='text-align:center'>".$ret."</div>";
		$caption = LAN_151;
	  }
	  unset($_POST);
	  return true;
	}
	else{
		return false;
	}
}



/**
 * @author e107 (Modifications done by Kris Sherrerd
 * @function e107createuser
 *
 * @params: string $eufVals, string $username, string $loginname,string $u_key
 * @returns: N/A 
 * @description: Takes the information from user registration, verifies it and adds it to the database.  Then displays appropriate information.
 */
function e107createuser($eufVals){
	global $tp,$e107,$sql,$ns,$e_event;
	
	$fp = new floodprotect;
	if ($fp->flood("user", "user_join") == FALSE)
	{
		header("location:".e_BASE."index.php");
		exit;
	}

	if ($_POST['email'] && $sql->db_Select("user", "*", "user_email='".$_POST['email']."' AND user_ban='1'")) 
	{
	  exit;
	}

	$username = $tp -> toDB(strip_tags($_POST['name']));
	$loginname = $tp -> toDB(strip_tags($_POST['loginname']));
	$time = time();
	$ip = $e107->getip();

	$ue_fields = "";
	if (count($eufVals))
	{
		foreach($eufVals as $key => $val)	// We've already ensured only valid keys here
		{
			$key = $tp->toDB($key);
			$val = $tp->toDB($val);
			$ue_fields .= ($ue_fields) ? ", " : "";
			$ue_fields .= $key."='".$val."'";
		}
	}

    $new_user = array(
        'username'		    => $username,
        'user_password'	    => md5($_POST['password1']),
        'user_email'	    => $_POST['email'],
        'group_id'		    => 0, // set in addusertophpbb
        'user_type'		    => 0, // set in addusertophpbb
        'user_ip'		    => $ip,
        'user_sig'          => $tp ->toDB($_POST['signature']),
        'user_timezone'     => (float)($tp -> toDB($_POST['timezone'])),
        'user_allow_viewemail' => $tp -> toDB($_POST['hideemail']),
        'user_ban'          =>'0',
        'user_login'        => $tp -> toDB($_POST['realname']),
        'user_xup'          => $tp -> toDB($_POST['xupexist']),
        'user_sess'         => md5(uniqid(rand(), 1)),
    );

   // $nid = $sql->db_Insert("user", "0, 0, '{$username}', '{$loginname}', '', '".md5($_POST['password1'])."', '{$u_key}', '".$_POST['email']."', '".$tp -> toDB($_POST['signature'])."', '".$tp -> toDB($_POST['image'])."', '".$tp -> toDB($_POST['timezone'])."', '".$tp -> toDB($_POST['hideemail'])."', '".$time."', '0', '".$time."', '0', '0', '0', '0', '".$ip."', '2', '0', '', '0', '0', '".$tp -> toDB($_POST['realname'])."', '', '', '', '0', '".$tp -> toDB($_POST['xupexist'])."' ");

    $nid=addusertophpbb($new_user);

	if(!$nid)
	{
		require_once(HEADERF);
		$ns->tablerender("", LAN_SIGNUP_36); //General error, user not added blah blah blah
		require_once(FOOTERF);
	}

	
	$_POST['ip'] = $ip;
	$_POST['user_id'] = $nid;

	if ($pref['user_reg_veri'])
	{
/*		// ==== Update Userclass =======>

		if ($_POST['class'])
		{
			unset($insert_class);
			sort($_POST['class']);
			$insert_class = implode(",",$_POST['class']);
			$sql->db_Update("user", "user_class='".$tp -> toDB($insert_class)."' WHERE user_id='".$nid."' ");
		}

		// ========= save extended fields into db table. =====

		if($ue_fields)
		{
			$sql->db_Select_gen("INSERT INTO #user_extended (user_extended_id) values ('{$nid}')");
			$sql->db_Update("user_extended", $ue_fields." WHERE user_extended_id = '{$nid}'");
		}
*/
		// ========== Send Email =========>

		if (($pref['user_reg_veri'] != 2) && $_POST['email'])		// Don't send if email address blank - means that its not compulsory
		{
			$eml = render_email();
			$mailheader_e107id = $eml['userid'];
			require_once(e_HANDLER."mail.php");

			if(!sendemail($_POST['email'], $eml['subject'], $eml['message'], "", "", "", $eml['attachments'], $eml['cc'], $eml['bcc'], "", "", $eml['inline-images']))
			{
				$error_message = LAN_SIGNUP_42; // There was a problem, the registration mail was not sent, please contact the website administrator.
			}
		}

		
		$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

		require_once(HEADERF);

		$srch = array("[sitename]","[email]");
		$repl = array(SITENAME,"<b>".$_POST['email']."</b>");

		if(trim($pref['signup_text_after']))
		{
			$text = str_replace($srch,$repl,$tp->toHTML($pref['signup_text_after'], TRUE, 'parse_sc,defs'))."<br />";
		}
		else
		{
			$LAN_AFTERSIGNUP = defined("LAN_SIGNUP_72") ? LAN_SIGNUP_72 : LAN_405;
			$text = ($pref['user_reg_veri'] == 2) ? LAN_SIGNUP_37 : str_replace($srch,$repl,$LAN_AFTERSIGNUP);  // Admin Approval / Email Approval
		}

		$caption_arr = array();
		$caption_arr[0] = LAN_406; // Thank you!  (No Approval).
		$caption_arr[1] = defined("LAN_SIGNUP_98") ? LAN_SIGNUP_98 : LAN_406; // Confirm Email (Email Confirmation)
		$caption_arr[2] = defined("LAN_SIGNUP_100") ? LAN_SIGNUP_100 : LAN_406; // Approval Pending (Admin Approval)
		$caption = $caption_arr[$pref['user_reg_veri']];

		if($error_message)
		{
			$text = "<br /><b>".$error_message."</b><br />";	// Just display the error message
			$caption = defined("LAN_SIGNUP_99") ? LAN_SIGNUP_99 : LAN_406; // Problem Detected  // Default for backwards compat.
		}

		$ns->tablerender($caption, $text);
		require_once(FOOTERF);
		exit;
	}
	else
	{
		require_once(HEADERF);

		if(!$sql -> db_Select("user", "user_id", "lower(user_name)=lower('{$username}') AND user_password='".md5($_POST['password1'])."'"))
		{
			$ns->tablerender("", LAN_SIGNUP_36);
			require_once(FOOTERF);
			exit;
		}
		$sql->db_Update("user", "user_ban = '0' WHERE user_id = '{$nid}'");

		// ==== Update Userclass =======
		if ($_POST['class'])
		{
			unset($insert_class);
			sort($_POST['class']);
			$insert_class = implode(",",$_POST['class']);
			$sql->db_Update("user", "user_class='".$tp -> toDB($insert_class)."' WHERE user_id='".$nid."' ");
		}
/*		// ======== save extended fields to DB table.

        if($ue_fields)
        {
            $sql->db_Select_gen("INSERT INTO #user_extended (user_extended_id) values ('{$nid}')");
            $sql->db_Update("user_extended", $ue_fields." WHERE user_extended_id = '{$nid}'");
        }
*/
		// ==========================================================
		$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

		if($pref['signup_text_after'])
		{
			$text = $tp->toHTML($pref['signup_text_after'], TRUE, 'parse_sc,defs')."<br />";
		}
		else
		{
			$text = LAN_107."&nbsp;".SITENAME.", ".LAN_SIGNUP_12."<br /><br />".LAN_SIGNUP_13;
		}
		$ns->tablerender(LAN_SIGNUP_8,$text);
		require_once(FOOTERF);
		exit;
	}
}


/**
 * @param array $udata
 * @return string $tmp
 * Given an array of user data, return a comma separated string which includes public, admin, member classes etc as
 * appropriate.
 */
function addCommonClasses($udata)
{
    $tmp = array();
    if ($udata['user_class'] != ""){
        $tmp = explode(",", $udata['user_class']);
    }
    $tmp[] = e_UC_MEMBER;
    $tmp[] = e_UC_READONLY;
    $tmp[] = e_UC_PUBLIC;
    if (($udata['user_admin'] == 1) || ADMIN)
    {
        $tmp[] = e_UC_ADMIN;
    }
    if ((strpos($udata['user_perms'],'0') === 0) || getperms('0'))
    {
        $tmp[] = e_UC_MAINADMIN;
    }
    return implode(",", $tmp);
}



/**
 *	Does some basic checks on a string claiming to represent an off-site image
 *
 *	@param string $imageName
 *
 *	@return boolean|string FALSE for unacceptable, potentially modified string if acceptable
 */
function checkRemoteImage($imageName)
{
	$newImageName = trim(str_replace(array('\'', '"', '(', ')'), '', $imageName));		// Strip invalid characters
	if ($imageName != $newImageName)
	{
		return FALSE;
	}
	if (!preg_match('#(?:localhost|\..{2,6})\/.+\.(?:jpg|jpeg|png|svg|gif)$#i', $newImageName))
	{
		return FALSE;
	}
	return $newImageName;
}

//--------------------------------------------
//		Email address checks
//--------------------------------------------
// Split up an email address to check for banned domains.
// Return false if invalid address
function make_email_query($email, $fieldname = 'banlist_ip')
{
  global $tp;
  $tmp = strtolower($tp -> toDB(trim(substr($email, strrpos($email, "@")+1))));
  if ($tmp == '') return FALSE;
  if (strpos($tmp,'.') === FALSE) return FALSE;
  $em = array_reverse(explode('.',$tmp));
  $line = '';
  $out = array($fieldname."='*@{$tmp}'");		// First element looks for domain as email address
  foreach ($em as $e)
  {
    $line = '.'.$e.$line;
	$out[] = $fieldname."='*{$line}'";
  }
  return implode(' OR ',$out);
}

/**
 * @author Kris Sherrerd
 * @function addusertophpbb
 *
 * @params: string $username, string $loginname, string $password, string $email
 * @returns: N/A 
 * @description: Takes the information from user registration, verifies it and adds it to the database.  Then displays appropriate information.
 */
function addusertophpbb($user_data){
	IncludePHPBB();
    global $config;
    /*$config = array('auth_method' =>	'e107',
		'board_dst' =>	0,
		'board_timezone' => 0,
		'default_dateformat' =>'D M d, Y g:i a',
		'default_lang' => 'en',
		'default_style' => 1,
		'new_member_group_default' => 	0,
		'new_member_post_limit' 	=> 3);//*/

	include_once(PHPBB_ROOT_PATH.'includes/functions_user.php');

    global $db;
    // first retrieve default group id
    $sq = 'SELECT group_id
		FROM ' . GROUPS_TABLE . "
		WHERE group_name = '" . $db->sql_escape('REGISTERED') . "'
			AND group_type = " . GROUP_SPECIAL;
    $result = $db->sql_query($sq);
    if (!$row = $db->sql_fetchrow($result))
    {
        $db->sql_freeresult($result);
        trigger_error('NO_GROUP');
    }
    $db->sql_freeresult($result);

    $user_data['user_type'] = USER_NORMAL;
    $user_data['group_id']= (int)$row['group_id'];

    //Get the custome data if required.
    $cp_data = array();
    GatherCusFields($cp_data);

	$user_id=user_add($user_data, $cp_data);

    if($user_id){
	return true;
    }
    //something went terribly wrong
    echo 'Somthing went wrong and you were not registered.  Please contact an admin.';
    return false;
}


function GatherCusFields(&$cp_data, $mode='register', $lang=NULL){

    IncludePHPBB($user_req=true);
    global $user;

    $lang =($lang==NULL)?$user->get_iso_lang_id():$lang;
    require_once(PHPBB_ROOT_PATH.'includes/functions_profile_fields.php');
    $error=array();

    $cp = new custom_profile();

    // validate custom profile fields
    $cp->submit_cp_field($mode, $lang, $cp_data, $error);

    if(strcmp($mode,'profile')==0){
        $cp->update_profile_field_data(USERID, $cp_data);
    }
}

function IncludePHPBB($user_req=false){
    static $inuse=false;

    if(!$inuse){
        if(!defined('PHPBB_ROOT_PATH')){
            define('PHPBB_ROOT_PATH', './forums/');
        }

        if(!defined('IN_PHPBB')){
            define('IN_PHPBB', true);
            include_once(PHPBB_ROOT_PATH.'common.php');
        }
        if($user_req){
            global $user, $auth;
            //$config = array('auth_method' =>	'e107',
            //);

            define("MISC_E107",true);
            $user->session_begin();
            $auth->acl($user->data);
        }
        $inuse=true;
    }
    return;
}




?>