<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/user.php $
|     $Revision: 11687 $
|     $Id: user.php 11687 2010-08-23 07:25:47Z e107steved $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");

// Next bit is to fool PM plugin into doing things
global $user;
$user['user_id'] = USERID;

require_once(e_FILE."shortcode/batch/user_shortcodes.php");
require_once(e_HANDLER."form_handler.php");

if (isset($_POST['delp']))
{
	$tmp = explode(".", e_QUERY);
	if ($tmp[0]=="self")
	{
		$tmp[1]=USERID;
	}
	if (USERID == $tmp[1] || (ADMIN && getperms("4")))
	{
		$sql->db_Select("user", "user_sess", "user_id='". USERID."'");
		@unlink(e_FILE."public/avatars/".$row['user_sess']);
		$sql->db_Update("user", "user_sess='' WHERE user_id=".intval($tmp[1]));
		header("location:".e_SELF."?id.".$tmp[1]);
		exit;
	}
}

$qs = explode(".", e_QUERY);
$self_page =($qs[0] == 'id' && intval($qs[1]) == USERID);

if (file_exists(THEME."user_template.php"))
{
	require_once(THEME."user_template.php");
}
else
{
	require_once(e_BASE.$THEMES_DIRECTORY."templates/user_template.php");
}
$user_frm = new form;
require_once(HEADERF);
if (!defined("USER_WIDTH")){ define("USER_WIDTH","width:95%"); }

$full_perms = getperms("0") || check_class(varset($pref['memberlist_access'], 253));		// Controls display of info from other users
if (!$full_perms && !$self_page)
{
	$ns->tablerender(LAN_20, "<div style='text-align:center'>".USERLAN_2."</div>");
	require_once(FOOTERF);
	exit;
}

if (isset($_POST['records']))
{
	$records = intval($_POST['records']);
	$order = ($_POST['order'] == 'ASC' ? 'ASC' : 'DESC');
	$from = 0;
}
else if(!e_QUERY)
{
	$records = 20;
	$from = 0;
	$order = "ASC";
}
else
{
	if ($qs[0] == "self")
	{
		$id = USERID;
	}
	else
	{
		if ($qs[0] == "id")
		{
			$id = intval($qs[1]);
		}
		else
		{
			$qs = explode(".", e_QUERY);
			$from = intval($qs[0]);
			$records = intval($qs[1]);
			$order = ($qs[2] == 'ASC' ? 'ASC' : 'DESC');
		}
	}
}
if ($records > 30)
{
	$records = 30;
}

//Profile.....
if (isset($id))
{
	if ($id == 0 || $id < 0)

	{
		$text = "<div style='text-align:center'>".LAN_137." ".SITENAME."</div>";
		$ns->tablerender(LAN_20, $text);
		require_once(FOOTERF);
		exit;
	}

	$loop_uid = $id;

	$ret = $e_event->trigger("showuser", $id);
	if ($ret!='')
	{
		$text = "<div style='text-align:center'>".$ret."</div>";
		$ns->tablerender(LAN_20, $text);
		require_once(FOOTERF);
		exit;
	}

	if($pref['profile_comments'])
	{
		require_once(e_HANDLER."comment_class.php");
		$comment_edit_query = 'comment.user.'.$id;
	}

	if (isset($_POST['commentsubmit']) && $pref['profile_comments'])
	{
		$cobj = new comment;
		$cobj->enter_comment($_POST['author_name'], $_POST['comment'], 'profile', $id, $pid, $_POST['subject']);
	}

	if($text = renderuser($id))
	{
		$ns->tablerender(LAN_402, $text);
	}
	else
	{
		$text = "<div style='text-align:center'>".LAN_400."</div>";
		$ns->tablerender(LAN_20, $text);
	}
	unset($text);
	require_once(FOOTERF);
	exit;
}//end profile....

//begin user list
$users_total = $e107cache->retrieve("nq_user_totals", 120);
$users_total = ($users_total==false?0:$users_total);

if (!$sql->db_Select("user", "*", "user_ban = 0 and (user_id>53 or user_id=2) ORDER BY user_id $order LIMIT $from,$records"))
{
	echo "<div style='text-ign:center'><b>".LAN_141."</b></div>";
}
else
{
	$userList = $sql->db_getList();
    if($users_total==0){
        $users_total=count($userList);
        $e107cache->set("nq_user_totals", $users_total);
    }
    $text = $tp->parseTemplate($USER_SHORT_TEMPLATE_START, TRUE, $user_shortcodes);
    foreach ($userList as $row)
	{
		$text .= renderuser($row, 'short');
	}
    $text .= $tp->parseTemplate($USER_SHORT_TEMPLATE_END, TRUE);//, $user_shortcodes);
}

$ns->tablerender(LAN_140, $text);
if($records < $users_total){
    $parms = $users_total.",".$records.",".$from.",".e_SELF.'?[FROM].'.$records.".".$order;
    echo "<div class='nextprev'>&nbsp;".$tp->parseTemplate("{NEXTPREV={$parms}}")."</div>";
}

function renderuser($uid, $mode = "verbose"){
	global $tp,  $user_shortcodes;
	global $USER_SHORT_TEMPLATE, $USER_FULL_TEMPLATE;
	global $user;

	if(is_array($uid))
	{
		$user = $uid;
	}
	else
	{
		if(!$user = get_user_data($uid))
		{
			return FALSE;
		}
	}

	if($mode == 'verbose'){
		return $tp->parseTemplate($USER_FULL_TEMPLATE, TRUE);//, $user_shortcodes);
	}
	else{
		return $tp->parseTemplate($USER_SHORT_TEMPLATE, TRUE);//, $user_shortcodes);
	}
}

require_once(FOOTERF);
?>
