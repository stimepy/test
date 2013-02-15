<?php

/**
*
*
*/
function addform($update=false){
	global $dbz;
	if(USER){
		$clean_post=MAF::X1Clean($_POST, 5, 3);
		
		//Required to be set.
		$table['formno']=$clean_post['formno'];
		$table['formtitle']=$clean_post['formtitle'];
		$table['forum_id']=$clean_post['forumno'];
		$table['group_id']=$clean_post['revgroupno'];

		//The texts!
		$table['apptxt']=(isset($clean_post['edcfg']))?$clean_post['edcfg']:'N/A'; //app text
		$table['tytxt']=(isset($clean_post['edtytxt']))?$clean_post['edtytxt']:''; // thank you text
		$table['noapptxt']=(isset($clean_post['noapptxt']))? $clean_post['noapptxt']:''; //No apps accepted at this time text
		$table['approvtxt']=(isset($clean_post['apprtxt']))? $clean_post['apprtxt']:''; //approved text
		$table['denytxt']=(isset($clean_post['denytxt']))?$clean_post['denytxt']:''; //denied text
		
		
		
		if(isset($clean_post['emailadmin'])){ //if email admin
			$table['email_admin']=1;
			$table['admaddr']=$clean_post['admad'];
		}
		else{
			$table['email_admin']=0;
			$table['admaddr']='';
		}
		
		if(isset($clean_post['autogroup'])){ //if autogroup (does not work atm)
			$table['auto_group']=1;
			$table['group_add']=$clean_post['accgroupno'];
		}
		else{
			$table['auto_group']=0;
			$table['group_add']='';
		}
		if(isset($clean_post['appslimit'])){ // if app limit
			$table['appslimit']=1;
			$table['appslimitno']=$clean_post['appslimitno'];
		}
		else{
			$table['appslimit']=0;
			$table['appslimitno']='';
		}
		
		$table['emdetail']=(isset($clean_post['detail']))?1:0;//details
		$table['emuser']=(isset($clean_post['emuser']))?1:0; //email details to user
		
		$table['emhtml']=(isset($clean_post['emhtml']))?1:0;//email in html?
		$table['mailgroup']=(isset($clean_post['mailgroup']))?1:0;//email the group
		$table['topicwatch']=(isset($clean_post['watchtopic']))?1:0;//allow watch topic?
		 
		$table['active']=(isset($clean_post['active']))?1:0;//form active?

		$table['annon']=(isset($clean_post['anonappsok']))?1:0;//allow annomous apps
		$table['formlist']=(isset($clean_post['listforms']))?1:0;//list in forums
		$table['VertAlign']=(isset($clean_post['vertalign']))?1:0;//vert align.
		
		
		if($update==true){//If we are updating the table
			//set the items to be updated.
			$set="formtitle='{$table['formtitle']}', forum_id='{$table['forum_id']}', group_id='{$table['group_id']}', apptxt='{$table['apptxt']}', tytxt='{$table['tytxt']}', noapptxt='{$table['noapptxt']}', approvtxt='{$table['approvtxt']}', denytxt='{$table['denytxt']}', email_admin='{$table['email_admin']}', admaddr='{$table['admaddr']}', auto_group='{$table['auto_group']}', group_add='{$table['group_add']}', appslimit='{$table['appslimit']}', appslimitno='{$table['appslimitno']}', emdetail='{$table['emdetail']}', emuser='{$table['emuser']}', emhtml='{$table['emhtml']}', mailgroup='{$table['mailgroup']}', topicwatch='{$table['topicwatch']}', active='{$table['active']}', annon='{$table['annon']}', formlist='{$table['formlist']}', VertAlign='{$table['VertAlign']}'";

			$result = $dbz->SqlUpdate(MA_cfg, $set, " where formno={$table['formno']}");
		}
		else{//We are inserting a new table;
			$result = $dbz->SqlInsert(MA_cfg, $table);
		}
		if (!$result)
		{
            //todo here
		  echo "<BR>".mysql_error()."<BR>";
		  echo "ERROR - 15A1 - ".MA_UTUCTERROR."! <br>";
		  exit();
		}

		return true;
	}
	else{
		return false;
	}
}

function updateform(){
	if(addform($update=true)){
		return true;
	}
	return false;
}

function addques($formno, $question=NULL){
	global $dbz;
	if(USER){
	 	$clean_post=MAF::X1Clean($_POST, 5, 3);

        print_r($clean_post);
		$table['formno']=$formno;
		$table['fldord']=$clean_post['ford'];//parent order
        if(($clean_post['frmat'] == 'r'|| $clean_post['frmat'] == 'b'||$clean_post['frmat'] == 'l')){
			$table['subfldord']=(isset($clean_post['lsord'])?$clean_post['lsord']:0);
			$table['parent']=isset($clean_post['parent'])?$clean_post['parent']:0;
		}
		$table['fldname']=$clean_post['edq'];//question
		$table['inuse']=(isset($clean_post['inuse']))?1:0;
		$table['requrd']=(isset($clean_post['rqrd'])?1:0);
		$table['format']=$clean_post['frmat'];
		$table['rgextxt']=(isset($clean_post['regextext'])?$clean_post['regextext']:'');

        if($question==NULL){//if its a new question
            $result = $dbz->SqlInsert(MA_mapp, $table);
            if($result){
                return true;
            }
        }
        else{//if it's an old question
            $result= $dbz->SqlUpdate(MA_mapp, "format='{$table['format']}', fldname='{$table['fldname']}',inuse='{$table['inuse']}', requrd='{$table['requrd']}', rgextxt='{$table['rgextxt']}'", " where fldnum={$question} and formno={$table['formno']}");
            if($result){
                if($clean_post['oldfmt']!=$table['format']){
                    if($clean_post['oldfmt']=='r' || $clean_post['oldfmt']=='b' || $clean_post['oldfmt'] == 'l'){
                        if($dbz->SqlGetRowCount('parent',MA_mapp, "parent={$formno}">=1)){
                            if(!$dbz->SqlDelete(MA_mapp, "parent={$formno}")){
                                echo 'Question updated but childern not removed!';
                            }
                        }
                    }
                }

                return true;
            }
        }
	}
	return false;
		
}

function addsubquest($formno){
	global $dbz;
	if(addques($formno)){
	 	$last=MAF::X1Clean($_POST['lsord']);
	 	$question=MAF::X1Clean($_POST['parent']);
		if($dbz->SqlUpdate(MA_mapp, " subfldord=".$last." where fldnum=".$question)){
			return true;
		}		
	}
	return false;				
}

function updateques($formno){
$question=MAF::X1Clean($_POST['ect']);
    if(addques($formno, $question)){
        return true;
    }
    return false;
}

function updatesubquest($formno){
    global $dbz;
    if(USER){
        $clean_post=MAF::X1Clean($_POST,5,3);
        $table['parent']=$clean_post['parent'];//parent
        $table['fldname']=$clean_post['sub_q'];//field
        $table['inuse']=(isset($clean_post['inuse']))?1:0;

        $result= $dbz->SqlUpdate(MA_mapp, "fldname='{$table['fldname']}', inuse='{$table['inuse']}'", " where fldnum={$clean_post['ect']} and formno={$formno}");

        if($result){
            return true;
        }
    }
    return false;
}


function orderq($formno, $parent, $sub_qn=NULL){
    global $dbz;
    $clean_post=MAF::X1Clean($_POST,5,3);
    if($sub_qn!=NULL){
        $where= " where parent={$clean_post['parent']}";
        $field="subfldord";
    }
    else{
        $where = " where formno={$formno}";
        $field="fldord";
    }
        if($clean_post['direction']==1){//go up by 1
            $new_order=((int)$clean_post['cord'])-1;
            if($new_order<1){
                echo "I'm on top, I can't go any higher!";
                return true;
            }
            $set_statement=" {$field}= case
                when ({$field}={$new_order})
                    then {$field}+1
                when ({$field}={$clean_post['cord']})
                    then {$new_order}
                else {$field}
                end";
        }
        else{//go down by 1
            $new_order=$clean_post['cord']+1;
            if($clean_post['maxd']<$new_order){
                echo "I'm already at the bottom of my world!";
                return true;
            }
            $set_statement=" {$field}= case
                when ({$field}={$new_order})
                    then {$field}-1
                when ({$field}={$clean_post['cord']})
                    then {$new_order}
                else {$field}
                end";
        }
        if($dbz->SqlUpdate(MA_mapp, $set_statement, $where)){
            return true;
        }


    return false;
}

function delq($formno, $question){
    global $dbz;
    //first move anything below $question, order wise, up
 //   $dbz->SqlUpdate(MA_mapp, "fldord=fldord-1", " where formno = {$formno} and fldord>".MAF::X1Clean($_POST['cord']));
    //check format to see if childern are possible.  If so, remove them first!
    $format=MAF::X1Clean($_POST['fmt']);
    if( $format== 'r' || $format== 'b' || $format== 'l' ){
        //verify there are childern to delete.
        if($dbz->SqlGetRowCount('formno', MA_mapp , " parent=$question")>0){
            //delete the childern
            if(!$dbz->SqlDelete(MA_mapp, " parent={$question}")){
                return false;
            }
        }
    }
    //finally remove the question itself
    if($dbz->SqlDelete(MA_mapp, " fldnum={$question}")){
        return true;
    }
    return false;
}

?>