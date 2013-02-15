<?php
/*
*************************************
*        Signup Secure				*
*									*
*        (C)Oyabunstyle.de			*
*        http://oyabunstyle.de		*
*        info@oyabunstyle.de		*
*		 Version 1.0				*
*************************************
edit by Angel  10-29-2012
*/
if (!defined('e107_INIT'))
{
    exit;
}
if (!isset($pref['plug_installed']['signup_secure']))
{
    return ;
}
include_lan(e_PLUGIN."signup_secure/languages/".e_LANGUAGE.".php");
include_once(e_PLUGIN."questionsandanswers.php");

if (e_PAGE=="signup.php")
{
	if (isset($_POST['human']))  //Getting the answer
	{
		if(isset($_POST['newver']){
			$coppa = "<input type=\"hidden\" name=\"coppa\" value={$_POST['coppa']}\" />
				<input type='hidden' name='e-token' value='".e_TOKEN."' />
				<input type='submit' name='newver' value='".SS_BACK."'/>";
		}
		else{
		$coppa="<input type=\"hidden\" name=\"e-token\" value=\"".e_TOKEN."\" />
			<input type=\"submit\" name=\"back\" value=\"".SS_BACK."\"/>"
		}
		
		if (!getAnswer()){
			$SIGNUP_BEGIN = substr ("{SIGNUP_FORM_OPEN}", 18);
			$SIGNUP_BODY = substr ("{SIGNUP_FORM_CLOSE}", 19);
			
			$SIGNUP_BEGIN = "
				<h3 class='center'>
					<br />
					".SS_FAIL."
				</h3>
				<br />
				<center>
					<form action='".e_SELF."?stage1' method='post'>
						{$coppa}
					</form>
				</center>
				<br /><br /><center>".SS_INQ." <a href='http://oyabunstyle.de' target='_blank'>Oyabunstyle.de</a></center>
			";
		}
	}
	else //Posting the question
	{
		$question=question();
		if($question[0]=="math"){
			$question[0]=$question[0]();
			$box=build_answer($question[1], $question[0][1]);
		}
		else{
			$box=build_answer($question[1])
		}
		if(isset($_POST['newver'])){
			$output ="<input type=\"hidden\" name=\"coppa\" value=\"".$_POST['coppa']."\" />
			<input type=\"hidden\" name=\"newver\" value=\"newver\" />";
		}
		else{
			$output ="";
		}
		
		$SIGNUP_BEGIN = substr ("{SIGNUP_FORM_OPEN}", 18);
		$SIGNUP_BODY = substr ("{SIGNUP_FORM_CLOSE}", 19);
		
		$SIGNUP_BEGIN = "
			<h3 class=\"center\">
				<br />
				".SS_REQUEST."
				<br /><br />
			</h3>
			<form action=\"".e_SELF."?stage1\" method=\"post\">
				<table class=\"fborder\" style=\"width:95%;\">
					<tr>
						<td class=\"forumheader3\" style=\"width:50%;\">
							{$question[0]}
						</td>
						".$box"
					</tr>
					<tr>
						<td class='forumheader3 colspan='2'>
							{$output}
							<input type='hidden' name='e-token' value='".e_TOKEN."' />
							<input type='submit' name='human' value='".SS_SEND."'/>
						</td>
					</tr>
				</table>
			</form>
			<br /><br /><center>".SS_INQ." <a href='http://oyabunstyle.de' target='_blank'>Oyabunstyle.de</a></center>
		";
	}
}
?>