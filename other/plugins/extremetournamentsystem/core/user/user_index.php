<?php
###############################################################
##Nuke  Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006 Kris Sherrerd 2008
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

/*########################################
Function X1_plugin_index
Needs:N/A
Returns:N/A
What does it do: Displays the page where u can view events and games.
#########################################*/
function X1plugin_index(){
	$span=2;
	//Get the games that are played.
	$games = SqlGetAll("*",X1_DB_games,"order by gamename");
	
	$c =IndexHeader();
	
	//get the active events for each game	if they exsist.
	if($games){
		foreach($games as $g_id){
			$game_ids[]=$g_id['gameid'];
		}
		$event_games=implode(",",$game_ids);
		$events = SqlGetAll("sid, title, game",X1_DB_events," WHERE game IN(".$event_games.") and active<>0");
		$game_event=SortEventByGame($events,$game_ids);
		unset($events,$game_ids,$event_games);
	}
	else{
		$c .= "<tr>
			<td colspan='$span' class='alt1'>".XL_index_none."</td>
    </tr>
    ".DispFunc::DisplaySpecialFooter($span, $break=false);
		
		return DispFunc::X1PluginOutput($c);
	}
	
	foreach($games AS $game){
		$c .= "<tr>
			<td class='alt1'>
      	<a href='".X1_publicpostfile.X1_linkactionoperator."home&amp;game=$game[gameid]'>
					<img src='".X1_imgpath."/games/$game[gameimage]' border='0' title='$game[gamename]'>
				</a>
			</td>
			<td class='alt2'>";
		
		$event=$game_event[$game['gameid']];
		if (!empty($event[0]['game'])) {
			foreach($event As $gam_event) {
				$c .= "<a href='".X1_publicpostfile.X1_linkactionoperator."ladderhome&amp;sid=$gam_event[sid]'>$gam_event[title]</a><br/>";
			}
		}
		else {
			$c .= XL_index_none;
		}
		$c .= "</td>
          </tr>";
	}
	
	$c .=DispFunc::DisplaySpecialFooter($span, $break=false);
	return DispFunc::X1PluginOutput($c);
}

/*###############################
Function: IndexHeader
Needs:N/A
Returns;string $output
What does it do: Sets up the information such as stype and tables.
################################*/
function IndexHeader(){
	$output  = DispFunc::X1PluginStyle();
	$output .= DispFunc::X1PluginTitle(XL_index_title);
  $output .= "<table class='".X1_teamlistclass."' width='100%'>
  	<thead class='".X1plugin_tablehead."'>
    	<tr>
      	<td>".XL_index_image."</td>
        <td>".XL_index_events."</td>
      </tr>
		</thead>
    <tbody class='".X1plugin_tablebody."'>";
    return $output;
}

/*###############################
Function: SortEventByGame
Needs:databaseinfo $events, array(int) $game_ids
Returns;array(array(array))) $temp
What does it do: Sorts the event information by game and stores the information in a 3 Dimentional array ordered by game id (gameid=>array(arrayofinformation())).  
################################*/
function SortEventByGame($events, $game_ids){
	$temp = array();
	foreach($game_ids as $id){
		array_push($temp,array($id=>array()));
		foreach($events as $event){
			if($id==$event['game']){
				$temp[$id][]=$event;
			}
		}
	}
	return $temp;
}
  

/*###############################
Function: ListMaps
Needs:N/A
Returns;N/A
What does it do: It lists the maps used in a certain event.
################################*/
function ListMaps() {
	$span=3;
	$c = DispFunc::X1PluginStyle();
	$event = SqlGetRow("*",X1_DB_events," WHERE sid=".MakeItemString(DispFunc::X1Clean($_REQUEST['id'])));

  if (!$event){
		return DispFunc::X1PluginOutput("Failed in maplist no events");
	}
	
	$game  = SqlGetRow("*",X1_DB_games," WHERE gameid=".MakeItemString(DispFunc::X1Clean($event['game'])));
    
	if (!$game){
		return DispFunc::X1PluginOutput("Failed to load maplist no games");
	}
	# Build the title and table head
	$c .= DispFunc::X1PluginTitle(XL_maplist_title.$event['title'])."
	<table class='".X1plugin_mapslist."' width='100%'>
		<thead class='".X1plugin_tablehead."'>
			<tr>
				<th>".XL_index_image."</th>
				<th>".XL_teamlist_hname."</th>
				<th>".XL_maplist_download."</th>
			</tr>
		</thead>";
		# Explode the mapgroups string to an array
		$groups = explode(",",$event['mapgroups']);
		# If we have an array
		if(is_array($groups)){
			# Loop through each group
			foreach($groups AS $group){
				# Grab the Mapgroup 
				$row = SqlGetRow("*",X1_DB_mapgroups," WHERE id=".MakeItemString(DispFunc::X1Clean($group)));
				# Output the Mapgroups name as a header
				$c .="<thead class='".X1plugin_tablehead."'>
					<tr>
						<th align='center' colspan='$span'>$row[name]</th>
					</tr>
				</thead>
				<tbody class='".X1plugin_tablebody."'>";
				# Explode the maps in the mapgroup string
				$arr = explode(",",$row['maps']);
				# If we have an array
				if(is_array($arr)){
					# Loop through each map
					foreach($arr AS $map){
						# If the value is not empty
						if(!empty($map)){
							# Grab info about the map
							$map_row = SqlGetRow("*",X1_DB_maps," WHERE mapid=".MakeItemString(DispFunc::X1Clean($map)));
							# If the download link is empty, no download, else a download link.
							if (empty($map_row['mapdl'])) {
								$download = XL_maplist_nodownload;
							}
							else{
								$download = "<a href='$map_row[mapdl]'>
								<img src='".X1_imgpath."/download.gif'border='0' title='".XL_maplist_download."'>
								</a>";
							}
							#Output the row with the map information
							$c .= "
							<tr>
								<td><img src='".X1_imgpath."/maps/$map_row[mappic]'
									title='$map_row[mapid]' width='80' height='80' border='0'></td>
									<td>$map_row[mapname]</td>
									<td>$download</td>
							</tr>";
						}	
					}
				# Else no maps
				}
			else{
				   $c .= "<tr>
							<td colspan='$span'>".XL_maplist_none."</td>
						</tr>";
				}
				$c .= "</tbody>";
		}
	}
	# Show the table footer
	$c .= DispFunc::DisplaySpecialFooter($span,$break=false);
	# Return the output
	return DispFunc::X1PluginOutput($c);
}

?>
