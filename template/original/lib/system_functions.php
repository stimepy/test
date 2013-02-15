<?php
	/******************************************************
	name stri_replace
	Needs String $find, String $replace, String $string 
	returns string $string
	What does it do: Finds a specified $find and replaces it with $replace in the string $string
	******************************************************/
	function stri_replace( $find, $replace, $string ) {
	// Case-insensitive str_replace()
	
	  $parts = explode( strtolower($find), strtolower($string) );
	
	  $pos = 0;
	
	  foreach( $parts as $key=>$part ){
	    $parts[ $key ] = substr($string, $pos, strlen($part));
	    $pos += strlen($part) + strlen($find);
	  }
	
	  return( join( $replace, $parts ) );
	}


	/***************************************
	name: txt2html
	Needs: String $txt
	returns: string $html
	What does it do: converts text to html
	***************************************/
	function txt2html($txt) {
	// Transforms txt in html
	
	  //Kills double spaces and spaces inside tags.
	  if( !( strpos($txt,'  ') === FALSE ) ) $txt = str_replace('  ',' ',$txt);
	  $txt = str_replace(' >','>',$txt);
	  $txt = str_replace('< ','<',$txt);
	
	  //Transforms accents in html entities.
	  $txt = htmlentities($txt);
	
	  //We need some HTML entities back!
	  $txt = str_replace('&quot;','"',$txt);
	  $txt = str_replace('&lt;','<',$txt);
	  $txt = str_replace('&gt;','>',$txt);
	  $txt = str_replace('&amp;','&',$txt);
	
	  //Ajdusts links - anything starting with HTTP opens in a new window
	  //$txt = stri_replace("<a href=\"http://","<a target=\"_blank\" href=\"http://",$txt);
	  //$txt = stri_replace("<a href=http://","<a target=\"_blank\" href=http://",$txt);
	
	  //Basic formatting
	  $eol = ( strpos($txt,"\r") === FALSE ) ? "\n" : "\r\n";
	  $html = '<p>'.str_replace("$eol$eol","</p><p>",$txt).'</p>';
	  $html = str_replace("$eol","<br />\n",$html);
	  $html = str_replace("</p>","</p>\n\n",$html);
	  $html = str_replace("<p></p>","<p>&nbsp;</p>",$html);
	
	  //Wipes <br> after block tags (for when the user includes some html in the text).
	  $wipebr = Array("table","tr","td","blockquote","ul","ol","li");
	
	  for($x = 0; $x < count($wipebr); $x++) {
	
	    $tag = $wipebr[$x];
	    $html = str_replace("<$tag><br />","<$tag>",$html);
	    $html = str_replace("</$tag><br />","</$tag>",$html);
	
	  }
	
	  return $html;
	}

	/*****************************************
	Function:Connect
	Needs:N/A
	Returns:N/A
	What does it do:Connects to the database
	******************************************/	
	function Connect(){
 		require_once('adodb/adodb.inc.php');
		global $xdb,$dbhost,$dbuname,$dbpass,$dbname;;
		$xdb = ADONewConnection('mysql');
		$result = $xdb->Connect($dbhost,$dbuname,$dbpass,$dbname);
		$ADODB_FETCH_MODE =  'ADODB_FETCH_ASSOC';
		if(!$result)die("Could not connect to the database.");
		$xdb->debug =false;
	}

function StriSeleteLastLines( $find, $string ) {
  $parts = explode($find, $string);

  return $parts[0];
}

function StriSplit( $find, $string, $get_council) {
	$parts = explode($find, $string);
	$council[0] = "<div style=\"text-align:center\">";
	$council[1] = $parts[1];
	$council[2] = "</div>";
	$council = join("", $council);
	$xfire = $parts[2];
	$parts = array_merge( (array)$parts[0],(array)$parts[2] );//merging the array to create a single division webpage.  (Council, and all divisions will be merged later.)
	$parts = join(" ", $parts);
	$xfire = XfireSort($xfire);
	if($get_council){
		return array($parts, $council, $xfire);
	}
	else{
		return array($parts, $xfire);
	}
		
}

function XfireSort($xfires){
	$xfires = explode("xxx", $xfires);
	$count=1;
	while($count!=sizeof($xfires)){
		$temp_xfire1 = explode("<br />",$xfires[$count]);
		$temp_xfire1 = $temp_xfire1[0];
		$temp_xfire1 = ltrim($temp_xfire1);
		if(preg_match("/-/",$temp_xfire1))
		{	
		 	$temp_xfire1 = explode("vote", strtolower($temp_xfire1));
		 	$xfire_list[$count-1]= $temp_xfire1[0];	
		}
		else{
			$xfire_list[$count-1]= strtolower($temp_xfire1);	
		}
		$count++;	
		
	}
	return $xfire_list;
	
}

?>