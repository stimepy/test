<?php
$text = "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php\">Event</a><br/>";
$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=myteams\">Team Administration</a><br/>";
if($_REQUEST['op']=="displayteam" || $_REQUEST['op']=="activate_team"){
   $text .="- <a href=\"javascript:x1showPanel('panel1')\">Team Info</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel2')\">Roster</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel3')\">Invites</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel4')\">Leagues</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel5')\">Match Manager</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel6')\">History</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel8')\">Messages</a><br/>";
   $text .="- <a href=\"javascript:x1showPanel('panel7')\">Leadership</a><br/>";
}

$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=createteam\">CreateTeam</a><br/>";
$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=jointeamform\">Join Team</a><br/>";
$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=quitteamform\">Quit Team</a><br/>";
$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=teamlist\">Team List</a><br/>";
$text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=modindex\">Moderator Area</a><br/>";

if(ADMIN){
   $text .= "<a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin\">Admin</a><br/>";
   if($_REQUEST['op']=="admin"){
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=home\">Help</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=teams\">Teams</a><br/>";
      $text .="- <a href=\"".e_Base."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=games\">Games</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=maps\">Maps</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=mapgroups\">Mapgroups</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=events\">Events</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=challenges\">Challenges</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=matches\">Matches</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=disputes\">Disputes</a><br/>";
      $text .="- <a href=\"".e_BASE."e107_plugins/extremetournamentsystem/Kompete.php?op=admin&panel=config\">Config</a><br/>";
   }
}
$ns->tablerender("Nuke Ladder - XTS", $text);
?>