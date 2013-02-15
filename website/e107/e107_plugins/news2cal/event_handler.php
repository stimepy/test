<?php

require_once(e_BASE."class2.php");

function news2cal_newspost($data) {
	
	global $pref;
	
	switch ($pref['news2cal_calendar_plugin']) {
	case 'calendar_menu':
		$url = e_PLUGIN ."news2cal/event.php?ne.". $data['news_id'];
		break;
	case 'agenda':
		$url = e_PLUGIN ."news2cal/agenda.php?". $data['news_id'];
		break;
	}
	
	echo "
	<script type='text/javascript'>
	<!--//
	
	var go = confirm('Would you like to create a calendar event for this newsitem?');
	if (go) {
		location.href='$url';
	}
	
	//-->
	</script>
	";
	
} // End news2cal_newspost()

?>