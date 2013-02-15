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

  //init_session();  // REQUIRED FOR USER AND USERNAME
require_once(e_PLUGIN."akismet/functions.php");

 global $pref;


if( $pref['akismet_key'] !== "" && $pref['akismet_stop_service'] == 0 ) {



if ( 
      /*From Comment*/
     isset( $_POST['commentsubmit'] ) ) {
     
     if ( !empty( $_POST['comment'] ) && !empty( $_POST['subject'] ) ) {
     
    // if ( akismet_scan( $_POST['comment'], $_POST['subject'], 'Comment' ) == true ) {
  
  if ( akismet_scan( $_POST['comment'], $_POST['subject'], 'Comment' ) == true ) {
   
  $_POST['comment'] = array_random( $messagge );
  $_POST['author_name'] = array_random( $posternames );

     }
     
      }
     }

if ( 
      /*From Comment*/
     isset( $_POST['replysubmit'] ) ) {
     
     if ( !empty( $_POST['comment'] ) && !empty( $_POST['subject'] ) ) {
     
     if ( akismet_scan( $_POST['comment'], $_POST['subject'], 'Comment' ) == true ) {
     
  $_POST['comment'] = array_random( $messagge );
  $_POST['author_name'] = array_random( $posternames );     
     
     }
     
      }
     }

if ( 
     /*From Forum Reply */
     isset( $_POST['reply'] ) ) {
     
     if ( !empty( $_POST['post'] ) ) {
     
    if (  akismet_scan( $_POST['post'], $_POST['subject'], 'Forum Reply' ) == true ) {
    
  $_POST['post'] = array_random( $messagge );
  $_POST['anonname'] = array_random( $posternames );
  $_POST['subject'] = array_random( $messagge );   
    }
     
      }
     }    
     
if ( 
      /*From Forum New Thread*/
     isset( $_POST['newthread'] ) ) {
     
     if ( !empty( $_POST['post'] ) && !empty( $_POST['subject'] ) ) {
     
    if (  akismet_scan( $_POST['post'], $_POST['subject'], 'Forum Thread' ) == true ) {
  $_POST['post'] = array_random( $messagge );
  $_POST['anonname'] = array_random( $posternames );   
  $_POST['subject'] = array_random( $messagge );  
    }
     
      }
     }     

if ( 
      /*From Chat*/
     isset( $_POST['chat_submit'] ) ) {
     
     if ( !empty( $_POST['cmessage'] ) ) {
     
    if ( akismet_scan( $_POST['cmessage'] , '', 'Chatbox' ) == true ) {
    
    
    }
     
      }
     }
     
if ( 
     /*From submitnews.php */
     isset( $_POST['submit'] ) ) {
     
     if ( !empty( $_POST['e107_submitnews_item'] ) && !empty( $_POST['itemtitle'] ) ) {
     
    if ( akismet_scan( $_POST['e107_submitnews_item'], $_POST['itemtitle'], 'SubmitNews' ) == true ) { 
    
    
    }
     
      }
     }     
     
/*if ( 
     
     isset( $_POST['submit'] ) ) {
     
     if ( !empty( $_POST['comment'] ) ) {
     
     akismet_scan( $_POST['comment'] );
     
      }
     }*/  

if ( 
      /*From content plugin*/
     isset( $_POST['create_content'] ) ) {
     
     if ( !empty( $_POST['content_text'] ) && !empty( $_POST['content_heading'] ) ) {
     
    if ( akismet_scan( $_POST['content_text'], $_POST['content_heading'], 'ContentPlugin' ) == true ) {
    
    }
     
      }
     }
     
if ( 
      /*From links.php */
     isset( $_POST['add_link'] ) ) {
     
     if ( !empty( $_POST['link_description'] ) && !empty( $_POST['link_name'] ) && !empty( $_POST['link_url'] ) ) {
     
    if ( akismet_scan( $_POST['link_description'], $_POST['link_name'], 'Links' ) == true ) { 
    
    
    }
     
      }
     }



      
      
      }
?>