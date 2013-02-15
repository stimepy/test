<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include')){die ("You cannot load this file outfile of X1plugin");}
###############################################################



function UserLog($output, $function, $title="General Error", $return_type=0){
	if(empty($output)||empty($function)){
		X1Log::log_write(X1_user_log, $output="output($output) or function($function) is empty", $function="AdminLog", $title = 'Major Error');
		return DispFunc::X1PluginOutput(XL_error_sys);
	}
	
	X1Log::log_write(X1_user_log, $output, $function, $title);
	DispFunc::DispPreLoggedError($return_type);
}

?>