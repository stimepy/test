<?php

/*
+---------------------------------------------------------------+
|        e107bb 3.1
|        DIPOrg (suporte@diporg.com)
|        http://www.diporg.com
|        e107.cc (e107@diporg.com)
|        http://e107.cc
|
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

/* e107bb Posts Importer v0.5.1 (Original Message)
Revision History:


   0.5.1 - Code is still bad. But however it does the job as it should... 

   Based at:
   e107bb e107Forum Importer v0.1.2 (with code from chipmunk scripts killmonster :S)
   e107 phpBB Plugin Users Importer 
   The code was so bad, that it was dificult to upgrade it!.

   TO DO LIST:
   Add Posts Import Type (Announcement...)
   Add username and first/last user post
   Add Support for huge DB's
   
*/

if(!isset($_POST['bbimport'])) {
echo "Can't run this script directly!";
exit;
}

require_once("../../class2.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }

/* Show Menu */
require_once (e_PLUGIN."e107bb/bbfunctions.php");
function admin_bbtheme_adminmenu() {
 e107bb_admin_menu();
}

//require_once(e_ADMIN.'auth.php');
//Do not use auth.php because auth class conflit
if (ADMIN)
{
    define("ADMIN_PAGE", TRUE);
    require_once(e_ADMIN."header.php");
}


set_time_limit(0);

define('IN_PHPBB', true);
$phpEx = "php";
$phpbb_root_path=PHPBB_PATH;
require(PHPBB_PATH."common.php");


$ns->tablerender("e107 Forum Import","Starting script...");


/* New part: Import Users */

if (!function_exists('user_add')) 
   {
   global $phpbb_root_path, $phpEx;
   include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
   include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
   include($phpbb_root_path . 'includes/functions_convert.' . $phpEx);
   include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
//   include($phpbb_root_path . 'includes/acp/acp_forums.' . $phpEx);
   }

/* From e107 auth */
function user_row_e107($userloginname, $username, $password, $email, $user_ip, $user_join, $user_lastvisit, $user_hideemail, $user_timezone, $user_location, $user_msn, $user_aim, $user_icq, $user_yahoo, $user_homepage)
   {
   global $db, $config, $user;
   // first retrieve default group id
   $sql = 'SELECT group_id FROM ' . GROUPS_TABLE . " WHERE group_name = '" . $db->sql_escape('REGISTERED') . "' AND group_type = " . GROUP_SPECIAL;
   $result = $db->sql_query($sql);
   $row = $db->sql_fetchrow($result);
   $db->sql_freeresult($result);
   if (!$row)
      {
      trigger_error('NO_GROUP');
      }

   if ($user_hideemail == 1) 
      {
      $user_allow_viewemail = 0;
      } 
   else 
      {
      $user_allow_viewemail = 1;
      }

   if ($user_timezone == "GMT"||strlen($user_timezone) == 0)
      {
      $phpbb_user_timezone = 0;
      } 
   else 
      {
      $phpbb_user_timezone = $user_timezone;
      }
      
   if (is_null($user_icq)||strlen($user_icq)>15) 
      {
      $user_icq = "";
      }
      
   if (is_null($user_aim)) 
      {
      $user_aim = "";
      }

   if (is_null($user_msn)) 
      {
      $user_msn = "";
      }

   if (is_null($user_yahoo)) 
      {
      $user_yahoo = "";
      }

   if (is_null($user_homepage)) 
      {
      $user_homepage = "";
      }

   if (is_null($user_location)) 
      {
      $user_location = "";
      }

   // generate user account data

   return array(
         'username'            => $userloginname,
         'username_clean'      => $username,
         'user_password'         => $password,
         'user_pass_convert'      => 0,
         'user_email'         => strtolower($email),
         'user_email_hash'      => crc32(strtolower($email)) . strlen($email),
         'user_allow_viewemail'   => $user_allow_viewemail,
         'user_timezone'         => $phpbb_user_timezone,
         'group_id'            => (int) $row['group_id'],
         'user_type'            => USER_NORMAL,
         'user_ip'            => $user_ip,
         'user_regdate'         => $user_join,
         'user_from'            =>  $user_location,
         'user_icq'            => $user_icq,
         'user_aim'            => $user_aim,
         'user_msnm'            => $user_msn,
         'user_yim'            => $user_yahoo,
         'user_website'         => $user_homepage,
         'user_lastvisit'       => $user_lastvisit,
         );
   }

function group_row_e107($userclass_name, $userclass_description)
   {
   /*
   'group_name'         => (string) $name,
   'group_desc'         => (string) $desc,
   */
   return array(
      'group_name'   => $userclass_name,
      'group_desc'   => $userclass_description,
      );
   }

function phpbbuserid($e107userid, $e107username){
       global $db;
       $useridnam="SELECT * from ".MPREFIX."user where user_id = '".$e107userid."'";
       if(!$useridnam2= $db->sql_query($useridnam)){//mysql_query($useridnam) or
           die("could not select useridnam");
       }
       $useridnam3 = $db->sql_fetchrow($useridnam2); =mysql_fetch_array($useridnam2);
       $db->sql_freeresult($useridnam2);

       $phpbbuser="SELECT user_id, user_ip from ".USERS_TABLE." where username = '".$useridnam3['user_loginname']."'";
       if(!$phpbbuser2=$db->sql_query($phpbbuser)){
           die("could not select phpbb user");
       }  //mysql_query($phpbbuser) or
       $phpbbuser3=$db->sql_fetchrow($phpbbuser2); //mysql_fetch_array($phpbbuser2);
       $db->sql_freeresult($phpbbuser2);

       if ($e107userid == "0"||$phpbbuser3['user_id'] == "")
      {
      $e107usrname_tmp=explode("|", $e107username);
      return array(
         'id'   => "1",
         'ip'   => $e107usrname_tmp[1],
         'name'   => $e107usrname_tmp[0],
         );
      }
   else
      {
      return array(
         'id'   => $phpbbuser3['user_id'],
         'ip'   => $phpbbuser3['user_ip'],
         'name'   => $useridnam3['user_loginname'],
         );
      }
   }

   // parsebb() Adapted from http://www.namepros.com/code/266965-php-simple-bbcode-parse-function.html
   // Totally reworked on this - Needs more still.../ Riiser

function parse107bb($body) 
   {
   $find = array(
      "/\[link\=(.+?)\](.+?)\[\/link\]/is",
      "/\[b\](.+?)\[\/b\]/is", 
        "/\[i\](.+?)\[\/i\]/is", 
        "/\[u\](.+?)\[\/u\]/is", 
        "/\[color\=(.+?)\](.+?)\[\/color\]/is",
        "/\[size\=(.+?)\](.+?)\[\/size\]/is", 
        "/\[font\=(.+?)\](.+?)\[\/font\]/is",
        "/\[center\](.+?)\[\/center\]/is",
        "/\[file\=(.+?)\](.+?)\[\/file\]/is",
        "/\[blockquote(.+?)\](.+?)\[\/blockquote\]/is",
      "/\[quote(.+?)\=(.+?)\](.+?)\[\/quote(.+?)\]/is",
        "/\[img\](.+?)\[\/img\]/is",
        "/\[list\](.+?)\[\/list\]/is",
      "/\[html\] /",
      "/\[\/html\]/",
      "/<br \/>/",
      "/<strong>(.+?)<\/strong>/is",
      "/<em>(.+?)<\/em>/is",
      "/<span style=&quot;color: (.+?)&quot;>/is",
      "/<\/span>/",
      "/&amp;#039;/",
      "/&amp;#39;/",
      "/&#39;/",
      //"/&quot;/"
      );
    $replace = array(
      "[url=$1]$2[/url]",
        "[b]$1[/b]", 
        "[i]$1[/i]", 
        "[u]$1[/u]", 
        "[color=$1]$2[/color]",
        "[size=$1]$2[/size]", 
        "[font=$1]$2[/font]",
        "[center:]$1[/center]",
        "[b:]e107 Attachement: [url=../$1]$2[/url][/b]",
        "[quote]$1[/quote]",
      "[quote=&quot;$2&quot;]$3[/quote]",
        "[img]$1[/img]",
        "[list]$1[/list]",
      "",
      "",
      "\n",
      "[b]$1[/b]",
      "[i]$1[/i]",
      "[color=$1]",
      "[/color]",
      "''",
      "''",
      "''",
      //'"'
      );
   $body = preg_replace($find, $replace, $body);
    //$body = htmlspecialchars($body);
   // Not so sure abot this html stuff. phpbb seems much more able to store chrs in the db...
   return $body;
   }

//echo "<strong><u>Copying e107 Groups to phpBB3</u></strong><br/>";

$ns->tablerender("e107 Groups","Starting copy...");

$e107class="SELECT * from ".MPREFIX."userclass_classes";
$e107class2= $sql->db_Select_gen($e107class);//mysql_query($e107class) or die("could not select e107 groups");
while($e107class3=$sql->db_Fetch())//mysql_fetch_array($e107class2)
   {
   //echo "<strong>".$e107class3['userclass_name'].":</strong>";
   $phpbbgroup="SELECT group_name from ".GROUPS_TABLE." where group_name = '".$e107class3['userclass_name']."'";
   if(!$phpbbgroup2=$sql->db_Select_gen($phpbbgroup)){ //mysql_query($phpbbgroup) or
       die("could not select phpbb group");
   }
   $phpbbgroup3=$sql->db_Fetch();//mysql_fetch_array($phpbbgroup2);
   if(empty($phpbbgroup3['group_name'])) 
      {
      $groupid = '';
      $group_attribs = array(
         'group_legend' => '0',
         );
      group_create($groupid, GROUP_OPEN, parse107bb($e107class3['userclass_name']), parse107bb($e107class3['userclass_description']), $group_attribs, false, false, false);
      //echo "Group Imported<br/>";
      }
   else 
      {
      //echo "Group Found in phpBB<br/>";
      }
   }

$ns->tablerender("e107 Groups","Done...");

$ns->tablerender("e107 Users","Starting copy...");

//echo "<strong><u>Copying e107 Users to phpBB3</u></strong><br/>";
$e107user="SELECT * FROM ".MPREFIX."user";
if(!$e107user2=$sql->db_Select_gen($e107user)){ //mysql_query($e107user) or
    die("could not select e107 user");
}
while($e107user3=$sql->db_Fetch())// mysql_fetch_array($e107user2))
   {
   //echo "<strong>".$e107user3['user_loginname'].":</strong>";
   $phpbbuser="SELECT username from ".USERS_TABLE." where username = '".$e107user3['user_loginname']."'";
   if(!$phpbbuser2=$sql->db_Select_gen($phpbbuser)){ //mysql_query($phpbbuser) or
       die("could not select phpbb user");
   }
   $phpbbuser3=$sql->db_Fetch();//mysql_fetch_array($phpbbuser2);

   $e107user_ext_sql="SELECT * FROM ".MPREFIX."user_extended WHERE user_extended_id='".$e107user3['user_id']."'";
   $sql->db_Select_gen($e107user_ext_sql);//mysql_query($e107user_ext_sql);
   $e107user_ext_sql3=$sql->db_Fetch();//mysql_fetch_array($e107user_ext_sql2);
   if(empty($phpbbuser3['username'])) 
      {
      if(count($e107user_ext_sql3)>0){
         user_add(user_row_e107($e107user3['user_loginname'], $e107user3['user_name'], $e107user3['user_password'], $e107user3['user_email'], $e107user3['user_ip'], $e107user3['user_join'], $e107user3['user_lastvisit'], $e107user3['user_hideemail'], $e107user3['user_timezone'], $e107user_ext_sql3['user_location'], $e107user_ext_sql3['user_msn'], $e107user_ext_sql3['user_aim'], $e107user_ext_sql3['user_icq'], $e107user_ext_sql3['user_yahoo'], $e107user_ext_sql3['user_homepage']));
         }
      else {
         user_add(user_row_e107($e107user3['user_loginname'], $e107user3['user_name'], $e107user3['user_password'], $e107user3['user_email'], $e107user3['user_ip'], $e107user3['user_join'], $e107user3['user_lastvisit'], $e107user3['user_hideemail'], $e107user3['user_timezone'], "", "", "", "", "", ""));
         }
      if (strlen($e107user3['user_class'])>0)
         {
         $e107_class_array = explode(",",$e107user3['user_class']);

         echo "Adding user to groups: ";
         if (count($e107_class_array)>0)
            {
            foreach ($e107_class_array as $e107groupid)
               {
               $e107_class_name="SELECT userclass_name from ".MPREFIX."userclass_classes WHERE userclass_id=".$e107groupid;

               if(!$e107_class_name2=$sql->db_Select_gen($e107_class_name)){//mysql_query($e107_class_name) or
                   die("could not select e107 groups");
               }
               $e107_class_name3=$sql->db_Fetch();//mysql_fetch_array($e107_class_name2);
               //echo "<strong>".$e107_class_name3['userclass_name']."</strong>, ";

               $phpbbuserid="SELECT user_id from ".USERS_TABLE." where username = '".$e107user3['user_loginname']."'";
               $phpbbuserid2=$sql->db_Select_gen($phpbbuserid);//mysql_query($phpbbuserid);
               $phpbbuserid3=$sql->db_Fetch();//mysql_fetch_array($phpbbuserid2);

               $phpbbgroupid="SELECT group_id from ".GROUPS_TABLE." where group_name = '".$e107_class_name3['userclass_name']."'";
               $phpbbgroupid2=$sql->db_Select_gen($phpbbgroupid);//mysql_query($phpbbgroupid);
               $phpbbgroupid3=$sql->db_Fetch();//mysql_fetch_array($phpbbgroupid2);
               $sql->freeQuery();
               group_user_add($phpbbgroupid3['group_id'],$phpbbuserid3['user_id']);
               }
            }
         }
      //echo "User Imported<br/>";
      } 
   else 
      {
      //echo "User Found in phpBB<br/>";
      }
   }

$ns->tablerender("e107 Users","Done...");

$ns->tablerender("e107 Forum Posts","Starting copy...");

//print "Users and Groups Imported without stop errors.<br/><br/><strong><u>Importing Posts</u></strong><br/>";

// todo: remove forums
// Select all e107 forum categories 
$e107parent="SELECT * from ".MPREFIX."forum WHERE forum_parent='0'";
$e107parent2=mysql_query($e107parent) or die("could not select e107 parent");
while($e107parent3=mysql_fetch_array($e107parent2))
   {
   //print "+Category ".$e107parent3['forum_name']."<br/>";
   // Insert them into phpBB 
   // New category id 
   $e107parentid = $e107parent3['forum_id'] + 500;

   //From phpBB Convertors
   $sql = 'SELECT MAX(right_id) AS right_id FROM ' . FORUMS_TABLE;
   $_result = $db->sql_query($sql);
   $cat_row = $db->sql_fetchrow($_result);
   $db->sql_freeresult($_result);
   $leftid = $cat_row['right_id'] + 1;
   $rightid = $cat_row['right_id'] + 2;
   //New parent insert
   $sql = "INSERT INTO `".FORUMS_TABLE."` (`forum_id`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `forum_name`, `forum_desc`, `forum_desc_bitfield`, `forum_desc_options`, `forum_desc_uid`, `forum_link`, `forum_password`, `forum_style`, `forum_image`, `forum_rules`, `forum_rules_link`, `forum_rules_bitfield`, `forum_rules_options`, `forum_rules_uid`, `forum_topics_per_page`, `forum_type`, `forum_status`, `forum_posts`, `forum_topics`, `forum_topics_real`, `forum_last_post_id`, `forum_last_poster_id`, `forum_last_post_subject`, `forum_last_post_time`, `forum_last_poster_name`, `forum_last_poster_colour`, `forum_flags`, `display_on_index`, `enable_indexing`, `enable_icons`, `enable_prune`, `prune_next`, `prune_days`, `prune_viewed`, `prune_freq`) VALUES ('$e107parentid', 0, '$leftid', '$rightid', '', '".$e107parent3['forum_name']."', '', '', '7', '', '', '', '0', '', '', '', '', '7', '', '0', '".FORUM_CAT."', '0', '0', '0', '0', '0', '0', '', '0', '', '', '".FORUM_FLAG_POST_REVIEW."', '0', '1', '0', '0', '0', '7', '7', '1')";
   mysql_query($sql) or die($ns->tablerender("ERROR","<strong>This usually means you already run Import Script</strong><br/>".$sql."<br/>".mysql_error()));
   // Select all e107 forums inside each e107 forum category //
   $e107forum="SELECT * from ".MPREFIX."forum WHERE forum_parent='".$e107parent3['forum_id']."'";
   $e107forum2=mysql_query($e107forum) or die("could not select forum");
   while($e107forum3=mysql_fetch_array($e107forum2))
      {
      if($e107forum3['forum_parent']==0)
         {
         }
      else 
         {
         //print "-+Forum ".$e107forum3['forum_name']."<br/>";
         $e107forumid = $e107forum3['forum_id'] + 500;
         $e107forumposts = $e107forum3['forum_threads']+$e107forum3['forum_replies'];
         $flapid=explode(".", $e107forum3['forum_lastpost_info']);
         $flapidt = $flapid[1];
         $flapid[1] = $flapid[1] + 500;
         $forum_lastpost=phpbbuserid($flupid[0], $flupid[1]);
         //From phpBB Convertors
         $sql = 'SELECT MAX(right_id) AS right_id FROM ' . FORUMS_TABLE;
         $_result = $db->sql_query($sql);
         $cat_row = $db->sql_fetchrow($_result);
         $db->sql_freeresult($_result);
         $leftid = $cat_row['right_id'];
         $rightid = $cat_row['right_id'] + 1;
         // Last Post Subject 
         $slpost="SELECT * from ".MPREFIX."forum_t WHERE thread_id='$flapidt'";
         $slpost2=mysql_query($slpost) or die("could not do this2!");
         $slpost3=mysql_fetch_array($slpost2);
         //New forums insert
         $sql = "INSERT INTO `".FORUMS_TABLE."` (`forum_id`,`parent_id`, `left_id`, `right_id`, `forum_parents`, `forum_name`, `forum_desc`, `forum_desc_bitfield`, `forum_desc_options`, `forum_desc_uid`, `forum_link`, `forum_password`, `forum_style`, `forum_image`, `forum_rules`, `forum_rules_link`, `forum_rules_bitfield`, `forum_rules_options`, `forum_rules_uid`, `forum_topics_per_page`, `forum_type`, `forum_status`, `forum_posts`, `forum_topics`, `forum_topics_real`, `forum_last_post_id`, `forum_last_poster_id`, `forum_last_post_subject`, `forum_last_post_time`, `forum_last_poster_name`, `forum_last_poster_colour`, `forum_flags`, `display_on_index`, `enable_indexing`, `enable_icons`, `enable_prune`, `prune_next`, `prune_days`, `prune_viewed`, `prune_freq`) VALUES ('$e107forumid', '$e107parentid', '$leftid', '$rightid', '', '".$e107forum3['forum_name']."', '".$e107forum3['forum_description']."', '', '7', '', '', '', '0', '', '', '', '', '7', '', '0', '".FORUM_POST."', '0', '$e107forumposts', '".$e107forum3['forum_threads']."', '".$e107forum3['forum_threads']."', '$flapid[1]', '".$forum_lastpost['id']."', '".$slpost3['thread_name']."', '$flapid[0]', '$flupid[1]', '000000', '".FORUM_FLAG_POST_REVIEW."', '0', '1', '0', '0', '0', '7', '7', '1')";
         mysql_query($sql) or die($sql." ".mysql_error());
         // Update Parent
         $sql = "UPDATE `".FORUMS_TABLE."` SET right_id = $rightid+1 WHERE forum_id = $e107parentid";
         mysql_query($sql) or die($sql." ".mysql_error());
         // Select topics 
         $e107topic="SELECT * from ".MPREFIX."forum_t WHERE thread_parent='0' AND thread_forum_id='".$e107forum3['forum_id']."'";
         $e107topic2=mysql_query($e107topic) or die("could not select topic");
            while($e107topic3=mysql_fetch_array($e107topic2)) 
            {
            // Topic Type 
            if ($e107topic3['thread_s']==1)
               {
               // Sticky
               $bbk=1;
               }
            else if ($e107topic3['thread_s']==2)
               {
               // Announcement
               $bbk=2;
               }
            else 
               {
               // Normal
               $bbk=0;
               }
            //print "|-+Topic ".$e107topic3['thread_name']." - Type: ".$bbk."<br/>";
            // Last Post ID? 
            $slpost="SELECT * from ".MPREFIX."forum_t WHERE thread_parent='".$e107topic3['thread_id']."' AND thread_datestamp='".$e107topic3['thread_lastpost']."'";
            $slpost2=mysql_query($slpost) or die("could not!");
            $slpost3=mysql_fetch_array($slpost2);
            if ($slpost3['thread_id']=='') 
               {
               $slpost3['thread_id']=$e107topic3['thread_id'];
               }
            $e107topicid = $e107topic3['thread_id'] + 500;
            $useridname=explode(".", $e107topic3['thread_user']);
            $thread_last_user=explode(".", $e107topic3['thread_lastuser']);
            $initial_topicposter=phpbbuserid($useridname[0], $useridname[1]);
            $last_topicposter=phpbbuserid($thread_last_user[0], $thread_last_user[1]);
            $my_subject   = utf8_normalize_nfc(parse107bb($e107topic3['thread_name']));
            $my_text   = utf8_normalize_nfc(parse107bb($e107topic3['thread_thread']));
            $sql = "INSERT INTO `".TOPICS_TABLE."` (`topic_id`, `forum_id`, `topic_approved`, `topic_title`, `topic_poster`, `topic_first_poster_name`, `topic_time`, `topic_views`, `topic_replies`, `topic_first_post_id`, `topic_last_post_id`, `topic_last_poster_id`, `topic_last_poster_name`, `topic_last_post_time`) VALUES ('".$e107topicid."', '".$e107forumid."', 1,'".$my_subject."', '".$initial_topicposter['id']."', '".$initial_topicposter['name']."', '".$e107topic3['thread_datestamp']."', '".$e107topic3['thread_views']."', '".$e107topic3['thread_total_replies']."', '".$e107topic3['thread_id']."', '".$slpost3['thread_id']."', '".$last_topicposter['id']."', '".$last_topicposter['name']."', '".$e107topic3['thread_lastpost']."')";
            mysql_query($sql) or die($sql." ".mysql_error());

            // New try. Using message parser. And this was more successful for me.. /Riiser
            $message_parser = new parse_message();
            $message_parser->message = $my_text;
            $message_parser->parse(true, true, true, true, true, true, true);

            $sql = "INSERT INTO `".POSTS_TABLE."` (`topic_id`, `forum_id`, `post_id`, `poster_id`, `post_time`, `poster_ip`, `enable_bbcode`, `enable_smilies`, `enable_sig`, `post_approved`, `post_subject`, `post_text`, `bbcode_bitfield`, `bbcode_uid`, `post_checksum`) VALUES ('".$e107topicid."', '".$e107forumid."', '".$e107topicid."', '".$initial_topicposter['id']."', '".$e107topic3['thread_datestamp']."', '".$initial_topicposter['ip']."', 1, 1, 1, 1, '".$my_subject."', '".$message_parser->message."', '".$message_parser->bbcode_bitfield."', '".$message_parser->bbcode_uid."', '".md5($message_parser->message)."')";
            mysql_query($sql) or die($sql." ".mysql_error()); 

            // Select Replies 
            $e107reply="SELECT * from ".MPREFIX."forum_t WHERE thread_parent='".$e107topic3['thread_id']."'";
            $e107reply2=mysql_query($e107reply) or die("could not select monster");
            while($e107reply3=mysql_fetch_array($e107reply2)) 
               {
              // print "||-+Reply ".$e107reply3['thread_name']."<br/>";
               $useridname=explode(".", $e107reply3['thread_user']);
               $initial_topicposter=phpbbuserid($useridname[0], $useridname[1]);
               $my_reply_subject   = "Re: ".$my_subject;
               $my_text   = utf8_normalize_nfc(parse107bb($e107reply3['thread_thread']));
               $e107postid = $e107reply3['thread_id'] + 500;
               $message_parser_reply = new parse_message();
               $message_parser_reply ->message = $my_text;
               $message_parser_reply ->parse(true, true, true, true, true, true, true);
               $sql = "INSERT INTO `".POSTS_TABLE."` (`topic_id`, `forum_id`, `post_id`, `poster_id`, `post_time`, `poster_ip`, `enable_bbcode`, `enable_smilies`, `enable_sig`, `post_approved`, `post_subject`, `post_text`, `bbcode_bitfield`, `bbcode_uid`, `post_checksum`) VALUES ('".$e107topicid."', '".$e107forumid."', '".$e107postid."', '".$initial_topicposter['id']."', '".$e107reply3['thread_datestamp']."', '".$initial_topicposter['ip']."', 1, 1, 1, 1, '".$my_reply_subject."', '".$message_parser_reply->message."', '".$message_parser_reply->bbcode_bitfield."', '".$message_parser_reply->bbcode_uid."', '".md5($message_parser_reply->message)."')";
               mysql_query($sql) or die($sql." ".mysql_error()); 
               }
            } 
         }
      }
   }

$ns->tablerender("e107 Forum Posts","Import Done! <br/>You must set forum permissions in phpBB Admin Panel, or you won't see the imported forums in phpBB.");
//print "Parents, Forums, Threads and Replies Imported without stop errors.<br/><br/>You should set permissions in phpBB Admin Panel to show forums in phpBB";

    require_once(e_ADMIN."footer.php");
?>