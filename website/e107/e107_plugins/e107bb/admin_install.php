<?php
/*
+---------------------------------------------------------------+
|        e107bb 3.1
|        DIPOrg (suporte@diporg.com)
|        http://www.diporg.com
|
|        Plugin for e107 (http://e107.org)
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once("../../class2.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }

define("BBNOMENU",true);

require_once(e_ADMIN.'auth.php');

require_once (e_PLUGIN."e107bb/bbfunctions.php");

if(e_QUERY == "stage4") {

e107bb_install_stage4();


} else {

e107bb_install_stage3();

}


?> 
