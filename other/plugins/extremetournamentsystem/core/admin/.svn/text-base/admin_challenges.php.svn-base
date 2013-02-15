<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################

function listchallenges($moderator=false){
	$date = date("U");
	$ladder_id=DispFunc::X1Clean($_POST['ladder_id']);
   $span=6;
   if($ladder_id==0){
   		return definemodoradminmenu($moderator,"challeneges");
   }
   $event=SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($ladder_id));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")", "listchallenges", "Major Error", ERROR_DIE);
	}
	$c  = definemodoradminmenu($moderator,"challeneges");
    $c .= '<br />'.DispFunc::X1PluginTitle(XL_achallenges_confirmed.$event['title']);
	$c .= "
	<table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th>".XL_teamprofile_hid."</th>
			<th>".XL_matchpreview_challenger."</th>
			<th>".XL_matchpreview_challenged."</th>
			<th>".XL_teamprofile_hdate."</th>
			<th>".XL_teamadmin_rostermodify."</th>
			<th>".XL_delete."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>";

	$laddername = $event['title'];

	$rows=SqlGetAll("winner, loser, date, randid",X1_DB_teamchallenges," where ladder_id=".MakeItemString($_POST['ladder_id'])." and ctemp=1");
	
	if(!$rows){
        $c .="<tr>
			<td colspan='5'>".XL_achallenges_none."</td>
			</tr>";
     }
	 else{
    	foreach($rows AS $row){
    	 $name=X1TeamUser::SetTeamName(array($row['winner'],$row['loser']));
    			    $c.="<tr>
	    			<td class='alt1'>$row[randid]</td>
	    			<td class='alt2'>".$name[$row['loser']]."</td>
	    			<td class='alt1'>".$name[$row['winner']]."</td>
	    			<td class='alt2'>".date(X1_dateformat.' H:i',$row['date'])."</td>
	    			<td class='alt1'>
	    				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    				<input name='randid' type='hidden' value='$row[randid]'>
	    				<input name='ladder_id' type='hidden' value='$ladder_id'>
	    				".AdminModButton($moderator,XL_edit,$action=array("edittempchallenge", "mod_edittempchallenge"))."
	    				</form>
	    			</td>
	    			<td>
	    				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    				<input name='randid' type='hidden' value='$row[randid]'>
	    				<input name='ladder_id' type='hidden' value='$ladder_id'>
							".AdminModButton($moderator,XL_delete,$action=array("deletetempchallenge","mod_deletetempchallenge"))."
	    			</form>
	    		</td>
	    	</tr>";
        }
    }
   $c .= DispFunc::DisplaySpecialFooter($span);
	$c .= DispFunc::X1PluginTitle(XL_achallenges_unconfirmed.$event['title']);
	$c .="
    <table class='".X1plugin_admintable."' width='100%'>
    <thead class='".X1plugin_tablehead."'>
		<tr>
			<th>".XL_matchpreview_challenger."</th>
			<th>".XL_matchpreview_challenged."</th>
			<th>".XL_teamprofile_hdate."</th>
			<th>".XL_achallenges_matchdate."</th>
			<th>".XL_teamadmin_message."</th>
			<th>".XL_teamadmin_rostermodify."</th>
			<th>".XL_delete."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>";

	$rows = SqlGetAll("winner, loser, date, matchdate, randid",X1_DB_teamchallenges," where ladder_id=".MakeItemString($_POST['ladder_id'])." and ctemp<>1");

	if(!$rows){
        $c .="<tr>
			<td colspan='5'>".XL_achallenges_none."</td>
			</tr>";
     }
	 else{//else1
    	foreach($rows AS $row) {
   			$name=X1TeamUser::SetTeamName(array($row['winner'],$row['loser']));
    		$c .= "
    		<tr>
    			<td class='alt1'>".$name[$row['loser']]."</td>
    			<td class='alt2'>".$name[$row['winner']]."</td>
    			<td class='alt1'>".date(X1_dateformat,$row['date'])."</td>
    			<td class='alt2'>".date(X1_dateformat.' H:i',$row['matchdate'])."</td>
    			<td class='alt1'>
	    			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    				<input name='randid' type='hidden' value='$row[randid]'>
	    				<input name='team_id_w' type='hidden' value='$row[winner]'>
	    				<input name='team_id_l' type='hidden' value='$row[loser]'>    				
						<input name='team_name_w' type='hidden' value='".$name[$row['winner']]."'>
						<input name='team_name_l' type='hidden' value='".$name[$row['loser']]."'>
						<input name='ladder_id' type='hidden' value='$ladder_id'>
							".AdminModButton($moderator, XL_view,$action=array("adminmessage","mod_message"))."
	    				</form>
	    			</td>
	    			<td class='alt2'>
					<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    				<input name='randid' type='hidden' value='$row[randid]'>
	    				<input name='ladder_id' type='hidden' value='$ladder_id'>
	    				".AdminModButton($moderator,XL_edit,$action=array("editchallenge","mod_editchallenge"))."
	    				</form>
	    			</td>
	    			<td class='alt2'>
	    				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	    				<input name='randid' type='hidden' value='$row[randid]'>
	    				<input name='ladder_id' type='hidden' value='$ladder_id'>
	    				".AdminModButton($moderator,XL_delete, $action=array("deletechallenge","mod_deletechallenge"))."
	    				</form>
	    			</td>
	    		</tr>";
    		}
    }//else1
	$c .= DispFunc::DisplaySpecialFooter($span);
//here is the split
	global $gx_message_param;
	switch($gx_message_param){
		case "admin_mess":
			$admin_mess=new ChallengeMessageSystem($_POST['team_id_w'],$_POST['team_name_w'],$_POST['team_id_l'],$_POST['team_name_l']);
			$c .=$admin_mess->AdminViewMess($_POST['randid']);
			break;
		case "admin_send"://incase something else is ever required.
		default:
			$c .= CreateChallForum($event,$moderator, $date);	
	}

	return DispFunc::X1PluginOutput($c);
}

function insertchallenge($moderator=false) {
	$challenger=DispFunc::X1Clean($_POST['challenger']);
	$challenged=DispFunc::X1Clean($_POST['challenged']);
	
	if ( (empty($challenger)) || (empty($challenged) )){
		$c = XL_achallenges_errblankteam1;
		return DispFunc::X1PluginOutput($c);
	}
	if ($challenger==$challenged){
		$c = XL_achallenges_errsameteams;
		return DispFunc::X1PluginOutput($c);
	}
	
	$randid = X1Misc::X1PluginRandid();
	
	$maparray1= implode(',',DispFunc::X1Clean($_POST['mapa'],5));
	$maparray2 = implode(',',DispFunc::X1Clean($_POST['mapb'],5));
	
    /*$m = date('n', DispFunc::X1Clean($_POST['date2']));
    $d = date('j', DispFunc::X1Clean($_POST['date2']));
    $y = date('y', DispFunc::X1Clean($_POST['date2']));*/
    
	$finalmatchdate = DispFunc::X1ReadTime();
	
	$result = ModifySql("insert into ",X1_DB_teamchallenges,"
	(winner, loser, date, randid, ladder_id, map1, map2, matchdate, ctemp)
	values (".MakeItemString($challenged).",
  	".MakeItemString($challenger).",
 	".MakeItemString(time()).",
	".MakeItemString($randid).",
	".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])).",
    ".MakeItemString($maparray1).",
    ".MakeItemString($maparray2).",
    ".MakeItemString($finalmatchdate).",
		".MakeItemString(0).")");
	$c = definemodoradminmenu($moderator,"challenges");
	if($result){
        $c .= DispFunc::X1PluginTitle(XL_achallenges_added);
    }
	else{
				AdminLog("Failed Database Insert(Table:".X1_DB_teamchallenges.")","insertchallenge","Major Error",ERROR_DISP);	
    }
	return DispFunc::X1PluginOutput($c);
}


function editchallenge($moderator=false) {
	$match = SqlGetRow("*",X1_DB_teamchallenges," where ladder_id=".MakeItemString($_POST['ladder_id'])." and randid=".MakeItemString($_POST['randid']));
	
	if(!$match) {
  	AdminLog(XL_failed_retr."(Var:match, Table".X1_DB_teamchallenges.")","editchallenge","Major Error",ERROR_DIE);
  }
	$c = definemodoradminmenu($moderator,"challenges");
	
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($_POST['ladder_id']));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")","editchallenge","Major Error",ERROR_DIE);
	}
	$mapa=explode(",",$match['map1']);
	$mapb=explode(",",$match['map2']);

	$c .= "<br />";
    $c .= DispFunc::X1PluginTitle("Challenge Admin");
    $c .="
	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_admintable."' width='100%'>
    <thead class='".X1plugin_tablehead."'>
	    <tr>
			<th colspan='2'>".XL_achallenges_editchallenge."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
            <td class='alt2'>".XL_matchpreview_challenger."</td>
            <td class='alt2'>".SelectBox_LadderTeamDrop('challenger',$event['sid'], $match['loser'])."</td>
		</tr>
	    <tr>
            <td class='alt1'>".XL_matchpreview_challenged."</td>
            <td class='alt1'>".SelectBox_LadderTeamDrop('challenged',$event['sid'], $match['winner'])."</td>
		</tr>
		<tr>
		  <th colspan='2' class='alt1'>".XL_achallenges_maps1."</th>
    	</tr>
	    <tr>
		".DisplayChallengeMaps($event,"nummaps1","mapa[]", $mapa, true)."
    	<tr>
    		<th colspan='2' class='alt1'>".XL_achallenges_maps2."</th>
    	</tr>
    	".DisplayChallengeMaps($event,"nummaps2","mapb[]", $mapb, true)."
			<tr>
				<th colspan='2' class='alt1'>".XL_achallenges_misc."</th>
			</tr>
    		<tr>
				<td class='alt1'>".XL_amatches_eventid."</td>
				<td class='alt1'><input readonly='text' value='$event[sid]' name='ladder_id' size='50'> </td>
			</tr>
			<tr>
				<td class='alt2'>".XL_achallenges_matchdate."</td>
				<td class='alt2'>".DispFunc::X1EditTime($match['matchdate'])."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_achallenges_randid."</td>
				<td class='alt1'><input readonly='text' value='$match[randid]' name='randid' size='50'> </td>
			</tr>
			</tbody>
            <tfoot class='".X1plugin_tablefoot."'>
                <tr>
                    <td colspan='2'>
				        <input type='hidden' value='$match[randid]' name='oldrandid'>
				        <input type='hidden' value='$event[sid]' name='oldladder_id'>
				        ".AdminModButton($moderator,XL_achallenges_updated,$action=array("updatechallenge","mod_updatechallenge"))."
								</td>
                </tr>
            </tfoot>
            </table>
			</form>";
	return DispFunc::X1PluginOutput($c);
}

function updatechallenge($moderator=false) {
	$c = definemodoradminmenu($moderator,"challenges");
	
	$challenger=DispFunc::X1Clean($_POST['challenger']);
	$challenged=DispFunc::X1Clean($_POST['challenged']);
	
	if ((empty($challenger))||(empty($challenged))){
      $c .= DispFunc::X1PluginTitle(XL_achallenges_errblankteam1);
		return DispFunc::X1PluginOutput($c);
	}
    if ($challenger==$challenged){
		$c .= XL_achallenges_errsameteams;
		return DispFunc::X1PluginOutput($c);
	}
	
	$maparray1= implode(',',$_POST['mapa']);
	$maparray2 = implode(',',$_POST['mapb']);
	$finalmatchdate = DispFunc::X1ReadTime();

	$result =ModifySql("update ",X1_DB_teamchallenges," SET
	winner=".MakeItemString($challenged).",
	loser=".MakeItemString($challenger).",
	date=".MakeItemString(time()).",
	randid=".MakeItemString(DispFunc::X1Clean($_POST['randid'])).",
	ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])).",
	map1=".MakeItemString($maparray1).",
	map2=".MakeItemString($maparray2).",
	matchdate=".MakeItemString($finalmatchdate)."
	where randid=".MakeItemString(DispFunc::X1Clean($_POST['oldrandid']))."
	and ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['oldladder_id'])));

    if($result){
        $c .= DispFunc::X1PluginTitle(XL_achallenges_updated);
    }else{
        AdminLog(XL_failed_updat."(Table:".X1_DB_teamchallenges.")", "updatechallenge","Major Error",ERROR_DISP);
    }
	return DispFunc::X1PluginOutput($c);
}

function edittempchallenge($moderator=false) {
	$challenge = SqlGetRow("*",X1_DB_teamchallenges," where randid=".MakeItemString($_POST['randid'])." and ctemp=1");
	if(!$challenge){
		AdminLog(XL_failed_retr."(Var:challenge, Table:".X1_DB_teamchallenges.")","edittempchallenge","Major Error",ERROR_DIE);
	}
	$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString($challenge['ladder_id']));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")","edittempchallenge","Major Error",ERROR_DIE);
	}
	$c = definemodoradminmenu($moderator,"challenges");
	
	$mapa=explode(",",$challenge['map1']);
	$mapb=explode(",",$challenge['map2']);
	$mdates=explode(",",$challenge['matchdate']);
	$dates=count($mdates);
	$c .= DispFunc::X1PluginTitle("Edit Pending Challenge");
	$c .= "
	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_admintable."' width='100%'>
    <thead class='".X1plugin_tablehead."'>
	    <tr>
			<th colspan='2'>".XL_achallenges_editunconfirmed."</th>
		</tr>
	</thead>
    <tbody class='".X1plugin_tablebody."'>
	    <tr>
			<td class='alt1'>".XL_matchpreview_challenger."</td>
			<td class='alt1'>".SelectBox_LadderTeamDrop('challenger', $event['sid'], $challenge['loser'])."</td>
		</tr>
		<tr>
			<td class='alt2'>".XL_matchpreview_challenged."</td>
			<td class='alt2'>".SelectBox_LadderTeamDrop('challenged', $event['sid'], $challenge['winner'])."</td>
		</tr>
		<tr>
			<td class='alt1'>".XL_achallenges_maps1."</td>
			<td class='alt1'>";
			for($cm = 1; $event['nummaps1'] >= $cm; $cm++){
				$b = $cm-1;
				$c .= SelectBox_Maplist("mapa[]", $event['sid'], $mapa[$b])."<br/>";
			}
			$c .= "
		   </td>
		   </tr>
			<tr>
				<td class='alt2'>".XL_amatches_eventid."</td>
				<td class='alt2'>".SelectBox_ladders("ladder_id", $event['sid'])."</td>
			</tr>
			<tr>
				<td class='alt1'>".XL_achallenges_dt1."</td>
				<td class='alt1'>";
				for($cm = 0; count($mdates) > $cm; $cm++){
					$c .= DispFunc::X1EditTime($mdates[$cm], $cm)."<br/>";
				}
				$c .="</td>
			</tr>
			<tr>
				<th colspan='2' class='alt1'>".XL_achallenges_misc."</th>
			</tr>
			<tr>
				<td class='alt2'>".XL_achallenges_randid."</td>
				<td class='alt2'><input type='textfield' readonly='readonly' value='$challenge[randid]' name='randid' size='50'> </td>
			</tr>
			<tr>
				<td class='alt1'>".XL_achallenges_setdate."</td>
				<td class='alt1'>".DispFunc::X1EditTime($challenge['date'])."</td>
			</tr>
			</tbody>
            <tfoot class='".X1plugin_tablefoot."'>
                <tr>
                    <td colspan='2'>
  					<input type='hidden' value='$dates' name='datecount'>
          ".AdminModButton($moderator,XL_achallenges_updated,$action=array("updatetempchallenge","mod_updatetempchallenge"))."
            </td>
             	</tr>
            </tfoot>
            </table>
			</form>";
	return DispFunc::X1PluginOutput($c);
}

function updatetempchallenge($moderator=false) {
	$c = definemodoradminmenu($moderator,"challenges");
	$challenged=DispFunc::X1Clean($_POST['challenged']);
	$challenger=DispFunc::X1Clean($_POST['challenger']);
	
	
	if (empty($challenger)){
		AdminLog("Blank Var challenger","updatetempchallenge","Major Error", ERROR_RET);
		return DispFunc::X1PluginTitle(XL_achallenges_errblankteam1);
	}
	if (empty($challenged)){
		AdminLog("Blank Var challenged","updatetempchallenge","Major Error",ERROR_RET);
		return DispFunc::X1PluginTitle(XL_achallenges_errblankteam2);
	}
	
	$maparray1= implode(',',$_POST['mapa']);
	
	$date = DispFunc::X1ReadTime();
	$date_count=DispFunc::X1Clean($_POST['datecount']);
	for($cm=0; $date_count > $cm; $cm++){
		$mdates[] = DispFunc::X1ReadTime($cm);
	}
	$pmdates = implode(',', $mdates);
	
	$result = ModifySql("update ",X1_DB_teamchallenges," SET
			winner=".MakeItemString($challenged).",
			loser=".MakeItemString($challenger).",
			date=".MakeItemString($date).",
			randid=".MakeItemString(DispFunc::X1Clean($_POST['randid'])).",
			ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])).",
			map1=".MakeItemString($maparray1).",
			map2=".MakeItemString($maparray2).",
			matchdate=".MakeItemString($pmdates)." 
			where randid=".MakeItemString(DispFunc::X1Clean($_POST['randid']))." 
			and ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	if($result){
		$c .= DispFunc::X1PluginTitle(XL_achallenges_updated);
	}
	else{
		AdminLog(XL_failed_updat."(Table:".X1_DB_teamchallenges.")","updatetempchallenge", "Major Error",ERROR_DISP);
	}
	return DispFunc::X1PluginOutput($c);
}

function deletechallenge($moderator=false) {
	$del = ModifySql("delete from ",X1_DB_teamchallenges," where randid=".MakeItemString(DispFunc::X1Clean($_POST['randid']))." and ladder_id=".MakeItemString(DispFunc::X1Clean($_POST['ladder_id'])));
	$op=Dispfunc::X1Clean($_REQUEST[X1_actionoperator]);
	if(strcmp($op,'mod_deletechallenge')||strcmp ($op,'deletechallenge')){
		$mes=ModifySql("delete from ",X1_DB_messages," where randid=".MakeItemString(DispFunc::X1Clean($_POST['randid'])));
	}

	$c = definemodoradminmenu($moderator,"challenges");
	
    if($del){
        $c .= DispFunc::X1PluginTitle(XL_achallenges_deleted);
    }
	else{
		AdminLog("Failed Database delete(Table:".X1_DB_teamchallenges.")","deletechallenge","Major Error",ERROR_RET);
    $c .= DispFunc::X1PluginTitle(XL_achallenges_databaseopps);
    }
	return DispFunc::X1PluginOutput($c);
}

/*######################################
Name: DisplayChallengeMaps
Needs: Databaseinfo $event, string $totalmaps, string $map_array, item $map, boolean $themap=false
Returns: string output
What does it do: Sets up the display for maps of the challange
#######################################*/
function DisplayChallengeMaps($event,$totalmaps,$map_array, $map,$themap=false){
	if(empty($event)||empty($totalmaps)||empty($map_array)){
		if(empty($event)){
			AdminLog("Empty event var","DisplayChallengeMaps","Major Error",ERROR_DIE);
		}
		elseif(empty($totalmaps)){
			AdminLog("Empty totalmaps var","DisplayChallengeMaps","Major Error",ERROR_DIE);
		}
		elseif(empty($map_array)){
			AdminLog("Empty map_array var","DisplayChallengeMaps","Major Error",ERROR_DIE);
		}
	}
	$output='';
	for($a=0; $a<$event[$totalmaps]; $a++){
    	$output .= "
		<tr>
    	    <td class='alt2'>Map $a</td>
			<td class='alt2'>";
		if($themap){
			if(empty($map)){
					AdminLog("Empty map var","DisplayChallengeMaps","Major Error",ERROR_DIE);
			}
			$output .= SelectBox_Maplist($map_array, $event['sid'], $map[$a]);
		}
		else{
			$output .= SelectBox_Maplist($map_array, $event['sid']);
		}
		$output .= "</td>
		</tr>";
    }
    return $output;
    
}

/*######################################
Name: CreateChallForum
Needs: Databaseinfo $event, boolean $moderator
Returns: string output
What does it do: Sets up the display creating a challenge
#######################################*/
function CreateChallForum($event,$moderator, $date){
	if(empty($event)){
		AdminLog("Empty event var","CreateChallForum","Major Error",ERROR_DIE);
	}
	$output = DispFunc::X1PluginTitle(XL_achallenges_create.$event['title']);
		$output .= "
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
			<table class='".X1plugin_admintable."' width='100%'>
	    		<thead class='".X1plugin_tablehead."'>
	        	    <tr>
	        			<th colspan='2'>".XL_achallenges_create."</th>
	        		</tr>
	    		</thead>
	            <tbody class='".X1plugin_tablebody."'>
	                <tr>
	                    <td class='alt2'>".XL_matchpreview_challenger."</td>
						<td class='alt2'>".SelectBox_LadderTeamDrop('challenger', $_POST['ladder_id'])."</td>
					</tr>
					<tr>
						<td class='alt1'>".XL_matchpreview_challenged."</td>
						<td class='alt1'>".SelectBox_LadderTeamDrop('challenged', $_POST['ladder_id'])."</td>
					</tr>
					<tr>
						<td class='alt2'>".XL_achallenges_matchdate."</td>
						<td class='alt2'>".DispFunc::X1EditTime(time())."
					<input type='hidden' value='$_POST[ladder_id]' name='ladder_id'>
					</td>
				</tr>
				<tr>
					<th colspan='2' class='alt1'>".XL_achallenges_maps1."</th>
				</tr>
				".DisplayChallengeMaps($event,"nummaps1","mapa[]", NULL)."
			<tr>
				<th colspan='2' class='alt1'>".XL_achallenges_maps2."</th>
			</tr>
				".DisplayChallengeMaps($event,"nummaps2","mapb[]", NULL)."
		</tbody>
	    <tfoot class='".X1plugin_tablefoot."'>
	        <tr>
	            <td colspan='2'>
	            	<input type='hidden' value='$date' name='date'>
	            	".AdminModbutton($moderator,XL_achallenges_add,$action=array("insertchallenge","mod_insertchallenge"))."
							</td>
	            </form>
	        </tr>
	    </tfoot>
	    </table>";
	    return $output;
}

?>
