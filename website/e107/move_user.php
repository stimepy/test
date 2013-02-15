<?php

include("./class2.php");
//include(e_HANDER."usersetting_handler.php");
include_once("./includes/database.class.php");
require_once("./includes/e107_database.class.php");

global $sql;
$dbz = new E107DatabaseTrans();

//Get all the info needed for the database user transfer
$sql->db_Query('SELECT * from e107_user_select', NULL, 'db_Select');//pride.nuke_users WHERE user_id in(5,8,3,30,11,6,18,10,74,61,73,12,14,26,31,40,72,73,75)
$count=0;
$item=$sql->db_fetch();
while($item!=NULL || $item!=false){
	$users[$count++]=$item;
	$item = $sql->db_fetch();
}


/*
foreach($users as $use){
	$eid=createuser($use);
    if($eid!=0){
        $query= "UPDATE e107_user_select SET e107_id={$eid} where nuke_id={$use['nuke_id']}";
        $sql->db_Query($query, NULL, 'db_Update');
    }
    else{
        print_r($use);
        echo "<br />error<br />";
    }
}
die('done');//*/
/*
foreach($users as $use){
    $pid=addusertophpbb($use, $use['e107_id']);
    $query= "UPDATE e107_user_select SET phpbb_id={$pid} where nuke_id={$use['nuke_id']}";
    $sql->db_Query($query, NULL, 'db_Update');
}
die('done');//*/

foreach($users as $use){
    $use['user_sig_bbcode_uid']=prep_message($use['user_sig'], $use['user_sig_bbcode_uid']);
    if(substr($use['user_avatar'],0,8)=='gallary/'){
       $use['user_avatar']='pride.jpg';
    }
    $query= "UPDATE e107_phpbb_users SET user_sig='{$use['user_sig']}', user_sig_bbcode_uid='{$use['user_sig_bbcode_uid']}',       user_avatar_type='{$use['user_avatar_type']}', user_avatar='{$use['user_avatar']}', user_website='{$use['user_website']}', user_icq='{$use['user_icq']}', user_msnm='{$use['user_msnm']}', user_icq='{$use['user_icq']}', user_aim='{$use['user_aim']}', user_from='{$use['user_from']}', user_avatar_width=90, user_avatar_height=90
 where user_id={$use['phpbb_id']}";
    $sql->db_Query($query, NULL, 'db_Update');

    if(isset($use['xfire']) && $use['xfire']!=''){
        $dbz->SqlInsert(phpbb_profile_fields_data, array("user_id"=>$use['phpbb_id'], "pf_xfire"=>$use['xfire']));
    }
}
die('done');//*/

function createuser($users){
	global $ns, $tp, $e107, $sql;
	
	$username = $tp -> toDB(strip_tags($users['name']));
	$loginname = $tp -> toDB(strip_tags($users['username']));

	$ip = $e107->getip();

    $reg_time=strtotime(strtolower($users['user_regdate']));

    $u_key = md5(uniqid(rand(1,999999999), 1));
	$nid = $sql->db_Insert("user", "0, 0, '{$username}', '{$loginname}', '', '".($users['user_password'])."', '{$u_key}', '".$users['user_email']."', '', '', '".$tp -> toDB($users['timezone'])."', '".$tp -> toDB($_POST['hideemail'])."', '".$reg_time."', '0', '".time()."', '0', '0', '0', '0', '".$ip."', '2', '0', '', '', '0', '0', '{$username}', '', '', '', '0', '' ");


    if(!$nid)
	{
		require_once(HEADERF);
		$ns->tablerender("", 'nope'); //General error, user not added blah blah blah
        print_r($users);
		require_once(FOOTERF);
        exit();
	}
	
	
	return $nid;
	

}

function addusertophpbb($users, $nid){
	global $sql, $config;
	if(!defined('PHPBB_ROOT_PATH')){
		define('PHPBB_ROOT_PATH', './forums/');
		$config = array('auth_method' =>	'e107', 	
			'board_dst' =>	0, 	
			'board_timezone' => 0, 	
			'default_dateformat' =>'D M d, Y g:i a',
			'default_lang' => 'en', 	
			'default_style' => 1, 	
			'new_member_group_default' => 	0,
			'new_member_post_limit' 	=> 3);
	}
	if(!defined('IN_PHPBB')){
		define('IN_PHPBB', true);
	}

    $phpbb_root_path = PHPBB_ROOT_PATH;
	include_once(PHPBB_ROOT_PATH.'common.php');
    include_once(PHPBB_ROOT_PATH.'includes/functions_user.php');
	$user_id = user_add(row_e107($users['username'], $users['user_password'], $users['user_email'], strtotime(strtolower($users['user_regdate']))));

	if(((int)$user_id)>=1){
		if($sql->db_Update("user", " phpbb_id=".$user_id." WHERE user_id = '{$nid}'")){
			return $user_id;
		}
       // echo "update e107_user set phpbb_id={$user_id} where user_id={$nid}";
       echo "<br />";
    }
	//something went terribly wrong
	echo 'Evil!';
	return 0;
}



function row_e107($username, $password, $email, $dispname)
{
    global $db, $config, $user;
    // first retrieve default group id
    $sql = 'SELECT group_id
		FROM ' . GROUPS_TABLE . "
		WHERE group_name = '" . $db->sql_escape('REGISTERED') . "'
			AND group_type = " . GROUP_SPECIAL;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);

    if (!$row)
    {
        trigger_error('NO_GROUP');
    }

    // generate user account data
    return array(
        'username'		=> $username,
        'user_password'	=> $password,
        'user_email'	=> $email,
        'group_id'		=> (int) $row['group_id'],
        'user_type'		=> USER_NORMAL,
        'user_ip'		=> $user->ip,
        'user_regdate' => $dispname,
    );

}



function prep_message(&$message, $old_uid)
{
	if (isset($old_uid) && $old_uid != '')
	{
        $new_id=substr($old_uid,0,8);
		$message = preg_replace('/\:(([a-z0-9]:)?)'. $old_uid. '/s', ":".$new_id, $message);
        return $new_id;
    }
    return NULL;
}


function get_avatar_dim($src)
{

    switch ($src['user_avatar_type'])
    {
        case 1:
        case AVATAR_UPLOAD:
            $path='./forums/images/avatars/upload/';
            $results= get_avatar_dim($src,$path);//0=width 1=hieght
            break;

        case 2:
        case AVATAR_GALLERY:
        $path='./forums/images/avatars/gallery/';
            $results=get_avatar_dim($src,$path);
            break;
        case 3:
        case AVATAR_REMOTE:
            // see notes on this functions usage and (hopefully) model $func to avoid this accordingly
            $results= get_remote_avatar_dim($src);
            break;

        default:
            $default_x = (defined('DEFAULT_AVATAR_X_CUSTOM')) ? 100 : 80;
            $default_y = (defined('DEFAULT_AVATAR_Y_CUSTOM')) ? 100 : 80;

           $results= array($default_y, $default_x);
            break;
    }

    return $results;
}


function get_image_dim($source, $path=false)
{
    if($path){
        $image=$path.$source;
    }
     if (file_exists($image))
    {
        return @getimagesize($image);
    }

    return false;
}



function get_remote_avatar_dim($src)
{
    if (empty($src))
    {
        return 0;
    }

    static $remote_avatar_cache = array();

    // an ugly hack: we assume that the dimensions of each remote avatar are accessed exactly twice (x and y)
    if (isset($remote_avatar_cache[$src]))
    {
        $retval = $remote_avatar_cache[$src['user_avatar']];
        unset($remote_avatar_cache);
        return $retval;
    }

    $url_info = @parse_url($src['user_avatar']);
    if (empty($url_info['host']))
    {
        return 0;
    }
    $host = $url_info['host'];
    $port = (isset($url_info['port'])) ? $url_info['port'] : 0;
    $protocol = (isset($url_info['scheme'])) ? $url_info['scheme'] : 'http';
    if (empty($port))
    {
        switch(strtolower($protocol))
        {
            case 'ftp':
                $port = 21;
                break;

            case 'https':
                $port = 443;
                break;

            default:
                $port = 80;
        }
    }

    $timeout = @ini_get('default_socket_timeout');
    @ini_set('default_socket_timeout', 2);

    // We're just trying to reach the server to avoid timeouts
    $fp = @fsockopen($host, $port, $errno, $errstr, 1);
    if ($fp)
    {
        $remote_avatar_cache[$src['user_avatar']] = @getimagesize($src['user_avatar']);
        fclose($fp);
    }

    $default_x 	= (defined('DEFAULT_AVATAR_X_CUSTOM')) ? 100 : 100;
    $default_y 	= (defined('DEFAULT_AVATAR_Y_CUSTOM')) ? 100 : 100;
    $default 	= array($default_x, $default_y);

    if (empty($remote_avatar_cache[$src['user_avatar']]) || empty($remote_avatar_cache[$src['user_avatar']][0]) || empty($remote_avatar_cache[$src['user_avatar']][1]))
    {
        $remote_avatar_cache[$src['user_avatar']] = $default;
    }
    else
    {
        // We trust gallery and uploaded avatars to conform to the size settings; we might have to adjust here
        if ($remote_avatar_cache[$src['user_avatar']][0] > $default_x || $remote_avatar_cache[$src['user_avatar']][1] > $default_y)
        {
            $bigger = ($remote_avatar_cache[$src['user_avatar']][0] > $remote_avatar_cache[$src['user_avatar']][1]) ? 0 : 1;
            $ratio = $default[$bigger] / $remote_avatar_cache[$src['user_avatar']][$bigger];
            $remote_avatar_cache[$src][0] = (int)($remote_avatar_cache[$src['user_avatar']][0] * $ratio);
            $remote_avatar_cache[$src][1] = (int)($remote_avatar_cache[$src['user_avatar']][1] * $ratio);
        }
    }

    @ini_set('default_socket_timeout', $timeout);
    return $remote_avatar_cache[$src['user_avatar']];
}

?>