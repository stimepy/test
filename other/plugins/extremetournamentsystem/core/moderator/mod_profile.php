<?php
	function x1_mod_profile(){
	$c  = X1plugin_style();
	$cookie = X1_userdetails();
	if (!isset($cookie[1]))return X1plugin_output($c .= X1plugin_title(XL_teamjoin_login));
	$row = SqlGetRowPre(X1_DB_usersidkey.",".X1_DB_usersnamekey,X1_userprefix.X1_DB_userstable," WHERE ".X1_DB_usersidkey."=".MakeItemString($cookie[0]));
	if (!$row){
		return X1plugin_output($c .= X1plugin_title(XL_teamjoin_login));
	}
	if(!isMod()){
		return X1plugin_output($c .= X1plugin_title(XL_mod_trylogin));
	}
	
	$c .= X1plugin_title(XL_mod_loginpage)."

	<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_jointeamtable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
    	<tr>
    		<th colspan='2'>".XL_mod_profile.":</th>
    	</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
	<tr>
		<td class='alt1'>".XL_teamprofile_husername."</td>
		<td class='alt1'><input name='member' readonly='text' value='$cookie[1]'></td>
	</tr>
	<tr> 
		<td class='alt1'>".XL_mod_changepassword.":</td>
		<td class='alt1'>
		<input type='password' name='m1password'>
		</td>
	</tr>
	<tr> 
		<td class='alt1'>".XL_mod_confirmpassword.":</td>
		<td class='alt1'>
		<input type='password' name='m2password'>
		</td>
	</tr>
	<tr> 
		<td class='alt1'>".XL_mod_email.":</td>
		<td class='alt1'>
		<input type='password' name='m2password'>
		</td>
	</tr>
		<tr> 
		<td class='alt1'>".XL_mod_msn.":</td>
		<td class='alt1'>
		<input type='password' name='m2password'>
		</td>
	</tr>
	<tr> 
		<td class='alt1'>".XL_mod_aim.":</td>
		<td class='alt1'>
		<input type='password' name='m2password'>
		</td>
	</tr>
	<tr> 
		<td class='alt1'>".XL_mod_xfire.":</td>
		<td class='alt1'>
		<input type='password' name='m2password'>
		</td>
	</tr>
	</tbody>
	<tfoot class='".X1plugin_tablefoot."'>
    	<tr> 
    		<input name='".X1_actionoperator."' type='hidden' value='x1_modprofileupdate'>
    		<th colspan='2'><input type='Submit' name='submit' value='".XL_mod_loginbutton."'/></th>
    	</tr>
	</tfoot>
	</table>
	</form>";
	return X1plugin_output($c);
}
?>