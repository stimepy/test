<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2011
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

function disputemanager($moderator=false) {
    $span=6;
    $c = "
	<table class='".X1plugin_admintable."' width='100%'>
    	<thead class='".X1plugin_tablehead."'>
        	<tr>
        		<th>".XL_teamprofile_hid."</th>
        		<th>".XL_adisputes_sender."</th>
        		<th>".XL_adisputes_offender."</th>
        		<th>".XL_adisputes_event."</th>
        		<th>".XL_teamprofile_hdate."</th>
				<!--<th>".XL_view."</th>-->
        		<th>".XL_delete."</th>
        	</tr>
        </thead>
		<tbody class='".X1plugin_tablebody."'> ";
	$rows = SqlGetAll("dispute_id, sender, offender, ladder_id, date, info, title",X1_DB_teamdisputes.", ".X1_prefix.X1_DB_events," where ".X1_prefix.X1_DB_events.".sid = ".X1_prefix.X1_DB_teamdisputes.".ladder_id order by dispute_id DESC");
	if($rows){
		foreach($rows AS $row){
			$names=X1TeamUser::SetTeamName(array($row['sender'], $row['offender']));
			$c .= "
			<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
			<tr>
				<td class='alt1'>$row[dispute_id]</td>
				<td class='alt2'>".$names[$row['sender']]."</td>
				<td class='alt1'>".$names[$row['offender']]."</td>
				<td class='alt2'>$row[title]</td>
				<td class='alt1'>".date(X1_dateformat,$row['date'])."</td>
				<!--<td class='alt1'>
					<input name='id' type='hidden' value='$row[dispute_id]'>
					<input name='".X1_actionoperator."' type='hidden' value='viewdispute'>
					<input type='submit' value='".XL_view."'>
				</td>
				-->
				<td class='alt2'>
					<input name='id' type='hidden' value='$row[dispute_id]'>
					".AdminModButton($moderator,XL_ok,$action=array("deldispute","mod_deldispute"))."
				</td>
			</tr>
			<tr>
				<td colspan='$span'>".XL_adisputes_comments."$row[info]</td>
				</tr>
			</form>";
		}
	}
	else{
		$c .= "	<tr>
					<td colspan='$span'>".XL_adisputes_none."</td>
				</tr>";
	}
	$c .= DispFunc::DisplaySpecialFooter($span+1,$break=false);
	return DispFunc::X1PluginOutput($c, 1);
}

function X1_removedispute($moderator=false){
	$row = SqlGetRow("dispute_id",X1_DB_teamdisputes," WHERE dispute_id=".MakeItemString($_POST['id']));
  if($row){
		$result = ModifySql("DELETE FROM ",X1_DB_teamdisputes," WHERE dispute_id=".MakeItemString($_POST['id']));
		
		if($result)
		{
    	$c = DispFunc::X1PluginTitle(XL_adisputes_delted);
		}
		else
		{
			AdminLog("Failed Delete on Database(Table:".X1_DB_teamdisputes.")", "X1_removedispute","Major Error",ERROR_DISP);
		}
	}
	else
	{
		$c = DispFunc::X1PluginTitle(XL_dispute_error);
	}
	$output = definemodoradminmenu($moderator,"disputes");
	$output .= $c;
	return DispFunc::X1PluginOutput($output);
}
?>
