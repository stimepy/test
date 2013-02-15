<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function playerprofile() {
	$c  = DispFunc::X1PluginStyle();
	$c .= DispFunc::X1PluginTitle(XL_playerprofile_title);
	$c .= "<table class='".X1plugin_playerprofiletable."' width='100%'>";
	$userinfo=X1_userdetails();
	$row = SqlGetRow(X1_prefix.X1_DB_userinfo.".*, ".X1_userprefix.X1_DB_userstable.".".X1_DB_usersnamekey,X1_DB_userinfo.",".X1_userprefix.X1_DB_userstable," WHERE ".X1_prefix.X1_DB_userinfo.".uid=".MakeItemString($_REQUEST['member'])." and ".X1_userprefix.X1_DB_userstable.".".X1_DB_usersidkey."=".MakeItemString($_REQUEST['member']));
	
	if ($row){
		if($userinfo[0]==$_REQUEST['member']){
			if(isset($_REQUEST['edit'])){
				if($_REQUEST['edit']=='yes'){
					$c .=EditableProfile($row);
					$span=6;
					$c .= DispFunc::DisplaySpecialFooter($span);
					return DispFunc::X1PluginOutput($c);
				}
			}
		$edit_button="<form method='post' action='".X1_publicpostfile."?op=playerprofile&member=$_REQUEST[member]&edit=yes ' style='".X1_formstyle."'>
						<input type='Submit' name='Submit' value='".XL_playerprofile_edit."' >
						<input name='".X1_actionoperator."' type='hidden' value='playerprofile'>";
		}	
		
		
		list ($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink) = X1TeamUser::ContactIcons($row, false);
		$c .= "
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_playerprofile_title.": ".$row['gam_name']."</th>
    		</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>";
		if(isset($edit_button)){
			$c.= $edit_button;
		}
		
		$c.= "<tr>
			<td class='alt1'>".XL_teamprofile_homepage."</td>.";
		if(isset($weblink)){
			$c.="<td class='alt1'>$weblink</td>";
		}
		else{
			$c.="<td class='alt1'>No Website Available</td>";
		}	
		$c.="</tr>
		<tr>
			<td class='alt2'>".XL_playerprofile_location."</td>
			<td class='alt2'><a href='http://en.wikipedia.org/wiki/$row[p_country]' target='_blank'>".$row['p_country']."</a></td>
		</tr>
		<tr>
			<td class='alt1'>".XL_playerprofile_contact."</td>
			<td class='alt1'>$maillink $msnlink $yimlink $aimlink $icqlink $xfirelink</td>
		</tr>
		<tr>
			<td class='alt2'>".XL_playerprofile_prof."</td>
			<td class='alt2'>".X1_userprofilelink($_REQUEST['member']).$row[X1_DB_usersnamekey]."</a></td>
		</tr>";
	}
	else{
		$c .= "
		<tr>
			<td>".XL_playerprofile_missing."</td>
		</tr>";
		$no_player=true;
	}

	$span=6;
	$c .= DispFunc::DisplaySpecialFooter($span);
	
	$retval = true;	   
	if(!isset($no_player)){
		$c .= UsersTeam($retval);
	}
	return DispFunc::X1PluginOutput($c);
}

/*############################################
 * Name:EditableProfile
 * Needs: Databaseinfo $row
 * What does it do: Sets up and prepares for display the editable section of the player profile.
 * Returns: string $output (the information
 ############################################*/

function EditableProfile($row){
	if(empty($row)){
		DispFunc::X1PluginOutput("Error row empty in EditableProfile");
		die();
	}
		
	$output = "
	<thead class='".X1plugin_tablehead."'>
  		<tr>
  			<th colspan='2'>".XL_playerprofile_title.":".$row[X1_DB_usersnamekey]."</th>
  		</tr>
	</thead>
	<form method='post' action='".X1_publicpostfile."?op=updateplayerprofile' style='".X1_formstyle."'>
	<tbody class='".X1plugin_tablebody."'>
	<tr>";
	if(X1_ingamename){
    $output .="<td class='alt1'>".XL_playerprofile_name."</td>
    <td class='alt1'><input type='text' name='ingamename' value='$row[gam_name]' size='20' maxlength='255'></td>";
    }
    
	$output .="</tr>
	<tr>
		<td class='alt1'>".XL_teamprofile_homepage."</td>
		<td class='alt1'><input type='text' name='website' value='$row[p_website]' size='20' maxlength='255'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_playerprofile_location."</td>
		<td class='alt2'>".SelectBox_Country("country", $row['p_country'])."</td>
	</tr>
	<tr>
		<td class'alt1'>".XL_teamadmin_mail."
		<td class='alt1'><input type='text' name='email' value='$row[p_mail]' size='20' maxlength='255'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_playerprofile_fmail."
		<td class='alt1'><input type='text' name='femail' value='$row[faux_email]' size='20' maxlength='255'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_playerprofile_usefmail."
		".SelectBoxYesNo("usefmail",$row['use_faux'],"alt1")."
	</tr>
	<tr>
		<td class'alt1'>".XL_ateams_aim."
		<td class='alt1'><input type='text' name='aim' value='$row[p_aim]' size='20' maxlength='40'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_ateams_icq."
		<td class='alt1'><input type='text' name='icq' value='$row[p_icq]' size='20' maxlength='40'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_ateams_msn."
		<td class='alt1'><input type='text' name='msn' value='$row[p_msn]' size='20' maxlength='255'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_teamadmin_xfire."
		<td class='alt1'><input type='text' name='xfire' value='$row[p_xfire]' size='20' maxlength='40'></td>
	</tr>
	<tr>
		<td class'alt1'>".XL_ateams_yim."
		<td class='alt1'><input type='text' name='yim' value='$row[p_yim]' size='20' maxlength='255'></td>
	</tr>
	<tr>
		<td class 'alt2'>
			<input type='hidden' name='member' value ='$row[uid]'> 	
			<input type='Submit' name='Submit' value='".XL_teamadmin_update."' >
			<input name='".X1_actionoperator."' type='hidden' value='updateplayerprofile'>
		</td>
	</tr>
	</form>
	<tbody>";
	
	return $output;
}

/*############################################
 * Name:UpdatePlayerProfile
 * Needs: N/A
 * What does it do: Updates the userinfo table of the xts system.
 * Returns: true if success false if not
 ############################################*/
function UpdatePlayerProfile(){
	//will need to update this to do some checking to ensure it's all real.
	
	$result=ModifySql("Update ",X1_DB_userinfo," SET
	p_website=".MakeItemString(DispFunc::X1Clean($_POST['website'],$mode=4)).",
	p_country=".MakeItemString($_POST['country']).",
	p_mail=".MakeItemString(DispFunc::X1Clean($_POST['email'],$mode=4)).",
	faux_email=".MakeItemString(DispFunc::X1Clean($_POST['femail'],$mode=4)).",
	use_faux=".MakeItemString($_POST['usefmail'],$mode=4).",
	p_aim=".MakeItemString(DispFunc::X1Clean($_POST['aim'],$mode=4)).",
	p_icq=".MakeItemString(DispFunc::X1Clean($_POST['icq'],$mode=4)).",
	p_msn=".MakeItemString(DispFunc::X1Clean($_POST['msn'],$mode=4)).",
	p_xfire=".MakeItemString(DispFunc::X1Clean($_POST['xfire'],$mode=4)).",
	p_yim=".MakeItemString(DispFunc::X1Clean($_POST['yim'],$mode=4))."
	WHERE uid=".MakeItemString($_POST['member']));

	if($result){
		return true;
	}
	return false;
}
?>
