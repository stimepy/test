<?php
###############################################################
##X1plugin Competition Management
##Homepage::http://www.nukeladder.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function x1_admin($panel="home") {

	$c  = DispFunc::X1PluginStyle();
	$c .= "<script type='text/javascript' >
	var panels = new Array('panel1', 'panel2', 'panel3', 'panel4', 'panel5', 'panel6', 'panel7', 'panel8', 'panel9', 'panel10', 'panel11');
	function x1showPanel(name){
		for(i = 0; i < panels.length; i++){
			document.getElementById(panels[i]).style.display = (name == panels[i]) ? 'block':'none';
		}
	}
	</script>\n";

	$panel = (empty($_REQUEST['panel'])) ? $panel: DispFunc::X1Clean($_REQUEST['panel']);
	
	if(!X1_custommenu){
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel1\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_help.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel2\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'"width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_games.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel4\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_events.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel5\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_maps.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel10\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_mapgroups.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel3\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_teams.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel7\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_challenges.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel6\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_matches.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel8\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_disputes.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel9\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_moderator.'</a>';
		$c .='<a href="javascript:" class="tab" onclick="x1showPanel(\'panel11\'); return false;">';
		$c .='<img src="'.X1_imgpath.X1_tab_image.'" width="'.X1_tab_width.'"
		height="'.X1_tab_height.'" border="'.X1_tab_border.'">'.XL_tab_config.'</a>';
		
		
	}
	
	$c .= DispFunc::X1PluginTitle(XL_admin_title);

	$panstyle = ( $panel=="home" ) ? '' : 'style="display:none"';
	$c .= "<div class='panel' id='panel1' $panstyle>";
	//PHP 5 and beyond no more 4 compadablity!
	$c .= @file_get_contents(X1_helpfile); //implode("\n", @file(X1_helpfile));
	$c .= "</div>";

	$panstyle = ( $panel=="games" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel2' $panstyle>".gamesmanager()."</div>";

	$panstyle = ( $panel=="events" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel4' $panstyle>".ladderlistmanager()."</div>";
	
	$panstyle = ( $panel=="maps" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel5' $panstyle>".mapsmanager()."</div>";

	$panstyle = ( $panel=="teams" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel3' $panstyle>".teamsmanager()."</div>";

	$panstyle = ( $panel=="matches" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel6' $panstyle>".matchmanager()."</div>";

	$panstyle = ( $panel=="challenges" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel7' $panstyle>
	<table class='".X1plugin_admintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>Select an Event</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td>
					<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".
					SelectBox_LadderDrop("ladder_id")."
					<input name='".X1_actionoperator."' type='hidden' value='createchallenge'>
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
	$c .= "<div class='panel' id='panel8' $panstyle>".disputemanager()."</div>";
	

	$panstyle = ( $panel=="config" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel11' $panstyle>";
	if(X1_useconfigpanel){
		$c .= configmanager();
	}else{
		$c .= "Remote config access has been disabled";
	}
	$c .= "</div>";

	$panstyle = ( $panel=="mapgroups" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel10' $panstyle>".mapgroups()."</div>";

	$panstyle = ( $panel=="moderator" ) ? '' : 'style="display:none;"';
	$c .= "<div class='panel' id='panel9' $panstyle>".X1ModeratorMenu()."</div>";
	
	return DispFunc::X1PluginOutput($c);
}
?>