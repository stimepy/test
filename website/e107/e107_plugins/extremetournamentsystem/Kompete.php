<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008-2009 (2.6.0+)
##Version 2.6.4
###############################################################
## +-----------------------------------------------------------------------+
## | This file is free software; you can redistribute it and/or modify     |
## | it under the terms of the GNU General Public License as published by  |
## | the Free Software Foundation; either version 3 of the License.        |                           |
## | This file is distributed in the hope that it will be useful           |
## | but WITHOUT ANY WARRANTY; without even the implied warranty of        |
## | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          |
## | GNU General Public License for more details.                          |
## +-----------------------------------------------------------------------+
##Credit to this fine project from the very first version on has to go to Shane Arndrusiak.
##If he had not started this project there never would have been a Nuke Ladder.
##Though he has gone on to other things let it be known we all appreciate what he started.
###############################################################

	require_once("../../class2.php");
	define('X1plugin_include', true);

	# Load X1 Config
	require_once("includes/X1File.class.php");


	require_once(HEADERF);
error_reporting(E_ALL ^ E_STRICT);
define('parent_path',"./");
	X1File::X1LoadFile("nukeladdersystem.php");

   echo ini_get( 'error_reporting' );
	require_once(FOOTERF);
?>