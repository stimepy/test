<?php
/***************************************************************************
*                             Member Application
*                            -------------------
*   begin                : 13 Nov, 2005
*   copyright            : (C) 2005, 2006 Tim Leitz DBF Designs
						 : (C) 2011, 2012 Kris Sherrerd (For e107)
*   email                : admin@dbfdesigns.net
*
*   file name           :   mem_app.php
*
***************************************************************************/
/***************************************************************************
*
*   This program is subject to the license agreement in the user manual.
*
***************************************************************************/
require_once("../../class2.php");
include_once("./language/lang-english.php");
include_once("./ma_config.php");
include_once("./member_app_functions.php");
include_once("./includes/database.class.php");
require_once("./includes/e107_database.class.php");
include_once("./includes/MA_functions.php");

//$minimum_posts = 5;
//$posts_message = "You have not posted enough to fill out this application.";

require_once(HEADERF);
//parant needed for more
$module_name = "./";

$dbz = new E107DatabaseTrans();


function X1_userdetails()
{
	if (USER)return array(USERID,USERNAME);
}

$MAuser_info = X1_userdetails();
//print_r($MAuser_info);


$appno = isset($_REQUEST['formno']) ? trim($_REQUEST['formno']) : -1;

if ($appno<0)// if appno was not set
{
	$appno = -1;
	//$msql ="SELECT * FROM `".$prefix."_MA_mapcfg` ORDER BY formno";
	if( !($mresult = $dbz->SqlGetRow('formno', MA_cfg, 'ORDER BY formno', 'order')) )
	{
		echo "<H3>ERROR - 1 - ".MA_UATOCTERROR."</H3><br>";
		exit('No applications could be located');
	}
	else
	{
		$appno = $mresult['formno'];
	}
}


if( !($mrow = $dbz->SqlGetRow('*', MA_cfg, 'formno = '.$appno)) )
{
	echo "<H3>ERROR - 2 - ".MA_UATOCTERROR."</H3><br>";
	exit('No application could be located');
}

switch(isset($_REQUEST['op']) ? trim($_REQUEST['op']) : ''){
case "apply"://user has applied
	if (((USER) ||($mrow['annon']))){
		$output=apply($appno, $mrow, $MAuser_info);
		if(!$dbz->SqlUpdate(MA_cfg, 'set app_cnt=app_cnt+1')){
			echo "<H3>ERROR - 12 - Application count incorrect.</H3><br>";
		}
	}
	break;
case "error":
	
	break;
default:
		$output = see_all_applications();
		if($appno!=-1){
			$output .=filloutapplication($appno);
		}
		$output .=MAF::createtable("close");

	break;

}


echo $output;
require_once(FOOTERF);


?>
