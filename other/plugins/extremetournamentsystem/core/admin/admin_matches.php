<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function matchmanager($moderator=false) {
    $span = 8;
	$c ="
	<table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th>".XL_amatches_addrecord."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
	<tr>
		<td class='alt1'>
        <form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".
		SelectBox_LadderDrop("ladder_id").
		"<input type='image' title='".XL_amatches_addrecord."' src='".X1_imgpath.X1_addimage."'>";
			if(!$moderator){
				$c.="<input type='hidden' name='".X1_actionoperator."' value='createplayedgame'>";
			}
			else{
				$c.="<input type='hidden' name='".X1_actionoperator."' value='mod_createplayedgame'>";	
			}
			$c.="</form>
			</td>
		</tr>
		".DispFunc::DisplaySpecialFooter($span-2)."
		<table class='".X1plugin_admintable."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamprofile_hid."</th>
				<th>".XL_amatches_hevent."</th>
				<th>".XL_teamprofile_hwinner."</th>
				<th>".XL_teamprofile_hloser."</th>
				<th>".XL_teamprofile_hdate."</th>
				<th>".XL_teamreport_draw."</th>
				<th>".XL_teamadmin_rostermodify."</th>
			</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";
	$rows = SqlGetAll("winner, loser, date, laddername, game_id, draw",X1_DB_teamhistory," order by game_id DESC");//get a max set and then next function.
	if($rows){
		foreach($rows AS $row){
		$event =  SqlGetRow("title",X1_DB_events," WHERE sid = ".MakeItemString($row['laddername']));
		if(!$event){
			$event =array("title"=>"___");
		}
		
			$draw = ($row['draw']) ? XL_yes : XL_no;
			$c .= "
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
			<tr>
				<td class='alt1'>$row[game_id]</td>
				<td class='alt2'>$event[title]</td>
				<td class='alt1'>$row[winner]</td>
				<td class='alt2'>$row[loser]</td>
				<td class='alt1'>".date(X1_dateformat,$row['date'])."</td>
				<td class='alt2'>$draw</td>
				<td class='alt1'>
					<input name='id' type='hidden' value='$row[game_id]'>";
					if(!$moderator){
						$c.="<select name='".X1_actionoperator."'>
							<option value='modifymatch'>".XL_edit."</option>
							<option value='delmatch'>".XL_delete."</option>
						</select>";
					}
					else{
						$c.="<select name='".X1_actionoperator."'>
							<option value='mod_modifymatch'>".XL_edit."</option>
							<option value='mod_delmatch'>".XL_delete."</option>
						</select>";
					}
					$c.="<input type='submit' value='".XL_ok."'>
				</td>
			</tr>
			</form>";
		}
	}
	else{
		$c .= "	<tr>
					<td colspan='$span'>".XL_amatches_none."</td>
				</tr>";
	}
	$c .= DispFunc::DisplaySpecialFooter($span,$break=false);
	return DispFunc::X1PluginOutput($c, 1);
}

function createplayedgame($moderator=false){
	$randid = X1Misc::X1PluginRandid();

	$c = definemodoradminmenu($moderator,"matches");
	$c .= "<br />";
	$c .= DispFunc::X1PluginTitle(XL_amatches_createtitle);
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")","createplayedgame","Major Error",ERROR_DIE);
	}	
	
	$c .= "
	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_admintable."' width='100%'>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_teamprofile_hwinner."</td>
			<td class='alt1'>".SelectBox_LadderTeamDrop('winner', $event['sid'])."</td>
		</tr>
		<tr>
			<td class='alt2'>".XL_teamprofile_hloser."</td>
			<td class='alt2'>".SelectBox_LadderTeamDrop('loser', $event['sid'])."</td>
		</tr>
	<tr>
	   <td class='alt1'>".XL_amatches_seldate."</td>
	   <td class='alt1'>".DispFunc::X1EditTime(time())."</td>
	</tr>
	<tr>
		<td colspan='2'>".XL_amatches_winnermaps."</td>
	</tr>"
		.MatchAdminDisplay($event,"mapa[]","a",$event['nummaps1'])."
	<tr>
		  <td colspan='2'>".XL_amatches_losermaps."</td>
   	</tr>"
		.MatchAdminDisplay($event,"mapb[]","b",$event['nummaps2'])."
	 <tr>
		<th>".XL_amatches_extras."</th>
	</tr>
	<tr>
		<td class='alt1'>".XL_amatches_screenshot."</td>
		<td class='alt1'><input type='textfield' value='' name='screen1'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_amatches_screenshot."</td>
		<td class='alt2'><input type='textfield' value='' name='screen2'></td>
	</tr>
	<tr>
		<td class='alt1'>".XL_amatches_demo."</td>
		<td class='alt1'><input type='textfield' value='' name='demo1'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_amatches_comments."</td>
		<td class='alt2'><input type='textfield' value='' name='comments'></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_amatches_draw."</td>
		".SelectBoxYesNo("draw",0)."
	</tr>
	<tr>
		<td class='alt1'>".XL_amatches_runplugincode."</td>
			".SelectBoxYesNo("runplugin",1,"alt1")."
		</tr>
		</tbody>
            <tfoot class='".X1plugin_tablefoot."'>
                <tr>
                    <td colspan='2'>
                    	<input type='hidden' value='$event[title]' name='laddername'>
    					<input type='hidden' value='$randid' name='randid'>
    					<input type='hidden' value='$event[sid]' name='ladder_id'>
    					".AdminModButton($moderator,XL_amatches_addmatch, $action=array("insertplayedgame","mod_insertplayedgame",))."
                    </td>
                </tr>
            </tfoot>
            </table>
			</form>";
	return DispFunc::X1PluginOutput($c);
}

function insertplayedgame($moderator=false) {
	global $gx_event_manager;
	
	$winner=DispFunc::X1Clean($_POST['winner'],4);
	$loser=DispFunc::X1Clean($_POST['loser'],4);
	if($winner==$loser||empty($loser)||empty($winner)){
		$c = definemodoradminmenu($moderator,"matches");
		if (empty($winner)){
			$c .= DispFunc::X1PluginTitle(XL_amatches_errnowinner);
			return DispFunc::X1PluginOutput($c);
		}
		if (empty($loser)){
			$c .= DispFunc::X1PluginTitle(XL_amatches_errnoloser);
			return DispFunc::X1PluginOutput($c);
		}
		if ($winner==$loser){
			$c .= DispFunc::X1PluginTitle(XL_amatches_errsameteams);
			return DispFunc::X1PluginOutput($c);
		}
		unset($c);
	}
	
	$event = SqlGetRow("*",X1_DB_events,"  where sid=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")","insertplayedgame","Major Error",ERROR_DIE);
	}
	X1File::X1LoadFile("event.php",X1_modpath."/$event[type]/");
	
	$challenge['map1']=implode(",",DispFunc::X1Clean($_POST['mapa'],5));
	$challenge['map2']=implode(",",DispFunc::X1Clean($_POST['mapb'],5));
	
	$mapnumarray = implode(",", array($event['nummaps1'],$event['nummaps2']));
	
	$m1winnerarray=implode(",",DispFunc::X1Clean($_POST['wscorea'],5));
	$m1loserarray=implode(",",DispFunc::X1Clean($_POST['lscorea'],5));
	$m2winnerarray=implode(",",DispFunc::X1Clean($_POST['wscoreb'],5));
	$m2loserarray=implode(",",DispFunc::X1Clean($_POST['lscoreb'],5));
	
	$team = SqlGetAll("team_id, name, mail",X1_DB_teams," where team_id=".MakeItemString($winner)." or team_id=".MakeItemString($loser));
	if(!$team){
		AdminLog(XL_failed_retr."(Var:team, Table:".X1_DB_teams.")","insertplayedgame", "Major Error",ERROR_DIE);
	}
	
	$temp=$team[0];
	if($temp['team_id']==$winner){
		$w=$temp;
		$l=$team[1];
	}
	else{
		$l=$temp;
		$w=$team[1];
	}
	unset($temp,$team);
		
	$winner_id = $winner;
	$loser_id = $loser;
	$winner = $w[1];
	$loser = $l[1];
	$ids=array("winner"=>$winner, "winner_id"=>$winner_id, "loser"=>$loser, "loser_id"=>$loser_id);
	$mail = $w[2];
	$mail2 = $l[2];
	$demo=(isset($_POST['demo']))? DispFunc::X1Clean($_POST['demo']): '';
	$screen1=(isset($_POST['screen1']))? DispFunc::X1Clean($_POST['screen1']): '';
	$screen2=(isset($_POST['screen2']))? DispFunc::X1Clean($_POST['screen2']): '';
	
	$result = ModifySql("insert into ",X1_DB_teamhistory,"
	(winner_id, winner, loser_id, loser, date, map1, map2, mapsettotal, map1t1, map1t2, map2t1, map2t2, scrnsht1, scrnsht2, comments, laddername, draw, demo)
	values(
	".MakeItemString($winner_id).",
	".MakeItemString($winner).",
	".MakeItemString($loser_id).",
	".MakeItemString($loser).",
	".MakeItemString(DispFunc::X1ReadTime()).",
	".MakeItemString($challenge['map1']).",
	".MakeItemString($challenge['map2']).",
	".MakeItemString($mapnumarray).",
	".MakeItemString($m1winnerarray).",
	".MakeItemString($m1loserarray).",
	".MakeItemString($m2winnerarray).",
	".MakeItemString($m2loserarray).",
	".MakeItemString($screen1).",
	".MakeItemString($screen2).",
	".MakeItemString(DispFunc::X1Clean($_POST['comments'])).",
	".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])).",
	".MakeItemString(DispFunc::X1Clean($_POST['draw'])).",
	".MakeItemString($demo).")");		
	
	if(!$result){
		AdminLog("Database failed insert(Table:".X1_DB_teamhistory.")","insertplayedgame","Major Error",ERROR_DISP);
		$out="";
	}
	else{
		$out=XL_amatches_added;
	}
		
	if(DispFunc::X1Clean($_POST['runplugin']) =="1"){
		if(DispFunc::X1Clean($_POST['draw']) =="1"){
			$gx_event_manager->X1ReportDraw($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event);
		}else{
			$gx_event_manager->X1ReportLoss($ids,$mapnumarray,$m1winnerarray,$m1loserarray,$m2winnerarray,$m2loserarray, $challenge, $event);
		}
	}
	$output = definemodoradminmenu($moderator,"matches");
	#Send off the Email to each team if enabled
	if (X1_emailon){
		$content = array(
				'winner' =>  $winner,
				'loser' => $loser,
				'winnermail' => $mail,
				'losermail' => $mail2,
				'event' => $event['title']
				);
		$output .= X1Misc::X1PluginEmail($mail2, "recievedloss.tpl", $content, XL_teamreport_emailloss);
		$output .= X1Misc::X1PluginEmail($mail, "recievedwin.tpl", $content, XL_teamreport_emailwin);
	}
	$output .= DispFunc::X1PluginTitle($out);
	return DispFunc::X1PluginOutput($output);
}



function modifymatch($moderator=false) {

	$c = definemodoradminmenu($moderator,"matches");
	$c .= "<br />";
  $c .= DispFunc::X1PluginTitle(XL_amatches_matchadmin);
  $row = SqlGetRow("*",X1_DB_teamhistory," where game_id=".MakeItemString(DispFunc::X1Clean($_POST['id'])));
  if(!$row){
		AdminLog(XL_failed_retr."(Var:row, Table:".X1_DB_teamhistory.")","modifymatch","Major Error",ERROR_DIE);
	}  
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($row['laddername']));
	
  if($event) {
		$draw = ($row['draw']) ? XL_yes : XL_no;
		$c .="
		    <form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
		    <table class='".X1plugin_admintable."' width='100%'>
		    <thead class='".X1plugin_tablehead."'>
	            <tr>
	                <td colspan='2'>".XL_amatches_modifymatch."</td>
	            </tr>
				<tr>
					<td class='alt1'>".XL_amatches_gameid."</td>
					<td class='alt1'><input readonly='text' name='game_id' value='$row[game_id]'></td>
				</tr>
				<tr>
					<td class='alt2'>".XL_amatches_eventid."</td>
					<td class='alt2'>".SelectBox_ladders('ladder_id',$row['laddername'])."</td>
				</tr>
				<tr>
					<td class='alt2'>".XL_teamprofile_hwinner."</td>
					<td class='alt2'>".SelectBox_LadderTeamDrop('winner',$event['sid'], $row['winner_id'])." </td>
				</tr>
				<tr>
					<td class='alt1'>".XL_teamprofile_hloser."</td>
					<td class='alt1'>".SelectBox_LadderTeamDrop('loser',$event['sid'], $row['loser_id'])."</td>
				</tr>
				<tr>
					<td class='alt2'>".XL_teamprofile_hdate."</td>
					<td class='alt2'>".DispFunc::X1EditTime($row['date'])."</td>
				</tr>
				<tr>
					<td class='alt1'>".XL_amatches_maparray1."</td>
					<td class='alt1'>";
					$map1 = explode(",", $row['map1']);
					$ws = explode(",", $row['map1t1']);
					$ls = explode(",", $row['map1t2']);
					$c .= MatchMap2Display($event,"nummaps1","mapa[]",$map1,"map1t1[]", "map1t2[]", $ws, $ls);
					/*for($a=0; $a <$event['nummaps1']; $a++){
						$c .= SelectBox_Maplist('mapa[]', $event['sid'], $map1[$a]).
						XL_teamprofile_hwinner." <input type='text' name='map1t1[]' value='$ws[$a]' size='5'>
						".XL_teamprofile_hloser." <input type='text' name='map1t2[]' value='$ls[$a]'  size='5'><br />";
					}*/
					$c .="
					</td>
				</tr>
				<tr>
					<td class='alt2'>".XL_amatches_maparray2."</td>
					<td class='alt2'>";
					$map2 = explode(",", $row['map2']);
					$ws = explode(",", $row['map2t1']);
					$ls = explode(",", $row['map2t2']);
					$c .= MatchMap2Display($event,"nummaps2","mapb[]",$map2,"map2t1[]", "map2t2[]", $ws, $ls);
					/*for($a=0; $a<$event['nummaps2']; $a++){
						$c .= SelectBox_Maplist('mapb[]', $event['sid'], $map2[$a]).
						XL_teamprofile_hwinner." <input type='text' name='map2t1[]' value='$ws[$a]'  size='5'>
						".XL_teamprofile_hloser." <input type='text' name='map2t2[]' value='$ls[$a]' size='5'><br />";
					}*/
					$c .="</td>
				</tr>
					<td class='alt1'>".XL_amatches_screenshot1."</td>
					<td class='alt1'><input type='text' name='map3t1' value='$row[scrnsht1]'></td>
				</tr>
				<tr>
					<td class='alt2'>".XL_amatches_screenshot2."</td>
					<td class='alt2'><input type='text' name='map3t2' value='$row[scrnsht2]'></td>
				</tr>
				<tr>
					<td class='alt1'>".XL_amatches_comments."</td>
					<td colspan='2' class='alt1'><textarea wrap='virtual' cols='50' rows='10' name='comments'>".$row['comments']."</textarea></td>
				</tr>
				<tr>
					<td class='alt1'>".XL_amatches_demolink."</td>
					<td class='alt1'><input type='text' name='demo' value='$row[demo]'></td>
				</tr>
				<tr>
					<td class='alt2'>".XL_amatches_draw."</td>
					".SelectBoxYesNo("draw", $row['draw'])."
				</tr>
		    </tbody>
	        <tfoot class='".X1plugin_tablefoot."'>
	            <tr>
	                <td colspan='2'>
	                ".AdminModButton($moderator,XL_save,$action=array("updatematch","mod_updatematch"))."
	              	</td>
	            </tr>
	        </tfoot>
	        </table>
			</form>";
	  } 
		else {
			$c .= DispFunc::X1PluginTitle(XL_amatches_nomatch);
    }
		return DispFunc::X1PluginOutput($c);
}


function updatematch($moderator=false) {

	$map1 = implode(',',$_POST['mapa']);
	$map2 = implode(',',$_POST['mapb']);
	$map1t1 = implode(',',$_POST['map1t1']);
	$map1t2 = implode(',',$_POST['map1t2']);
	$map2t1 = implode(',',$_POST['map2t1']);
	$map2t2 = implode(',',$_POST['map2t2']);
	$date = DispFunc::X1ReadTime();
	
	$winner=DispFunc::X1Clean($_POST['winner']);
	
	$team = SqlGetAll("team_id, name",X1_DB_teams," WHERE team_id=".MakeItemString($winner)." or team_id=".MakeItemString(DispFunc::X1Clean($_POST['loser'])));
	
	$temp = $team[0];
	if($temp['team_id']==$winner){
		$winner=$temp;
		$loser=$team[1];
	}
	else{
		$loser=$temp;
		$winner=$team[1];
	}
	unset($temp,$team);
	
	$results=ModifySql("update ",X1_DB_teamhistory," set
		winner=".MakeItemString($winner['name']).",
		loser=".MakeItemString($loser['name']).",
		date=".MakeItemString($date).",
		map1=".MakeItemString($map1).",
		map2=".MakeItemString($map2).",
		map1t1=".MakeItemString($map1t1).",
		map1t2=".MakeItemString($map1t2).",
		map2t1=".MakeItemString($map2t1).",
		map2t2=".MakeItemString($map2t2).",
		scrnsht1=".MakeItemString(DispFunc::X1Clean($_POST['map3t1'])).",
		scrnsht2=".MakeItemString(DispFunc::X1Clean($_POST['map3t2'])).",
		comments=".MakeItemString(DispFunc::X1Clean($_POST['comments'])).",
		laddername=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])).",
		draw=".MakeItemString(DispFunc::X1Clean($_POST['draw'])).",
		demo=".MakeItemString(DispFunc::X1Clean($_POST['demo']))." 
		where game_id=".MakeItemString(DispFunc::X1Clean($_POST['game_id'])));
		
		if(!$results){
		    AdminLog(XL_failed_updat."(".X1_DB_teamhistory.")","updatematch","Major Error",ERROR_DISP);
		    $out="";
		}
		else{
			$out=XL_amatches_updated;
		}
		$c = definemodoradminmenu($moderator,"matches");
		$c .= DispFunc::X1PluginTitle($out);
	return DispFunc::X1PluginOutput($c);
}


function X1_removematch($moderator=false){
	if(!ModifySql("DELETE FROM ",X1_DB_teamhistory," WHERE game_id=".MakeItemString($_POST['id']))){
		AdminLog("Failed Database Delete(Table:".X1_DB_teamhistory.")","X1_removematch","Major Error",ERROR_DISP);
	}
	else{
		$out = DispFunc::X1PluginTitle("Match Removed");
	}
	$c = definemodoradminmenu($moderator,"matches"); 
	$c .= $out;
	return DispFunc::X1PluginOutput($c);
}

function MatchAdminDisplay($event, $map, $mapname, $mapnum){
	$cm=1;
	$output='';
	while ($mapnum >= $cm){
		$output .= "<tr><td class='alt2'>Map $cm;</td><td class='alt2'>"
		.SelectBox_Maplist($map, $event['sid']);
		$output .= XL_amatches_winnerscore;
		$output .= "<input type='textfield' value='' name='wscore".$mapname."[]' size='5'>";
        $output .= XL_amatches_loserscore;
		$output .= "<input type='textfield' value='' name='lscore".$mapname."[]' size='5'>
		</td></tr>";
		$cm++;
	}
	return $output;
}

/*##############################
Name:MatchMap2Display
Needs:
Returns:
What it Does:
###############################*/
function MatchMap2Display($event, $total_maps, $map_array, $map, $tmap1, $tmap2, $win_scor, $los_scor){
	$output='';
	for($a=0; $a <$event[$total_maps]; $a++){
		$output .= SelectBox_Maplist($map_array, $event['sid'], $map[$a])
		.XL_teamprofile_hwinner." <input type='text' name='$tmap1' value='$win_scor[$a]' size='5'>
		".XL_teamprofile_hloser." <input type='text' name='$tmap2' value='$los_scor[$a]'  size='5'>
		<br />";
	}
	return $output;
}
?>
