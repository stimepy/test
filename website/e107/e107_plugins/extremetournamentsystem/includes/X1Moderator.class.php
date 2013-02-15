<?php
	
class X1Moderator{
 
	/*############################################
	name:ModeMakeLogin
	what does it do: Sets up the login page for the moderator
	needs:N/A
	returns:N/A
	###########################################*/
	function ModMakeLogin(){
		$c  = DispFunc::X1PluginStyle();
		$cookie = X1_userdetails();
		if (!isset($cookie[1])){
		 	return X1plugin_output($c .= DispFunc::X1PluginTitle(XL_teamjoin_login));
		}
		$row = SqlGetRowPre(X1_DB_usersidkey.",".X1_DB_usersnamekey , X1_userprefix.X1_DB_userstable, " WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));  
		if (!$row){
			return X1plugin_output($c .= DispFunc::X1PluginTitle(XL_teamjoin_login));
		}
		if(!X1Moderator::IsMod()){
			return X1plugin_output($c .= DispFunc::X1PluginTitle(XL_mod_trylogin));
		}
		
		$c .= DispFunc::X1PluginTitle(XL_mod_loginpage)."
	
		<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_jointeamtable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
	    	<tr>
	    		<th colspan='2'>".XL_mod_loginpage.":</th>
	    	</tr>
	    </thead>
	    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamprofile_husername."</td>
			<td class='alt1'><input name='member' type='text' readonly value='$cookie[1]'></td>
		</tr>
		<tr> 
			<td class='alt1'>".XL_mod_password.":</td>
			<td class='alt1'>
			<input type='password' name='mpassword'>
			</td>
		</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
	    	<tr> 
	    		<input name='".X1_actionoperator."' type='hidden' value='loginmod'>
	    		<th colspan='2'><input type='Submit' name='submit' value='".XL_mod_loginbutton."'/></th>
	    	</tr>
		</tfoot>
		</table>
		</form>";
		return DispFunc::X1PluginOutput($c);
	}

	/*############################################
	name:X1ActivateModerator
	what does it do: Displays a screen while your cookies refresh
	needs:N/A
	returns:N/A
	###########################################*/
	function X1ActivateModerator(){
		$c  = DispFunc::X1PluginStyle();
		$c .= "<meta http-equiv='refresh' content='".X1_refreshtime.";URL=".X1_publicpostfile.X1_linkactionoperator."modindex'>";	
		$c .= DispFunc::X1PluginTitle("<a href='".X1_publicpostfile.X1_linkactionoperator."modindex'>".XL_moderator_activating."</a>");	
		return DispFunc::X1PluginOutput($c);
	}
	
	/*############################################
	name:ModDoLogin
	what does it do: Logins in the moderator
	needs:N/A
	returns:true on success, false on falure
	###########################################*/	
	function ModDoLogin(){
		$mod_Pass=md5($_POST['mpassword']);
	
		$results =	SqlGetRow("mod_id",X1_DB_nukstaff," WHERE mod_name =".MakeItemString($_POST['member']). "and mod_pswd =" .MakeItemString($mod_Pass));
		if($results){
			if(X1Moderator::X1SetModLogin($results['mod_id'])){
				X1Moderator::X1ActivateModerator();
				return true;
			}
		}
		DispFunc::X1PluginOutput(XL_mod_failedlog);
		X1Moderator::ModMakeLogin();
		return false;
			
	}



	/*############################################
	name:CheckStaff
	what does it do: Sees if the person who wants access to moderator pages can access them officially
	needs:N/A
	returns:true on success false on failure
	###########################################*/
	function CheckStaff($hide=false){
		$mod=X1Moderator::IsMod();
	 	if(X1Cookie::CheckLogin(X1_cookiemod))
		{
			return true;	
		}
		elseif($mod)
		{
			X1Moderator::ModMakeLogin();
			return false;
		}
		else{
			if($hide==false){
				X1File::X1LoadFile("user_index.php",X1_plugpath."core/user/");
				//require_once(X1_plugpath."core/user/user_index.php");
				DispFunc::X1PluginOutput(XL_Mod_nopriv);
				X1plugin_index();
			}
			return false;
		}
	 }


	/*############################################
	name:IsMod
	what does it do: Sees if the person has moderator privliges or not
	needs:N/A
	returns:true on success false on failure
	###########################################*/
	function IsMod(){
		$cookie = X1_userdetails();
		$mod_cookie=X1Cookie::CookieRead(X1_cookiemod);
		if(empty($cookie[0]))return false;
		if(empty($cookie[1]))return false;
		$row = SqlGetRow("mod_id, mod_name",X1_DB_nukstaff," WHERE mod_name =".MakeItemString($cookie[1]));
		if($row){
			return true;
		}
		return false;		
	}

	/*############################################
	name:X1SetModLogin
	what does it do: Sets the moderator login
	needs:int $stfid: the staff ID;
	returns:true on success false on failure
	###########################################*/
function X1SetModLogin($stfid=""){
	if(!isset($stfid))return false;
	$row = SqlGetRow("*",X1_DB_nukstaff," WHERE mod_id =".MakeItemString($stfid));
	
	if($row)
	{
		X1Cookie::SetCookie(X1_cookiemod,$row['mod_id'],$row['mod_name']);
		return true;
	}
	else{
		return false;
	}
}


}

?>