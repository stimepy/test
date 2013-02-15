<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################

if (!defined('X1plugin_include'))exit();
###############################################################

function gamesmanager() {
    $c = "
    <script type='text/javascript' src='".X1_jspath."/functions.js'></script>
		<table class='".X1plugin_admintable."' width='100%'>
			<thead class='".X1plugin_tablehead."'>
				<tr>
					<th colspan='4'>".XL_agames_add."</td>
					<th>".XL_save."</td>
				</tr>
			</thead>
			<tbody class='".X1plugin_tablebody."'>
				<tr>
					<td class='alt1' width='96%' colspan='4' align='left'>
						<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
							<input type='int' value='1' size='3' name='num_games'>
							<input type='image' title='".XL_agames_add."' src='".X1_imgpath.X1_addimage."'>
							<input name='".X1_actionoperator."' type='hidden' value='addgames''>
						</form>
					</td>
					<td class='alt2' width='4%' align='center'>
						<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
						<input type='image' title='".XL_save."' src='".X1_imgpath.X1_saveimage."' >
					</td>
				</tr>
			</tbody>
	    <thead class='".X1plugin_tablehead."'>
	       <tr>
        		<th>".XL_teamprofile_hid."</th>
        		<th>".XL_agames_name."</th>
        		<th>".XL_agames_pic."</th>
        		<th>".XL_agames_desc."</th>
        		<th align='center'><img src='".X1_imgpath.X1_delimage."' title='".XL_delete."' border='0'></th>
        	<!--	<th align='center'><img src= -->
            </tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>";


		$result = SqlGetAll("*",X1_DB_games," order by gameid");

    	if(!$result){
        	$c .= "
				<tr>
					<td colspan='5'>".XL_agames_none."</td>
				</tr>
            </tbody>
            <tfoot class='".X1plugin_tablefoot."'>
                <tr>
                    <td colspan='5'>&nbsp;</td>
                </tr>
            </tfoot>
            </table>";
        	return  $c;
    	}
		$image=GetAdminImages($file_path=X1_imgpath."/games");
		$count=0;
		//todo: java script to check and if modified set to update.
		foreach($result AS $row)
		{
		 	$count_x=0;
			$c .= "<tr>
					<td class='alt1'><input type='text' name='nlv_".$count."[id]' value='".$row[0]."' readonly size='2'></td>
					<td class='alt2'><input type='text' name='nlv_".$count."[name]' value='".$row[1]."' size='15'></td>
					<td class='alt1'>
					<select name='nlv_".$count."[image]' onchange=\"X1plugin_imgpreview(this, '".X1_imgpath."/games/'); return false;\">";				
					$c .= "<option value=''>".XL_agames_selectimage."</option>\n";
					while(sizeof($image)!=$count_x){
						if ($image[$count_x] == $row[2]){
								$sel="selected";
							}
							else{
								$sel = "";
							}
							$c .= "<option $sel value='$image[$count_x]'>$image[$count_x]</option>\n";
							unset($sel);
							$count_x++;
					}
			$c .= "</select>
				</td>
				<td class='alt2'><input type='text' name='nlv_".$count."[desc]'  value='".$row[3]."'size='25'></td>
				<td  class='alt1'align='center'>
                <input type='checkbox' name='nlv_".$count."[checked]' value='checked'></td>
				</tr>";
			$count++;
		}
		$c .= "
		<input type='hidden' name='num_rows' value='".$count."'>
		</tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td colspan='5'>
                <input type='hidden' name='".X1_actionoperator."' value='updategames'>
                </td>
            </tr>
        </tfoot>
    </table>
	</form>
	<br/>
	<table class='".X1plugin_admintable."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
    		<tr>
    			<th width='25%'>".XL_agames_preview."</th>
    			<th  width='75%'></th>
    		</tr>
        </thead>
        <tbody class='".X1plugin_tablebody."'>
    		<tr>
    			<td class='alt1' width='25%' align='center'>
				<img id='X1plugin_preimg' src='".X1_imgpath.X1_defpreviewimage."'></td>
    			<td class='alt2' width='75%' align='right'></td>
    		</tr>
	   </tbody>
        <tfoot class='".X1plugin_tablefoot."'>
            <tr>
                <td colspan='2'>&nbsp;</td>
            </tr>
        </tfoot>
        </table>";
	return DispFunc::X1PluginOutput($c, 1);
}

function updategames(){ //Needs rewrite, but first javascript function needed.
		$fail=false;
		$total_rows=DispFunc::X1Clean($_POST['num_rows']);
		for ($i=0; $i <$total_rows ; $i++) {
			$nlv_info = $_POST["nlv_".$i];
			$nlv_info=DispFunc::X1Clean($nlv_info,5);
		//	$iq = SqlGetAll("*",X1_DB_games," WHERE gameid=".MakeItemString($nlv_info['id']));
		//	$irows = count($iq);
			if(true){
				$results_update=ModifySql("UPDATE ",X1_DB_games," SET
				gamename=".MakeItemString($nlv_info['name']).",
				gameimage=".MakeItemString($nlv_info['image']).",
				gametext=".MakeItemString($nlv_info['desc'])." 
				WHERE gameid=".MakeItemString($nlv_info['id']));
				if(!$results_update){
					AdminLog(XL_failed_updat.", result number:$i(Table".X1_DB_games.")","updategames", "Major Error",ERROR_DISP);
					$fail=true;
				}
			}
			if(isset($nlv_info['checked'])){
				if($nlv_info['checked']=="checked"){
					$game_id=MakeItemString($nlv_info['id']);
					$game_inuse=SqlGetRow("game",X1_DB_events,"where game=".$game_id);
					if(!isset($game_inuse['game'])){
						$result_delete=ModifySql("delete from",X1_DB_games," WHERE gameid=".$game_id);
						if(!$result_delete){
							AdminLog("Failed database Delete, result number:$i(Table".X1_DB_games.")","updategames", "Major Error",ERROR_DISP);
							$fail=true;
						}//result_delete
					}// But only if the game is NOT used by a event.
				}//nlv_info[checked]
			}//isset
	 	}//for
	 	if($fail){//Would like to fail when error occured BUT want to record ALL errors that could occur before die.
	 		die();
	 	}
	 	
		$c  = x1_admin("games");
	    $c .= DispFunc::X1PluginTitle(XL_agames_updated);
        return DispFunc::X1PluginOutput($c);
}

function addgames(){
	for ($i=0; $i<$_POST['num_games']; $i++) {
		$result = ModifySql("insert into ",X1_DB_games," values (NULL,NULL,NULL,NULL)");
	}
	if(!$result){
		AdminLog("Failed database insert(Table:".X1_DB_games.")", "addgames", "Major Error",ERROR_DISP);
		$output="";
	}
	else{
		$output=DispFunc::X1Clean($_POST['num_games']).XL_agames_added;
	}
	$c  = x1_admin("games");
	$c .= DispFunc::X1PluginTitle($output);
	return DispFunc::X1PluginOutput($c);
}
?>
