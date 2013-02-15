<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2011
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function matchdetails() {
 
	$c  = DispFunc::X1PluginStyle();
	$game_id=DispFunc::X1clean($_REQUEST['game_id']);
	if(empty($game_id))
	{
		$c .= DispFunc::X1PluginTitle("Error: problem getting game id");
		return DispFunc::X1PluginOutput($c);
	}
	
	$t_history = SqlGetRow("*",X1_DB_teamhistory," WHERE game_id=".MakeItemString($game_id)." LIMIT 1;");		
	
	if(!$t_history)
	{
		UserLog("Failed to get match details, gameid may not be an interger (game_id: $game_id) or played history doesn'r exist.",$func="matchdetails", $title="Major Error", ERROR_DISP);
		return false;
	}

	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($t_history['laddername']));
	
	$demolink = MakeDemoLink($t_history['demo'], $t_history['game_id']);
	$screenshots=MakeScreenShots($t_history['scrnsht1'],$t_history['scrnsht2']);
	if($t_history['draw']){
		$c .= DispFunc::X1PluginTitle(XL_matchinfo_gamewasdraw);	
	} 
    $c .= "
    <table class='".X1plugin_matchdetailstable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamprofile_hevent."</th>
				<th>".XL_teamprofile_hwinner."</th>
				<th>".XL_teamprofile_hloser."</th>
				<th>".XL_teamprofile_hdate."</th>
				<th>".XL_matchinfo_demo."</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>
			<tr>
				<td class='alt1'><a href='".X1_publicpostfile.X1_linkactionoperator."ladderhome&sid=$event[sid]'>$event[title]</a></td>
				<td class='alt2'><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".$t_history["winner_id"]."'>$t_history[winner]</a></td>
				<td class='alt1'><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".$t_history["loser_id"]."'>$t_history[loser]</a></td>
				<td class='alt2'>".date(X1_dateformat,$t_history['date'])."</td>
				<td class='alt1'>$demolink</td>
			</tr>
		</tbody>
		<tfoot class='".X1plugin_tablefoot."'>
			<tr>
				<td colspan='5'>".XL_matchinfo_comments.": $t_history[comments]</td>
			</tr>
		</tfoot>
	</table>
	<br />
	<table class='".X1plugin_matchdetailstable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_matchinfo_screen."</th>
				<th>".XL_matchinfo_screen."</th>
			</tr>
		</thead>
	<tbody class='".X1plugin_tablebody."'>
	<tr> 
		".$screenshots[0].$screenshots[1]."
	</tr>
	</tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td colspan='2'>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
	<br/>
	<table class='".X1plugin_matchdetailstable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_matchinfo_mapimage."</td>
				<th>".XL_matchinfo_mapname."</th>
				<th>$t_history[winner]</th>
				<th>$t_history[loser]</th>
			</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>";
	$scoresarryW1 = explode(",",$t_history['map1t1']);
	$scoresarryL1 = explode(",",$t_history['map1t2']);
	$scoresarryW2 = explode(",",$t_history['map2t1']);
	$scoresarryL2 = explode(",",$t_history['map2t2']);

	$c .= MapDisplay($t_history['map1'], $event['nummaps1'], $scoresarryW1, $scoresarryL1);
	$c .= MapDisplay($t_history['map2'], $event['nummaps2'], $scoresarryW2, $scoresarryL2);
	
	$c .= "</tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td colspan='4'>&nbsp;</td>
            </tr>
        </tfoot>
    </table>";
	return DispFunc::X1PluginOutput($c);
}

/*##################################
Function:MakeDemoLink
Needs: string $demo, int $his_id
returns string $output
what does it do: Sets us the link to get the demo.
###################################*/
function MakeDemoLink($demo,$his_id){
	if(empty($demo)){
		return XL_matchinfo_nodemo;
	}
	$the_demo=explode('::',$demo);
	if($the_demo[0]=0){
		$output = "<a href='$t_history[demo]' targer='_blank' title='Download demo'>".XL_matchinfo_demo."</a>";
	}
	else{
		$output = "<a href='".X1_publicpostfile.X1_linkactionoperator."getdemo&id=$his_id' targer='_blank' title='Download demo'>".XL_matchinfo_demo."</a>";
	}
	return $output;
  
}

/*##################################
Function:MakeScreenShots
Needs: string $scrn_shot_1, string $scrn_shot_2
returns string $output
what does it do: Sets us the link(if available) and the img of the screen shot.
###################################*/
function MakeScreenShots($scrn_shot_1,$scrn_shot_2){
/*	if(empty($scrn_shot_1)&& empty($scrn_shot_2)){
		UserLog("Empty screen shots (1::$scrn_shot_1 2::$scrn_shot_2)","MakeScreenShots","Major Error",ERROR_DIE);
	}*/
	//Break tbe screen shots into arrays
	$scrn_shot_1=explode('::',$scrn_shot_1);
	$scrn_shot_2=explode('::',$scrn_shot_2);
	
	$img1 = ImageSwitch($scrn_shot_1);
	$img2 = ImageSwitch($scrn_shot_2);	

	return array($img1, $img2);

}

/*##################################
Function:ImageSwitch
Needs: array $image
returns string $output
what does it do: From the array it determines if it's an uploaded img or not and sets up the appropriate display
###################################*/
function ImageSwitch($image){
	
	if(empty($image[1])){
		return "<td>".XL_matchinfo_noscreen."</td>";
	}
	
	switch($image[0]){
		case 0:
			return "<td><a href='$image[1]'><img src='$image[1]' border='0' width='100' height='100' /></a></td>";
			break;
		case 1:
			return "<td><img src='$image[1]' border='0' width='100' height='100' /></td>";
			break;
	}
}

/*##################################
Function:MapDisplay
Needs: string $thistorymap, int $eventnummap, string $scoresarray1, string $scoresarray2
returns string $output
what does it do: Sets up information on the maps and scores.
###################################*/
function MapDisplay($t_history_map, $event_num_map, $scores_array_1, $scores_array_2) {
	$maps_arry = explode(",",$t_history_map);
	$cur_map=0;
	$output='';
	$maps = X1Misc::MapInfo($t_history_map);
	while ($cur_map < $event_num_map){
		list ($map_name, $map_pic, $map_dl) = $maps[$maps_arry[$cur_map]];
		$output .= "
		<tr>
			<td class='alt1'><img src='".X1_imgpath."/maps/$map_pic' border='0'></td>
			<td class='alt2'>$map_name</td>
			<td class='alt1'>$scores_array_1[$cur_map]</td>
			<td class='alt2'>$scores_array_2[$cur_map]</td>
		</tr>";
		$cur_map++;
	}
	return $output;

}
/*###############################
Function: newmatches
Needs: Int $sid, Int $limit, boolean $returnofprint  (true is return false is print)
Returns: $string (if $returnofprint is true)
What does it do:sets up a preview for matches
################################*/
function newmatches($sid=0, $limit="", $returnorprint=0) {
	$c  = DispFunc::X1PluginStyle();
	$c .= "
	<table class='".X1plugin_newmatchestable."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th>".XL_teamprofile_hdate."</th>
    			<th>".XL_matchpreview_challenger."</th>
    			<th>".XL_matchpreview_challenged."</th>
    			<th>".XL_matchpreview_matchdate."</th>
    		</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>";
	SetLimit($limit);
	
	$challenges = SqlGetAll("*",X1_DB_teamchallenges," WHERE ladder_id=".MakeItemString($sid)." and ctemp <> 1 ORDER BY date DESC $limit");
	if ($challenges){
		foreach($challenges AS $row){
		$name = X1TeamUser::SetTeamName(array($row['winner'], $row['loser']));
			
			$c .="
			<tr>
				<td class='alt1'>".date(X1_dateformat,$row['date'])."</td>
				<td class='alt2'>
                <a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".
				$row["loser"]."'>".$name[$row['loser']]."</a>
                </td>
				<td class='alt1'>
                <a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".
				$row["winner"]."'>".$name[$row['winner']]."</a>
                </td>
				<td class='alt2'>".date(X1_extendeddateformat,$row['matchdate'])."</td>
			</tr>";
		}
	}
	else{
		$c .= "
		<tr>
			<td colspan='4'>".XL_matchpreview_none."</td>
		</tr>";
	}
	$span=4;
	$c.= DispFunc::DisplaySpecialFooter($span);
    if($returnorprint){
        return $c;
    }else{
        return DispFunc::X1PluginOutput($c);
    }
}


/*#############################
Function: pastmatches
Needs:Int $laddername, int $limit, boolean $returnorprint (true return, false print)
Returns:string $output (if return or print is true)
What does it do:Displays match history.
##############################*/
function pastmatches($laddername=0, $limit="", $returnorprint=0) {
	$span=6;
	if(isset($_POST['sid'])){
		$laddername=$_POST['sid'];
	}
	$c = DispFunc::X1PluginStyle();
	$c .= "
	<table class='".X1plugin_pastmatchestable."' width='100%'>
    	<thead class='".X1plugin_tablehead."'>
        	<tr>
        		<th>".XL_teamprofile_hid.":</th>
        		<th>".XL_teamprofile_hwinner."</th>
        		<th>".XL_teamprofile_hloser."</th>
        		<th>".XL_teamprofile_hdate."</th>
				<th>".XL_teamreport_draw."</th>
        		<th>".XL_teamprofile_hdetails."</th>
        	</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";
	SetLimit($limit);
	
	$rows = SqlGetAll("*",X1_DB_teamhistory," WHERE laddername=".MakeItemString($laddername)." ORDER BY game_id DESC $limit"); 
	if($rows){
		foreach($rows As $row){
			$draw = ($row['draw']) ? XL_yes : XL_no;
			$c .="
			<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
			<tr>
				<td class='alt1'>$row[game_id]</td>
				<td class='alt2'>
					<a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".
					$row["winner_id"]."'>$row[winner]</a></td>
				<td class='alt1'>
					<a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".
					$row["loser_id"]."'>$row[loser]</a></td>
				<td class='alt2'>".date(X1_dateformat, $row['date'])."</td>
				<td class='alt1'>$draw</td>
				<td class='alt2'>
					<input name='".X1_actionoperator."' type='hidden' value='matchdetails'>
					<input name='game_id' type='hidden' value='$row[game_id]'>
					<input type='Submit' name='Submit' value='".XL_view."' >
				</td>
			</tr>
			</form>";
		}
	}else{
		$c .= "
		<tr>
			<td colspan='$span'>".XL_matchhistory_none."</td>
		</tr>";
	}
	
	$c .= DispFunc::DisplaySpecialFooter($span);
	if($returnorprint){
        return $c;
    }else{
        return DispFunc::X1PluginOutput($c);
    }
}

/*#######################
FunctionL SetLimit
Needs: referenced int $limit
Returns: N/A
What does it do: Sets limit as needed
#########################*/
function SetLimit(&$limit)
{
	if (!empty($limit)){
		$limit="LIMIT $limit";
	}
	else{
		$limit = "";
	}	
}

?>
