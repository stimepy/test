<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2009-2010
##Version 2.6.4
###############################################################
# This is in the process of being completely rewritten, will be released soon
//if (!defined('X1plugin_include'))exit();
###############################################################

/*	<tr>
				<td class='alt2'>".XL_aconfig_langadmin.":</td>
				<td class='alt2'>".AdminSelectBoxLanguage("adminlang", X1_adminlang)."</td>
			</tr>
*/


function configmanager(){
	
	if(X1_plugpath!=''){
		$filename=X1_plugpath."/my_config.php";
	}
	else{
		$filename="my_config.php";
	}
	if (is_writable($filename)) {
		$t_cookie_exp_min=X1_cookietime/60;
		$m_cookie_exp_min=X1_cookietimemod/60;

		$c = "<br />
	    
	    <table class='".X1plugin_admintable."' width='100%'>
	    <thead class='".X1plugin_tablehead."'>
			<tr>
				<th colspan='2'>".XL_aconfig_title."</th>
			</tr>
        </thead>
        </table>
        <br />

		<p>".XL_aconfig_welcome."</p>
		<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
		<table class='".X1plugin_admintable."' width='100%'>
			<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td class='alt1'>".XL_aconfig_sitnam.":</td>
				<td class='alt1'><input type='text' name='sitename' value='".X1_sitename."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_returl.":</td>
				<td class='alt2'><input type='text' name='rurl' value='".X1_url."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_langcore.":</td>
				<td class='alt2'>".AdminSelectBoxLanguage("corelang", X1_corelang)."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_datefor.":</td>
				<td class='alt1'><input type='text' name='dateformat' value='".X1_dateformat."' size='20' maxlength='20'></td>			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_dateforext.":</td>
				<td class='alt2'><input type='text' name='dateformatext' value='".X1_extendeddateformat."' size='20' maxlength='20'></td>
			</tr>			
			<tr>
				<td class='alt2'>".XL_aconfig_showlb.":</td>
				".SelectBoxYesNo("showlinkback", X1_showlinkback, $class="alt2")."
			</tr>			
			<tr>
				<td class='alt2'>".XL_aconfig_ver.":</td>
				".SelectBoxYesNo("showversion", X1_showversion, $class="alt2")."
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_align.":</td>
				".AdminSelectBoxAlign("align", X1_lbalign, $class="alt1")."
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_logoc.":</td>
				<td class='alt2'>".AdminImageSelectBox("linkbackimg", GetAdminImages(X1_imgpath."/linkback") ,X1_lbimage)."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_lburl.":</td>
				<td class='alt1'><input type='text' name='blink' value='".X1_lblink."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_cookmod.":</td>
			 	".AdminSelectBoxCookMod("howcookie",X1_cookiemode, "alt1")."
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_tcook.":</td>
			 	<td class='alt1'><input type='text' name='tcook' value='".X1_cookiename."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_mcook.":</td>
			 	<td class='alt1'><input type='text' name='mcook' value=".X1_cookiemod." size='25' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_tcooktime.":</td>
				<td class='alt1'><input type='text' name='tcooktime' value='".$t_cookie_exp_min."' size='20' maxlength='30'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_mcooktime.":</td>
				<td class='alt1'><input type='text' name='mcooktime' value='".$m_cookie_exp_min."' size='20' maxlength='30'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_cookref.":</td>
				<td class='alt1'><input type='text' name='cookref' value='".X1_refreshtime."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_logout.":</td>
				<td class='alt1'>".X1_url."/<input type='text' name='logoutpg' value='".str_replace(X1_url."/","",X1_logoutpage)."' size='25' maxlength='60'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_emailon.":</td>
				".SelectBoxYesNo("emailon", X1_emailon, $class="alt2")."
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_replyadd.":</td>
				<td class='alt1'><input type='text' name='retadd' value='".X1_returnmail."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_emstamp.":</td>
				<td class='alt1'><input type='text' name='emailstamp' value='".X1_emailtimestamp."' size='20' maxlength='20'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_showem.":</td>
				".SelectBoxYesNo("emailsendon", X1_emaildebug, $class="alt2")."
			</tr>			
			<tr>
				<td class='alt1'>".XL_aconfig_showteams.":</td>
				<td class='alt1'><input type='text' name='numteam' value='".X1_teamlistlimit."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_maxteam.":</td>
				<td class='alt1'><input type='text' name='maxteamc' value='".X1_maxcreate."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_maxjoin.":</td>
				<td class='alt1'><input type='text' name='maxteamj' value='".X1_maxjoin."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_timagesz.":</td>
				<td class='alt1'>".XL_aconfig_wide.":<input type='text' name='teamimagew' value='".X1_teamimagew."' size='10' maxlength='10'> ".XL_aconfig_high.":<input type='text' name='teamimageh' value='".X1_teamimageh."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_xtrafields.":</td>
				<td class='alt2'>".ExtraFieldBox("extrarosterfields", X1_extrarosterfields)."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_field1.":</td>
				<td class='alt1'><input type='text' name='extra1' value='".X1_extraroster1."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_field2.":</td>
				<td class='alt1'><input type='text' name='extra2' value='".X1_extraroster2."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_field3.":</td>
				<td class='alt1'><input type='text' name='extra3' value='".X1_extraroster3."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_ingamename.":</td>".
				SelectBoxYesNo("ingamename", X1_ingamename, $class="alt2")."
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_rossort.":</td>
				<td class='alt1'><input type='text' name='rostsort' value='".X1_rostersort."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_standtmz.":</td>
				<td class='alt1'><input type='text' name='numteamstand' value='".X1_topteamlimit."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_newmac.":</td>
				<td class='alt1'><input type='text' name='numnewmatch' value='".X1_newmatchlimit."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_macshw.":</td>
				<td class='alt1'><input type='text' name='nummatch' value='".X1_resultslimit."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_tzone.":</td>
				<td class='alt2'>GMT:".ExtraFieldBox("timezone",str_replace("GMT ", "",X1_timezone), -12, 14)."</td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_evenset.":</td>
				".SelectBoxYesNo("showsettingschall", X1_showsettingschall, $class="alt2")."
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_shwrule.":</td>
				".SelectBoxYesNo("showruleschall", X1_showruleschall, $class="alt2")."
			</tr>
			<tr>";
				$images = GetAdminImages(X1_imgpath."/submit");
				$c.="<td class='alt2'>".XL_aconfig_addbut.":</td>
				<td class='alt2'>".AdminImageSelectBox("addimage", $images ,str_replace("/submit/","",X1_addimage))."<img id='add' src='".X1_imgpath.X1_addimage."'></td>
				
    			</td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_delbut.":</td>
				<td class='alt2'>".AdminImageSelectBox("deleteimage", $images ,str_replace("/submit/","",X1_delimage))."<img id='del' src='".X1_imgpath.X1_delimage."'></td>
				
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_savbut.":</td>
				<td class='alt2'>".AdminImageSelectBox("saveimage", $images ,str_replace("/submit/","",X1_saveimage))."<img id='sav' src='".X1_imgpath.X1_saveimage."'></td>
				
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_editbut.":</td>
				<td class='alt2'>".AdminImageSelectBox("editimage", $images ,str_replace("/submit/","",X1_editimage))."<img id='add' src='".X1_imgpath.X1_editimage."'></td>
				
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_tab.":</td>
				<td class='alt2'>".AdminImageSelectBox("tabimage", GetAdminImages(X1_imgpath."/icons") ,str_replace("/icons/","",X1_tab_image))."<img id='add' src='".X1_imgpath.X1_tab_image."'></td>	
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_gampre.":</td>
				<td class='alt1'>".AdminImageSelectBox("gamedefimg", GetAdminImages(X1_imgpath."/games") ,str_replace("/games/","",X1_defpreviewimage))."<img id='add' src='".X1_imgpath.X1_defpreviewimage."'></td>
			</tr>
				<tr>
				<td class='alt1'>".XL_aconfig_iupload.":</td>
				<td class='alt1'>".AdminChooseIfUpload("imag_up", X1_fup_image)."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_filetype.":</td>
				<td class='alt1'><input type='text' name='imgfile' value='".str_replace('::',',',X1_fup_imgext)."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_dupload.":</td>
				<td class='alt1'>".AdminChooseIfUpload("demo_up", X1_fup_demo)."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_filetype.":</td>
								<td class='alt1'><input type='text' name='demofile' value='".str_replace('::',',',X1_fup_demoext)."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_tabsz.":</td>
				<td class='alt1'>".XL_aconfig_wide.":<input type='text' name='tabw' value='".X1_tab_width."' size='10' maxlength='10'> ".XL_aconfig_high.":<input type='text' name='tabh' value='".X1_tab_height."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_tabbord.":</td>
				<td class='alt1'><input type='text' name='tabbord' value='".X1_tab_border."' size='10' maxlength='10'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_styleuse.":</td>
				".SelectBoxYesNo("usestylesheet", X1_customstyle, $class="alt2")."
			</tr>

			<tr>
				<td class='alt2'>".XL_aconfig_styshe.":</td>
				<td class='alt2'>".GetStyleSheetSelectBox("stylsheet", X1_style)."</td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_tbor.":</td>
				<td class='alt2'>".AltSelectBox("altstyle",X1_alternativesyle)."</td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_cusmen.":</td>
				".SelectBoxYesNo("custommenu", X1_custommenu, $class="alt2")."
			</tr>
			<tr>
				<td class='alt1'>".XL_aconfig_cusfile.":</td>
				<td class='alt1'><input type='text' name='custommenfil' value='".X1_custommenu_inc."' size='50' maxlength='255'></td>
			</tr>
			<tr>
				<td class='alt2'>".XL_aconfig_log.":</td>
				".SelectBoxYesNo("logfile",X1_logfiles,"alt2")."
			</tr>
	    </tbody>
   		<tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='2'>";
				$c.="<input type='hidden' name='".X1_actionoperator."' value='updateconfigfile'>
                <input type='submit' value='".XL_teamadmin_update."'>";
            $c.= "</td>
        </tr>
        </form>
        <tr>
           <tr>
        	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
           		<td colspan='2'>
					<input type='hidden' name='".X1_actionoperator."' value='defaultconfigfile'>
            	    <input type='submit' value='".XL_aconfig_reset."'>
				</td>
			</form>
        </tr>
    	</tfoot>
    	</table>";
	}
	else{
		$c=DispFunc::X1PluginTitle(XL_aconfig_nowrtpt1.$filename.XL_aconfig_nowrtpt2);
	}
	
	return DispFunc::X1PluginOutput($c,1);
 
 }

function AltSelectBox($type, $cur){
	if(empty($type)){
		AdminLog("Empty type variable","AltSelectBox","Minor Error",ERROR_DISP);
	}
	if(empty($cur) && $cur!=0){
		AdminLog("Empty cur variable","AltSelectBox","Minor Error",ERROR_DISP);
	}
	$output ="<select name='$type' id='$type'>";
	if($cur){
		$output .="<option value='true' selected>".XL_aconfig_forhead."</option>
		<option value='false' selected>".XL_aconfig_tbord."</option>";
			
	}
	else{
		$output .="<option value='false' selected>".XL_aconfig_tbord."</option>
		<option value='true' selected>".XL_aconfig_forhead."</option>";
	}
	
	$output .="</select>";
	return $output;
	
}

function ExtraFieldBox($type, $cur, $start=0, $max=4){
	if(empty($type)){
		AdminLog("Empty type variable","ExtraFieldBox","Minor Error",ERROR_DISP);
	}
	if(empty($cur)&& $cur!=0){
		AdminLog("Empty cur variable","ExtraFieldBox","Minor Error",ERROR_DISP);
	}	
	$output ="<select name='$type' id='$type'>";
	for($count=$start; $count<$max; $count++){
		if($cur==$count){
			$output .="<option value='".$count."' selected>".$count."</option>";
		}
		else{
			$output .= "<option value='".$count."'>".$count."</option>";
		}
	}
	$output .="</select>";
	return $output;
}


function updateconfigfile($default=false){
	X1File::X1LoadFile("template_config.php",X1_plugpath."templates/");
	if(!$default){
		$configinfo =updatedtemplate();
	}
	else{
		$configinfo =defaulttemp();
	}
	if(X1_plugpath!=''){
		$path=X1_plugpath."/";
	}
	else{
		$path=NULL;
	}
	$file = @fopen($path."my_config.php", "w");
	if (!@fwrite($file, $configinfo))
	{
		@fclose ($file);
		AdminLog("failed to update the Configuration File","updateconfigfile", "Major Error",ERROR_DISP);
		return false;
	}
	@fclose ($file);
	return true;
}

function GetStyleSheetSelectBox($type, $cur){
	$output = "<select name='$type'>";
 	if ($dir = @opendir(X1_csspath)) {
		while (($file = readdir($dir)) !== false) {
			if($file != ".." && $file != "." && $file != "index.htm"){
				if($file == $cur) {
				 	$output .="<option value='$file' selected>$file</option>";
				}
				else{
					$output .= "<option value='$file'>$file</option>";
				}
			}
		}
		closedir($dir);
	}
	else{
		AdminLog("Can not Open Dir:".X1_csspath,"GetStyleSheetSelectBox","Major Error",ERROR_DIE);
	}
	$output .= "</select>";
	return $output;
}

function updatingconfigfile(){
	$c = DispFunc::DirectToRefresh('admin&panel=config');
	$c .= DispFunc::X1PluginTitle(XL_aconfig_plzwt);
	DispFunc::X1PluginOutput($c);
}


?>