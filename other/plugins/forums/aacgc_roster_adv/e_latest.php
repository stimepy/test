<?php


/*
#######################################
#     e107 website system plguin      #
#     AACGC Advanced Roster           #
#     M@CH!N3                         #
#     http://www.aacgc.com            #
#     admin@aacgc.com                 #
#######################################
*/


//-----------------------------------------------

$apps = $sql -> db_Count("aacgc_roster_adv_apps");
$text .= "
<div style='padding-bottom: 2px;'>
<img src='".e_PLUGIN."aacgc_roster_adv/images/icon_16.png' style='width: 16px; height: 16px; vertical-align: bottom' alt=''>Applications: ".$apps."
</div>";


//-----------------------------------------------


?>