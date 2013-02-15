<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008 (2.6.0)
##Version 2.6.3
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function X1ModeratorMenu(){
	$c = "
	<table class='".X1plugin_admintable."' width='100%'>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<th>".XL_modadmin_title."</th>
		</tr>
		<tr>
			<td align='left'>
				<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
					<input name='Add_mod' value='".XL_modadmin_addmoder."' type='submit'>
					<input name='".X1_actionoperator."' type='hidden' value='assignmod'>
				</form>
			</td>
		</tr>
	</tbody>";
	$c .="
	<tbody class='".X1plugin_tablebody."'>
		<tr>
		<td>
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".XL_mod_team;
				$c .= SelectBoxModerator();
				$c .= "	<select name='".X1_actionoperator."'>
					<option value='modifymoderator'>".XL_edit."</option>\n
					<option value='delmoderator'>".XL_delete."</option>\n
				</select>\n
				<input type='submit' value='".XL_ok."'>
			</form>
			</td>
		</tr>
	</tbody>
	</table>
	<br />";
	return DispFunc::X1PluginOutput($c,1);
}



function X1AdminAssignMod(){
	$c = "
	<table class='".X1plugin_teamadmintable."' width='100%'>
    <tbody class='".X1plugin_tablebody."'>
		<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
		<tr>
			<td>";
				
				$person = SqlGetAllPre(X1_DB_usersidkey.",".X1_DB_usersnamekey, X1_userprefix.X1_DB_userstable," order by ".X1_DB_usersnamekey);
				if(!$person){
					AdminLog(XL_failed_retr."(Table:".X1_userprefix.X1_DB_userstable.")","X1AdminAssignMod", "Major Error",ERROR_DIE);
				}
					$c .="
					".XL_teamprofile_husername."  
					<SELECT NAME='user_id'>";		
					foreach($person AS $itm){
						$c .= "<option value='$itm[1]'>$itm[1]</option>";		
					}
					
				$c .= "</select>
				".XL_mod_password."<input name='modpass' type='text' value=''>
				</td>
		</tr>
		<tr>
				<td>
				
					<input name='Submit' type='Submit' value='".XL_modadmin_createmod."' >
					<input name='".X1_actionoperator."' type='hidden' value='addmoder'>
				</td>
		</tr>

		</form>
	</tbody>
	</table>
	<div>";
	$c  .= x1_admin("moderator");
	return DispFunc::X1PluginOutput($c);
}

function X1AddModer(){
	$pass=$_POST['modpass'];
	
	if(!empty($pass)){
	 	if(strlen($pass)>=4){
		$pass=md5($_POST['modpass']);		
		}
		else{
		 	$c=X1AdminAssignMod();
			$c .= DispFunc::X1PluginTitle(XL_modadmin_passshort);
			return DispFunc::X1PluginOutput($c);
		}
	}
	else{
		$c=X1AdminAssignMod();
		$c.= DispFunc::X1PluginTitle(XL_modadmin_nopass);
		return DispFunc::X1PluginOutput($c);
	}
	
	$checkdouble= SqlGetAll("*",X1_DB_nukstaff,"Where mod_name=".MakeItemString($_POST['user_id']));
	if($checkdouble)
	{
		$c  = x1_admin("moderator");
		$c .= DispFunc::X1PluginTitle(XL_modadmin_alreadymod);	
		return DispFunc::X1PluginOutput($c);
	}
	$result = ModifySql("INSERT into ",X1_DB_nukstaff," (mod_name, mod_pswd)
	values (
   	".MakeItemString($_POST['user_id']).",
  	".MakeItemString($pass).")");
	$c  = x1_admin("moderator");
	if($result){
        $c .= DispFunc::X1PluginTitle(XL_modadmin_success);
    }else{
        AdminLog("Failed Database Insert(Table:".X1_DB_nukstaff.")","X1AddModer","Major Error",ERROR_DISP);
    }
	return DispFunc::X1PluginOutput($c);
}

function X1ModifyModerator(){
	$c = "
	<table class='".X1plugin_teamadmintable."' width='100%'>
    <tbody class='".X1plugin_tablebody."'>
		<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
		<tr>
			<td>
				".XL_mod_modname."<input name='x1_name' readonly='text' value='".DispFunc::X1Clean($_POST['moderatorsel'])."'>
			</td>
		</tr>
		<tr>
			<td>
				".XL_mod_password."<input name='x1_mod_pass' type='text' value=''>
			</td>
		</tr>
		<tr>
				<td>
				
					<input name='Submit' type='Submit' value='".XL_modadmin_update."' >
					<input name='".X1_actionoperator."' type='hidden' value='X1_updatemoderator'>
				</td>
		</tr>

		</form>
	</tbody>
	</table>
	<div>";
	$c  .= x1_admin("moderator");
	return DispFunc::X1PluginOutput($c);
}

function X1UpdateMod()
{
  $pass=md5($_POST['x1_mod_pass']);
	$result =ModifySql("UPDATE ",X1_DB_nukstaff," SET mod_pswd =".MakeItemString($pass)." where mod_name=".MakeItemString(DispFunc::X1Clean($_POST['x1_name'])));
	$c  = x1_admin("moderator");
	if($result){
        $c .= DispFunc::X1PluginTitle(XL_modadmin_success);
    }
	else{
        AdminLog(Xl_failed_updat."(Table:".X1_DB_nukstaff.")","X1UpdateMod","Major Error",ERROR_DISP);
    }	
	return DispFunc::X1PluginOutput($c);	
}


function X1DelModerator(){
 	$name=$_POST['moderatorsel'];
 	$result = ModifySql("DELETE FROM ",X1_DB_nukstaff," WHERE mod_name=".MakeItemString($name));
	
	$c  = x1_admin("moderator");	
	if($result){
        $c .= DispFunc::X1PluginTitle($name.XL_modadmin_moddeleted);
    }else{
        AdminLog("Failed delete(Table:".X1_DB_nukstaff.")","X1DelModerator","Major Error",ERROR_DISP);
    }	
	return DispFunc::X1PluginOutput($c);
	
}
