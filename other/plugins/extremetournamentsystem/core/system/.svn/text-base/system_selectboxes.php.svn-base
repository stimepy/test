<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2011
##Version 2.6.4
###############################################################
function SelectBox_LadderDrop($type="ladder", $cur="", $start=XL_select_event) {
    $rows = SqlGetAll("sid, title",X1_DB_events," order by title");
	$c=SelectBoxLadderDropMenu($rows, $type, $cur);
	return $c;
}

function SelectBox_LadderDropUser($type="ladder", $cur="", $start=XL_select_event) {
    $rows = SqlGetAll("sid, title",X1_DB_events," where enabled<>0 order by title");
	$c=SelectBoxLadderDropMenu($rows, $type, $cur);
	return $c;
}

function SelectBox_ladders($type, $cur="") {
    $rows = SqlGetAll("sid, title",X1_DB_events," order by sid");
	$c=SelectBoxLadderDropMenu($rows, $type, $cur);
	return $c;
}

function SelectBox_TeamDrop($type, $cur="", $start=XL_select_team, $startval="") {
    $rows = SqlGetAll("team_id, name",X1_DB_teams," order by name");
    $c  = "<select name='$type' id='$type'>" ;
    if($cur=="")$c .= "<option value='$startval'>$start</option>";
	if($rows){
		foreach($rows As $row) {
			if ($row['team_id']==$cur) {
				$sel = "selected ";
			}else{
				$sel = "";
			}
			$c .= "<option $sel value='$row[team_id]' align='left'>$row[name]</option>";
			unset($sel);
		}
    }
	$c .= "</select>";
	return $c;
}

function SelectBox_games($type='game', $cur='',  $start="", $startval="") {
	$rows = SqlGetAll("gameid, gamename",X1_DB_games," order by gametext");
	$c  = "<select name='$type'>";
	if(!empty($start)){
    	$c .= "<option value='$startval'>$start</option>";
	}
	foreach($rows As $row){
		if ($row['gameid']==$cur) {
			$sel = "selected ";
		}else{
			$sel = "";
		}
		$c .= "<option $sel value='$row[gameid]'>$row[gamename]</option>\n";
		$sel = "";
	}
	$c .= "</select>";
	return $c;
}
function SelectBox_mods($type, $cur){
	$c = "<select name='$type'>";
 	if ($dir = @opendir(X1_modpath)) {
		while (($file = readdir($dir)) !== false) {
			$sel="";
			if($file == $cur) {
				$sel="selected";
			}
			if($file != ".." && $file != "." && $file != "index.htm" && $file != "X1EventMod.php") {
				$c .= "<option value='$file' $sel>$file</option>";
			}
		}
		closedir($dir);
	}
	$c .= "</select>";
	return $c;
}
function SelectBox_JoinedLadderDrop($team) {
    $toplist = SqlGetAll(X1_prefix.X1_DB_teamsevents.".ladder_id, ".X1_prefix.X1_DB_events.".title",X1_DB_teamsevents.", ".X1_prefix.X1_DB_events," where ".X1_prefix.X1_DB_teamsevents.".team_id=".MakeItemString($team)." and ".X1_prefix.X1_DB_teamsevents.".ladder_id =".X1_prefix.X1_DB_events.".sid order by title");
    $c = "<SELECT NAME='ladder_id'>" ;
	if($toplist!=NULL){
		foreach($toplist AS $row){
			//$event =  $xdb->GetRow("select sid,title from ".X1_prefix.X1_DB_events." where sid=".MakeItemString($row['ladder_id']));
			$c .=  "<option value='$row[ladder_id]' align='left'>$row[title]</option>";
		}
	}
	else{
		$c .="<option value='-1' align='left'>".XL_select_event."</option>";
	}
	$c .= "</select>";
	return $c;
}//aa

/*################################
Name:SelectBox_JoinedTeamDrop
What does it do:
Needs:string $info, datainfo $info
returns:string $output
#################################*/
function SelectBox_JoinedTeamDrop($type, $info) {
    $output = "<SELECT NAME='$type'>" ;
		foreach($info As $team){
			$output .= "<option value='$team[team_id]' align='left'>$team[name]</option>";
		}
	$output .= "</select>";
	return $output;
}


function SelectBox_Maplist($type, $ladder_id, $selected=0) {
	$groups = SqlGetRow("mapgroups",X1_DB_events," WHERE sid=".MakeItemString($ladder_id));
	$groups = explode(",",$groups[0]);
	if(is_array($groups)){
		$final = array();
		foreach($groups AS $group){
			$maps = SqlGetRow("maps",X1_DB_mapgroups," WHERE id=".MakeItemString($group));
			$final = array_merge($final, explode(",",$maps[0]));
		}
		$maps = array_unique($final);
		$c = "<SELECT NAME=".$type."'>" ;
		$sel = "";
		foreach($maps AS $map){
			if ($selected==$map){
				$sel = "selected ";
			}
			$info = SqlGetRow("mapid, mapname",X1_DB_maps," WHERE mapid=".MakeItemString($map));
			if($info){
				$c .=  "<option $sel value='$info[mapid]' align='left'>$info[mapname]</option>";
			}
			$sel = "";
		}
		$c .=  "</select>";
		return $c;
	}else{
		return "No Map Groups Yet";
	}
}

function SelectBox_LadderTeamDrop($type, $ladder_id, $cur='') {
	$toplist = SqlGetAll(X1_prefix.X1_DB_teamsevents.".team_id, ".X1_prefix.X1_DB_teams.".name",X1_DB_teamsevents.", ".X1_prefix.X1_DB_teams," WHERE ladder_id = ".MakeItemString($ladder_id)." and ".X1_prefix.X1_DB_teamsevents.".team_id =".X1_prefix.X1_DB_teams.".team_id  order by ".X1_prefix.X1_DB_teams.".name");
	$sel="";
	$c = "<SELECT NAME='$type'>" ;
	if(!$toplist){
		if($cur!=''){
			$name=X1TeamUser::SetTeamName(array($cur));
			$c .= "<option selected value='$cur'>$name[$cur]</option>";
			$c .= "</select>";
			return $c;		
		}
		else{
			$c .= "<option selected value='-1'>XL_tab_teams</option>";
			$c .= "</select>";
			return $c;
		}
  }
  
	foreach($toplist AS $row){
		if (($row['team_id']==$cur)) {
			$sel = "selected ";
		}
		$c .= "<option $sel value='$row[team_id]'>$row[name]</option>";
		$sel = "";
    }
	$c .= "</select>";
	return $c;
}

function SelectBox_ChallLadderTeamDrop($type, $ladder_id, $cur='', $elim='') {
	if($elim!==''){
		$elim="and ".X1_prefix.X1_DB_teamsevents.".team_id<>".$elim;
	}
	$toplist = SqlGetAll(X1_prefix.X1_DB_teamsevents.".team_id, ".X1_prefix.X1_DB_teams.".name" ,X1_DB_teamsevents.", ".X1_prefix.X1_DB_teams," WHERE ".X1_prefix.X1_DB_teamsevents.".ladder_id=".MakeItemString($ladder_id)." AND ".X1_prefix.X1_DB_teamsevents.".team_id=".X1_prefix.X1_DB_teams.".team_id ".$elim." order by ".X1_prefix.X1_DB_teams.".name");
	$c = "<SELECT NAME='$type'>" ;
	foreach($toplist As $row){
		if ($row['team_id']==$cur) {
			$sel = "selected ";
		}
		else{
			$sel = "";
		}
		$c .= "<option $sel value='$row[team_id]' align='left'>$row[name]</option>";
	}
	$c .= "</select>";
	return $c;
}

function SelectBox_Country($type, $valone) {
 	$flags=array('Argentina','Australia','Austria','Belgium','Bosnia','Brazil','Bulgaria','Canada','Chile','Croatia','Cyprus','Czechoslavakia','Denmark','England','Finland','France','Georgia','Germany','Greece','Holland','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Japan','Leichenstein','Luxembourg','Malaysia','Malta','Mexico','Morocco','New Zealand','North Vietnam','Norway','Poland','Portugal','Puerto Rico','Qatar','Rumania','Russia','Scotland','Singapore','South Africa','Spain','Sweden','Switzerland','Turkey','United Kingdom','United States','Pirates');
	$c = "
	<select size='1' name='$type'>";
	if ($valone){
		$c .= "<option>$valone</option>";
		foreach($flags As $flg){
		 	if($valone!=$flg){
				$c .= "<option>$flg</option>";
			}
		}
		return $c;
	}
	foreach($flags As $flg){
	 	
		$c .= "<option>$flg</option>";
	}
	return $c;
}




function SelectBox_MapGroups($options_selected=array(), $size=4) {
	$ids = array();
	$groups = SqlGetAll("id, name",X1_DB_mapgroups," ORDER BY name;");
	foreach ($groups AS $group) {
		$ids[] = $group[0]; 
		$names[$group[0]] = $group[1];
	}
	$difference = array_diff($ids, $options_selected);
	$c = "
	<table border='0'>
		  <tr>
			<td>
				<select size='$size' id='availablemapgroups' name='available_mapgroups' >";
				foreach($difference AS $id){
					$c .="<option value='$id'>$names[$id]</option>";
				}
	$c .='		</select> 
			</td>
			<td valign="top">
				<a href="javascript:" onclick="addAttribute();return false;">Add<br>maps<br></a> 
				<br>
				<a href="javascript:" onclick="delAttribute();return false;">Remove<br>maps</a> 
			</td>
			<td>';
	$c .="		<select name='selectedmapgroups[]' id='selectedmapgroups[]' size='$size' multiple>";
				foreach($options_selected AS $id){
					$c .="<option value='$id'>$names[$id]</option>";
				}
	$c .="		</select>
			</td>
		  </tr>
		</table>
		<script type='text/javascript'>createListObjects('availablemapgroups','selectedmapgroups[]');</script>";
	return $c;
}


function SelctBox_CommonEvents($t1, $t2, $type='event_id', $cur){
	$team = SqlGetAll("ladder_id",X1_DB_teamsevents," WHERE team_id = ".MakeItemString($t1));
	if($team){
		foreach($team AS $row){
			$t1_arr[] = $row[0];
		}
	}else{
		return false;
	}
	$team = SqlGetAll("ladder_id",X1_DB_teamsevents," WHERE team_id = ".MakeItemString($t2));
	if($team){
		foreach($team AS $row){
			$t2_arr[] = $row[0];
		}
	}else{
		return false;
	}
	$inter = array_intersect($t1_arr, $t2_arr);
	if($inter){
		$c = "<select name='$type'>";
		foreach($inter AS $row){
			$event = SqlGetRow("sid, title",X1_DB_events," WHERE sid = ".MakeItemString($row));
			if($event){
				$sel =($event['sid']==$cur) ? "selected " : "";
				$c .= "<option value='$event[sid]' $sel>$event[title]</option>";
			}
		}
		$c .= "</select>";
		return $c;
	}else{
		return false;
	}
}



#2.6.0
/*##########################
Function: SelcetBoxModerator
Needs:	String $start(defaulted), string $startvalue
Returns: String $c
What does it do: Creates a drop down box in order to create a list of Moderators currently created for XTS.
############################*/
function SelectBoxModerator($start=XL_modadmin_start, $startval="") {
    $mods = SqlGetAll("mod_id, mod_name",X1_DB_nukstaff," order by mod_name");
    $c  = "<select name='moderatorsel' id='modername'>";
	$c .= "<option value='$startval'>$start</option>";
	if($mods){
		foreach($mods As $moder) {
			$c .= "<option value='$moder[mod_name]' align='left'>$moder[mod_name]</option>";
		}
    }
	$c .= "</select>";
	return $c;
}

/*#########################
Function:SelectBox_inviteanyuser
Need: N/A
Return: String $c
What does it do: Creates a drop down box in order to create a list of users on the website.
#########################*/

function SelectBoxInviteAnyUser(){
	$list = SqlGetAllPre(X1_DB_usersidkey.", ".X1_DB_usersnamekey,X1_userprefix.X1_DB_userstable," order by ".X1_DB_usersnamekey."");		
		$c =  "<SELECT NAME='user_id''>";		
		foreach($list AS $item){
			$c .= "<option value='$item[0]'>$item[1]</option>";		
		}
		$c .="</select>";
		return $c;
}

/*##################################
Function:SelectBoxTeamTransfer
Needs: string $cookieteamid
Returns: string $output
What does it do: Creates a drop down box of users that the team can be transfered to.
###################################*/
function SelectBoxTeamTransfer($cookieteamid){
	$list = SqlGetAll(X1_prefix.X1_DB_userinfo.".uid, ".X1_prefix.X1_DB_userinfo.".gam_name",X1_DB_teamroster.", ".X1_prefix.X1_DB_userinfo," WHERE ".X1_prefix.X1_DB_teamroster.".team_id=".MakeItemString(DispFunc::X1Clean($cookieteamid))." and ".X1_prefix.X1_DB_userinfo.".uid=".X1_prefix.X1_DB_teamroster.".uid and ".X1_prefix.X1_DB_teamroster.".cocaptain='1' order by gam_name;");		
	$output =  "<SELECT NAME='user_id''>" ;		
	$output .= "<option value='X1_NA'>".XL_select_user."</option>";		
	if($list){
		foreach($list AS $item){
			$output .= "<option value='$item[0]'>$item[1]</option>";		
		}
	}
	$output .= "
	</select>";
	return $output;
}

/*##################################
Name:SelectBoxYesNo
Needs:string $type, boolean $question, string $class
Returns: String $output
What does it do: Creates a simple drop down box for editing yes or no
####################################*/
function SelectBoxYesNo($type, $question, $class="alt2"){
	$output ="<td class='$class'>
	<select name='$type' id='$type'>";
		if($question){
			$output .="<option value='1' selected>".XL_yes."</option>
			<option value='0'>".XL_no."</option>";
		}
		else{
			$output .="<option value='0' selected>".XL_no."</option>
			<option value='1'>".XL_yes."</option>";	
		}
	$output .="</select>
	</td>";
	return $output;
	}

/*##################################
Name:SelectBoxLadderDropMenu
Needs:array $menu_data, string $type, string $cur, string $start(XL_select_event)
Returns: String $output
What does it do: Creates a simple drop down box for choosing a ladder.
####################################*/
function SelectBoxLadderDropMenu($menu_data, $type, $cur, $start=XL_select_event){
	if($menu_data){
		$output  = "<select name='$type' id='$type'>";
		if($cur==""){
			$output .= "<option value='selected'>$start</option>";
		}
		foreach($menu_data As $row) {
			if ($row['sid']==$cur) {
				$sel = "selected ";
			}else{
				$sel = "";
			}
			$output .= "<option $sel value='$row[sid]' align='left'>$row[title]</option>";
			unset($sel);
		}
		$output .= "</select>";
	}
	else{
		$output = " ";
	}
	return $output;
}
?>
