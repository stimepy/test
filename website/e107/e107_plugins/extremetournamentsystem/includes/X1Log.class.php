<?php
##original Copyright:
/************************************************************************
   Nuke-Evolution: Admin / Error Tracker
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : log.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 11.28.2005 (mm.dd.yyyy)

   Notes         : Logs the following:
                        - Admin account creation
                        - Failed admin logins
                        - Intruder Alert
                        - MySQL Errors
                   Original admin tracker by Technocrat
************************************************************************/
//Modified for Nuke Ladder - XTS by Kris Sherrerd:
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Version of file: 1.0.3
## Modified 2/1/2010
###############################################################

if (!defined('X1plugin_include'))exit();

class X1Log{
	function log_write($file, $output, $function="", $title = 'General Error') {
			if(empty($file)){
				$file=X1_logpath.'/system_log.log';
			}
			else{
				$file=X1_logpath.'/'.$file.'.log';
			}
			
			$cookie=X1_userdetails();
	    if(isset($cookie)) {
	    	$username = $cookie[1];
	    } 
			else {
	    	$username = _ANONYMOUS;
	    }
	    
	    $ip = X1Misc::get_ip();
	    $date = date("d M Y - H:i:s");
	    $count=0;
	    $keys=array_keys($_REQUEST);
			foreach($_REQUEST as $request){
				$c ='Name of Item::'.$keys[$count++].'<>Item::'.$request.', ';
			}
			
	    $string = "Get: ".$c." <br /> Function:".$function."\n.";
	    $header = "--------- [" . $title . "] ------------------------------------------------------------------------------------------------------------\n";
	    $wdata = $header;
	    $wdata .= "- [" . $date . "] - \n";
	    $wdata .= "User: ".$username."\n";
	    $wdata .= 'IP: '.$ip."\n";
	    $wdata .= $string;
	    $wdata .= "\n Error Message:";
	    if(is_array($output)) {
	        foreach($output as $line) {
	             $wdata .= htmlspecialchars($line) . "\n";
	        }
	    } else {
	        $wdata .= htmlspecialchars($output) . "\n";
	    }
	    $wdata .= str_repeat('-', strlen($header));
	    $wdata .= "\n\n";
	    if(X1_logfiles){
		  	if(is_writable($file)){
		   		if($handle = @fopen($file,'a')) {
		      	  fwrite($handle, $wdata);
		        	fclose($handle);
		    	}
		    }
				else{
		    	DispFunc::X1PluginOutput(XL_log_nowrite.'<br />');
		    	DispFunc::X1PluginOutput($wdata."<br />");
		    	return false;
		    }
		  }
			else{
				DispFunc::X1PluginOutput($wdata);
			}
	    return true;
	}
	
	function log_size($file) {
	 //global $db, $prefix;
	
    $filename = X1_logpath.'/'.$file.'.log';
    if(!is_file($filename)) {
        return -1;
    }
    if(!is_writable($filename)) {
        return -2;
    }
    if(filesize($filename) == 0) {
        return 0;
    }
    $handle = @fopen($filename,'r');
    if($handle) {
        $content = fread($handle, filesize($filename));
        @fclose($handle);
    } 
		else {
        return -1;
    }
    $file_num = substr_count($content, "\n");
    //$row_log = $db->sql_ufetchrow('SELECT ' . $file . '_log_lines FROM '.$prefix.'_config');
    if($row_log[0] != $file_num) {
        return 1;
    }
	  return 0;
	}
	
	/*#############################
	Name: EraseLog
	What does it do: clears the log.
	Params: $string $log_name
	Returns: true on success, false on falure
	###############################*/	 
	function DeleteLog($file_name){
		if(empty($file_name)){
			return false;
		}
		$file=X1_logpath.'/'.$file_name.'.log';
		if(is_writable($file)){
			return unlink($file);		
		}
		return false;
	}
}

?>