<?php
/*############################
File:event.php
Needs:N/A
returns:$event set as X1LadderMod
What does it do: Sets global $event to X1LadderMod
#############################*/
	X1File::X1LoadFile(X1_corelang.".php",X1_modpath."/".basename(dirname(__FILE__))."/language/");
	X1File::X1LoadFile("X1LadderMod.php", X1_modpath."/".basename(dirname(__FILE__))."/");
	global $gx_event_manager; 
	$gx_event_manager = new X1LadderMod();
?>
