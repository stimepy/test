<?php

$override->override_function('tablerender', 'my_tablerender', 1);

function my_tablerender($caption, $text) {
	
	$patterns = array("/forum\/forum_viewtopic\.php\?([0-9]+)\.([0-9]+)\.report/");
	$replacements = array("report/report.php?$1");

	$text = preg_replace($patterns,$replacements,$text);

	$return = array();
	$return["caption"] = $caption;
	$return["text"] = $text;
	
	return $return;

}


?>