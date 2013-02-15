<?php
/**
 *
 * @package phpBB3
 * @version $Id: topic_preview.php, 11 2010/4/03 23:13:42 VSE Exp $
 * @copyright (c) Matt Friedman
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


function getusersin24(){

    global $user, $db, $cache;

    $user->add_lang('mods/usersin24');// User language added.
    //Get the time 24 hours prior to now.
    $time=time();
    $pre_now=$time-(24*3600);
    //useful??
    //$pre_now=$pre_now+($user->data['user_timezone']*3600);

    $sql_statement= "select user_id, username, user_colour from ".USERS_TABLE." where user_lastvisit>=".$pre_now." and (user_id>53 or user_id=2)";
    $result = $db->sql_query($sql_statement);
    $fail=true;
    //while names exsist, build an array of strings that will be used to display users active over the last 24 hours.
    $been_there=false;
    while ($row = $db->sql_fetchrow($result) ){
       $fail=false;
        if($row['user_id']==$user->data['user_id']){
            $been_there=true;
        }
        $name[]=get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
    }
    $db->sql_freeresult($sql_statement);

   //not sure this is needed but have to make sure the bots and anonymous don't get updated due to this.
    $bots = $cache->obtain_bots();
    foreach($bots as $bot){
        $id[]=$bot['user_id'];
    }
    $id[]=1;

    if(!$been_there && !array_search($user->data['user_id'], $id)){
        $sql_statement="update ".USERS_TABLE." set user_lastvisit=".$time." where user_id=".$user->data['user_id'];
        $db->sql_query($sql_statement);
    }
    $db->sql_freeresult($sql_statement);

    if(!$fail){
        return count($name).$user->lang['ONLINE_24'].implode(', ', $name);
    }
    return '0'.$user->lang['ONLINE_24'];
}

?>