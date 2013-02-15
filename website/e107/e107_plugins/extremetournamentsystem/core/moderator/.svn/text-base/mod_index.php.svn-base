<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008 (2.6.0)
##Version 2.6.0
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function X1_moderator($panel="home") {

	$c  = DispFunc::X1PluginStyle();
	$c .= "<script type='text/javascript' >
	var panels = new Array('panel1', 'panel2', 'panel3', 'panel4', 'panel5');
	function x1showPanel(name){
		for(i = 0; i < panels.length; i++){
			document.getElementById(panels[i]).style.display = (name == panels[i]) ? 'block':'none';
		}
	}
	</script>\n";
	$panel = (empty($_REQUEST['panel'])) ? $panel: $_REQUEST['panel'];
	
	if(!X1_custommenu){
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel1\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_help.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel2\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_teams.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel3\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_challenges.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel4\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_matches.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel5\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_disputes.'</a>';


	}
	$c .= DispFunc::X1PluginTitle(XL_admin_title);
	
	$panstyle = ( $panel=="home" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel1' $panstyle>";
	$c .= (str_split(str_replace(".","",preg_replace("/[^0-9\.]+/","",phpversion())),3) > 430)? @file_get_contents(X1_helpfile):implode("\n", @file(X1_helpfile));
	$c .= "</div>";
	


	$panstyle = ( $panel=="teams" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel2' $panstyle>".teamsmanager(true)."</div>";

	$panstyle = ( $panel=="matches" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel4' $panstyle>".matchmanager(true)."</div>";

	$panstyle = ( $panel=="challenges" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel3' $panstyle>
	<table class='".X1plugin_admintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_achallenges_selectevent."'</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td>
					<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".
					SelectBox_LadderDrop("ladder_id")."
					<input name='".X1_actionoperator."' type='hidden' value='mod_createchallenge'>
					<input name='Submit' type='Submit' value='Ok'>
					</form>
				</td>
			</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</tfoot>
    </table>
	</div>";

	$panstyle = ( $panel=="disputes" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel5' $panstyle>".disputemanager(true)."</div>";

	return DispFunc::X1PluginOutput($c);
}
?>