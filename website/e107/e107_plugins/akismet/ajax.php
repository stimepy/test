<?php
require_once("../../class2.php");
//if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }


 function delete_children($row, $cid) {
 global $sql, $sql2, $table;
 	$c_del[] = $cid;
	while (list ($key, $cid) = each ($c_del)) {
		$sql->db_Delete("akismet", "spam_id='$cid'");
	}
 }


function update_children($row, $cid) {
 
 	
   $c_del[] = $cid;

  while (list ($key, $cid) = each ($c_del)) {
	
	switch( strlower( $row['spam_where'] ) ){
        case 'chatbox':
        	global $sql;
          $sql->db_Insert("chatbox", array("cb_message" => $row['spam_comment'], 
                                           "cb_nick" => $row['spam_username'],
                                           "cb_datestamp" => mktime(),
                                           "cb_ip" => $row['spam_userip']
                                           ));
        	
        break;
        
        case 'submitnews':
        global $sql;
        $sql->db_Insert("submitnews", array("submitnews_item" => $row['spam_comment'], 
                                            "submitnews_name" => $row['spam_username'], 
                                            "submitnews_title" => $row['spam_subject'], 
                                            "submitnews_ip" => $row['spam_userip'], 
                                            "submitnews_datestamp" => mktime() ) );
        break;
        
        case 'comment':
        global $sql;
        $c = explode("?",$row['spam_query']);
	      $n = explode(".",$c[1]);
	      $nid = $n[2];
        $sql->db_Insert("comments", array("comment_item_id" => $nid, "comment_comment" => $row['spam_comment'], "comment_author" => $row['spam_username']));
	
  $sql3 = new db;
  $sql3->db_Update("news","news_comment_total=news_comment_total+1 WHERE news_id = '$nid'");
        break;
     }
   }
 }


 function get_e_akismet_spam() { 
  global $sql;

 $sql2 = new db;
 $spam_count = $sql2 -> db_Count("akismet","(*)");

 if ( $spam_count > 0 ) {
 
 $akismet_content_spam .="<table class='akis_table_body' border='0' colspan='0' celpadding='0' cellspacing='0' >";
  
 $sql->db_select('akismet','*','ORDER BY spam_timedate DESC', false);
 
  while($row = $sql -> db_Fetch())
	  {
 extract($row);


//$cat = explode("?",$spam_where);
//$where = substr($cat[0],0,-4);

//user or guest?	
  if ($spam_userid == 0){
  
  if ($spam_username == "USERNAME"){
$user_link = "<b>Anonymous</b>";
  } else {
$user_link = "<b>".$spam_username."</b>";
  }
  
  } else {

  $user_link = e107UserUrl($spam_userid, $spam_username);
}

//textarea size based on words count
$words = explode(' ',$spam_comment);
$count = count($words);
if ($count > 6){
$height = "60px";

} else {
$height = "20px";
}



    $akismet_content_spam .= "<tr>
    <td style='width:50px' class='akis_td'>".$spam_id."</td>
    <td style='width:450px' class='akis_td'>
    <input type='text' name='akis_info_text' class='akis_info_text' value='subject: $spam_subject' />
    <textarea style='height:$height;' name='comdata[]'>".$spam_comment."</textarea>
    </td>
    
   
    <td style='width:100px' class='akis_td'>".nicetime($spam_timedate)."</td>
    <td style='width:100px' class='akis_td'> {$spam_userip}</td>
    <td style='width:100px' class='akis_td'> {$user_link} </td>
    <td style='width:100px' class='akis_td'> {$spam_where}</td>
    <td style='width:50px'  class='akis_td'><input type='checkbox' class='check_me akismet_spam_delete'  name='akismet_spam_delete' value='{$spam_id}' /></td>
    <td style='width:50px' class='akis_td'><input class='akismet_spam_update' type='checkbox' name='akismet_spam_update' value='{$spam_id}' />
    </td>
    </tr>
   
    ";
    
      
    
  }
  
  
    $akismet_content_spam .= "

</table>   <table class='akis_table_foot'><tr>
								<td style='text-align:right;padding:10px;'>
<input id='akismet_moderate_button' class='akis_button' type='button' name='akismet_moderate_button' value='Moderate' onclick='AkismetModButton();' />
								</td></tr>
   </table>";
  
  
  return $akismet_content_spam;
  
 } else {
 
 return "<table class='akis_table_body' ><tr><td style='width:500px' class='akis_td'>no spam yet! ;)</td></tr></table>";
 
 }
 
 
 }


if ( ( $_POST['action'] ) ) {

switch(  $_POST['action'] ) {

case "akismet_update_spam":

echo get_e_akismet_spam();

break;

case "akismet_moderate_button":

	if (is_array($_POST['akismet_spam_delete'])) {
		while (list ($key, $cid) = each ($_POST['akismet_spam_delete'])) {
				$sql->db_Delete("akismet","spam_id='$cid'");
	 }
  }
  
  		if (is_array($_POST['akismet_spam_update'])) {
				while (list ($key, $cid) = each ($_POST['akismet_spam_update'])) {
			if ($sql->db_Select("akismet", "*", "spam_id='$cid' ")) { 
        $row = $sql->db_Fetch();
				update_children($row, $cid);
				delete_children($row, $cid);
				
			}
		}
  }
  
  break;
	
	$e107cache->clear("akismet");
 }
}
 
 
 

 
 
?>