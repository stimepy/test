<?php
/***************************************************************************
*                             Member Application
*                            -------------------
*   begin                : 13 Nov, 2005
*   copyright            : (C) 2005, 2006 Tim Leitz DBF Designs
*   email                : admin@dbfdesigns.net
*
*   Id: memberapplication v 2.1.4 Tim Leitz
*
*   file name           :   admin/index.php
*
***************************************************************************/
/***************************************************************************
*
*   This program is subject to the license agreement in the user manual.
*
***************************************************************************/
require_once("../../class2.php");

if (!getperms("P")){
  die ("Access Denied");
}
include_once("./language/lang-english.php");
include_once("./ma_config.php");
include_once("./includes/database.class.php");
require_once("./includes/e107_database.class.php");
include_once("./includes/MA_functions.php");
include_once("./admin/main.php");
include_once("./admin/add.php");

$dbz = new E107DatabaseTrans();

//global $admin_file, $module_name, $prefix, $user_prefix, $db, $op, $adminmail;

$module_name = "./";
$page_title = $lang['Member Application'];
$MA_Version;




//check if we have forms or if a form is currently selected.
$formck = isset($_REQUEST['formno']) ? trim($_REQUEST['formno']) : '';
$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'MAsetup';
if ((!$formck)){//No forums currently selelcted 
	if ( !($row1 = $dbz->SqlGetRow('*', MA_cfg." ORDER BY formno ASC")) )
	{
		$formno = -1;// No forms available.
	}
	else
	{
		$formno = $row1['formno'];//Grab the first form
	}
}
else
{
	$formno = $formck;
}

require_once(HEADERF);
//if we have a forum and it's not new, get its information
if ($formno >= 0 && $op!='MAconfig'){
  if ( !($row1 = $dbz->SqlGetRow('*', MA_cfg,"formno = ".$formno)) )
  {
    echo "ERROR - I1 - ".MA_UATOCTERROR."! <br>";
    require_once(FOOTERF);
    exit();
  }
}


$script =javascripter('head');

if ($formno == -1){//No form, then op must = MAnewform
	$op = "MAnewform";
}


$myoutput=mainhead($formno);
switch ($op)
{
  case "MAlistpq" ://list the questions of a form.
	$myoutput .=Qlist($formno);
	//include_once($module_name."/listpq.php");
    break;

  case "MAaddpq" ://Parent Question form
	$script.=javascripter($op);
	$myoutput.=quesform($formno);
   // include_once($module_name."/addpq.php");
    break;

  case "MAinsertsq" ://add a sub question
	if(addsubquest($formno)){
		$forma=MAF::X1Clean($_POST['frmat']);
		$par=MAF::X1Clean($_POST['parent']);
		$myoutput .=QuestionDetail($formno, $par);
	}
	else{
		$myoutput .="Failed to add question to the database!";
	}
    break;
    
  case "MAinsertq" ://adds parent question
	if(addques($formno)){
		$forma=MAF::X1Clean($_POST['frmat']);
		$par=$dbz->LastInsertId();
		if($forma=='b' || $forma=='l' || $forma=='r'){//if it was a radio, list, or checkbox list go here
			$myoutput .=QuestionDetail($formno, $par);
		}
		else{
			$script.=javascripter($op);
			$myoutput .=Qlist($formno);
		}
	}
	else{
		$myoutput .="Failed to add question to the database!";
	}
    break;

  case "MAreconfig" ://update main form info
	if(updateform()){
		$myoutput .=formbase($formno);
	}
	else{
		$myoutput .="Failed to update form in the database!";
	}
	break;
	
  case "MAconfig" ://adds forms to.
	if(addform()){
		$myoutput =mainhead($formno);
		$myoutput .=formbase($dbz->LastInsertId());
	}
	else{
		$myoutput .="Failed to add form to database!";
	}
    break;
  
  case "MAsetup" ://form
	$script.=javascripter($op);
	$myoutput .=  formbase($formno);
	break;
	
  case "MAnewform" ://New form
	$script.=javascripter($op);
	$myoutput .= formbase();
    break;

  case "MAeditpq" ://edit a parent question
      $script.=javascripter($op);
      $question = MAF::X1Clean($_GET['ect']);
      $myoutput .= quesform($formno, $question);
    break;

  case "MAupdatepq" ://Update parent questions.
    if(updateques($formno)){
        $myoutput .= Qlist($formno);
    }
    else{
        $myoutput .="Failed to Update Question in database!";
    }
    break;

  case "MAaddeditcq" ://see detail about a subquestion
  	$question_num=MAF::X1Clean($_POST['ect']);
    $myoutput.=QuestionDetail($formno,$question_num);
	//include_once($module_name."/addeditcq.php");
    break;

  case "MAorderpq" :// order Question
      if(orderq($formno, MAF::X1Clean($_POST['ect']))){
          $myoutput .=Qlist($formno);
      }
      else{
          $myoutput .="Failed to update order in the database!";
      }
      break;

  case "MAordercq" ://order sub_Q
      $parent=MAF::X1Clean($_POST['parent']);
      if(orderq($formno, $parent, MAF::X1Clean($_POST['ect']))){
          $myoutput.=QuestionDetail($formno,$parent);
      }
      else{
          $myoutput .="Failed to update order in the database!";
      }
    break;

  case "MAupdatecq" ://update question
    if(updatesubquest($formno)){
        $myoutput.=QuestionDetail($formno, MAF::X1Clean($_POST['parent']));
    }
    else{
        $myoutput.="Failed to Update Question in database!";
    }
    break;

  case "MAdeleteq" ://remove question
    if(delq($formno, MAF::X1Clean($_POST['ect']))){
        $myoutput .=Qlist($formno);
    }
    else{
        $myoutput.="Failed to Remove Question from database!";
    }
    break;

  case "error" :
    echo "<br><br><h1>";
    echo "ERROR - I4 - ".MA_AUEHOERROR."! <br>";
    echo "</h1><br><br>";
    break;
}

$myoutput .="</td>
		</tr>
	".MAF::createtable('close')."
	</td>
</tr>
".MAF::createtable('close');

echo $script;
echo $myoutput;
require_once(FOOTERF);


  
 function mainhead($formno){
	 return MAF::createtable('open')."
	<tr>
		<td>
			<title>".MA_TITLE."</title> <br />
			<center><H3>Member Application V ".$MA_Version." ".MA_ADMINPANEL."</H3></center>
		</td>
	</tr>
	<tr>
		<td>
			".MaHeader($formno)."
		</td>
	</tr>
	<tr>
		<td>
		".MAF::createtable('open', "align=\"center\" width=\"100%\"")."
			<tr>
				<td>
				";
	}

?>