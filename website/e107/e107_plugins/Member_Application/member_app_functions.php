<?php

function see_all_applications(){
	global $dbz;
	//$app = $dbz->SqlGetRow('*', MA_cfg);

	

	if( !($app = $dbz->SqlGetAll('*', MA_cfg, " active <> 0 ORDER BY keyfld")) )
	{	
		echo "<H3>ERROR - 4 - ".MA_UTOQTERROR."</H3><br>";
		
		require_once(FOOTERF);
		exit();
	}
  // get operation desired
  $output = MAF::createtable("open","align:\"center\" width=\"100%\" cellpadding = \"5%\" hspace=\"10\" vspace=\"10\" ")."
    <thead>";
	$output .= "<form NAME=\"appy\" METHOD=POST action=\"".X1_publicpostfile."\">\n";

	$output .="<tr>  
		<td style=\"text-align:center;\" colspan=\"2\">
			Available Applications<br />
			<SELECT ID=\"formno\" NAME=\"appno\" onchange='updFormNo();'>";
	foreach($app as $rowform)
	{
		$output .="<OPTION VALUE=\"".$rowform['formno']."\">".$rowform['formtitle'];
	} 
	$output .="</SELECT>
	</center>
		</td>
	</tr>
	<tr>
		<td style=\"text-align:center;\" colspan=\"2\">
		 <input type=\"submit\" value=\"View application\">
		</td>
	</tr>
	</form>
	</thead>";

	
	return $output;
}

function filloutapplication($appno){
	global $dbz;
	
	$appinfo = $dbz->SqlGetRow('*', MA_cfg, " formno=".$appno);
	$question_list = $dbz->SqlGetAll('*', MA_mapp, " formno=".$appno." and inuse <> 0 and isdel <> 1 order by fldnum");
	$output ="<tbody>
		<form NAME=\"app\" METHOD=POST action=\"".X1_publicpostfile."?op=apply\">
			<input type=\"hidden\" name=\"name\" value=\"Member_Application\">
			<input type=\"hidden\" name=\"op\" value=\"apply\">
			<input type=\"hidden\" name=\"appno\" value=\"".$appno."\">
			<tr>
				<td style=\"text-align:center;\" colspan=\"2\"> <h3>".$appinfo['formtitle']."</h3></td>
			</tr>
			<br />  <br / >
			<tr> 
			<td style=\"v-align:top;\" colspan=\"2\"><p>".MAF::X1_HTMLReady($appinfo['apptxt'])."</p>
				</td> 
			</tr>";
	$question_count=0;
	$questions = determinttype($question_list, $question_count);
	foreach($questions as $question){
		$output .= $question;
	}
	$output .="</tbody>
	<tfoot>
	<tr>
		<td align=\"center\" colspan=\"2\">
			<input type=\"hidden\" name=\"quescnt\" value=\"".$question_count."\">\n
			<input type=\"hidden\" name=\"op\" value=\"apply\">\n
			<input type=SUBMIT VALUE=\"Submit Now!\">
		</td>
	</tr>
	</form>
	</tfoot>";
	return $output;
	
}
			

function determinttype($array, &$my_count){
	$size=count($array);
	$b_true=false;
	$l_true=false;
	$r_true=false;
	$b_p=$l_p=$r_p=$b_c=$l_c=$r_c=$post=0;
	

	for($i=0; $i<$size; $i++){
		switch($array[$i]['format']){
			case "b":
				$b_true=true;
				if($array[$i]['parent']==0){
					$b_parent['pos'][$b_p]=$i;
					$b_parent['pos'][$b_p]=$i;
					$b_parent['par'][$b_p]=$array[$i]['fldnum'];
					$b_parent['q'][$b_p]=$array[$i]['fldname'];
					$b_p++;
				}
				else{
					$b_child['pos'][$b_c]=$i;
					$b_child['par'][$b_c]=$array[$i]['parent'];
					$b_child['q'][$b_c]=$array[$i]['fldname'];
					$b_c++;
				}
				break;
			case "l":
				$l_true=true;
				if($array[$i]['parent']==0){
					$l_parent['pos'][$l_p]=$i;
					$l_parent['par'][$l_p]=$array[$i]['fldnum'];
					$l_parent['q'][$l_p]=$array[$i]['fldname'];
					$l_p++;
				}
				else{
					$l_child['pos'][$l_c]=$i;
					$l_child['par'][$l_c]=$array[$i]['parent'];
					$l_child['q'][$l_c]=$array[$i]['fldname'];
					$l_c++;
				}
				break;
			case "r":
				$r_true=true;
				if($array[$i]['parent']==0){
					$r_parent['pos'][$r_p]=$i;
					$r_parent['par'][$r_p]=$array[$i]['fldnum'];
					$r_parent['q'][$r_p]=$array[$i]['fldname'];
					$r_p++;
				}
				else{
					$r_child['pos'][$r_c]=$i;
					$r_child['par'][$r_c]=$array[$i]['parent'];
					$r_child['q'][$r_c]=$array[$i]['fldname'];
					$r_c++;
				}
				break;
			case "t":
				$collection[$i]="<tr><td align=\"right\" width=\"40%\">".$array[$i]['fldname']."</td><td align=\"right\" width=\"50%\"><input name=\"data".$my_count."\" id=\"data".$my_count."\" value=\"".$row['rgextxt']."\">
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$my_count++;
				break;
			case "T":
				$collection[$i]="<tr><td align=\"right\" width=\"40%\" colspan=\"2\">".$array[$i]['fldname']."<br /><textarea name=\"data".$my_count."\" id=\"data".$my_count."\" cols=\"60\" rows=\"20\" ></textarea>
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$my_count++;
				break;
			case "p":
				$collection[$i]= "<tr><td align=\"right\" width=\"40%\">".$array[$i]['fldname']."</td><td align=\"left\" width=\"50%\"><INPUT TYPE=\"password\" name=\"data".$my_count."\" id=\"data".$my_count."\">
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$my_count++;
				break;
			case "v":
				$collection[$i]= "<tr><td align=\"right\" width=\"40%\">".$array[$i]['fldname']."</td><td align=\"left\" width=\"50%\"><i>".MA_VALIDATED."</i><br />
              <INPUT NAME=\"data".$my_count."\" id=\"data".$my_count."\" VALUE=\"\">
			  <INPUT type=\"hidden\" NAME=\"regexdef".$my_count."\" id=\"regexdef".$my_count."\" value=\"".$row['rgextxt']."\">
			  <input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
			  $my_count++;
			  break;
			case "c":
				$collection[$i]="<tr><td align=\"right\" width=\"40%\">".$array[$i]['fldname']."</td><td align=\"left\" width=\"50%\"><input type=checkbox name=\"data".$my_count."\" id=\"data".$my_count."\">
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$my_count++;
				break;
			case "L":
				$collection[$i]="<tr><td align=\"left\" colspan=\"3\" width=\"100%\">".$array[$i]['fldname']."
				<td align=\"left\" width=\"50%\"><input type=hidden name=\"data".$my_count."\" id=\"data".$my_count."\" value=\"label\">
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td> </tr>";
				$my_count++;
				break;
			default:
		}
	}	
	if($b_true || $l_true ||$r_true){
		if($b_true){
			
			for($count=0; $count<$b_p; $count++){
				$string="<tr><td align=\"right\" width=\"40%\">".$b_parent['q'][$count]."</td><td align=\"left\" width=\"40%\">";
				$i=$b_parent['pos'][$count];
				$my_kids=array_keys($b_child['par'], $b_parent['par'][$count]);
				foreach($my_kids as $kids){
					$string .="<input type=checkbox name=\"data".$my_count."\" id=\"data".$my_count."\" value=\"".$b_child['q'][$kids]."\">".$b_child['q'][$kids].
					"<input type=\"hidden\" name=\"parent".$i."\" value=\"".$b_child['par'][$kids]."\">
					<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ><br />";
					$my_count++;
				}
				$string .="</td></tr>";
				$collection[$i]=$string;
			}
		}
		if($l_true){
			$count=0;
			$i=$l_parent['pos'][$count];
			$string="<tr>
				<td align=\"right\">".$l_parent['q'][$count]."</td>
				<td align=\"left\"><select name=\"data".$my_count."\" id=\"data".$my_count."\">";
			
			$i=$l_parent['pos'][$count];
			$my_kids=array_keys($l_child['par'], $l_parent['par'][$count]);
				foreach($my_kids as $kids){
					$string .="<option value=\"".$l_child['q'][$kids]."\">".$l_child['q'][$kids]." </option>";
				}
				$string .="</select>
				<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$my_count++;
				$collection[$i]=$string;
			}
		if($r_true){
			$count=0;
				$string="<tr><td align=\"right\" width=\"40%\">".$r_parent['q'][$count]."</td><td align=\"left\" width=\"40%\">";
				$i=$r_parent['pos'][$count];
				$my_kids=array_keys($r_child['par'], $r_parent['par'][$count]);
				foreach($my_kids as $kids){
					$string .="<input type=\"radio\" name=\"data".$my_count."\" id=\"data".$my_count."\" value=\"".$r_child['q'][$kids]."\"> ".$r_child['q'][$kids]."<br />";
				}
				$string .="<input type=\"hidden\" name=qnum".$my_count." value=\"".$array[$i]['fldnum']."\" ></td></tr>";
				$collection[$i]=$string;
				$my_count++;
			}
	}
	
	return $collection;
}


function apply($appno, $appinfo, $userinfo){
	global $dbz;


	$MAuser_info=X1_userdetails();
	if (USER)
	{
	  $nu=$MAuser_info[0];
	  $username = $MAuser_info[1];
	}
	else{
	  $e107_id = 0;
	  $username = "Anonymous";
	}
	
	$noerr = true;
	$i=0;
	$question_count=$_POST['quescnt'];
	//$dv = "data";
	while (($i<$question_count) && ($noerr))
	{
		$data[$i]['data'] = isset($_POST['data'.$i]) ? trim($_POST['data'.$i]) : 'x1_no_data';
		$data[$i]['ques'] = isset($_POST['qnum'.$i])? trim($_POST['qnum'.$i]): -1;
		$data[$i]['parent'] = isset($_POST['parent'.$i]) ? trim($_POST['parent'.$i]) : 0;
		$data[$i]['data'] = strip_tags($data[$i]['data']);
		$i++;
	}


	 // add to database

	$question_list = $dbz->SqlGetAll('fldnum, fldname, format', MA_mapp, " where formno=".$appno." and inuse <> 0 and parent = 0 order by fldnum");
	if(!$question_list){
		echo "Error Q1 - NO QUESTIONS FOUND";
		exit();
	}
	foreach($question_list as $question){
		$q_list[$question['fldnum']]=$question;
	}
	$appnum=$appinfo['app_count'] + 1;
	$frmusr = $username;
	
	$post = "Application number: ".$appnum." \n\r".MA_YHANMSG." ".$appinfo['formtitle']." \n\r".MA_FRMUSRTXT." ".$username."\n\r\n\r";

	//print_r($data);
	$i=$dat=0;
	while ($dat<$question_count){

		if ($data[$dat]['parent']==0 && $data[$dat]['data']!='x1_no_data')
		{
			if($q_list[$data[$dat]['ques']]['format']!='L'){
				$post .= " <b>".MA_QUESTION.":  ".$q_list[$data[$dat]['ques']]['fldname']." \n\r ".MA_RESPONSE.": \n\r</b>".$data[$dat]['data']."\n\r\n\r";
			}
			else{
				$post .="<b>".$q_list[$data[$dat]['ques']]['fldname']."</b>\n\r ";
			}
		}
		elseif($data[$dat]['parent']!=0 && $data[$dat]['data']!='x1_no_data')
		{

			$string ="<b>".MA_QUESTION.": ".$q_list[$data[$dat]['ques']]['fldname']."\n\r ".MA_RESPONSE.": \n\r</b>";
			while($data[$dat]['parent']==$q_list[$data[$dat]['ques']]['fldnum']){
				$string .="&#187; ".$data[$dat]['data']."\n\r";
				$dat++;
			}
			$post .=$string;
		}
		$dat++;
	}
	$post .= "\n\r\n\r";
	
    $output = MAF::createtable("open");
    if(foruminsert($appinfo,$userinfo,$appnum,$post)){
        $output = createtable("open");
        $output .= "<tr><td colspan=\"3\">
        ".$appinfo['tytxt']."
        <br /> <br />
        <h2><center>".MA_YANI." ".$appnum."</center></h2>
        <tr>";

    }
    else{
        $output .= "<tr><td colspan=\"3\">
        <h2><center>".$appinfo['denytxt']."</center></h2>
        <tr>";
    }
    $output .=MAF::createtable("close");
    return $output;

}

function foruminsert($appinfo,$userin, $appnum,$post){
	global $dbz;
	//print_r($appinfo);
	//$fnamqry = "SELECT `forum_name` FROM `".$prefix."_bbforums` WHERE `forum_id` = \"$fmid\"";
	if ( !($fmidr = $dbz->SqlGetRow("forum_name", MA_bbforums, "`forum_id` = ".$appinfo['forum_id'])) )
	{
	  echo "ERROR - GS3 - ".MA_UATOFTERR."! <br>";
	}
	else
	{
	  $fmname = $fmidr['forum_name'];
	  if ($fmname=="")
	  {
		echo "WARNING - ".MA_DNFFFMARWARN."! <br><br>";
	  }
	}
	$ptime = time();

	


	//** create topic in bbtopics first - add last 3 fields for nuke
	//$bbtopqry = "INSERT INTO ".$prefix."_bbtopics (`forum_id`, `topic_title`, `topic_poster`, `topic_time`, `topic_status`, `topic_vote`, `topic_type`) VALUES ($fmid, \"New ".$mrow['formtitle']." #".$lastapp." from ".$frmusr."\", $nu, $ptime, 0, 0, 0)";
	$userinfo=$dbz->SqlGetRow("e7.phpbb_id, pb.username, pb.user_colour, e7.user_email", E_users." as e7 inner join ".EPre.MA_bbusers." as pb on(e7.phpbb_id=pb.user_id) where e7.user_id=".$userin[0]);
	
	if(!$userinfo){
		echo "ERROR - UERR100 - ".MA_UATOFTERR."! <br>";
		exit();
	}

	$top_title="\"New ".$appinfo["formtitle"]." #".$lastapp." from ".$userinfo['username']."\"";
	
	
	
	$bbtopresult = $dbz->SqlInsert(MA_bbtopics, array("forum_id"=>$appinfo['forum_id'], "topic_title"=>$top_title, "topic_poster"=>$userinfo['phpbb_id'], "topic_time"=>$ptime, "topic_status"=>'0', "topic_type"=>'0', "topic_first_poster_name"=>$userinfo['username'], "topic_first_poster_colour"=>$userinfo['user_colour'], "topic_last_poster_name"=>$userinfo['username'], "topic_last_poster_colour"=>$userinfo['user_colour'], "topic_last_post_subject"=>$top_title, "poll_title"=>'' ));
	if(!$bbtopresult)
	{
	  echo "ERROR - GS5 - ".MA_UTCTIFERR."! <br>";
	  exit();
	}
	else{

		$topid=$dbz->LastInsertId();

	}

	$ip_sep = explode('.', $_SERVER['REMOTE_ADDR']);
	$pip = sprintf('%02u.%02u.%02u.%02u', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);



	//$bbpoqry = "INSERT INTO ".$prefix."_bbposts (`topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_smilies`, `enable_sig`) VALUES ($topid, $fmid, $nu, $ptime, '$pip', '$frmusr', 0, 0, 0, 0)";
	$bbporesult = $dbz->SqlInsert(MA_bbposts, array("topic_id"=>$topid, "forum_id"=>$appinfo['forum_id'], "poster_id"=>$userinfo['phpbb_id'], "post_time"=>$ptime, "poster_ip"=>$pip, "post_username"=>$userinfo['username'], "enable_bbcode"=>1, "enable_smilies"=>1, "enable_sig"=>1, "post_subject"=>$top_title, "post_text"=>$post, "post_checksum"=>md5($post), "bbcode_bitfield"=>'', "bbcode_uid"=>'', "post_edit_reason"=>'', "post_approved"=>1));
	if(!$bbporesult)
	{
	  echo "ERROR - GS6 - ".MA_UTPTFERR."! <br>";
	  exit();
	}
	else{
	
		$poid = $dbz->LastInsertId();
	}
	//$bbpotqry = "INSERT INTO ".$prefix."_bbposts_text (`post_id`, `bbcode_uid`, `post_subject`, `post_text`) VALUES ($poid, 0, \"New ".$mrow['formtitle']." #".$lastapp." from ".$frmusr ."\",\"".$post."\")";

	/*
	//** update bbsearch
	define('IN_PHPBB', 1);
	define('SEARCH_WORD_TABLE', $prefix.'_bbsearch_wordlist');
	define('SEARCH_MATCH_TABLE', $prefix.'_bbsearch_wordmatch');
	define('POSTS_TABLE', $prefix.'_bbposts');
	function message_die(){};
	if (file_exists("includes/functions_search.php"))
	{
	  include_once("includes/functions_search.php");
	  add_search_words("single", $poid, $post, "New ".$mrow['formtitle']." #".$lastapp." from ".$frmusr);
	}
	*/

	//** update forum statistics
	//$bbforqry = "UPDATE ".$prefix."_bbforums SET forum_posts = forum_posts + 1, forum_topics = forum_topics + 1, forum_last_post_id = $poid WHERE forum_id = '$fmid'";
	$bbforresult = $dbz->SqlUpdate(MA_bbforums, "forum_posts=forum_posts+1, forum_topics=forum_topics+1, forum_topics_real=forum_topics_real+1, forum_last_post_id=".$poid.",forum_last_poster_id=".$userinfo['phpbb_id'].", forum_last_post_subject=".$top_title.",forum_last_post_time=".$ptime.", forum_last_poster_name='".$userinfo['username']."',forum_last_poster_colour='".$userinfo['user_colour']."' where forum_id=".$appinfo['forum_id']);
	if(!$bbforresult)
	{
	  echo "ERROR - GS9 - ".MA_UTPTFERR."! <br>";
	  exit();
	}
	
	if(!$dbz->SqlUpdate(MA_bbtopics, "topic_first_post_id=".$poid.", topic_last_post_id=".$poid." where topic_id=".$topid)){
	  echo "ERROR - GS11 - ".MA_UTPTFERR."! <br>";
	  exit();
	}
	

//** update user post count

	if (USER)
	{
	  $bbusrqry = "UPDATE ".$user_prefix."_users SET user_posts = user_posts + 1 WHERE user_id = '$cookie[0]'";
	  $bbusrresult = $dbz->SqlUpdate(MA_bbusers, "user_posts = user_posts + 1 WHERE user_id = ".$userinfo['phpbb_id']);
	  if(!$bbusrresult)
	  {
		echo "ERROR - GS10 - ".MA_UTPTFERR."! <br>";
		exit();
	  }
	  //send an email to the user so that they have a copy of the application.
	  e107_require_once(e_HANDLER."mail.php");
	  sendemail($userinfo['user_email'], "Application # ".$appnum." Received", str_ireplace ( "\r\n" , "<br />" , $post ),  $userinfo['user_name'], S_mail, S_name);
	}
	return true;
 
 }
  
      















  ?>