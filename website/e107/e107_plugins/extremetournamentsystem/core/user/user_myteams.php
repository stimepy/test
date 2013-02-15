<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008-2011
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
#My Administratable Teams
function X1_myteams(){
	$span = 7;
	$c = DispFunc::X1PluginStyle();
	$user = X1_userdetails();
	$cook = X1Cookie::CookieRead(X1_cookiename);
	$c .= DispFunc::X1PluginTitle(XL_myteams_title);
	$c .=  "<table class='".X1_teamlistclass."' width='100%'>
            <thead class='".X1plugin_tablehead."'>
    			<tr>
    				<th>".XL_myteams_loc."</th>
    				<th>".XL_teamprofile_name."</th>
    				<th>".XL_teamprofile_captain."</th>
					<th>".XL_teamlist_hmembers."</th>
					<th>".XL_index_events."</th>
					<th>".XL_teamlist_recruiting."</th>
					<th>".XL_eventhome_active."</th>
				</tr>
            </thead>
			<tbody class='".X1plugin_tablebody."'>";
	$teamx[] = array();
	if (!empty($user[1]))
	{
		$teams = SqlGetAll("*",X1_DB_teams," WHERE playerone=".MakeItemString($user[0]));

		$i=0;
		if($teams)
		{
		 $i=1;
			foreach ($teams AS $row)
			{
				$c .=DisplayMyCapTeams($row, $teamx, $cook);
			}
		}
		$rows = SqlGetAll("team_id",X1_DB_teamroster," WHERE uid =".MakeItemString($user[0])." AND cocaptain=1");
		
		if ($rows)
		{
		 $i=1;
			foreach($rows AS $row2){
				if(!in_array($row2['team_id'], $teamx))
				{
					$row = SqlGetRow("*",X1_DB_teams," WHERE team_id=".MakeItemString($row2['team_id']));
					$c .=DisplayMyCapTeams($row, $teamx, $cook);
				}
			}
		}
		if($i==0)
		{
			$c .="<tr><td colspan='$span' align='center'><a href='".X1_publicpostfile.X1_linkactionoperator."createteam'>".XL_myteams_noteams."</a></td></tr>\n";
		}
	}//end top if
	else{
		$c .="<tr><td colspan='$span' align='center'>".XL_myteams_notloggedin."</td></tr>\n";
	}
	
	#Table Footer
	
	$c .=  DispFunc::DisplaySpecialFooter($span);
	return DispFunc::X1PluginOutput($c);
}

/*###########################
Function DisplayMyCapTeams
Needs: Databaseinfo $row, Referenced Array $teamx, Array $cookie
Returns: String $output
What does it do: Sets up and displays the teams the person is captain of.
###########################*/
function DisplayMyCapTeams($row, &$teamx, $cookie){
	$active = ($cookie[0] == $row['team_id']) ? "<img src='".X1_imgpath.X1_editimage."'/>" :"";
	list ($totalmembers, $totalevents, $rout) = CountTotalValues($row);

	$output =  "<tr>
		<td class='alt1'><img src='".X1_imgpath."/flags/".DispFunc::X1Clean($row['country']).".bmp' align='absmiddle'></td>
		<td class='alt2'><a href=".X1_publicpostfile.X1_linkactionoperator."activate_team&t=".$row['team_id'].">".DispFunc::X1Clean($row['name'])."</a></td>
		<td class='alt1'>".DispFunc::X1Clean(X1TeamUser::GetUserName($row['playerone']))."</td>
		<td class='alt2'>".DispFunc::X1Clean($totalmembers)."</td>
		<td class='alt1'>".DispFunc::X1Clean($totalevents)."</td>
		<td class='alt2'>".DispFunc::X1Clean($rout)."</td>
		<td class='alt1'>$active</td>
	</tr>";
	$teamx[] = $row['team_id'];
	return $output;
}

/*####################################
Function:UsersTeam
Needs:Boolean $returnorprint ( true returns, false prints)
Returns:string $output
What does it do:
#####################################*/
function UsersTeam($returnorprint=0){
	if(isset($_REQUEST['member'])){
		$member=$_REQUEST['member'];
	}
	else{
		$output ="I'm sorry no users were specified, please specify a user";
		return DispFunc::X1PluginOutput($output);
	}
	
	$output = DispFunc::X1PluginTitle(XL_playerprofile_joinedteams);
	
	$output .= "
	<table class='".X1plugin_playerprofiletable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr> 
			<th>".XL_teamlist_hcountry."</th>
			<th>".XL_teamlist_hname."</th>
			<th>".XL_playerprofile_tags."</th>
			<th>".XL_teamlist_hmembers."</th>
			<th>".XL_index_events."</th>
			<th>".XL_teamlist_recruiting."</th>";


//not complete work in progress
/*	if(check_admin()){
		$output .=  "<th align='center'><img src='".X1_imgpath.X1_editimage."'/></th>
		<th align='center'><img src='".X1_imgpath.X1_delimage."'/></th>";
    }*/   
	$output .= "</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>";
    
	$rows=SqlGetAll(X1_prefix.X1_DB_teamroster.".team_id, ".X1_prefix.X1_DB_teams.".*" ,X1_DB_teamroster.",".X1_prefix.X1_DB_teams," WHERE ".X1_prefix.X1_DB_teamroster.".uid=".MakeItemString($member)." and ".X1_prefix.X1_DB_teams.".team_id=".X1_prefix.X1_DB_teamroster.".team_id ORDER BY ".X1_prefix.X1_DB_teamroster.".joindate ASC");
	if($rows){
		foreach($rows As $row){
			//$row1= SqlGetRow("*",X1_DB_teams," WHERE team_id = $row[team_id]");
			
			list ($totalmembers, $totalevents, $rout) = CountTotalValues($row);	
			
			if(!empty($row['name'])){
				$output .= "
				<tr> 
					<td class='alt2'>
                    <img src='".X1_imgpath."/flags/$row[country].bmp' width='20' height='15' border='0'>$row[country]</td>
					<td class='alt1'><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=$row[team_id]'>$row[name]</a></td>
					<td class='alt2'><a href='".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=$row[team_id]'>$row[clantags]</a></td>
					<td class='alt2'>".DispFunc::X1Clean($totalmembers)."</td>
					<td class='alt1'>".DispFunc::X1Clean($totalevents)."</td>
					<td class='alt2'>".DispFunc::X1Clean($rout)."</td>
				</tr>";
			}
		}
	}
	else{
        $output .= "<tr>
			<td>".XL_playerprofile_none."</td>
		</tr>";
	}
	$output .= DispFunc::DisplaySpecialFooter($span=7);


	if($returnorprint){
        return $output;
    }else{
		DispFunc::X1PluginOutput($output);
    }
}

/*##################################
Function:CountTotalValues
Needs:Databaseinfo $row
Returns: int array of values (totalmembers, totalevents, and rout)
What does it do:Defines some basic variables used to display number of members
###################################*/
function CountTotalValues($row){
	#Total Members
	$totalmembers = GetTotalCountOf("team_id",X1_DB_teamroster," WHERE team_id=".MakeItemString(DispFunc::X1Clean($row['team_id'])));
	#Total Events Joined
	$totalevents = GetTotalCountOf("team_id",X1_DB_teamsevents," WHERE team_id=".MakeItemString(DispFunc::X1Clean($row['team_id'])));
	#Recruiting
	if (!isset($totalevents)){
		$totalevents = 0;
	}
	$rout = ($row['recruiting']) ? XL_yes:XL_no;
	
	return array($totalmembers, $totalevents, $rout);
}

/*####################################
Function: teamlist
Needs: N/A
Returns:
What does it do:
#####################################*/
function teamlist() {
	$c  = DispFunc::X1PluginStyle();
	$totalteams = GetTotalCountOf("name",X1_DB_teams,"");
	
	#Get Page Number
	$page = (!isset($_GET['page'])) ? 1 : DispFunc::X1Clean($_GET['page']);
	$limit = (isset($_REQUEST['limit'])) ? DispFunc::X1Clean($_REQUEST['limit']) : X1_teamlistlimit;
	#Get Page Limit
	$limitvalue = $page * $limit - ($limit);
	#Total Number of Pages
	$pages = $totalteams / $limit;
	#Set Column Span 
	$colspan = (check_admin()) ? 8 : 6;
	#Title
	$c .= DispFunc::X1PluginTitle(XL_teamlist_title);
	#Table Head
	$c .=  "<table class='".X1_teamlistclass."' width='100%'>
            <thead class='".X1plugin_tablehead."'>
    			<tr>
    				<th>".XL_teamlist_hcountry."</th>
    				<th>".XL_teamlist_hname."</th>
    				<th>".XL_teamprofile_hcontact."</th>
    				<th>".XL_teamlist_hmembers."</th>
    				<th>".XL_index_events."</th>
					<th>".XL_teamlist_recruiting."</th>";
	#If Admin, show extra columns
	if(check_admin()){
			$c .=  "<th align='center'><img src='".X1_imgpath.X1_editimage."'/></th>
					<th align='center'><img src='".X1_imgpath.X1_delimage."'/></th>";
    }
	$c .=  "	</tr>
            </thead>
			<tbody class='".X1plugin_tablebody."'>";
	
	#Query Database for this page
	$teams = SqlGetAll("*",X1_DB_teams," ORDER BY name ASC LIMIT $limitvalue, ".$limit);
	
	if($teams){
		#Loop through the rows
		foreach($teams AS $team){
			
			#GCaptain Contact Info
			list ($capmaillink, $capmsnlink, $capicqlink, $capaimlink, $capyimlink, $capweblink, $xfirelink, $irclink) = X1TeamUser::TeamContactIcons($team["team_id"], true);			
			
			list ($totalmembers, $totalevents, $rout) = CountTotalValues($team);
			
			#Table Rows
			$c .=  "<tr>
                    <td class='alt1'>
                    <img src='".X1_imgpath."/flags/".DispFunc::X1Clean($team['country']).".bmp' align='absmiddle'> ".DispFunc::X1Clean($team['country'])."</td>
                    <td class='alt2'><a href=".X1_publicpostfile.X1_linkactionoperator."teamprofile&teamname=".$team['team_id'].">".$team['name']."</a></td>
                    <td class='alt1'>$capmaillink $capweblink $capmsnlink $capicqlink $capaimlink $capyimlink $xfirelink</td>
                    <td class='alt2'>".DispFunc::X1Clean($totalmembers)."</td>
                    <td class='alt1'>".DispFunc::X1Clean($totalevents)."</td>
					<td class='alt2'>".DispFunc::X1Clean($rout)."</td>";
					#If admin, add extra columns
					if(check_admin()){
						$c .=  "
						<td class='alt1' align='center'>
							<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
								<input type='hidden' value='$team[team_id]' name='team_id'>
								<input type='image' title='".XL_edit."' src='".X1_imgpath.X1_editimage."'>
								<input name='".X1_actionoperator."' type='hidden' value='modifyTeam''>
							</form>
							</td>
							<td class='alt2' align='center'>
							<form method='post' action='".X1_adminpostfile."' style='".X1_formstyle."'>
								<input type='hidden' value='$team[team_id]' name='team_id'>
								<input type='hidden' value='$limit' name='limit'>
								<input type='image' title='".XL_delete."' src='".X1_imgpath.X1_delimage."'>
								<input name='".X1_actionoperator."' type='hidden' value='delTeam''>
							</form>
						</td>
						";
					}
		$c .=  "</tr>";
		}
	}
	#Table Footer and Pagination   make use the paginate function.
	$c .=  "</tbody>
	<tfoot class='".X1plugin_tablefoot."'>
		<tr>
			<th colspan='$colspan' align='center'>";
		if($pages > 1){
			if($page != 1){
				$pageprev = $page-1;
				$c .= "<a href='".X1_publicpostfile.X1_linkactionoperator."teamlist&page=$pageprev'>".XL_teamlist_prev.X1_teamlistlimit."</a>&nbsp;";
			}
			else {
				$c .= XL_teamlist_prev.X1_teamlistlimit."&nbsp;";
			}
			for($i = 1; $i <= $pages; $i++){
				if($i == $page){
					$c .= $i."&nbsp;";
				}else{
					$c .= "<a href='".X1_publicpostfile.X1_linkactionoperator."teamlist&page=$i'>$i</a>&nbsp;";
				}
			}
			if(($totalteams % X1_teamlistlimit) != 0){
				if($i == $page){
					$c .= $i."&nbsp;";
				}else{
					$c .= "<a href='".X1_publicpostfile.X1_linkactionoperator."teamlist&page=$i'>$i</a>&nbsp;";
				}
			}
			if(($totalteams - (X1_teamlistlimit * $page)) > 0){
				$pagenext=$page+1;
				$c .= "<a href='".X1_publicpostfile.X1_linkactionoperator."teamlist&page=$pagenext'>".XL_teamlist_next.X1_teamlistlimit."</a>";
			}
			else{
				$c .= XL_teamlist_next.X1_teamlistlimit;
			}
		}
		else{
			$c .= "&nbsp;";
		}
		$c .=  "</th>
		</tr>
	</tfoot>
	</table>";
	#Return Output
	return DispFunc::X1PluginOutput($c);
}
?>
