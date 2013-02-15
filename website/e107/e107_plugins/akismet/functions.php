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


require_once(e_BASE."class2.php");

global $pref;
 
$posternames = explode('|', $pref['akismet_poster_names'] );

$messagge = explode('|', $pref['akismet_poster_messages'] );

function array_random($array){

return $array[array_rand($array)];

}

 function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date         = strtotime($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
        
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}




 
 
   function start_akis($c,$d,$u,$i,$s,$w,$q) {
   global $sql, $pref;
   
   //$p = explode("?",$q);
   $go = $pref['akismet_redir'] ? $pref['akismet_redir'] : e_PAGE."?".e_QUERY;
   
      $sql -> db_Insert("akismet", array("spam_comment" => $c, "spam_userid" => $d, "spam_username" => $u, "spam_userip" => $i, "spam_subject" => $s, "spam_where" => $w, "spam_query" => $q ));
      
      header("location: ".$go);
      exit;
     }
     
  function akismet_scan( $body_text , $subject, $cat )
  {
    global $pref;

      $subject = $subject ? $subject : '';
      $author = $author ? $author : USERNAME;
      $query = (e_QUERY ? e_PAGE."?".e_QUERY : e_PAGE);
      $ip        = getip();
     

 
     $php_ver = sprintf ( '%d' , phpversion() );
   
    
    if ( $php_ver == '4' ) {
    
    require_once(e_PLUGIN."akismet/akismet.class.4.php");
    
    
    $datas = array(
            'author'    => $author,
            'email'     => '',
            'website'   => '',
            'body'      => $body_text,
            'permalink' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'],
         );
 
 $akismet = new Akismet('http://'.$_SERVER['SERVER_NAME'], $pref['akismet_key'], $datas);
    
 if($akismet->errorsExist()) {
         echo"Couldn't connected to Akismet server!";
     } 
    
    if($akismet->isSpam()) {
       
       //$pref['askimet_spam'] = ( $pref['askimet_spam'] + 1 );
       
     if ( $pref['akismet_reuse_spam_message'] == 0 ) {
      
     start_akis($body_text,USERID,$author,$ip,$subject,$cat,$query);
     
     } 
     
     return true;
     
    } 
    
    } else if ( $php_ver == '5' ){
    require_once(e_PLUGIN."akismet/akismet.class.5.php");
    
    $akismet = new Akismet('http://'.$_SERVER['SERVER_NAME'], $pref['akismet_key']);
    $akismet->setCommentAuthor($author);
    $akismet->setCommentAuthorEmail('');
    $akismet->setCommentAuthorURL('');
    $akismet->setCommentContent($body_text);
    $akismet->setPermalink('http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']);
    if($akismet->isCommentSpam())
     { 
     
       //$pref['askimet_spam'] = ( $pref['askimet_spam'] + 1 );
       
      if ( $pref['akismet_reuse_spam_message'] == 0 ) {
      
     start_akis($body_text,USERID,$author,$ip,$subject,$cat,$query); 
      
      }
      
      return true;
      
     }
    
    }
    
    



    }
  //}

?>