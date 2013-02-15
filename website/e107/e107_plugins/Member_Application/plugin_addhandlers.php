<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2011 Kris Sherrerd
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://ww.aodhome.com
|     $Revision: 11678
|     $Id: plugin_addhandlers.php 
|     $Author: Kris Sherrerd
+----------------------------------------------------------------------------+
*/

if (!getperms("P")){
	$handler="../".e_HANDLER;
	e107_require_once("../".e_ADMIN."auth.php");
	e107_require_once($handler."mail.php");
}
else{
e107_require_once(e_HANDLER."mail.php");
}

?>