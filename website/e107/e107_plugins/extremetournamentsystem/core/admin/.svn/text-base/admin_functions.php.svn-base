<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

/*######################################################
name:definemodoradminmenu
what does it do: Sets up and returns the appropriate menu based on moderator status.
needs:boolean $moderator, string $menuselection
returns:string $output
#######################################################*/
function definemodoradminmenu($moderator,$menuselection=NULL){
	if(!isset($moderator)){
		AdminLog("Moderator is NOT set", "definemodoradminmenu","Major Error",ERROR_DIE);
	}
	if(!$moderator){
		return x1_admin($menuselection);
	}
	else{
		return x1_moderator($menuselection);	
	}
}

/*############################################
name:AdminModButton
what does it do: Sets up and returns the appropriate button based on moderator status.
needs:boolean $moderator, string $button_text, string array $action, string $details=""
returns:string $output
###########################################*/
function AdminModButton($moderator, $button_text, $action){
	if(!isset($moderator)){
		AdminLog("Var moderator is NOT set", "AdminModButton","Major Error",ERROR_DIE);
	}
	if(empty($button_text)){
		AdminLog("Var button_text is empty", "AdminModButton","Major Error",ERROR_DISP);
	}
	if(empty($action)||!is_array($action)){
		if(empty($action)){
			AdminLog("Var action is empty","AdminModButton", "Major Error",ERROR_DIE);
		}
		else{
			AdminLog("Var action is not an array","AdminModButton", "Major Error",ERROR_DIE);
		}
	}
	
	if(!$moderator){
		$output ="<input type='hidden' name='".X1_actionoperator."' value='".$action[0]."' />
		<input type='submit' value='".$button_text."'>";
	}
	else{
		$output ="<input type='hidden' name='".X1_actionoperator."' value='".$action[1]."' />
		<input type='submit' value='".$button_text."'>";
	}
	return $output;
}


/*############################################
name:GetAdminImages
needs:n/a
returns:array $images
what does it do: Gets all the images for the array.
###########################################*/
function GetAdminImages($file_path){
	if(empty($file_path)){
		AdminLog("Var File_path is empty","GetAdminImages", "Major Error",ERROR_DIE);
	}
	if ($handle = opendir($file_path)) {
	 	$count=0;
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$images[$count]=$file;
				$count++;				
			}
		}
		closedir($handle);
	}
	return $images;
}

/*############################################
name:AdminSelectBoxLanguage
needs:n/a
returns:array $images
what does it do: Gets all the languages and puts out a select box
###########################################*/
function AdminSelectBoxLanguage($type, $default=""){
	if(empty($type)){
		AdminLog("Var type is empty", "AdminSelectBoxLanguage", "Major Error",ERROR_DIE);
	}
	$language=array('english');
    $c  = "<select name='$type' id='$type'>" ;
    if($default=="")$c .= "<option value='english'>English</option>";
    foreach($language as $lang){
		if ($lang==$default) {
			$sel = "selected ";
		}else{
			$sel = "";
		}
		$c .= "<option $sel value='$lang' align='left'>$lang</option>";
		unset($sel);
	}
	$c .= "</select>";
	return $c;
}

/*############################################
name:AdminSelectBoxAlign
needs:string type, string $what, string $class
returns:string $output
what does it do: Determines the values of the alignment.
###########################################*/
function AdminSelectBoxAlign($type, $what, $class="alt2"){
	if(empty($type)){
		AdminLog("Var type is empty", "AdminSelectBoxAlign", "Major Error",ERROR_DIE);
	}
	if(empty($what)){
		AdminLog("Variable what is empty", "AdminSelectBoxAlign","Major Error",ERROR_DIE);
	}
	$output ="<td class='$class'>
	<select name='$type' id='$type'>";
		switch($what){
			case "right":
				$output .="<option value='right' selected>".XL_aconfig_right."</option>
				<option value='center'>".XL_aconfig_center."</option>
				<option value='left'>".XL_aconfig_left."</option>";
				break;
			case "left":
				$output .="<option value='left' selected>".XL_aconfig_left."</option>
				<option value='center'>".XL_aconfig_center."</option>
				<option value='right'>".XL_aconfig_right."</option>";
				break;
			case "center":
				$output .="<option value='center' selected>".XL_aconfig_center."</option>
				<option value='right'>".XL_aconfig_right."</option>
				<option value='left'>".XL_aconfig_left."</option>";
				break;
		}
	$output .="</select>
	</td>";
	return $output;
}


/*############################################
name:AdminSelectBoxCookMod
needs:string $type, string $what, $string $class
returns:string $output
what does it do: Creates a select box for how cookies are set.
###########################################*/
function AdminSelectBoxCookMod($type, $what, $class='alt2'){
	if(empty($type)){
		AdminLog("Var type is empty", "AdminSelectBoxCookMod", "Major Error",ERROR_DIE);
	}
	if(!isset($what)){
		AdminLog("Variable what is empty", "AdminSelectBoxCookMod","Major Error",ERROR_DISP);
	}

		$output ="<td class='$class'>
	<select name='$type' id='$type'>";
		if($what==0){
			$output .="<option value='0' selected>".XL_phpcook."</option>
			<option value='1'>".XL_javacook."</option>";
		}
		else{
			$output .="<option value='1' selected>".XL_javacook."</option>
			<option value='0'>".XL_phpcook."</option>";	
		}
	$output .="</select>
	</td>";
	return $output;
}

/*############################################
name:AdminImageSelectBox
needs:string $type, array $imges, $string $cur
returns:string $output
what does it do: Creates a Select Box for what images to choose.
###########################################*/
function AdminImageSelectBox($type, $images, $cur=""){
	if(empty($type)){
		AdminLog("Var type is empty", "AdminImageSelectBox", "Major Error",ERROR_DIE);
	}
	if(empty($images)){
		AdminLog("Variable images is empty", "AdminImageSelectBox", "Major Error",ERROR_DIE);
	}
	$output ="<select name='$type' id='$type'>";
	foreach($images as $img){
		if($cur==$img){
			$output .="<option value='".$img."' selected>".$img."</option>";
		}
		else{
			$output .= "<option value='".$img."'>".$img."</option>";
		}
	}
	$output .="</select>";
	return $output;
}
	
	
/*############################################
name:AdminChooseIfUpload
needs:string $type, array $imges, $string $cur
returns:string $output
what does it do: Creates a Select Box for what images to choose.
###########################################*/
function AdminChooseIfUpload($type, $cur=""){
	if(empty($type)){
		AdminLog("Var type is empty", "AdminChooseIfUpload", "Major Error",ERROR_DIE);
	}
	$output ="<select name='$type' id='$type'>";
	$what = array(XL_no);//,XL_yes,XL_aconfig_either);
	for($count=0; $count<sizeof($what); $count++){
		if($cur==$count){
			$output .="<option value='".$count."' selected>".$what[$count]."</option>";
		}
		else{
			$output .= "<option value='".$count."'>".$what[$count]."</option>";
		}
	}
	$output .="</select>";
	return $output;
}	


function AdminLog($output, $function, $title="General Error", $return_type){
	if(empty($output)||empty($function)){
		X1Log::log_write(X1_admin_log, $output="output($output) or function($function) is empty", $function="AdminLog", $title = 'Major Error');
		return DispFunc::X1PluginOutput(XL_error_sys);
	}
	
	X1Log::log_write(X1_admin_log, $output, $function, $title);
	DispFunc::DispPreLoggedError($return_type);
}
	


?>