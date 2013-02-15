<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################
function teamprofile() {
	$cookie = X1Cookie::CookieRead(X1_cookiename);	
	$c  = DispFunc::X1PluginStyle();
	$row = SqlGetRow("*",X1_DB_teams," WHERE team_id=".MakeItemString($_REQUEST['teamname'])); 
	if (!$row){
		return DispFunc::X1PluginOutput($c.=DispFunc::X1PluginTitle(XL_teamprofile_noteam));
	}

	$c.="<script type='text/javascript' >
	var panels = new Array('panel1', 'panel2', 'panel3', 'panel4');
	function x1showPanel(name,length){
		for(i = 0; i < panels.length; i++){
			document.getElementById(panels[i]).style.display = (name == panels[i]) ? 'block':'none';
		}
	}
	</script>\n";
		//"<script type='text/javascript' src='".X1_jspath."/x1showPanel.js' ></script>	
	if(!X1_custommenu){
		$c .="
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel1',4); return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>
		".XL_teamprofile_tprofile."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel2',4); return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>
		".XL_teamprofile_troster."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel3',4); return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>
		".XL_index_events."</a>
		<a href='javascript:' class='tab' onclick=\"x1showPanel('panel4',4); return false;\" STYLE='text-decoration:none'>
		<img src='".X1_imgpath.X1_tab_image."' width='".X1_tab_width."' height='".X1_tab_height."' border='".X1_tab_border."'>
		".XL_teamprofile_thistory."</a>";
	}
	//figures out what panel you want
	$panel = (empty($_REQUEST['panel'])) ? 'home': strtolower($_REQUEST['panel']);
	
	$c .= DispFunc::X1PluginTitle(XL_teamprofile_title.$row['name']);
	
	$panstyle = ( $panel=="home" ) ? '' : 'style="display:none"';
	$c .="<div class='panel' id='panel1' $panstyle>";
	$c .=DisplayHomePanel($row);
	
	$panstyle = ( $panel=="roster" ) ? '' : 'style="display:none"';
	$c .="<div class='panel' id='panel2' $panstyle>";
	$c .=DisplayRosterPanel($row['team_id']);
	$c .="</div>";
	
	$panstyle = ( $panel=="events" ) ? '' : 'style="display:none"';
	$c .="<div class='panel' id='panel3' $panstyle>";
	$c .=DisplayEventPanel($row['team_id']);
	$c .="</div>";
	
	
	$panstyle = ( $panel=="history" ) ? '' : 'style="display:none"';
	$c .="<div class='panel' id='panel4' $panstyle>";
	$c .=DisplayHistoryPanel(SqlGetAll("*",X1_DB_teamhistory," WHERE winner_id=".MakeItemString($row['team_id'])." OR loser_id=".MakeItemString($row['team_id'])." ORDER BY game_id DESC"));
	$c .="</div>";
	
	return DispFunc::X1PluginOutput($c);
}

/*############################
FunctionL DisplayHomePanel
Needs:Databaseinfo $teaminfo
Returns: String $output
What does it do:Returns the panel for home display
#############################*/
function DisplayHomePanel($teaminfo) {
	$maillinkpic = ($teaminfo['mail'] == "") ? XL_na : "<a href='mailto:$teaminfo[mail]'>
    <img border='0' src='".X1_imgpath."/mail.gif' align='absmiddle'></a>";
 	list ($capmaillink, $capmsnlink, $capicqlink, $capaimlink, $capyimlink, $capweblink, $capxfirelink) = X1TeamUser::TeamContactIcons($teaminfo);
 	$rout = ($teaminfo['recruiting']) ? XL_yes:XL_no;
	$output = "<table class='".X1plugin_teamprofiletable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
    	<tr>
    		<th colspan='2'>".XL_teamprofile_title."</th>
    	</tr>
	</thead>
	<tbody class='".X1plugin_tablebody."'>";

	if(!empty($teaminfo['clanlogo'])){
		$ishttp=substr($teaminfo['clanlogo'],0,7);
		$ishttp=strcmp($ishttp,"http://");

		if($ishttp==0)
		{
		 	$image= $teaminfo['clanlogo'];
		 	list($image_width,$image_height)=SetLogoSize($image);
		}
		else if($ishttp<0 || $ishttp>0){
			$image="http://$teaminfo[clanlogo]";
			list($image_width,$image_height)=SetLogoSize($image);
		}
		else{
			 //needs to send a pm or email to the captain telling them the logo is bad and reset the logo  TODO
		 	$image = X1_team_image;
			list($image_width,$image_height)=SetLogoSize($image);
		}
	}
	else{
	 	$image = X1_team_image;
		list($image_width,$image_height)=SetLogoSize($image);
	}		
	
	$output .="
	<tr>
		<td colspan='2' align='center' class='alt1'>
			<a href='$teaminfo[website]' target='_blank'>
			<img src='$image' 
				border='0' hspace='5' vspace='5'
			width='$image_width' 
				height='$image_height'>
			</a>
		</td>
	</tr>";
	$output .="
	<tr>
		<td class='alt2'>".XL_teamprofile_name.":</td>
		<td class='alt2'>$teaminfo[name]</td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamprofile_homepage.":</td>
		<td class='alt1'><a href='$teaminfo[website]' target='_blank'> $teaminfo[website]</a></td>
	</tr>
	<tr>
		<td class='alt2'>".XL_teamprofile_location.":</td>
		<td class='alt2'><img src='".X1_imgpath."/flags/$teaminfo[country].bmp' align='absmiddle'> $teaminfo[country]</td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamprofile_mail.":</td>
		<td class='alt1'>$maillinkpic</td> 
	</tr>
	<tr>
		<td class='alt2'>".XL_teamprofile_captain.":</td>
		<td class='alt2'>".X1TeamUser::GetUserName($teaminfo['playerone'])."</td>
	</tr>
	<tr>
		<td class='alt1'>".XL_teamprofile_contact."</td>
		<td class='alt1'>$capmaillink $capmsnlink $capicqlink $capyimlink $capaimlink $capweblink</td>
	</tr>
	<tr>
		<td class='alt2'>".XL_teamprofile_recruiting."</td>
		<td class='alt2'>$rout</td>
	</tr>
	<tr>
		<th colspan='2' class='alt1'>".XL_teamprofile_moto.":</th>
	</tr>";

	if(empty($row['playerone2'])){
	 	$row['playerone2'] = XL_teamprofile_noprofile;
	}
	$output .="<tr>
		<td colspan='2'  class='alt2'>$teaminfo[playerone2]</td>
	</tr>";
	if(isset($cookie[0]) &&  $cookie[0] !=$teaminfo['playerone']){
		$output .="
		<tr>
			<td colspan='2'  class='alt2' align center>
				<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
				<input type='Submit' name='Submit' value='Challenge This Team' >
				<input name='".X1_actionoperator."' type='hidden' value='cookiechallenge'>
				<input name='team_id' type='hidden' value='$teaminfo[team_id]'>
				</form>
			</td>
		</tr>";
	}
	$span = 6;
	$output .=DispFunc::DisplaySpecialFooter($span);
	$output .= "
	</div>";
	return $output;
}

/*##################################
Function: SetLogoSize
Needs: string $image
Returns: Array of int($image_width, $imageheigt)
What does it do: Takes an image path(local or otherwise) and gets the image, gets it's properties from the image and returns them
###################################*/
function SetLogoSize($image){
	list($width, $height, $type, $attr) = getimagesize($image);
	$image_width = ($width > X1_teamimagew) ? X1_teamimagew : $width;
	$image_height = ($height > X1_teamimageh) ? X1_teamimageh : $height;
	return array($image_width,$image_height);
}

/*######################
Function: DisplayRosterPanel
needs: Databaseinfo $teamroster
Returns: string $output
What does it do:Sets up and displays an uneditable version of the team roster.
######################*/
function DisplayRosterPanel($team_id){
	$team_roster=SqlGetAll(X1_prefix.X1_DB_teamroster.".*, ".X1_prefix.X1_DB_userinfo.".*",X1_DB_teamroster.",".X1_prefix.X1_DB_userinfo," WHERE ".X1_prefix.X1_DB_teamroster.".team_id=".MakeItemString($team_id)." and ".X1_prefix.X1_DB_teamroster.".uid=".X1_prefix.X1_DB_userinfo.".uid ORDER BY ".X1_rostersort);
 	$span =6;
	$output ="<table class='".X1plugin_teamprofiletable."' width='100%'>
    <thead class='".X1plugin_tablehead."'>
		<tr> 
			<th>".XL_teamprofile_husername."</th>
			<th>".XL_teamprofile_hcontact."</th>
			<th>".XL_teamprofile_hjoindate."</th>";
			switch(X1_extrarosterfields){
			 case 1:
				$output .="<th>".X1_extraroster1."</th>";
				break;
			case 2:
				$output .="<th>".X1_extraroster1."</th>
				<th>".X1_extraroster2."</th>";
				break;
			case 3:
				$output .="<th>".X1_extraroster1."</th>
				<th>".X1_extraroster2."</th>			
				<th>".X1_extraroster3."</th>";
				break;
			}
		$output .="</tr>
    </thead>
    <tbody class='".X1plugin_tablebody."'>";

	if($team_roster){
		foreach($team_roster As $row){
			list ($maillink, $msnlink, $icqlink, $aimlink, $yimlink, $weblink, $xfirelink) = X1TeamUser::ContactIcons($row["uid"], $old=true);
			switch(X1_extrarosterfields){
			 case 1:
				$extra1 = (empty($row['extra1'])) ? "<td class='alt2'>n/a</td>" : "<td class='alt2'>".$row['extra1']."</td> </tr>";
				$extra2 = "";
				$extra3 = "";
				break;
			 case 2:
				$extra1 = (empty($row['extra1'])) ? "<td class='alt2'>n/a</td>" : "<td class='alt2'>".$row['extra1']."</td>";
				$extra2 = (empty($row['extra2'])) ? "<td class='alt2'>n/a</td>" : "<td class='alt2'>".$row['extra2']."</td> </tr>";
				$extra3 = "";
				break;
			 case 3:
				$extra1 = (empty($row['extra1'])) ? "<td class='alt2'>n/a</td>" : "<td class='alt2'>".$row['extra1']."</td>";
				$extra2 = (empty($row['extra2'])) ? "<td class='alt2'>n/a</td>" : "<td class='alt2'>".$row['extra2']."</td>";			
				$extra3 = (empty($row['extra3'])) ? "<td class='alt2'>n/a</td>": "<td class='alt2'>".$row['extra3']."</td> </tr>";
				break;
			}
			
			$output .= "
            <tr>
                <td class='alt1'>
    			<a href='".X1_publicgetfile."?".X1_linkactionoperator."=playerprofile&member=$row[uid]'>$row[gam_name]</a></td>
    			<td class='alt2'>$maillink &nbsp $msnlink &nbsp $icqlink &nbsp $aimlink &nbsp $yimlink &nbsp $xfirelink &nbsp $weblink</td>
    			<td class='alt1'>".date(X1_dateformat, $row['joindate'])."</td>".
    			$extra1.$extra2.$extra3;
		} 
	}
	else{
		$$output .= "<tr>
				<td colspan=".$span.">".XL_teamprofile_nomembers."</td>
				</tr>";
	}
	
	$output .= DispFunc::DisplaySpecialFooter($span, $break=false);
	return $output;
}

/*############################
Function:DisplayEventPanel
Needs:int $team_id
Returns: string $output
What does it do: Creates the events panel for the events of the team and it's points
#############################*/
function DisplayEventPanel($team_id){
 	$events = SqlGetAll(X1_prefix.X1_DB_teamsevents.".*, ".X1_prefix.X1_DB_events.".title",X1_DB_teamsevents.", ".X1_prefix.X1_DB_events," WHERE ".X1_prefix.X1_DB_teamsevents.".team_id=".MakeItemString($team_id)." and ".X1_prefix.X1_DB_teamsevents.".ladder_id=".X1_prefix.X1_DB_events.".sid ORDER BY ".X1_prefix.X1_DB_teamsevents.".ladder_id ASC");
	$span =13;
	$output ="<table class='".X1plugin_teamprofiletable."' width='100%'>
	<thead class='".X1plugin_tablehead."'>
		<tr> 
			<!--<th>".XL_teamprofile_hid."</th>-->
			<th>".XL_teamprofile_hevent."</th>&nbsp
			<th>".XL_teamprofile_gp."</th>
			<th>".XL_teamprofile_w."</th>
			<th>".XL_teamprofile_l."</th>
			<th>".XL_teamprofile_d."</th>
			<th>".XL_teamprofile_p."</th>
		</tr>
		</thead>
		<tbody class='".X1plugin_tablebody."'>";
/*					<!--<th>".XL_teamprofile_tgp."</th>-->
			<!--<th>".XL_teamprofile_tw."</th>-->
			<!--<th>".XL_teamprofile_tl."</th>-->
			<!--<th>".XL_teamprofile_td."</th>-->
			<!--<th>".XL_teamprofile_tp."</th>-->*/

	if($events) {
		foreach($events AS $row){
			//$ladder=SqlGetRow("title",X1_DB_events," WHERE sid=".MakeItemString($row['ladder_id']));
			$output .="
			<tr> 
				<!--<td class='alt1'><a href='".X1_publicgetfile."?".X1_linkactionoperator."=ladderhome&sid=$row[ladder_id]'>
				$row[ladder_id]</a></td>-->
				<td class='alt2'><a href='".X1_publicgetfile."?".X1_linkactionoperator."=ladderhome&sid=$row[ladder_id]'>
				$row[title]</a></td>
				<td class='alt1'>$row[games]</td>
				<td class='alt2'>$row[wins]</td>
				<td class='alt1'>$row[losses]</td>
				<td class='alt1'>$row[draws]</td>
				<td class='alt1'>$row[points]</td>
			</tr>";
			/*<td class='alt2'>$row[totalgames]</td>-->
				<td class='alt1'>$row[totalwins]</td>-->
				<td class='alt2'>$row[totallosses]</td>-->
				<td class='alt2'>$row[totaldraws]</td>-->
				<td class='alt1'>$row[totalpoints]</td>-->*/
		}
	}
	else{
		$output .= "<tr>
				<td colspan=".$span.">".XL_teamprofile_noevents."</td>
				</tr>";
	}
	unset($events,$row);
	
	$row=SqlGetRow("*",X1_DB_teams,"where team_id=".MakeItemString($team_id));
	$output.="</tbody> 
		<tbody class='".X1plugin_tablebody."'>
		<tr><td><br /></td></tr>
		<tr>
			<td colspan=".$span.">".XL_teamprofile_totaldetails."</td>
		</tr>
		<thead class='".X1plugin_tablehead."'>
			<tr> 
				<th>".XL_playerprofile_team."</th>
				<th>".XL_teamprofile_tgp."</th>
				<th>".XL_teamprofile_tw."</th>
				<th>".XL_teamprofile_tl."</th>
				<th>".XL_teamprofile_td."</th>
				<th>".XL_teamprofile_tp."</th>
			</tr>
		</thead>
		<tr> 
		<td class='alt1'><a href='".X1_publicgetfile."?".X1_linkactionoperator."=teamprofile&teamname=$row[team_id]'>
			$row[name]</a></td>
			<td class='alt1'>$row[totalgames]</td>
			<td class='alt2'>$row[totalwins]</td>
			<td class='alt1'>$row[totallosses]</td>
			<td class='alt1'>$row[totaldraws]</td>
			<td class='alt1'>$row[totalpoints]</td>
		</tr>";

	$output .= DispFunc::DisplaySpecialFooter($span, $break=false);
	return $output;
}

/*########################
Function:DisplayHistoryPanel
Needs:Databaseino $teamhistory
Returns: string $output
What does it do: Creates the panel for displaying team history.
#########################*/
function DisplayHistoryPanel($teamhistory){
	$span = 6;
	$output ="<table class='".X1plugin_teamprofiletable."' width='100%'>
        <thead class='".X1plugin_tablehead."'>
        	<tr>
            	<th>".XL_teamprofile_hid."</th>
            	<th>".XL_teamprofile_hevent."</th>
            	<th>".XL_teamprofile_hwinner."</th>
            	<th>".XL_teamprofile_hloser."</th>
            	<th>".XL_teamprofile_hdate."</th>
            	<th>".XL_teamprofile_hdetails."</th>
        	</tr>
    	</thead>
    	<tbody class='".X1plugin_tablebody."'>";

	if($teamhistory){
		foreach($teamhistory AS $row) {
			$event = SqlGetRow("title",X1_DB_events," where sid=".MakeItemString($row['laddername']));
			$output .= "
			<form method='post' action='".X1_publicpostfile."' style='".X1_formstyle."'>
                <tr>
    				<td class='alt1'>$row[game_id]</td>
    				<td class='alt2'>$event[title]</td>
    				<td class='alt1'>$row[winner]</td>
    				<td class='alt2'>$row[loser]</td>
    				<td class='alt1'>".date(X1_dateformat, $row['date'])."</td>
    				<td class='alt2'>
    					<input name='".X1_actionoperator."' type='hidden' value='matchdetails'>
    					<input name='game_id' type='hidden' value='$row[game_id]'>
    					<input name='ladder' type='hidden' value='$row[laddername]'>
    					<input type='Submit' name='Submit' value='".XL_teamprofile_hdetails."' >
    				</td>
    			</tr>
			</form>";
		} 
	}
	else{
		$output .="<tr>
				<td colspan=".$span.">".XL_teamprofile_nomatches."</td>
            </tr>";
	}
	
	$output .= DispFunc::DisplaySpecialFooter($span);
	return $output;
}

?>
