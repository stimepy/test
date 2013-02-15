<?php
/*
+---------------------------------------------------------------+
|        Akismet AntiSpam v6.0
|        coded by aSeptik
|        http://ask.altervista.org
|        aseptik@gmail.com
|        
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License Version 3 (http://gnu.org).
+---------------------------------------------------------------+
*/

//---------------------------

require_once("../../class2.php");

 if (!getperms("P")) {
	header("location:".e_BASE."index.php");
	exit;
}
@include_once(e_PLUGIN.'akismet/languages/'.e_LANGUAGE.'.php');
@include_once(e_PLUGIN.'akismet/languages/English.php');
require_once(e_ADMIN."auth.php");
 // require_once("functions.php");

  //unset($text);

//---------------------------





/*if ( ( $_POST['action'] ) ) {
echo "<script>alert('porko dio')</script>";
switch(  $_POST['action'] ) {
case "akismet_moderate_button":
	if (is_array($_POST['akismet_spam_delete'])) {
		while (list ($key, $cid) = each ($_POST['akismet_spam_delete'])) {
			if ($sql->db_Select("akismet", "*", "spam_id='$cid' ")) {
				$row = $sql->db_Fetch();
				delete_children($row, $cid);
			} 
	
		}
	} 
  break;
	case "spam_update":
		if (is_array($_POST['spam_update'])) {
		
		
				while (list ($key, $cid) = each ($_POST['spam_update'])) {
			if ($sql->db_Select("akismet", "*", "spam_id='$cid' ")) { 
        $row = $sql->db_Fetch();
        $c = explode("?",$row['spam_where']);
	      $n = explode(".",$c[1]);
	      $nid = $n[2];
				update_children($row, $cid, $nid);
				delete_children($row, $cid);
				
			}
		}
  }
	break;
	
	$e107cache->clear("akismet");
 }
}
 function update_children($row, $cid, $nid) {
 global $sql, $sql2, $sql3, $table;
 	$c_del[] = $cid;

  while (list ($key, $cid) = each ($c_del)) {
	
	
	$sql->db_Insert("comments", array("comment_item_id" => $nid, "comment_comment" => $row['spam_comment'], "comment_author" => $row['spam_username']));
	
  $sql3 = new db;
  $sql3->db_Update("news","news_comment_total=news_comment_total+1 WHERE news_id = '$nid'");
  }
 }
 
 function delete_children($row, $cid) {
 global $sql, $sql2, $table;
 	$c_del[] = $cid;
	while (list ($key, $cid) = each ($c_del)) {
		$sql->db_Delete("akismet", "spam_id='$cid'");
	}
 }*/
 
 
 
        
         
if ( isset( $_POST['option_submit'] ) ) {
$pref['akismet_stop_service'] = isset($_POST['akismet_stop_service']) ? '1' : '0';
$pref['akismet_reuse_spam_message'] = isset($_POST['akismet_reuse_spam_message']) ? '1' : '0';
  
  $pref['akismet_poster_names'] =  $tp->toDB($_POST['akismet_poster_names']);
  $pref['akismet_poster_messages'] = $tp->toDB($_POST['akismet_poster_messages']);
   save_prefs();
   echo "Preferences Updated!";
  }
  
if (isset($_POST['config_submit'])) {
  
    $pref['akismet_key']   = $_POST['akismet_key'];
   // $pref['akismet_type']  = $_POST['akismet_type'];
    $pref['akismet_redir'] = $_POST['akismet_redir'];
    save_prefs();
 echo "Preferences Updated!";
  }
  
   $pref['akismet_reuse_spam_message'] = $pref['akismet_reuse_spam_message'] ? $pref['akismet_reuse_spam_message'] : 0;
   $pref['akismet_stop_service'] = $pref['akismet_stop_service'] ? $pref['akismet_stop_service'] : 0;
   $akismet_stop_service = ( $pref['akismet_stop_service'] == '1' ? 'checked' : '' );
   $akismet_reuse_spam_message = ( $pref['akismet_reuse_spam_message'] == '1'  ? 'checked' :  '' );
  
/*if ($pref['akismet_type'] == "php4"){
$php4 = " selected='selected'";
$php5 = "";
} else {
$php5 = " selected='selected'";
$php4 = "";
}*/


    


  $text .= "
<div id='akis_wrap'>
    <div class=\"tabs\">
        <ul class=\"tabNavigation\">
            <li><a href=\"#first\">Settings</a></li>
            <li><a href=\"#second\">Moderate</a></li>
            <li><a href=\"#third\">Statistics</a></li>
            <li><a href=\"#four\">Options</a></li>
        </ul>
         ";


$text .= "<div id=\"third\">";

if ( !empty($pref['akismet_key']) ) {

$text .= "<div style=\"width:100%;height:800px\"><iframe src=\"http://".$pref['akismet_key'].".web.akismet.com/1.0/user-stats.php?blog=http://".$_SERVER['SERVER_NAME']."\" width=\"100%\" height=\"100%\" frameborder=\"0\" id=\"akismet-stats-frame\"></iframe></div>\n";

} else { 

$text .= "<table class='akis_table_body' ><tr><td style='width:500px' class='akis_td'>no spam yet! ;)</td></tr></table>";

}

$text .= "</div>";

  $text .= " <div id=\"first\">
					<form class='akismet_form' method='post' action='".e_SELF."'>
						<table class='akis_table'>


  				<tr>
								<td class='akis_td_1'>Akismet API Key</td>
								<td class='akis_td_1'>
									<input class='akis_input' type='text' name='akismet_key' value='".$pref['akismet_key']."' /></td><td class='akis_td_1'> ".AKIS_0."
								</td>
							</tr>
              
             <!-- 			<tr>
								<td class='akis_td_1'>Chose type:</td>
								<td class='akis_td_1'>
								<select class='akis_select' name='akismet_type'>
								<option value='php4' ".$php4." >php 4</option>
								<option value='php5' ".$php5." >php 5</option>
                </select> </td><td class='akis_td_1'> ".AKIS_2."
                </td>
							</tr> -->
              	
                <tr>
								<td class='akis_td_1'>Page Redirection:</td>
								<td class='akis_td_1'>
									<input class='akis_input'  type='text' name='akismet_redir' value='".$pref['akismet_redir']."' /> </td><td class='akis_td_1'>".AKIS_1."
								</td>
							</tr>
              <tr><td></td><td></td>
								<td style='text-align:right;padding:10px;'>
									<input class='akis_button' type='submit' name='config_submit' value='Submit' />
								</td>
							</tr></table>	</form>
				\n </div>
              ";
 
//---------------------------



  
  $text .= "<div id='second'>
  
  <form class='akismet_form' method='POST' action='".e_SELF."'>";
  
 $sql2 = new db;
 $spam_count = $sql2 -> db_Count("akismet","(*)");

 if ( $spam_count > 0 ) {
  
  $text .= "
  <table class='akis_table_body' border='0' colspan='0' celpadding='0' cellspacing='0' >
  <tr>
  <td style='width:50px'   class='akis_td'>Id</td>
  <td style='width:450px'  class='akis_td'>Spam Comment</td>
  <td style='width:100px'  class='akis_td'>Date</td>
  <td style='width:100px'  class='akis_td'>User ip</td>
  <td style='width:100px'  class='akis_td'>Username</td>
  <td style='width:100px'  class='akis_td'>Category</td>
  <td style='width:50px'   class='akis_td'><input id=\"select_deselect\" type=\"checkbox\" name=\"select_deselect\" value=\"1\" /> Delete</td>
  <td style='width:50px' class='akis_td'>Not Spam</td>
  
  </tr>
  </table>";
  
  }
  
  $text .= "<div id='akismet_content_spam'></div>";  
  
  $text .= "</form></div>";
  
  $text .= "<div id='four'>";
  
  $text .= "	<form class='akismet_form' method='post' action='".e_SELF."'>
  
  <table class='akis_table'>
                    <tr>
								<td class='akis_td_1'>Stop Akismet Service</td>
								<td class='akis_td_1'>
									<input type='checkbox' id='akismet_stop_service' name='akismet_stop_service' value='".$pref['akismet_stop_service']."' $akismet_stop_service/></td><td class='akis_td_1'> ".AKIS_6."
								</td>
							</tr>
          <tr>
								<td class='akis_td_1'>Reuse Spam Message As normal Post</td>
								<td class='akis_td_1'>
									<input type='checkbox' id='akismet_reuse_spam_message' name='akismet_reuse_spam_message' value='".$pref['akismet_reuse_spam_message']."' $akismet_reuse_spam_message/>
                  </td><td class='akis_td_1'> ".AKIS_5."
								</td>
							</tr>
  				<tr>
								<td class='akis_td_1'>Poster Names</td>
								<td class='akis_td_1'>
									<textarea class='akis_textarea' name='akismet_poster_names'>".$tp->toDB($pref['akismet_poster_names'])."</textarea></td><td class='akis_td_1'> ".AKIS_4."
								</td>
							</tr>
							  				<tr>
								<td class='akis_td_1'>Poster Messages</td>
								<td class='akis_td_1'>
									<textarea class='akis_textarea' name='akismet_poster_messages'>".$tp->toDB($pref['akismet_poster_messages'])."</textarea></td><td class='akis_td_1'> ".AKIS_3."
								</td>
							</tr>						
              <tr><td></td><td></td>
								<td style='text-align:right;padding:10px;'>
									<input class='akis_button' type='submit' name='option_submit' value='Save Options' />
								</td>
							</tr>
              </table></form>";
  
  $text .= "</div";
  
  
  
  $text .= "</div></div>";




  $ns -> tablerender(" Akismet &rarr; Control Panel ", $text);

  require_once(e_ADMIN."footer.php");

?>