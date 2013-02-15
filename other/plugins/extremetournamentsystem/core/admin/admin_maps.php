<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function mapsmanager() {
    $c = "
	<table class='".X1plugin_admintable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr>
			<th colspan='4'>".XL_amaps_add."</th>
			<th>".XL_save."</th>
		</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>
		<tr>
			<td class='alt1' width='96%'  colspan='4'>
				<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
					<input type='int' value='1' size='2' name='num_maps'>
					<input type='image' title='".XL_amaps_add."' src='".X1_imgpath.X1_addimage."'>
					<input name='".X1_actionoperator."' type='hidden' value='addmaps''>
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
				<th>".XL_matchinfo_mapname."</th>
				<th>".XL_amaps_picture."</th>
				<th>".XL_maplist_download."</th>
				<th><img src='".X1_imgpath.X1_delimage."' title='".XL_delete."' border='0'></td>
			</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";
	$rows = SqlGetAll("*",X1_DB_maps," ORDER BY mapid  DESC LIMIT 500");
	$count = 0;
	if($rows){
	 	$image=GetAdminImages($filepath=X1_imgpath.'/maps');
		foreach($rows AS $row) {
			$count_x=0;
			$c .= "<tr>
					<td class='alt1'><input type='text' name='nlv_".$count."[]' value='".$row[0]."' readonly size='2'></td>
					<td class='alt2'><input type='text' name='nlv_".$count."[]' value='".$row[1]."' size='10'></td>
					<td class='alt1'>
					<select name='nlv_".$count."[]'>";
				while (sizeof($image)!= $count_x) {
						if ($image[$count_x] == $row[2]){
							$sel="selected";
						}else{
							$sel = "";
						}
						$c .= "<option $sel value='$image[$count_x]'>$image[$count_x]</option>\n";
						unset($sel);
						$count_x++;
				}

			$c .= "</select>";
			$c .= "</td>";
			if ($row[3]!=""){
				$dl_link="<a href='".$row[3]."' target='_blank'>
				<img src='".X1_imgpath.X1_saveimage."' title='".XL_maplist_download."' border='0'>
				</a>";
			}else {
				$dl_link="";
			}
			$c .= " <td class='alt2'>
						<input type='text' name='nlv_".$count."[]'  value='".$row[3]."' size='10'> $dl_link</td>
					<td class='alt1' align='center'>
						<input type='checkbox' name='nlv_".$count."[]' value='checked'>
					</td>
				</tr>";
				$count++;
		}
	}else{
		$c .= "<tr><td colspan='5'>".XL_amaps_none."</td></tr>";
	}
		$c .= "
			</tbody>
    <tfoot class='".X1plugin_tablefoot."'>
        <tr>
            <td colspan='5'>&nbsp;
            <input type='hidden' name='".X1_actionoperator."' value='updatemaps'>
            <input type='hidden' name='num_rows' value='$count'>
            </td>
        </tr>
    </tfoot>
    </table>
	</form>";
	return DispFunc::X1PluginOutput($c, 1);
 }


function updatemaps(){
	$num_rows=DispFunc::X1Clean($_POST['num_rows']);
	$fail=false;
	for ($i=0; $i < $num_rows; $i++) {
		$nlv_info = "nlv_".$i;
    //list($mapid, $mapname, $mappic, $mapdl, $checked) 
			$maps= DispFunc::X1Clean($_POST[$nlv_info],5);
		//$iq = GetTotalCountOf("map_id",X1_DB_maps," WHERE mapid=".MakeItemString($mapid));
			$result=ModifySql("UPDATE ",X1_DB_maps," SET
			mapname=".MakeItemString($maps[1]).",
			mappic=".MakeItemString($maps[2]).",
			mapdl=".MakeItemString($maps[3])." 
			WHERE mapid=".MakeItemString($maps[0]));
			if(!$result){
				AdminLog(XL_failed_retr."(Table:".X1_DB_maps.")","updatemaps","Major Error",ERROR_DISP);
				$fail=true;
			}
			if((isset($maps[4]) && $maps[4]=="checked")){
			//Make sure that mapgroups does NOT have anyof these maps in them.
				$can_del=SqlGetRow("gpmp_cnt",X1_DB_maps, "where mapid=".MakeItemString($maps[0]));
				if($can_del['gpmp_cnt']==0){
					$del_suc=ModifySql("delete from ",X1_DB_maps," where mapid=".MakeItemString($maps[0]));
				}
				elseif($can_del['gpmp_cnt']<0){
					MapCorr($map[0]);
				}
				else{
					$err[] = $maps[0];
					$del_suc=true;
				}
				if(!$del_suc){
					AdminLog("Failed Database delete(Table:".X1_DB_maps.")","updatemaps","Major Error",ERROR_DISP);
					$fail=true;
				}
			}
	}
	if($fail){
		die();
	}
	elseif(!empty($err)){
		$update =	XL_amaps_noupdate;
	}
	else{
		$update=XL_amaps_updated;
	}
	$c  = x1_admin("maps");
	$c .= DispFunc::X1PluginTitle($update);
    return DispFunc::X1PluginOutput($c);
}

function addmaps(){
	$num_maps=DispFunc::X1Clean($_POST['num_maps']);
	for ($i=0; $i<$num_maps; $i++) {
		$result = ModifySql("insert into ",X1_DB_maps," values(NULL,'','', '',DEFAULT)");
		if(!$result){
			AdminLog("Failed Database insert(Table:".X1_DB_maps.")","updatemaps","Major Error",ERROR_DISP);
			$out=" ";
		}
		else{
			if(!isset($out)){
				$out=XL_amaps_added;
			}
		}
	}
	$c  = x1_admin("maps");
	$c .= DispFunc::X1PluginTitle($_POST['num_maps'].$out);
	return DispFunc::X1PluginOutput($c);
}

/*################################################
 	name:MapCorr
	what does it do:If a gpmp_cnt goes negative, figure out if the value is still in map groups and updates the tuples column.
	needs:int $map
	returns:N/A
 ##################################################*/ 
function MapCorr($map){
	$maps=SqlGetAll("maps",X1_DB_mapgroups);
	$unsorted='';
	foreach($maps as $temp){
		$unsorted.=$temp['maps'].",";
	}
	$unsorted=rtrim($unsorted, ',');
	$maps=explode($unsorted);
	if(in_array($unsorted,$map)){
		$valuesof=array_count_values($unsorted);
		ModifySql("Update", X1_DB_maps, "Set gpmp_cnt=".MakeItemString($valuesof[$map])." where mapid=".MakeItemString($map));
		return;
	}
	ModifySql("Update", X1_DB_maps, "Set gpmp_cnt=0 where mapid=".MakeItemString($map));
}

?>
