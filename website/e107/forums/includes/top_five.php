<?php
/**
*
* @package phpBB3
* @version $Id:
* @copyright (c) 2010 Rich McGirr
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Include only once.
*/
if (!defined('INCLUDES_TOP_FIVE_PHP'))
{
	define('INCLUDES_TOP_FIVE_PHP', true);
	
	global $auth, $user, $db, $phpbb_root_path, $phpEx, $template;

    $user->add_lang('mods/top_five');// User language added.
	
	// grab auths that allow a user to read a forum
	$forum_array = array_unique(array_keys($auth->acl_getf('!f_read', true)));

	// we have auths, change the sql query below
	$sql_and = '';
	if (sizeof($forum_array))
	{
		$sql_and = ' AND ' . $db->sql_in_set('t.forum_id', $forum_array, true);
	}
	// grab all posts that meet criteria and auths
	$sql_ary = array(
		'SELECT'	=> 'u.user_id, u.username, u.user_colour, t.topic_title, t.forum_id, t.topic_last_post_id, t.topic_last_post_time, t.topic_last_poster_name, f.forum_name, f.forum_image, t.topic_views, t.topic_replies, tf.mark_time',
		'FROM'		=> array(TOPICS_TABLE => 't'),
		'LEFT_JOIN'	=> array(
			array(
				'FROM'	=> array(USERS_TABLE => 'u'),
				'ON'	=> 't.topic_last_poster_id = u.user_id',
   			),
			array('FROM' => array(FORUMS_TABLE => 'f'),
			'ON'=> 'f.forum_id=t.forum_id',
			),
			array('FROM' => array(FORUMS_TRACK_TABLE => 'tf'),
			'ON'	=> 't.forum_id=tf.forum_id and tf.user_id='.$user->data['user_id'],
			),
		),
		'WHERE'		=> 't.topic_approved = 1 AND t.topic_status <> ' . ITEM_MOVED . ' ' . $sql_and,
		'ORDER_BY'	=> 't.topic_last_post_time DESC',
	);
	//Run the query
	$result = $db->sql_query_limit($db->sql_build_query('SELECT', $sql_ary), 10);

	$is_row = false;
    while( $row = $db->sql_fetchrow($result) )
    {
		$is_row = true;//Only true if we have valid forums.
		$forum_url = append_sid("{$phpbb_root_path}viewforum.{$phpEx}", 'f=' . $row['forum_id']);
		$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id']);
		$topic_title = censor_text($row['topic_title']);
		$is_guest = $row['user_id'] != ANONYMOUS ? false : true;
        if($row['forum_name']==NULL ||$row['forum_name']==''){
            $row['forum_name']='Global Announcment!';
        }
		if($user->data['user_id']!=1){
			$for_img=($row['mark_time']<$row['topic_last_post_time']) ?  $user->img('topic_unread', 'UNREAD_POSTS') : $user->img('topic_read', 'NO_UNREAD_POSTS');
		}
		else{
			$for_img=$user->img('topic_read', 'NO_UNREAD_POSTS');
		}
			
       	$template->assign_block_vars('top_five_topic',array(
			'F_NAME'		=> $row['forum_name'],
			'FORUM_URL'		=> $forum_url,
       		'U_TOPIC' 		=> $view_topic_url,
       		'USERNAME_FULL'	=> $is_guest ? $user->lang['BY'] . ' ' . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $row['topic_last_poster_name']) : $user->lang['BY'] . ' ' . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
			'LAST_TOPIC_TIME'	=> $user->format_date($row['topic_last_post_time']),			
       		'TOPIC_TITLE' 	=> $topic_title,
			'TOPIC_REP'		=> $row['topic_replies'],
			'TOPIC_VIEW'	=> $row['topic_views'],	
			'TOP_ICON'		=> $for_img,
			));
    }

    $db->sql_freeresult($result);

	// if user doesn't have permission to read any forums, show a message
	if (!$is_row)
	{
		$template->assign_block_vars('top_five_topic', array(
			'NO_TOPIC_TITLE'	=> $user->lang['NO_TOPIC_EXIST'],
		));
	}
}
?>