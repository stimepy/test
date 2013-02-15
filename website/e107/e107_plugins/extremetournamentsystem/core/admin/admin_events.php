<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function ladderlistmanager() {
		$span=8;
    $c = "
    <table class='".X1plugin_admintable."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
        	<tr>
        		<th>".XL_aevents_add."</th>
        		<th>".XL_aevents_fixtherungs."</th>
        	</tr>
    	</thead>
    <tbody class='".X1plugin_tablebody."'>
	<tr>
		<td class='alt1' width='50%'>
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".
    			SelectBox_mods("type", "league")."
				<input name='Submit' type='Submit' value='".XL_aevents_add."'>
				<input name='".X1_actionoperator."' type='hidden' value='xadminladder'>
			</form>
		</td>
		<td class='alt2' align='right' width='50%'>
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>".
			SelectBox_LadderDrop("fix_ladder_id").
			"<input name='".X1_actionoperator."' type='hidden' value='fixladderrungs'>
			<input name='Reset Rungs' type='Submit' value='Reset'>
			</form>
		</td>
	</tr>
	</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='2'>&nbsp;</td>
        </tr>
    </tfoot>
    </table>
	<br />
	<table class='".X1plugin_admintable."' width='100%'>
    	<thead class='".X1plugin_tablehead."'>
        	<tr>
        		<th>".XL_teamprofile_hid."</th>
        		<th>".XL_aevents_hname."</th>
        		<th>".XL_aevents_hgame."</th>
        		<th>".XL_aevents_hmod."</th>
        		<th>".XL_eventhome_active."</th>
        		<th>".XL_eventhome_enabled."</th>
        		<th>".XL_teamadmin_rostermodify."</th>
        	</tr>
    	</thead>
	<tbody class='".X1plugin_tablebody."'>";
	$result = SqlGetAll("active, enabled, sid, title, type, ".X1_prefix.X1_DB_games.".gamename",X1_DB_events.", ".X1_prefix.X1_DB_games," where ".X1_prefix.X1_DB_games.".gameid = ".X1_prefix.X1_DB_events.".game order by sid DESC");
	if($result){
		foreach($result AS $row) {
			if($row['gamename']){
				$active  = ($row['active']) ? XL_yes : XL_no;
				$enabled = ($row['enabled']) ? XL_yes : XL_no;
				$c .= "
				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<tr>
						<td class='alt1'>$row[sid]</td>
						<td class='alt2'>$row[title]</td>
						<td class='alt1'>$row[gamename]</td>
						<td class='alt2'>$row[type]</td>
						<td class='alt1'>$active</td>
						<td class='alt2'>$enabled</td>
						<td class='alt1'>
							<input name='sid' type='hidden' value='$row[sid]'>
							<select name='".X1_actionoperator."'>
								<option value='editevent'>".XL_edit."</option>
								<option value='RemoveLadder'>".XL_delete."</option>
							</select>
							<input name='Submit' type='Submit' value='".XL_ok."'>
						</td>
					</tr>
				</form>";
				}
			}
	}else{
		$c .= "	<tr>
					<td colspan='$span'>".XL_aevents_none."</td>
				</tr>";
	}
	$c .= DispFunc::DisplaySpecialFooter($span,$break=false);
	return DispFunc::X1PluginOutput($c, 1);
}

function X1plugin_adminladder() {
    global $gx_event_manager;
	$type=DispFunc::X1Clean($_POST['type']);
	X1File::X1LoadFile("event.php" ,X1_modpath."/$type/");
	$c  = x1_admin("events");
	$c .= '<br />';
	$c .= DispFunc::X1PluginTitle('New Event :: '.$type);
    $c .= "<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>";
	$c .= "<script type='text/javascript' src='".X1_jspath."/mapgroups.js' ></script>
	<table class='".X1plugin_admintable."' width='100%'>
    	<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_general."</th>
    		</tr>
        </head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_hname."</td>
    			<td class='alt1'><input type='text' name='subject' size='40' value=''></td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_hgame."</td>
    			<td class='alt2'>".SelectBox_games()."</td>
    		</tr>";
    	if($gx_event_manager->X1HasSpecialFeatures())
    	{
    		$c .= $gx_event_manager->X1DisplaySpecialFeatures();
    	}
		$c .="</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_options."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_active."</td>
    			".SelectBoxYesNo("active", 1,"alt1")."
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_enabled."</td>
    			".SelectBoxYesNo("enabled", 1,"alt2")."
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_simchall."</td>
    			<td class='alt1'><input type='int' name='challengelimit' size='6' value='2'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_maxgames."</td>
    			<td class='alt2'><input type='int' name='gamesmaxday' size='6' value='1'> </td>
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_maxteams."</td>
    			<td class='alt1'><input type='int' name='maxteams' size='6' value='500'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_minplayers."</td>
    			<td class='alt2'><input type='int' name='minplayers' size='6' value='1'> </td>
    		</tr>
			<tr>
    			<td class='alt2'>".XL_aevents_maxplayers."</td>
    			<td class='alt2'><input type='int' name='maxplayers' size='6' value='500'> </td>
    		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_challdate."</th>
    		</tr>
    		</head>
            <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_resdates."</td>
    			".SelectBoxYesNo("restrictdates", 1,"alt1")."
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_numdates."</td>
    			<td class='alt1'><input type='int' name='numdates' size='6' value='3'></td>
    		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_mapoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_resmaps."</td>
    			".SelectBoxYesNo("restrictmaps", 1,"alt1")."
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_nummaps1."</td>
    			<td class='alt2'><input type='int' name='nummaps1' size='6' value='2'> </td>
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_nummaps2."</td>
    			<td class='alt1'><input type='int' name='nummaps2' size='6' value='1'> </td>
    		</tr>
			
			<tr>
    			<td class='alt1'>".XL_aevents_mapgroups."</td>
    			<td class='alt1'>".SelectBox_MapGroups(array())."</td>
    		</tr>
			
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_pointoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_win."</td>
    			<td class='alt1'><input type='int' name='pointswin' size='6' value='2'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_loss."</td>
    			<td class='alt2'><input type='int' name='pointsloss' size='6' value='0'> </td>
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_draw."</td>
    			<td class='alt1'><input type='int' name='pointsdraw' size='6' value='1'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_declinedchall."</td>
    			<td class='alt2'><input type='int' name='declinepoints' size='6' value='0'> </td>
    		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_expireoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_enableexpires."</td>
				".SelectBoxYesNo("enableexpires", 1,"alt1")."			
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_expirehours."</td>
    			<td class='alt2'><input type='int' name='expirehours' size='6' value='120'> </td>
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_expirepenalty."</td>
    			<td class='alt1'><input type='int' name='expirepen' size='6' value='1'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_expirebonus."</td>
    			<td class='alt2'><input type='int' name='expirebon' size='6' value='1'> </td>
    		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_reportoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_whoreports."</td>
    			<td class='alt1'>   				
					<select name='whoreports'>
    					<option value='winner'>".XL_teamprofile_hwinner."</option>
    					<option value='loser' selected>".XL_teamprofile_hloser."</option>
    				</select></td>
    		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_description."</th>
			</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td colspan='2' class='alt1'><textarea wrap='virtual' cols='100' rows='24' name='hometext'></textarea></td>
			</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
			<tr>
    			<th colspan='2'>".XL_aevents_rules."</th>
    		</tr>
		</head>
       	<tbody class='".X1plugin_tablebody."'>
			<tr>
    			<td colspan='2' class='alt2'><textarea wrap='virtual' cols='100' rows='24' name='bodytext'></textarea></td>
    		</tr>
		</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
		<tr>
			<th colspan='2'>
				<input type='submit' value='".XL_aevents_post."'>
				<input type='hidden' name='type' value='$type'>
				<input type='hidden' name='".X1_actionoperator."' value='newevent'>
			</th>
		</tr>
	</tfoot>
    </table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}


function newcompevent() {
    global $gx_event_manager;
    X1File::X1LoadFile("event.php",X1_modpath."/".DispFunc::X1Clean($_POST['type'])."/");
    $selectedmapgroups=DispFunc::X1Clean($_POST['selectedmapgroups'],5);
	$mapgroups = (is_array($selectedmapgroups))?implode(",",$selectedmapgroups):"";
	if($gx_event_manager->X1HasSpecialFeatures())
	{
		$item=$gx_event_manager->X1DataInsert();
		$result = ModifySql("insert into ",X1_DB_events," (
		title,hometext,bodytext,game,pointswin,pointsloss,pointsdraw,gamesmaxday,
		declinepoints,active,enabled,challengelimit,restrictdates,numdates,
		restrictmaps,nummaps1,nummaps2,maxteams,minplayers,maxplayers,type,
		expirechalls,expirehours,expirepen,expirebon,whoreports,mapgroups,".$item[0].")
		 values (
		".MakeItemString(DispFunc::X1Clean($_POST['subject'])).",
		".MakeItemString(DispFunc::X1Clean(trim($_POST['hometext'],3))).",
		".MakeItemString(DispFunc::X1Clean(trim($_POST['bodytext'],3))).",
		".MakeItemString(DispFunc::X1Clean($_POST['game'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointswin'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointsloss'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointsdraw'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['gamesmaxday'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['declinepoints'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['active'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['enabled'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['challengelimit'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['restrictdates'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['numdates'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['restrictmaps'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['nummaps1'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['nummaps2'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['maxteams'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['minplayers'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['maxplayers'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['type'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['enableexpires'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirehours'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirepen'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirebon'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['whoreports'])).",
		".MakeItemString($mapgroups).",
		".$item[1].")");
	}
	else{
		$result = ModifySql("insert into ",X1_DB_events," (
		title,hometext,bodytext,game,pointswin,pointsloss,pointsdraw,gamesmaxday,
		declinepoints,active,enabled,challengelimit,restrictdates,numdates,
		restrictmaps,nummaps1,nummaps2,standingstype,maxteams,minplayers,maxplayers,type,
		expirechalls,expirehours,expirepen,expirebon,whoreports,mapgroups)
		 values (
		".MakeItemString(DispFunc::X1Clean($_POST['subject'])).",
		".MakeItemString(DispFunc::X1Clean(trim($_POST['hometext'],3))).",
		".MakeItemString(DispFunc::X1Clean(trim($_POST['bodytext'],3))).",
		".MakeItemString(DispFunc::X1Clean($_POST['game'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointswin'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointsloss'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['pointsdraw'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['gamesmaxday'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['declinepoints'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['active'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['enabled'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['challengelimit'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['restrictdates'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['numdates'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['restrictmaps'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['nummaps1'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['nummaps2'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['maxteams'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['minplayers'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['maxplayers'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['type'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['enableexpires'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirehours'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirepen'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['expirebon'])).",
		".MakeItemString(DispFunc::X1Clean($_POST['whoreports'])).",
		".MakeItemString($mapgroups).")");
	}
		

	$c = x1_admin("events");
	if(!$result){
		AdminLog("Failed Database insert(Table:".X1_DB_events.").", "newcompevent","Major Error",ERROR_DISP);
	}
	else{
  	$c .= DispFunc::X1PluginTitle(XL_aevents_added);
  }
	return DispFunc::X1PluginOutput($c);
}

function x1_editevent() {
    global $gx_event_manager;

		$event = SqlGetRow("*",X1_DB_events," where sid=".MakeItemString(DispFunc::X1Clean($_POST['sid'])));
		if(!$event){
			AdminLog(XL_failed_retr."(Var:event, Table:".X1_DB_events.")","X1_editevent","Major Error",ERROR_RET);
		}
    X1File::X1LoadFile("event.php",X1_modpath."/".$event['type']."/");

	$c  = x1_admin("events");
	$c .= '<br />';

	$active2 = ($event['active']) ? XL_yes : XL_no;
	$enabled2 = ($event['enabled']) ? XL_yes : XL_no;
	$restrictdates2 = ($event['restrictdates']) ? XL_yes : XL_no;
	$restrictmaps2 = ($event['restrictmaps']) ? XL_yes : XL_no;
	$expirechalls = ($event['expirechalls']) ? XL_yes : XL_no;
	$whoreports = ($event['whoreports']=="winner") ? XL_teamprofile_hwinner : XL_teamprofile_hloser;
	
    $c .= DispFunc::X1PluginTitle(XL_aevents_editing."$event[title]");
	$c .= "<script type='text/javascript' src='".X1_jspath."/mapgroups.js' ></script>
	<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
	<table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
			<tr>
				<th colspan='2'>".XL_aevents_general."</th>
			</tr>
	</thead>
		<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_aevents_hname."</td>
			<td class='alt1'><input type='text' name='subject' size='50' value='$event[title]'></td>
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_hgame."</td>
			<td class='alt2'>".SelectBox_games('game', $event['game'])."</td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_mod."</td>
			<td class='alt1'>".SelectBox_mods("type", $event['type'])."</td>
		</tr>";
    	if($gx_event_manager->X1HasSpecialFeatures())
    	{
    		$c .= $gx_event_manager->X1DisplaySpecialFeatures($edit=true,$event);
    	}

	$c .=" </tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_options."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_aevents_active."</td>
			".SelectBoxYesNo("active",$event['active'],"alt1")."
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_enabled."</td>
			".SelectBoxYesNo("enabled",$event['enabled'])."
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_simchall."</td>
			<td class='alt1'><input type='int' name='challengelimit' size='6' value='$event[challengelimit]'> </td>
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_maxgames."</td>
			<td class='alt2'><input type='int' name='gamesmaxday' size='6' value='$event[gamesmaxday]'> </td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_maxteams."</td>
			<td class='alt1'><input type='int' name='maxteams' size='6' value='$event[maxteams]'> </td>
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_minplayers."</td>
			<td class='alt2'><input type='int' name='minplayers' size='6' value='$event[minplayers]'> </td>
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_maxplayers."</td>
			<td class='alt2'><input type='int' name='maxplayers' size='6' value='$event[maxplayers]'> </td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_challdate."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_aevents_resdates."</td>
			".SelectBoxYesNo("restrictdates",$event['restrictdates'],"atl1")."
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_numdates."</td>
			<td class='alt2'><input type='int' name='numdates' size='6' value='$event[numdates]'></td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_mapoptions."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1'>".XL_aevents_resmaps."</td>
			".SelectBoxYesNo("restrictmaps", $event['restrictmaps'],"alt1")."
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_nummaps1."</td>
			<td class='alt2'><input type='int' name='nummaps1' size='6' value='$event[nummaps1]'> </td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_nummaps2."</td>
			<td class='alt1'><input type='int' name='nummaps2' size='6' value='$event[nummaps2]'> </td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_mapgroups."</td>
			<td class='alt1'>".SelectBox_MapGroups(explode(",",$event['mapgroups']))."</td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_pointoptions."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt2'>".XL_aevents_win."</td>
			<td class='alt2'><input type='int' name='pointswin' size='6' value='$event[pointswin]'> </td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_loss."</td>
			<td class='alt1'><input type='int' name='pointsloss' size='6' value='$event[pointsloss]'> </td>
		</tr>
		<tr>
			<td class='alt2'>".XL_aevents_draw."</td>
			<td class='alt2'><input type='int' name='pointsdraw' size='6' value='$event[pointsdraw]'> </td>
		</tr>
		<tr>
			<td class='alt1'>".XL_aevents_declinedchall."</td>
			<td class='alt1'><input type='int' name='declinepoints' size='6' value='$event[declinepoints]'> </td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_expireoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_enableexpires."</td>
				".SelectBoxYesNo("enableexpires",$event['expirechalls'],"alt1")."
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_expirehours."</td>
    			<td class='alt2'><input type='int' name='expirehours' size='6' value='$event[expirehours]'> </td>
    		</tr>
    		<tr>
    			<td class='alt1'>".XL_aevents_expirepenalty."</td>
    			<td class='alt1'><input type='int' name='expirepen' size='6' value='$event[expirepen]'> </td>
    		</tr>
    		<tr>
    			<td class='alt2'>".XL_aevents_expirebonus."</td>
    			<td class='alt2'><input type='int' name='expirebon' size='6' value='$event[expirebon]'> </td>
    		</tr>
		</tbody>
		
	</tbody>
	<thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th colspan='2'>".XL_aevents_reportoptions."</th>
    		</tr>
		</head>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1'>".XL_aevents_whoreports."</td>
    			<td class='alt1'>   				
					<select name='whoreports'>";
						if($event['whoreports']=="winner"){
							$c .="<option value='$event[whoreports]' selected>$whoreports</option>
    						<option value='loser'>".XL_teamprofile_hwinner."</option>";
						}
						else{
							$c .="<option value='$event[whoreports]' selected>$whoreports</option>
    						<option value='winner'>".XL_teamprofile_hwinner."</option>";
						}
    				$c .="</select></td>
    		</tr>
		</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_description."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt2' colspan='2'><textarea wrap='virtual' cols='100' rows='12' name='hometext'>".stripslashes($event['hometext'])."</textarea></td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_rules."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1' colspan='2'><textarea wrap='virtual' cols='100' rows='24' name='bodytext'>".stripslashes($event['bodytext'])."</textarea></td>
		</tr>
	</tbody>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2'>".XL_aevents_notes."</th>
		</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt2' colspan='2'><textarea wrap='virtual' cols='100' rows='24' name='notes'>".stripslashes($event['notes'])."</textarea></td>
		</tr>
	</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='2'>
				<input type='hidden' name='sid' size='10' value='$_POST[sid]'>
				<input type='hidden' name='".X1_actionoperator."' value='".XL_aevents_change."'>
				<input type='Submit'  value='Update'>
			</td>
        </tr>
    </tfoot>
    </table>
	</form>";
	return DispFunc::X1PluginOutput($c); 
}


function removeLadder() {
	$suc_count=0;
	$sid=DispFunc::X1Clean($_REQUEST['sid']);
	if(isset($_GET['ok'])){
		$ok=DispFunc::X1Clean($_GET['ok']);
	}
	else{
		$ok = false;
	}
	if($ok) {
		$sid=MakeItemString($sid);
		$results[$suc_count++]=ModifySql("DELETE FROM ",X1_DB_events," where sid=".$sid);
		$results[$suc_count++]=ModifySql("DELETE FROM ",X1_DB_teamsevents," where ladder_id=".$sid);

	  for($x=0;$x<$suc_count;$x++){
	  	if(!$results[$x]){
	  		AdminLog("Failed Delete from database (defined table:$x)(Table: 0:".X1_DB_events." and/or 1".X1_DB_teamsevents.")", "removeLadder", "Major Error",ERROR_DISP);
	  		$x=$suc_count;
	  	}
	  	else{
	  		$output = XL_aevents_removed;
	  	}
	  }
	}
	else {
	    $output = XL_aevents_removewarning."[ <a href='".X1_admingetfile."'>".XL_no."</a> |
		 <a href='".X1_admingetfile."?".X1_linkactionoperator."=RemoveLadder&amp;sid=".$sid."&amp;ok=1'>".XL_yes."</a> ]";
	}
	$output_1  = x1_admin("ladders");
	$output_1 .= DispFunc::X1PluginTitle($output);
	return DispFunc::X1PluginOutput($output_1);
}

function changeLadder() {
	 global $gx_event_manager;
	 $selectedmapgroups=DispFunc::X1Clean($_POST['selectedmapgroups'],5);
	$groups = (!empty($selectedmapgroups)) ? implode(",",$selectedmapgroups):"";
	$type=DispFunc::X1Clean($_POST['type']);
	X1File::X1LoadFile("event.php",X1_modpath."/$type/");
	if($gx_event_manager->X1HasSpecialFeatures())
	{
		$item=$gx_event_manager->X1DataInsert($edit=true);
		$result= ModifySql("update ",X1_DB_events," set
						title=".MakeItemString(DispFunc::X1Clean($_POST['subject'])).",
						hometext=".MakeItemString(trim(DispFunc::X1Clean($_POST['hometext'],3))).",
						bodytext=".MakeItemString(trim(DispFunc::X1Clean($_POST['bodytext'],3))).",
						game=".MakeItemString(DispFunc::X1Clean($_POST['game'])).",
						notes=".MakeItemString(DispFunc::X1Clean($_POST['notes'])).",
						pointswin=".MakeItemString(DispFunc::X1Clean($_POST['pointswin'])).",
						pointsloss=".MakeItemString(DispFunc::X1Clean($_POST['pointsloss'])).",
						pointsdraw=".MakeItemString(DispFunc::X1Clean($_POST['pointsdraw'])).",
						gamesmaxday=".MakeItemString(DispFunc::X1Clean($_POST['gamesmaxday'])).",
						declinepoints=".MakeItemString(DispFunc::X1Clean($_POST['declinepoints'])).",
						active=".MakeItemString(DispFunc::X1Clean($_POST['active'])).",
						enabled=".MakeItemString(DispFunc::X1Clean($_POST['enabled'])).",
						challengelimit=".MakeItemString(DispFunc::X1Clean($_POST['challengelimit'])).",
						restrictdates=".MakeItemString(DispFunc::X1Clean($_POST['restrictdates'])).",
						numdates=".MakeItemString(DispFunc::X1Clean($_POST['numdates'])).",
						restrictmaps=".MakeItemString(DispFunc::X1Clean($_POST['restrictmaps'])).",
						nummaps1=".MakeItemString(DispFunc::X1Clean($_POST['nummaps1'])).",
						nummaps2=".MakeItemString(DispFunc::X1Clean($_POST['nummaps2'])).",
						maxteams=".MakeItemString(DispFunc::X1Clean($_POST['maxteams'])).",
						minplayers=".MakeItemString(DispFunc::X1Clean($_POST['minplayers'])).",
						maxplayers=".MakeItemString(DispFunc::X1Clean($_POST['maxplayers'])).",
						type=".MakeItemString($type).",
						expirechalls=".MakeItemString(DispFunc::X1Clean($_POST['enableexpires'])).",
						expirehours=".MakeItemString(DispFunc::X1Clean($_POST['expirehours'])).",
						expirepen=".MakeItemString(DispFunc::X1Clean($_POST['expirepen'])).",
						expirebon=".MakeItemString(DispFunc::X1Clean($_POST['expirebon'])).",
						whoreports=".MakeItemString(DispFunc::X1Clean($_POST['whoreports'])).",
						mapgroups=".MakeItemString(DispFunc::X1Clean($groups)).",
						".$item[0]."
						where sid=".MakeItemString(DispFunc::X1Clean($_POST['sid'])));
	}					
	else{
		$result= ModifySql("update ",X1_DB_events," set
						title=".MakeItemString(DispFunc::X1Clean($_POST['subject'])).",
						hometext=".MakeItemString(trim(DispFunc::X1Clean($_POST['hometext'],3))).",
						bodytext=".MakeItemString(trim(DispFunc::X1Clean($_POST['bodytext'],3))).",
						game=".MakeItemString(DispFunc::X1Clean($_POST['game'])).",
						notes=".MakeItemString(DispFunc::X1Clean($_POST['notes'])).",
						pointswin=".MakeItemString(DispFunc::X1Clean($_POST['pointswin'])).",
						pointsloss=".MakeItemString(DispFunc::X1Clean($_POST['pointsloss'])).",
						pointsdraw=".MakeItemString(DispFunc::X1Clean($_POST['pointsdraw'])).",
						gamesmaxday=".MakeItemString(DispFunc::X1Clean($_POST['gamesmaxday'])).",
						declinepoints=".MakeItemString(DispFunc::X1Clean($_POST['declinepoints'])).",
						active=".MakeItemString(DispFunc::X1Clean($_POST['active'])).",
						enabled=".MakeItemString(DispFunc::X1Clean($_POST['enabled'])).",
						challengelimit=".MakeItemString(DispFunc::X1Clean($_POST['challengelimit'])).",
						restrictdates=".MakeItemString(DispFunc::X1Clean($_POST['restrictdates'])).",
						numdates=".MakeItemString(DispFunc::X1Clean($_POST['numdates'])).",
						restrictmaps=".MakeItemString(DispFunc::X1Clean($_POST['restrictmaps'])).",
						nummaps1=".MakeItemString(DispFunc::X1Clean($_POST['nummaps1'])).",
						nummaps2=".MakeItemString(DispFunc::X1Clean($_POST['nummaps2'])).",
						maxteams=".MakeItemString(DispFunc::X1Clean($_POST['maxteams'])).",
						minplayers=".MakeItemString(DispFunc::X1Clean($_POST['minplayers'])).",
						maxplayers=".MakeItemString(DispFunc::X1Clean($_POST['maxplayers'])).",
						type=".MakeItemString($type).",
						expirechalls=".MakeItemString(DispFunc::X1Clean($_POST['enableexpires'])).",
						expirehours=".MakeItemString(DispFunc::X1Clean($_POST['expirehours'])).",
						expirepen=".MakeItemStringDispFunc::X1Clean(($_POST['expirepen'])).",
						expirebon=".MakeItemString(DispFunc::X1Clean($_POST['expirebon'])).",
						whoreports=".MakeItemString(DispFunc::X1Clean($_POST['whoreports'])).",
						mapgroups=".MakeItemString(DispFunc::X1Clean($groups))."
						where sid=".MakeItemString(DispFunc::X1Clean($_POST['sid'])));
	}
	$c = x1_admin("events");
	if(!$result){
		AdminLog(XL_failed_updat."(Table:".X1_DB_events.")", "changeLadder", "Major Error",ERROR_DISP);
	}
	else{
		$c .= DispFunc::X1PluginTitle(XL_aevents_updated);
	}
	return DispFunc::X1PluginOutput($c);
}


function X1ResetEvents(){
	global $gx_event_manager;
	$event_id=DispFunc::X1Clean($_POST['fix_ladder_id']);
	$event = SqlGetRow("type",X1_DB_events," where sid=".MakeItemString($event_id));
	if(!$event){
		AdminLog(XL_failed_retr."(Var:events, Table:".X1_DB_events.")", "X1ResetEvents", "Major Error",ERROR_DIE);
	}
	X1File::X1LoadFile("event.php",X1_modpath."/".$event['type']."/");
	$gx_event_manager->X1ResetEvent($event_id);
	
	$c  = x1_admin("ladders");
	$c .= DispFunc::X1PluginTitle(XL_aevents_fixed." $event_id");
	return DispFunc::X1PluginOutput($c);
}
?>
