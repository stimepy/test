<?php
/***************************************************************************
*                             Member Application
*                            -------------------
*   begin                : 13 Nov, 2005
*   copyright            : (C) 2005, 2006 Tim Leitz DBF Designs  2011 Kris Sherrerd
*   email                : stimepy@aodhome.com
*
*   Id: memberapplication v 2.1.5 Kris Sherrerd
*
*
***************************************************************************/
/***************************************************************************
*
*   This program is subject to the license agreement 
***************************************************************************/
if(!getperms("P")){
	exit('Your Not allowed here!');
}


function MaHeader($formno){
	global $dbz;
	$option="<option value=\"-1\">".MA_CNFTXT;
	if($formno>=0){//If there are forms then we should be able to select them.
		if (($resultform = $dbz->SqlGetAll('*', MA_cfg)) )
		{
			foreach ($resultform as $rowform) {
				if ($rowform['formno']==$formno)
				{
					$option .="<OPTION selected VALUE=\"".$rowform['formno']."\">".$rowform['formtitle'];
				}
				else
				{
					$option .="<option value=\"".$rowform['formno']."\">".$rowform['formtitle'];
				}
			}  //end foreach
		}
		else{
			//error;
		}
	}
	else{//No forms.
	$option= "<OPTION selected VALUE=\"-1\">".MA_NFOFTXT."
		  <option value=\"-1\">".MA_CFFTXT."";
	}

	//create form here for this menu.  include return to site admin.
	
	$output =MAF::createtable('open', 'class=\"centered_top\"')."
	 <div align=\"center\">
	<form id=\"mainnav\" name=\"mainnav\" method=POST action='".X1_adminpostfile.X1_linkactionoperator."MAsetup'>
	  <tr>
		<td align='center'>
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAsetup&formno=".$formno."' ><img src='./images/formsetup.png' border='0'></a><br />
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAsetup&formno=".$formno."'>".MA_FORMSETUP."</a> &nbsp
		</td>

		<td align='center'>
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAapplist&formno=".$formno."'><img src='./images/applist.png' border='0'></a><br />
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAapplist&formno=".$formno."'>".MA_APPLIST."</a>&nbsp
		</td>

		<td align='center'>
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAlistpq&formno=".$formno."'><img src='./images/qlist.png' border='0'></a><br />
			<a href='".X1_adminpostfile.X1_linkactionoperator."MAlistpq&formno=".$formno."'>".MA_QUESTIONLIST."</a>&nbsp
		</td>

		<td align='center'>
			<img src='./images/chgform.png' border='0'><br />
			<SELECT ID=\"formno\" name=\"formno\" onchange='updFormNo();'>
			".$option.
			" </SELECT>
		</td>
		<td align='center'>
			<a href=\"../".X1_publicpostfile."?appno=".$formno."\" target=\"_blank\"><img src ='./images/viewform.png' border='0'></a><br />
			<a href=\"../".X1_publicpostfile."?appno=".$formno."\" target=\"_blank\">".MA_VIEWFORM."</a>&nbsp
		</td>

		<td align='center'>
			<a href=\"".X1_adminpostfile."\"><img src='./images/admin.png' border='0'></a><br />
			<a href=\"".X1_admin_page."\">".MA_MAINADMINIStrATION."</a>
		</td>
	</tr>
	
	  
	</form>
	</div>
".MAF::createtable('close')."
	<br />
";

	return $output;
	//".MAF::createtable('close')."
}


function formbase($formno=0){
	global $dbz;

	if($formno!=0){
		if(!$appinfo=$dbz->SqlGetRow('*',MA_cfg." where formno=".$formno)){
			die('Error, no form info!');
		}
		$action='MAreconfig';
	}
	else{
		//Check to see if there are forms, and if so get the last overall form number, otherwise, start at one.
		if( !($lfrow = $dbz->SqlGetRow('MAX(formno) AS formno ', MA_cfg)) )
		{
			$formno=1;  
		}
		else{
			$formno=($lfrow['formno']) + 1;
		}
		$action='MAconfig';
	}
	
	$output ="<tbody onload='onloadf();'>
	<form name=\"frmAppSetup\" METHOD=POST action='".X1_adminpostfile.X1_linkactionoperator.$action."'>
		<input type=hidden name=\"formno\" value=\"".$formno."\">
		".MAF::createtable('open', 'align="center"')."
			<tr><!--c1-->
				<td colspan=\"2\" align=\"left\">
					<h4>Form Title</h4> 
					<input name=\"formtitle\"  VALUE=\"".(isset($appinfo)?$appinfo['formtitle']:MA_ETHTXT)."\" SIZE=\"70\" >
				</td>
			</tr>
			<tr>
				<td align=\"top\">
					".MAF::createtable('open','align="left"')."
						<tr>
							<td>
								<br /><h4>Application Text</h4>
								<i>".MA_APPTXTHINT.".</i><br />
							</td>
						</tr>
						<tr>
							<td align=\"left\">
								<textarea name=\"edcfg\" cols=80 rows=10>".(isset($appinfo)?$appinfo['apptxt']:MA_EYATAHHHINT."<br />")."</TEXTAREA>
								<input type=hidden name=\"amode\" value=\"tedit\"><br /><hr>
							</td>
						</tr>
		<td>
							".MAF::createtable('open','align=\"left\"')."
								<tr>
									<td>
										<b>Active?</b>
									</td>
									<td>
										".((isset($appinfo) && $appinfo['active']==1)?'<input type=checkbox name="active" checked>':'<input type=checkbox name="active">')."
									</td>
								</tr>
								<tr>
									<td>
										<b>".MA_UHMTXT."</b>
									</td>
									<td align=\"left\">";
					
										$output .=((isset($appinfo) && $appinfo['emhtml']==1)?"<input type=checkbox name=\"emhtml\" CHECKED>":"<input type=checkbox name=\"emhtml\">");
									$output .="</td>
								</tr>
								<tr>
									<td>
									  <b>".MA_SNDADMINEMAIL."</b>
									</td>
									<td align=\"left\">";
	if (isset($appinfo) && $appinfo['email_admin'])
	{
	   $output .=" <input type=checkbox name=\"emailadmin\" CHECKED onClick='updEmailAdmin();'>
					<input name=\"admad\" ID=\"admad\" VALUE=\"".$appinfo['admaddr']."\" SIZE=\"20\" style=\"display:none\">";
	}
	else
	{
		 $output .="   <input type=checkbox name=\"emailadmin\" onClick='updEmailAdmin();'>
						<input name=\"admad\" ID=\"admad\" VALUE=\"you@somewhere.com\" SIZE=\"20\" style=\"display:none\">";
	}
									 $output .=" 
									 </td>
								</tr>
								<tr valign=\"top\">
									<td>
									  <b>".MA_SNDGRPNOTIFYEMAIL."</b>
									</td>
									<td align=\"left\">";
	if (isset($appinfo) && $appinfo['mailgroup'])
	{
			$output .="<input type=checkbox name=\"mailgroup\" CHECKED onClick='updMailGroup();'>";
	}
	else
	{
			$output .="<input type=checkbox name=\"mailgroup\" onClick='updMailGroup();'>";
	}
										$output .="<LABEL for=\"watchtopic\" ID=\"wtlabel\" style=\"display:none\">".MA_TOPICWATCH."</LABEL>";
	if (isset($appinfo) && $appinfo['topicwatch'])
	{
		$output .="<input type=checkbox name=\"watchtopic\" ID=\"watchtopic\" CHECKED style=\"display:none\" value=\"Topic Watch\">";
	}
	else
	{
		$output .="<input type=checkbox name=\"watchtopic\" ID=\"watchtopic\" style=\"display:none\" value=\"Topic Watch\">";
	}
									$output .="</td>
								</tr>
								<tr valign=\"top\">
									<td>
									  <b>".MA_DETAILON."</b>
									</td>
									<td align=\"left\">";

	if (isset($appinfo) && $appinfo['emdetail'])
	{
		$output .="<input type=checkbox name=\"detail\" CHECKED>";
	}
	else
	{
		$output .="<input type=checkbox name=\"detail\">";
	}

									$output .="</td>
								</tr>
								<tr valign=\"top\">
									<td>
									  <B>".MA_SNDUSRNOTIFYEMAIL."</B>
									</td>
									<td align=\"left\">";
	if (isset($appinfo) && $appinfo['emuser'])
	{
		$output .="<input type=checkbox name=\"emuser\" CHECKED>";
	}
	else
	{
		$output .="<input type=checkbox name=\"emuser\">";
	}
									$output .=" </td>
								</tr>
								<tr valign=\"top\">
									<td>
									  <b>".MA_FORUMPOST."</b>
									</td>
									<td align=\"left\">";

	if (isset($appinfo) && $appinfo['fpdetail'])
	{
		   $output .=" <input type=checkbox name=\"grpset\" CHECKED>";
	}
	else
	{
			$output .="<input type=checkbox name=\"grpset\">";
	}

									$output .=" </td>
								</tr>
								<tr>
									<td>
									  <b>".MA_REVIEWFORUM."</b>
									</td>
									<td>
										<select name=\"forumno\">";

	if( !($fmresult = $dbz->SqlGetAll('*', MA_bbforums." where forum_type NOT IN(0,3)")) )
	{
		echo "<br />ERROR - 17A3 ".MA_UATOFTERR."!";
		exit();
	}
	if(isset($appinfo)) {
		foreach ($fmresult as $forumlist)
		{
			if ($appinfo['forum_id'] == $forumlist['forum_id'])
			{
				$output .="<option selected VALUE=\"".$forumlist['forum_id']."\">".$forumlist['forum_name'];
			}
			else
			{
				$output .="<option value=\"".$forumlist['forum_id']."\">".$forumlist['forum_name'];
			}
		}
	}
	else{
		foreach ($fmresult as $forumlist)
		{
			$output .="<option value=\"".$forumlist['forum_id']."\">".$forumlist['forum_name'];
		}
	}
										$output .=" </SELECT>
									</td>
								</tr>
 							    <tr>
									<td>
									  <b>".MA_REVIEWGROUP."a</b>
									</td>
									<td>
									<select name=\"revgroupno\">";

	if( !($grresult = $dbz->SqlGetAll('*', MA_bbgroups, " group_name not in('NEWLY_REGISTERED', 'BOTS', 'REGISTERED_COPPA', 'REGISTERED', 'GUESTS')")) )
	{
		 echo " ERROR - 17A4 - ".MA_UATOGTERR."!";
		exit();
	}
	if(isset($appinfo)){
		foreach ($grresult as $grouplist)
		{
			if ($appinfo['group_id'] == $grouplist['group_id'])
			{
				$output .=" <option selected VALUE=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
			}
			else
			{
				$output .=" <option value=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
			}
		}
	}
	else{
		foreach ($grresult as $grouplist)
		{
			$output .=" <option value=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
		}
	}
										$output .=" </select>
									</td>
								</tr>
								<tr valign=\"top\">
									<td>
									  <b>".MA_AUTOGROUP."</b>
									</td>
									<td align=\"left\">
									";
	if (isset($appinfo) && $appinfo['auto_group'])
	{
		$output .="<input type=checkbox name=\"autogroup\" CHECKED onClick='updAutoGroup();'>";
	}
	else
	{
		$output .="<input type=checkbox name=\"autogroup\" onClick='updAutoGroup();'>";
	}
										$output .="<select name=\"accgroupno\" ID=\"accgroupno\" style=\"display:none\">";

	if(isset($appinfo)){
		foreach ($grresult as $grouplist)
		{
			if ($appinfo['group_add'] == $grouplist['group_id'])
			{
				$output .=" <OPTION selected VALUE=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
			}
			else
			{
				$output .="<option value=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
			}
		}
	}
	else{
		foreach ($grresult as $grouplist){
			$output .="<option value=\"".$grouplist['group_id']."\">".$grouplist['group_name'];
		}
	}
										$output .=" </select>
									</td>
								</tr>
								<tr valign=\"top\">
									<td>
										<b>".MA_LIMITAPPCOUNT."</b>
									</td>
									<td align=\"left\" rowspan=\"2\">";

	if (isset($appinfo) && $appinfo['appslimit'])
	{
		$output .="<input type=checkbox name=\"appslimit\" CHECKED onClick='updFormElement1();'>
			<input name=\"appslimitno\" ID=\"appslimitno\" VALUE=\"".$appinfo['appslimitno']."\" SIZE=\"10\" style=\"display:none\">";
	}
	else
	{
		$output .="<input type=checkbox name=\"appslimit\" onClick='updFormElement1();'>
			<input name=\"appslimitno\" ID=\"appslimitno\" VALUE=\"\" SIZE=\"10\" style=\"display:none\">";
	}
										$output .= "
									</td>
								</tr>
								<tr valign=\"top\">
									<td rowspan=\"2\">
									".MA_SETTOZEROSTOP."<br /><br />
									</td>
								</tr>
								<tr valign=\"top\">
									<td >
										<b>".MA_ANONOK."</b>
									</td>
									<td align=\"left\">";
	if (isset($appinfo) && $appinfo['annon'])
	{
		$output .="<input type=checkbox id=\"anonappsok\" NAME=\"anonappsok\" CHECKED>";
	}
	else
	{
		$output .="<input type=checkbox id=\"anonappsok\" NAME=\"anonappsok\">";
	}
									$output .="</td>
								</tr>
								<tr lforms=\"top\">
									<td >
										<b>".MA_SHOWFORMS."</b>
									</td>
									<td align=\"left\">";
		if (isset($appinfo) && $appinfo['formlist'])
		{
			$output .=" <input type=checkbox id=\"listforms\" NAME=\"listforms\" CHECKED>";
		}
		else
		{
			$output .= "<input type=checkbox id=\"listforms\" NAME=\"listforms\">";
		}
									$output .="  </td>
								</tr>
								<tr valign=\"top\">
									<td >
										<b>".MA_VERTICLEALIGN."</b>
									</td>
									<td align=\"left\">";
	if (isset($appinfo) && $appinfo['VertAlign'])
	{
		$output .="<input type=checkbox id=\"vertalign\" NAME=\"vertalign\" CHECKED>";
	}
	else
	{
		$output .="<input type=checkbox id=\"vertalign\" NAME=\"vertalign\">";
	}
									$output.= "</td>
								</tr>
							".MAF::createtable('close')."<!--t3-->
				</td>

			<tr>
				<td>
					<hr><h4>".MA_TYTXT."</h4>
					<i>".MA_TYTXTHINT.".</i><br />
				</td>
			</tr>
			<tr>
				<td align=\"left\">
					<textarea name=\"edtytxt\" cols=80 rows=10>".(isset($appinfo)?$appinfo['tytxt']:MA_EYTYTXTHERE."<br />")."</TEXTAREA>
				</td>
			<tr>
				<td>
					<br /><hr><h4>".MA_NOAPPTXT."</h4>
					<i>".MA_NOAPPTXTHINT.".</i><br />
				</td>
			</tr>
			<tr>
				<td align=\"left\">
					<textarea name=\"noapptxt\" cols=80 rows=10>".(isset($appinfo)?$appinfo['noapptxt']:MA_SAANBAATTTXT).".</TEXTAREA><br />
				</td>
			</tr>
				</table>
		</td>
	  <tr>
		<td>
		  <br /><hr><H4>".MA_APPAPROVTXT."</H4>
		  <i>".MA_APPAPROVTXTHINT.".</i><br />
		</td>
	  </tr>
	  <tr>
		<td align=\"left\">
		  <textarea name=\"apprtxt\" cols=80 rows=10>".((isset($appinfo))?$appinfo['approvtxt']:'Approval Text')."</TEXTAREA><br />
		</td>
	  </tr>
	  <tr>
		<td>
		  <br /><hr><H4>".MA_APPDENYTXT."</H4>
		  <i>".MA_APPDENYTXTHINT.".</i><br />
		</td>
	  </tr>
	  <tr>
		<td align=\"left\">
		  <textarea name=\"denytxt\" cols=80 rows=10>".((isset($appinfo))?$appinfo['denytxt']:'You have been Denied!')."</TEXTAREA><br /><hr>
		</td>
	  </tr>
	  </tr>
	  <tr>
		<td align=\"center\" colspan=\"2\">
		  <input type=SUBMIT VALUE=\"Save Changes\">
		</td>
	  </tr>
		</form>
		</form>
	</table>
	</tbody>";
	return $output;
}

/**
* @name javascripter
* @params: N/A
* @details: returns the javascript used in each area.
* @returns: String
*/
function javascripter($op){
	switch($op){
		case "head":
			return "<script type=\"text/javascript\" language=\"javascript\">
			function updFormNo(){
			  document.mainnav.submit();
			}
			</script>";
			break ;
			
		case "MAapplist":
			include_once($module_name."/applist.php");
			break;

		case "MAappstatus" :
		include_once($module_name."/appstatus.php");
		break;
		
		case "MAinsertq":
        case "MAeditpq"://edit parent Q form
		case "MAaddpq" ://new parent Q form
		return "<script language=\"javascript\" type=\"\">
			function updFormElement1(){
			  ElemValue = document.frmQedit.frmat.value;
			  if (ElemValue == \"v\"){
				document.getElementById('deftxtl').style.display = \"none
				document.getElementById('regex').style.display = \"
				document.getElementById('regexl').style.display = \"
				document.getElementById('rqrd').style.display = \"
			  } else {
				if (ElemValue == \"t\"){
				  document.getElementById('regexl').style.display = \"none
				  document.getElementById('regex').style.display = \"
				  document.getElementById('deftxtl').style.display = \"
				  document.getElementById('rqrd').style.display = \"
				} else {
				  document.getElementById('regex').style.display = \"none
				  document.getElementById('regexl').style.display = \"none
				  document.getElementById('deftxtl').style.display = \"none
				  document.getElementById('rqrd').style.display = \"
				  if ((ElemValue == \"L\") || (ElemValue == \"b\")){
					document.getElementById('rqrd').style.display = \"none
					document.getElementById('rqrd').checked = false;
				  } else {
					document.getElementById('rqrd').style.display = \"
				  }
				}
			  }
			}
			</script>";
			break;

		case "MAviewapp" :
		include_once($module_name."/viewapp.php");
		break;

		case "MAconfig"://add forms to.
			//include_once($module_name."/maconfig.php");
			break;
		case "MAsetup"://old form.
		case "MAnewform"://New form
			return "<script language=\"javascript\" type=\"\">
			function onloadf(){
				updFormElement1();
				updEmailAdmin();
				updMailGroup();
				updAutoGroup();
			}
			function updFormElement1(){
				if (document.frmAppSetup.appslimit.checked == true){
					document.getElementById('appslimitno').style.display = \"\";
				} else {
					document.getElementById('appslimitno').style.display = \"none\";
				}
			}
			function updEmailAdmin(){
				if (document.frmAppSetup.emailadmin.checked == true){
					document.getElementById('admad').style.display = \"\";
				} else {
					document.getElementById('admad').style.display = \"none\";
				}
			}

			function updAutoGroup(){
				if (document.frmAppSetup.autogroup.checked == true){
					document.getElementById('accgroupno').style.display = \"\";
				} else {
					document.getElementById('accgroupno').style.display = \"none\";
				}
			}

			function updMailGroup(){
			if (document.frmAppSetup.mailgroup.checked == true){
				document.getElementById('wtlabel').style.display = \"\";
				document.getElementById('watchtopic').style.display = \"\";
				} else {
				document.getElementById('wtlabel').style.display = \"none\";
				document.getElementById('watchtopic').style.display = \"none\";
				}
			}


			</script>";
			break;
	}
}




function Qlist($formno){
	global $dbz;
	
	$output = "<tbody>
	".MAF::createtable('open', 'border="1" align="center"')."
	  <tr>
		<td align=\"center\">
		  ".MA_QUESTION."
		</td>
		<td align=\"center\">
		  ".MA_ORDER."
		</td>
		<td align=\"center\">
		  ".MA_INUSE."
		</td>
		<td align=\"center\">
		  ".MA_REQUIRED."
		</td>
		<td align=\"center\">
		  ".MA_FORMAT."
		</td>
		<td align=\"center\">
		  ".MA_EDIT."
		</td>
	  </tr>";
	$r=0;
	if ( $ques = $dbz->SqlGetAll('*', MA_mapp." where isdel <> 1 AND formno = ".$formno." and parent = 0 ORDER BY fldord"))
	{
        $total_q=count($ques);
		foreach($ques as $row)
		{
			switch ($row['format'])
			{
					case "L" :
					$frmtword = MA_LABEL;
						break;
					case "t" :
					$frmtword = MA_ENTRY;
						break;
					case "v" :
					$frmtword = MA_VALIDENTRY;
						break;
					case "T" :
					$frmtword = MA_TEXTAREA;
						break;
					case "p" :
					$frmtword = MA_PSWORD;
						break;
					case "c" :
					$frmtword = MA_CHECKBOX;
						break;
					case "b" :
					$frmtword = MA_CKBXLIST;
						break;

					case "l" :
					$frmtword = MA_DDLIST;
					break;

					case "r" :
					$frmtword = MA_RADIOBUTTONS;
					break;
			}
			
			if($row['parent']==0)
			{
				$r = $row['fldord'];
				$name = $row['fldname'];
				$crow = $row['fldnum'];
				$output .="<tr>
				<td>";

				if ($name)
				{
					$output .=$name;
				}
				else
				{
					$output .="&nbsp";
				}
				$output .=" </td>
				<td align=\"center\">
					".MAF::createtable('open')."
						<tr>
							<td>
								<form method=post action='".X1_adminpostfile.X1_linkactionoperator."MAorderpq' >
									<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
									<input type=\"hidden\" name=\"cord\" value=\"".$r."\">
									<input type=\"hidden\" name=\"direction\" value=\"1\">
									<input type=\"image\" src=\"./images/mauparrow.gif\" alt=\"Up\">
								</form>
							</td>
							<td>
								<form METHOD=POST action='".X1_adminpostfile.X1_linkactionoperator."MAorderpq' >
									<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
									<input type=\"hidden\" name=\"cord\" value=\"".$r."\">
									<input type=\"hidden\" name=\"direction\" value=\"2\">
									<input type=\"hidden\" name=\"maxd\" value=\"{$total_q}\">
									<input type=image src=\"./images/maarrowdn.gif\" alt=\"Down\">
								</form>
							</td>
						</tr>
					".MAF::createtable('close')."
				</td>
				<td align=\"center\">
					".(($row['inuse']==1)?'<input type=checkbox name="mllist" checked disabled>':'<input type=CHECKBOX NAME=\"mllist\" disabled>')."
				</td>
				<td align=\"center\">
					".(($row['requrd']==1)?'<input type=checkbox name="mllist" checked disabled>':'<input type=CHECKBOX NAME=\"mllist\" disabled>')."
				</td>
				<td align=\"center\">";

					$output .=$frmtword."
				</td>
				<td align=\"left\" width=\"48\">
					".MAF::createtable('open')."
				<tr>
				<td align=\"center\" width=\"16\">
					<form METHOD=\"POST\" action=\"".X1_adminpostfile.X1_linkactionoperator."MAeditpq&formno=".$formno."&ect=".$crow."\" >
						<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
						<input type=\"hidden\" name=\"amode\" value=\"edit\">
						<input type=\"hidden\" name=\"ect\" value=\"".$crow."\">
						<input type=image src=\"./images/maedit.gif\" alt=\"Edit\" align=\"absmiddle\" name=\"enam".$crow."\">
					</form>
				</td>
				<td align=\"center\" width=\"16\">
						<form METHOD=POST action='".X1_adminpostfile.X1_linkactionoperator."MAdeleteq' >
						<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
						<input type=\"hidden\" name=\"ect\" value=\"".$crow."\">
						<input type=\"hidden\" name=\"fmt\" value=\"".$row['format']."\">
						<input type=\"hidden\" name=\"cord\" value=\"{$r}\">
						<input type=image src=\"./images/madelete.gif\" alt=\"Edit\" align=\"absmiddle\" name=\"enam".$crow."\"> <!--//HEIGHT=20 WIDTH=16 -->
					</form>";

				if (($row['format'] == "l") || ($row['format'] == "r")|| ($row['format'] == "b"))
				{
					$output .=" </td>
					<td align=\"center\" width=\"16\">
						<form METHOD=POST action='".X1_adminpostfile.X1_linkactionoperator."MAaddeditcq' >
						<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
						<input type=\"hidden\" name=\"lsord\" value=\"".$row['subfldord']."\">
						<input type=\"hidden\" name=\"ect\" value=\"".$crow."\">
						<input type=image src=\"./images/malist.gif\" alt=\"Options\" align=\"absmiddle\" name=\"enam".$crow."\"> 
						</form>";
				}

				$output .=" </td>
				</tr>
				".MAF::createtable('close')."
				</td>
				</tr>";
			} 

			$i++;
		} 


	}

	$output .="</table>
	<table align=\"center\" width=\"100%\">
		  <tr>
			<td align=\"center\">
			  <form METHOD=POST action='".X1_adminpostfile.X1_linkactionoperator."MAaddpq' >
				  <INPUT TYPE=HIDDEN NAME=\"formno\" value=\"".$formno."\">
				  <input type=\"hidden\" name=\"ect\" value=\"".(($r!=0)?$r+1:1)."\">
				  <INPUT TYPE=SUBMIT VALUE=\"".MA_ADDQ."\"></TD><TD>
			  </form>  
			</td>
		  </tr>
	</table>
	</tbody>";

	return $output;
}

/**
* @name quesform
* @params int $formno, int $ques=0
* @return string
*
* This is the basic question form
*/
function quesform($formno, $ques=0){
    if($ques==0){
        $rn= isset($_POST['ect']) ? trim($_POST['ect']) : '';

        $action="MAinsertq";
        $select ="<option value=\"L\">".MA_LABEL."
                <option value=\"t\">".MA_ENTRY."
				<option value=\"v\">".MA_VALIDENTRY."
				<option value=\"T\">".MA_textarea."
				<option value=\"p\">".MA_PSWORD."
				<option value=\"c\">".MA_CHECKBOX."
				<option value=\"b\">".MA_CKBXLIST."
				<option value=\"l\">".MA_DDLIST."
				<option value=\"r\">".MA_RADIOBUTTONS;
        $old ='';
        $val=MA_ADDQ;
    }
    else{
        global $dbz;
        if( !($row = $dbz->SqlGetRow('*', MA_mapp, " formno = ".$formno." AND fldnum = ".$ques)) )
        {
            echo "ERROR - 12A1 - ".MA_UTOQTERROR."! <br />";
            exit();
        }
        $action="MAupdatepq";
        $format=$row['format'];
        $select ="<option value=\"L\"".(($format=='L')? ' selected':'').">".MA_LABEL."
                <option value=\"t\"".(($format=='t')? ' selected':'').">".MA_ENTRY."
				<option value=\"v\"".(($format=='v')? ' selected':'').">".MA_VALIDENTRY."
				<option value=\"T\"".(($format=='T')? ' selected':'').">".MA_textarea."
				<option value=\"p\"".(($format=='p')? ' selected':'').">".MA_PSWORD."
				<option value=\"c\"".(($format=='c')? ' selected':'').">".MA_CHECKBOX."
				<option value=\"b\"".(($format=='b')? ' selected':'').">".MA_CKBXLIST."
				<option value=\"l\"".(($format=='l')? ' selected':'').">".MA_DDLIST."
				<option value=\"r\"".(($format=='r')? ' selected':'').">".MA_RADIOBUTTONS;
        $old = "<input type=\"hidden\" name=\"oldfmt\" value=\"".$format."\">";
        $val=MA_UPDATE;
    }
    print_r($row);
	$output ="<tbody onload=\"updFormElement1();\">
	<form name=\"frmQedit\" method=\"post\" action=\"".X1_adminpostfile.X1_linkactionoperator.$action."\" >
	<input type=hidden name=\"formno\" value=\"". $formno ."\">
	".((isset($row)) ? '<input type=hidden name="ect" value="'.$ques.'">' : '')."
	<table align=\"center\">
	  <tr>
		<td align=\"center\" colspan=\"2\">
		  ".MA_QUESTION." # ".((isset($row))? $ques : $rn)."
		  <br /><br />
		  <input type=hidden name=\"ford\" value=\"".((isset($row)) ? $row['fldord'] : $rn)."\">
		</td>
	  </tr>
	  <tr>
		<td width=\"100\">
		  ".MA_QUESTION."
		</td>
		<td>
		  <textarea name=\"edq\" cols=40 rows=6>".((isset($row))? $row['fldname'] : '' )."</textarea>
		</td>
	  </tr>
	  <tr>
		<td width=\"100\">
		  ".MA_INUSE."
		</td>
		<td>
		  <input type=checkbox name=\"inuse\" value=\"on\" ".( (isset($row) && $row['inuse']==1 )?'checked':'').">
		</td>
	  </tr>
	  <tr>
		<td width=\"100\">
		  ".MA_REQUIRED."
		</td>
		<td>
		  <input type=checkbox name=\"rqrd\" id=\"rqrd\"".( (isset($row) && $row['requrd'] )?'checked':'').">
		</td>
	  </tr>
	  <tr>
		<td width=\"100\">
		  ".MA_FORMAT."
		</td>
		<td>
		  <select name=\"frmat\" onchange=\"updFormElement1();\">
		    ".$select."	       
		  </select>
		  ".$old."  
		</td>
	  </tr>
	  <tr>
		<td width=\"100\">
		   <div name=\"regexl\" id=\"regexl\" style=\"display:none\">".MA_REGEXPRES."</div>
		   <div name=\"deftxtl\" id=\"deftxtl\" style=\"display:none\">".MA_DEFAULTTXT."</div>
		   <input type=\"hidden\" name=\"lsord\" value=\"0\">
		</td>
		<td name=\"regex\" id=\"regex\" style=\"display:none\">
		  <input type=\"text\" name=\"regextext\" id=\"regextext\" value=\"".((isset($row))? $row['rgextxt'] : '')."\" size=\"40\">
		</td>
		<td></td>
		</tr>
	  <tr>
		<td align=\"center\" colspan=\"2\">
		  <br /><br />
		  <input type=\"submit\" value=\"".$val."\">
		</td>
	  </tr>
	</table>
	</form> ";
	return $output;
}

function QuestionDetail($formno, $question){
	global $dbz;
	$main_q=$dbz->SqlGetRow('*', MA_mapp." WHERE formno = $formno AND fldnum = ".$question);
    $fldname=$main_q['fldname'];
	$lastorder=MAF::X1Clean($_POST['lsord']);

	$output = "<center>".$fldname."<br /><br /></center>

	".MAF::createtable('open', 'border="1" align="center"')."
		<tr>
			<td align=\"center\">
				".MA_CHOICE."
			</td>
			<td align=\"center\">
				".MA_INUSE."
			</td>
			<td align=\"center\">
				".MA_SAVE."
			</td>
			<td align=\"center\">
				".MA_ORDER."
			</td>
		</tr>";
    $total_q=0;
	if ( $sqres = $dbz->SqlGetAll('*', MA_mapp, " formno = ".$formno." AND parent = ".$question." ORDER BY subfldord") )
	{
        $total_q=count($sqres);
		foreach($sqres as $sqrow){
			$output .= "<tr>
				<td align=\"left\">
					<form method=\"post\" action=\"".X1_adminpostfile.X1_linkactionoperator."MAupdatecq&formno={$formno}\" >
						<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
						<input type=\"hidden\" name=\"ect\" value=\"".$sqrow['fldnum']."\">
						<input type=\"hidden\" name=\"parent\" value=\"".$main_q['fldnum']."\">

						<input type=\"text\" name=\"sub_q\" value=\"".$sqrow['fldname']."\">

				</td>
				<td align=\"center\">";
			if ($sqrow['inuse'])
			{
				$output .="      <input type=\"checkbox\" name=\"inuse\" value=\"on\" checked>";
			}
			else
			{
				$output .="      <input type=\"checkbox\" name=\"inuse\" value=\"on\">";
			}
				//save	
				$output .="    </td>
				<td align=\"center\">
					<input type=\"image\" src=\"./images/madisk.gif\" alt=\"".MA_EDIT."\" align=\"absmiddle\" name=\"enam\">
				</form><!-- End of edit for sub_q -->
				</td>
				<td align=\"center\"><!-- Begin ordering -->
					".MAF::createtable('open')."
						<tr>
							<td align=\"center\">
								<form method=\"post\" action=\"".X1_adminpostfile.X1_linkactionoperator."MAordercq\" >
									<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
									<input type=\"hidden\" name=\"parent\" value=\"".$main_q['fldnum']."\">
									<input type=\"hidden\" name=\"ect\" value=\"".$sqrow['fldnum']."\">
									<input type=\"hidden\" name=\"cord\" value=\"".$sqrow['subfldord']."\">
									<input type=\"hidden\" name=\"direction\" value=\"1\">
									<input type=\"image\" src=\"./images/mauparrow.gif\" ALT=\"".MA_UP."\" name=\"enam1\">
								</form>
							</td>
							<td align=\"center\">
								<form method=\"post\" action=\"".X1_adminpostfile.X1_linkactionoperator."MAordercq\" >
									<input type=\"hidden\" name=\"formno\" value=\"".$formno."\">
									<input type=\"hidden\" name=\"parent\" value=\"".$main_q['fldnum']."\">
									<input type=\"hidden\" name=\"ect\" value=\"".$sqrow['fldnum']."\">
									<input type=\"hidden\" name=\"cord\" value=\"".$sqrow['subfldord']."\">
									<input type=\"hidden\" name=\"maxd\" value=\"{$total_q}\">
									<input type=\"hidden\" name=\"direction\" value=\"2\">
									<input type=IMAGE SRC=\"./images/maarrowdn.gif\" ALT=\"".MA_DOWN."\" name=\"enam2\">
								</form>
							</td>
						</tr>
					".MAF::createtable('close')."
				</td>
			</tr>";

		}//foreach
	}//if
                    //New subquestion
	$output.= "  <form method='post' action='".X1_adminpostfile.X1_linkactionoperator."MAinsertsq' >
		<input type=hidden name=\"formno\" value=\"".$formno."\">
		<tr>
			<td align=\"center\">
				<INPUT name=\"edq\" value=\"\">
			</td>
			<td align=\"center\">
				<input type=\"hidden\" name=\"parent\" value=\"".$question."\">
				<input type=\"hidden\" name=\"ford\" value=\"".($main_q['fldord'])."\">
				<input type=\"hidden\" name=\"lsord\" value=\"".(($total_q)+1)."\">
				<input type=\"hidden\" name=\"frmat\" value=\"".$main_q['format']."\">
				<input type=\"checkbox\" name=\"inuse\">
			</td>
			<td align=\"center\">
				<input type=image src=\"./images/madisk.gif\" alt=\"".MA_ADD."\" align=\"absmiddle\" name=\"Add\">
			</td>
			<td align=\"center\">
				".MA_NEW."
			</td>
		</tr>
	</form>	
	".MAF::createtable('close')."
	".MAF::createtable('open', 'align="center"')."
		<tr>
			<td>
				<center><I>".MA_VALIDCHILDEDIT." <img src=\"./images/madisk.gif\"> .</I></center>
			</td>
		</tr>
		<tr>
			<td valign=\"top\">
				<form method=\"post\" action='".X1_adminpostfile.X1_linkactionoperator."MAlistpq' >
					<input type=\"hidden\" name=\"formno\" value=\"".$formno."\"><center>
					<input type=\"submit\" value=\"".MA_RETURN."\"></center></td><td>
				</form>
			</td>
		</tr>
	".MAF::createtable('close');

	return  $output;
}
?>