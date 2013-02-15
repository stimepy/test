<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################

function mapgroups() {

	$c ="<table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='4' align='left'>".XL_amapgroups_add."</th>
			<th>".XL_save."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1' width='96%' colspan='4'>
				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<input type='int' value='1' size='2' name='num_mapgroups'>
					<input type='image' title='".XL_amapgroups_add."' src='".X1_imgpath.X1_addimage."'>
					<input name='".X1_actionoperator."' type='hidden' value='addmapgroups''>
				</form>
			</td>
			<td class='alt2' width='4%' align='center'>
				<form action='".X1_adminpostfile."' method='POST' style='".X1_formstyle."'>
				<input type='image' title='".XL_save."' src='".X1_imgpath.X1_saveimage."'>
			</td>
		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_teamprofile_hid."</th>
				<th>".XL_amapgroups_name."</th>
				<th>".XL_amapgroups_contents."</th>
				<th>".XL_edit."</th>
				<th><img src='".X1_imgpath.X1_delimage."' title='".XL_delete."' border='0'></td>
			</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";
	$rows = SqlGetAll("*",X1_DB_mapgroups," ORDER BY id LIMIT 500");
	$count = 0;
	if($rows){
		foreach($rows AS $row){
			$maps = explode(",",$row["maps"]);
			if(!empty($maps[0])){ //if there is an array of maps the first should not be blank.
				$maps = array_chunk($maps, 3);
				$contents = implode(",", MapId2Names($maps[0]));
			}	
			else{
				$contents="";
			}

			$c .= "<tr>
					<td class='alt1'><input type='text' name='nlv_".$count."[]' value='".$row[0]."' readonly size='2'></td>
					<td class='alt2'><input type='text' name='nlv_".$count."[]' value='".$row[1]."' size='30'></td>
					<td class='alt1'>$contents</td>
					<td class='alt2'><a href='".$_SERVER['SCRIPT_NAME']."?".X1_linkactionoperator."=addmapstogroup&amp;groupid=$row[0]'>".XL_edit."</a></td>
					<td class='alt1' align='center'><input type='checkbox' name='nlv_".$count."[]' value='checked'>
					</td>
				</tr>";
				$count++;
		}
	}else{
		$c .= "<tr><td colspan='6'>".XL_amapgroups_none."</td></tr>";
	}
		$c .= "
			</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='6'>&nbsp;
            <input type='hidden' name='".X1_actionoperator."' value='updatemapgroups'>
            <input type='hidden' name='num_rows' value='$count'>
            </td>
        </tr>
    </tfoot>
    </table>
	</form>";
	return DispFunc::X1PluginOutput($c, 1);
}


function updatemapgroups(){//needs to be updated, javascript added.
	$num_rows=DispFunc::X1Clean($_POST['num_rows']);
	$fail=false;
	for ($i=0; $i < $num_rows; $i++) {
		$nlv_info = "nlv_".$i;
		//list($mapgid, $mapgname,  $checked)
		$maplist = DispFunc::X1Clean($_POST[$nlv_info],5);
		
		if(empty($maplist[1]) && $maplist!=0){
			$maplist[1]='';
		}
			$result=ModifySql("UPDATE ",X1_DB_mapgroups," SET name=".MakeItemString($maplist[1])." WHERE id=".MakeItemString($maplist[0]));
			if(!$result){
				AdminLog(XL_failed_updat."(Table:".X1_DB_mapgroups.")", "updatemapgroups", "Major Error",ERROR_DISP);
				$fail=true;
			}
		
		if(isset($maplist[2]) && $maplist[2]=="checked")
		{
			$del_suc=ModifySql("delete from ",X1_DB_mapgroups," where id=".MakeItemString($maplist[0]));
			if(!$del_suc){
				AdminLog("Failed database delete(Table:".X1_DB_mapgroups.")", "updatemapgroups", "Major Error",ERROR_DISP);
				$fail=true;
			}
		}
	}
	if($fail){//Waiting to ensure all errors are recorded.
		die();
	}
	$c = x1_admin("mapgroups");
	$c .= DispFunc::X1PluginTitle(XL_amapgroups_updated);
    return DispFunc::X1PluginOutput($c);
}

function addmapgroups(){
	$num_mapgroups=DispFunc::X1Clean($_POST['num_mapgroups']);
	for ($i=0; $i<$num_mapgroups; $i++) 
	{
		$result = ModifySql("insert into ",X1_DB_mapgroups," (name) values ('n/a')");
		if(!$result)
		{
	    AdminLog("Failed database insert(Table:".X1_DB_mapgroups.")", "addmapgroups","Major Error",ERROR_DISP);
	  }

	}
	$c  = x1_admin("mapgroups");
	$c .= DispFunc::X1PluginTitle($_POST['num_mapgroups'].' '.XL_amapgroups_added);
	return DispFunc::X1PluginOutput($c);
}



function addmapstogroup() {
	$c  = x1_admin("mapgroups");
	
	$group = SqlGetRow("*",X1_DB_mapgroups," WHERE id=".MakeItemString(DispFunc::X1Clean($_REQUEST['groupid'])));
		
	if(!$group){
		AdminLog(XL_failed_retr."(Var:group, Table:".X1_DB_mapgroups.")","addmapstogroup", "Major Error",ERROR_DISP);
		$c .= DispFunc::X1PluginTitle(XL_amapgroups_notfound);
		return DispFunc::X1PluginOutput($c);
	}
	
	$c ="<br /><table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='2' align='left'>".XL_amapgroups_addmapstogroup." :: $group[name]</th>
			<th>".XL_save."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1' width='96%' colspan='2'>
				".XL_amapgroups_addmapstogroup_info."
			</td>
			<td class='alt2' width='4%' align='center'>
				<form action='".X1_adminpostfile."' method='POST' style='".X1_formstyle."'>
				<input type='image' title='".XL_save."' src='".X1_imgpath.X1_saveimage."'>
			</td>
		</tr>
		</tbody>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				
				<th>".XL_teamprofile_hid."</th>
				<th>".XL_amapgroups_mapname."</th>
				<th>".XL_amapgroups_select."</th>
			</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";
	$result = SqlGetAll("*",X1_DB_maps," ORDER BY mapid DESC");
	if(!$result){
		AdminLog(XL_failed_retr."(Var:result, Table:".X1_DB_maps.")","addmapstogroup", "Major Error",ERROR_DIE);
	}
	$count=0;
	foreach($result AS $row){ 
		$sel = (in_array($row[0], explode(",", $group["maps"]))) ? "checked" : "";
		$c .= "<tr>
					<td class='alt1'><input type='text' name='nlv_".$count."[]' value='".$row[0]."' readonly size='2'></td>
					<td class='alt2'><input type='text' name='nlv_".$count."[]' value='".$row[1]."' readonly size='30'></td>
					<td class='alt1'><input type=\"checkbox\" name=\"nlv_".$count."[]\" value=\"checked\" $sel></td>
				</tr>";
		$count++;
	}
	$c .= "</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='6'>&nbsp;
            <input type='hidden' name='".X1_actionoperator."' value='editmapgroup'>
            <input type='hidden' name='num_rows' value='".$count."'>
			<input type='hidden' name='groupid' value='".$_REQUEST['groupid']."'>
            </td>
        </tr>
    </tfoot>
    </table>
	</form>";
	return DispFunc::X1PluginOutput($c);
}
	
	
function editmapgroup(){
	$group_id=DispFunc::X1Clean($_POST['groupid']);
	$array = array();
	$num_rows=DispFunc::X1Clean($_POST['num_rows']);
	for ($i=0; $i< $num_rows; $i++) {
		$nlv_info = "nlv_".$i;
		list($mapid, $mapname,  $checked) = DispFunc::X1Clean($_POST[$nlv_info],5);
		if($checked=="checked"){
			$array[] = $mapid; 
		 }
	}
	MapDiff($array, $group_id,&$rem_mp,&$new_maps);
	$result = array(false,true,true);
	$result[0] = ModifySql("UPDATE ",X1_DB_mapgroups," SET maps=".MakeItemString(implode(",", $array))." WHERE id=".MakeItemString($group_id));
	if($new_maps){
		$result[1] = ModifySql("Update ",X1_DB_maps, "Set gpmp_cnt = gpmp_cnt+1 where mapid in(".implode(",", $new_maps).")");
	}
	if($rem_mp){
		$result[2] = ModifySql("Update ",X1_DB_maps, "Set gpmp_cnt = gpmp_cnt-1 where mapid in(".implode(",", $rem_mp).")");
	}
	
	if(!$result[0])
	{
	AdminLog(XL_failed_updat."(Table:".X1_DB_mapgroups.")", "editmapgroup", "Major Error",ERROR_DISP);
	}
	elseif(!$result[1] && $new_maps){
		AdminLog(XL_failed_updat."new maps(Table:".X1_DB_maps.")", "editmapgroup", "Major Error",ERROR_DISP);
	}
	elseif(!$result[2] && $rem_mp){
		AdminLog(XL_failed_updat."old maps(Table:".X1_DB_maps.")", "editmapgroup", "Major Error",ERROR_DISP);
	}
	else
	{
		$c = x1_admin("mapgroups");
		$c .= DispFunc::X1PluginTitle(XL_amapgroups_updated);
	}
	return DispFunc::X1PluginOutput($c);
}	


/*################################################
 	name:MapId2Names
	what does it do:Takes the a maps id and maps it to a name. 
	needs:array $array
	returns:An array of string, false otherwise.
 ##################################################*/  //X1_mapid2names
function MapId2Names($map_array){
	if(is_array($map_array)){
		$return = array();
		foreach($map_array AS $mapid){
			if(!isset($where_clause)){
				$where_clause=" Where mapid IN (";	
			}
			else{
				$where_clause.=",";
			}
			$where_clause.=MakeItemString($mapid);
		}
		$where_clause.=")";
		$result = SqlGetAll("mapname",X1_DB_maps, $where_clause);
		if(!$result){
			AdminLog(XL_failed_retr."(Var:result, Table:".X1_DB_maps.")","MapId2Names", "Major Error",ERROR_DIE);
		}
		foreach($result as $ret){
			$return[]=$ret[0];
		}	
		return $return;
	}
	else{
		return false;
	}
}

/*################################################
 	name:MapDiff
	what does it do:Takes the maps and figures out the difference between the old and new maps(if any difference)
	needs:array $array, int $group_id, string &$rem_mp, string &$new_maps
	returns:An array of string, false otherwise.
 ##################################################*/ 
function MapDiff($cked_maps, $group_id,&$rem_mp,&$new_maps){
	$maps=SqlGetRow("maps",X1_DB_mapgroups,"where id=".MakeItemString($group_id));
	$maps=explode(",",$maps['maps']);
	$rem_mp=array_diff($maps,$cked_maps);
	$new_maps=array_diff($cked_maps, $maps);
	if(empty($rem_mp) || $rem_mp[0]==NULL){
		$rem_mp=false;
	}
	if(empty($new_maps) || $new_maps[0]==NULL){
		$new_maps=false;
	}
}



?>
