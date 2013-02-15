<?php

/**************************************************************************
 * Copyright (c) 2008 ClanTemplates.com
 * Shell'd; a free template by Asherz at ClanTemplates.com
 * No part of this file may be redistributed without written permission
 * Designed by Asherz <http://www.asherz.com/>
 * Coded by Ross <ross@clantemplates.com>
 * http://www.clantemplates.com/pages/Legal
 
 Translated to PHP by Angelofdoom
 Copyright (c) 2009
 www.aodhome.com
 *************************************************************************/

//Global Variables
require_once("config.php");
require_once("template_functions.php");
require_once("lib/system_sql.php");
require_once("lib/system_functions.php");


$op=$_REQUEST['op'];
//Head and start of the template
	echo "
	<?xml version=\"1.0\" encoding=\"utf-8\"?>
	<!doctype html public \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\"><!--1-->

	<!-- Copyright (c) 2008 ClanTemplates.com
 	* Shell'd; a free template by Asherz at ClanTemplates.com
 	* No part of this file may be redistributed without written permission
 	* Designed by Asherz <http://www.asherz.com/>
 	* Coded by Ross <ross@clantemplates.com>
 	* http://www.clantemplates.com/pages/Legal-->";

	Head();
	
	echo "<body><!--3-->";
	Body();
	echo "</body><!--3-->
	</html><!--1-->";
	
	//end of the page
	
	
	
	function Head(){
	global $title, $style, $csstype, $javatype, $robot, $keyw, $desc;
		 
		echo"<head><!--2-->
			<title>$title</title>
			<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
			<meta name=\"keywords\" content=$keyw />
			<meta name=\"description\" content=$desc />
			<meta name=\"ROBOTS\" content=$robot />
			<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />
			<link rel=$style type=$csstype href=\"./lib/web.css\" />
			<!--[if IE 6]>
			<link rel=$style type=$csstype href=\"./lib/ie6.css\" />
			<![endif]-->
			<script type=$javatype src=\"./lib/roster.js\"></script>
		</head><!--2-->";
	}
	
	function Body(){
	 global $op;
		echo"<div id=\"container\"><!--4-->";
		
		ButtonNavigation();
		
		echo "<!-- Header row -->
		<div id=\"header\"><!--6-->";
		
		WelcomeBox();
		Banner();
			
		echo "</div><!--6-->
		
		<!-- Column Wrapper -->
		<!--clears columns before continuing-->
		<div id=\"column-container\"><!--c-->
			<!-- Left Column -->";
			LeftColumn();
			
		//	Connect();//connects to the database
			echo "<!-- Right Column -->";
			
			switch($op){
			 case "roster":
                                echo "There are no rosters at this moment nor planned in the future, there may be a contact page placed here at some point and time, please check back later!";
                                RightColumnNews();
				//Roster();
				break;
			 case "events":
				echo "Coming soon!";
				RightColumnNews();
				break;
			 case "media":
				echo "Coming soon!";
				RightColumnNews();
				break;
			 default:
				RightColumnNews();					
				break;
			}
			
						
		echo "</div><!--c-->		
		<!-- Footer -->";
		Footer();
		
	echo "</div><!--4-->";


	}
?>