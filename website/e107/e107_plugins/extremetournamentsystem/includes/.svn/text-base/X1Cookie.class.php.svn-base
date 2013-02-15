<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2009 (2.6.0)
##Version 2.6.4
###############################################################


/*
Class X1Cookie
Description: Creates, sets, retrieves anything todo with cookies.
*/
class X1Cookie{

	/*############################################
	Name:SetCookie
	What does it do: Sets the cookies needed for the XT System
	parms:int $set_id: Id of team/mod/user/whatever.  string $set_name: Name of team/mod/user/whatever
	returns: N/A
	##############################################*/ //setteamcookie  domodcookie
	function SetCookie($cookie_name, $set_id, $set_name, $other_info=NULL) {
		$user_details=X1_userdetails();
		if(isset($other_info)){
			$info = base64_encode("$set_id:$set_name:$user_details[0]:$other_info");	
		}
		else{
			$info = base64_encode("$set_id:$set_name:$user_details[0]");
		}
		
		switch($cookie_name){
		 case X1_cookiename:
			$cookie_time=X1_cookietime;
			break;
		 case X1_cookiemod:
			$cookie_time=X1_cookietimemod;
			break;
		 default :
			break;
		}
		if(X1_cookiemode==0){
			if($cookie_time>0){
				return setcookie($cookie_name,"$info",time()+$cookie_time);
			}
			else{
				return setcookie($cookie_name,"$info");			
			}
		}
		elseif(X1_cookiemode==1){
		
			$jscript = 'function setCookie(name, value, expires, path, domain, secure) {
						  var curCookie = name + "=" + escape(value) +
							  ((expires) ? "; expires=" + expires : "") +
							  ((path) ? "; path=" + path : "") +
							  ((domain) ? "; domain=" + domain : "") +
							  ((secure) ? "; secure" : "");
						  document.cookie = curCookie;
						}
						function SetExpDate(time){
							var exdate=new Date();
							exdate.setSeconds(exdate.getDate() + time);
							return exdate.toUTCString();
						}';
			
				$time = time()+$cookie_time;
			$output = "<script type='text/javascript'>
					".$jscript;
					if($cookie_time>=0){
						$output.="setCookie('".$cookie_name."', '$info', SetExpDate('".$cookie_time."'))";
					}
					else{
						$output.="setCookie('".$cookie_name."', '$info')";
					}
					$output .="</script>";
			return DispFunc::X1PluginOutput($output);
			
		}
		else{
		 //error
			return DispFunc::X1PluginOutput(X1_cookie_config);
		}
	}

	/*###########################################
	Name:CookieRead
	What does it do: Read the cookies and returns the data
	parms:string $cookie_name(=X1_cookiename): Name of the cookie 
	returns:cookie data if present else False
	##############################################*/ //cookieread  
	function CookieRead($cookie_name=X1_cookiename){
		if(!isset($_COOKIE[$cookie_name]))return false;
		$my_cookie = explode(":", base64_decode($_COOKIE[$cookie_name]));
		if(isset($my_cookie[0])){
			if(!empty($my_cookie[3])){
				return array($id=$my_cookie[0], $name=$my_cookie[1], $user_id=$my_cookie[2], $other=$my_cookie[3]);	
			}
			return array($id=$my_cookie[0], $name=$my_cookie[1], $user_id=$my_cookie[2]);
		}
		//error
		return false;
	}
	
	/*###########################################
	Name:CheckLogin
	What does it do: Reads the team cookies and determines if your logged in.(Captain and CoCaptains)
	parms:string $cookie_name(=X1_cookiename): The name of the cookie
	returns:True on being logged in and accurate for whom you are, false otherwise.
	##############################################*/
	function CheckLogin($cookie_name=X1_cookiename) {
		if(isset($_GET['op'])){
			$op=DispFunc::X1Clean($_GET['op']);
		}
		else{
			$op=NULL;
		}
		if(isset($_GET['t'])){
			$get_team=DispFunc::X1Clean($_GET['t']);
		}
		else{
			$get_team=NULL;
		}
		
		$my_cookie = X1Cookie::CookieRead($cookie_name);
		$user_cookie = X1_userdetails();

		//Checks to see if we have data in the team cookie
		if (empty($my_cookie[0]) && empty($my_cookie[1])){
			if(isset($my_cookie) && $my_cookie!=false){
				X1Cookie::RemoveCookie($cookie_name);
			}
			return false;
		}
		//Checks to see if we have data in the user cookie.
		if(empty($user_cookie[0]) && empty($user_cookie[1])){
			if(isset($my_cookie)){
				X1Cookie::RemoveCookie($cookie_name);
			}
			return false;
		}
		
		//cookie for teams
		if($cookie_name==X1_cookiename){
			$team_info = SqlGetRow("team_id, name",X1_DB_teams," WHERE team_id =".MakeItemString($my_cookie[0]));
			if($my_cookie[0]!=$team_info['team_id'] && $my_cookie[1]!=$team_info['name']){
				X1Cookie::RemoveCookie($cookie_name);
				return false;
			}	
			//Checks to see if the team requested IS the team the current cookie has.
			if($op=="activate_team"){
				if($team_info['team_id']!=$get_team){
					X1Cookie::RemoveCookie($cookie_name);
					return false;
				}
			}
		}
		elseif($cookie_name==X1_cookiemod){//cookie for Mod
			$mod_info = SqlGetRow("mod_id, mod_name",X1_DB_nukstaff," WHERE mod_id =".MakeItemString($my_cookie[0]));
		    if(strtolower($my_cookie[1]) != strtolower($mod_info['mod_name']) && $my_cookie[0] != $mod_info['mod_id']){
		    X1Cookie::RemoveCookie($cookie_name);
		    echo 5;
				return false;
			}
		}
		else{		
			
		 	//wrong cookie, possible error?
			return false;
		}
		if($my_cookie[2]!=$user_cookie[0]){//Know we have a logged in user, know we have a set cookie, if users don't match remove cookie.
			X1Cookie::RemoveCookie($cookie_name);
			return false;
		}
	    return true;
	}
	
	/*############################################
	Name:RemoveCookie
	What does it do: Sets the cookies needed for the XT System
	parms:int $set_id: Id of team/mod/user/whatever.  string $set_name: Name of team/mod/user/whatever
	returns: N/A
	##############################################*/ //setteamcookie  domodcookie
	function RemoveCookie($cookie_name, $set_id="-0", $set_name="teamloggedout") {
		$info = base64_encode("$set_id:$set_name");
		if(X1_cookiemode==0){
				return setcookie(X1_cookiename,"$info",time());
		}
		elseif(X1_cookiemode==1){
			$jscript = 'function setCookie(name, value, expires, path, domain, secure) {
						  var curCookie = name + "=" + escape(value) +
							  ((expires) ? "; expires=" + expires : "") +
							  ((path) ? "; path=" + path : "") +
							  ((domain) ? "; domain=" + domain : "") +
							  ((secure) ? "; secure" : "");
						  document.cookie = curCookie;
						}
						function SetExpDate(time){
							var exdate=new Date();
							exdate.setSeconds(exdate.getDate() + time);
							return exdate.toUTCString();
						}';
			
				$time = time();
			$c = "
			<script type='text/javascript'>
				$jscript
				setCookie('".X1_cookiename."', '$info', SetExpDate('0'))
			</script>";
			return DispFunc::X1PluginOutput($c);
		}
		else{
		 //error
			return DispFunc::X1PluginOutput(X1_cookie_config);
		}
	}
	
}	
	


?>