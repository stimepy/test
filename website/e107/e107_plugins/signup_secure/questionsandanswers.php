<?php


/**
 * @return array
 * @function question
 *
 * Randomly picks from a list of questions including math questions that are randomly generated.
 */
function question(){
	$questions=array('math', //0
	"How many world wars have there been?",
	"Apple made the first MP3 player?",
	"EA owns and makes the Call of Duty Series?",
	"EA Owns and publishes the Battlefield series?",
	"What year did was the attack on Pearl Harbor?", //5
	"Type out the quoted word: timeto'compete'withus",
	"Name to most printed book in the western world?",
	"Type out the quoted word: It'send' of the world and we know it!",
	"Type out the non-quoted word: 'Peace out'bro",
	"Fix the number sequence: 2,3,4,5,_,7,8,9",  //10
	"Fix the number sequence: 2,4,6,8,10,12,_,16,18",
	"Name the ocean just to the west of the USA?",
	"The answer to life the universe and everything?",
	"Athiests believe in God?",
	"The name of the site you are on?",  //15
	"math",
	"Type out the non-quoted word: face'book' \"sucks\"",
	"Type out the non-quoted word: devils 'remorse'",
	"Choose the last letter of this sentence.",
	"Maker of the iPad.",		//20
	"<a href=\"http://www.giantitp.com/comics/oots0729.html\" target=\"_blank\">Word in next to last panel.</a>",
	"math",
    "Author of the Lord of the Rings is?",
	);

	$total=count($questions)-1;
	$question_num=mt_rand(0,$total);
	$quest=$questions[$question_num];

	return array($quest,$question_num);
}

/**
 * @funtion answers
 * @param $question_num
 * @return mixed
 *
 * Has an array of all the answers to the questions, returns the answer or array with the answer.
 */
function answers($question_num){
    //Answer format  math = math question.  number/word = single answer, arrays = multiple choice, spot 0 = answer to question
    $answers=array("math",  //0
    2,array('no','yes','none of the above'),array('no','yes','none of the above'),array('yes','no','none of the above'),
    1941,	//5
    'compete','bible','send','bro',
    6,	//10
    14,'pacific',42,array('no','yes','none of the above'),
    array('pride gaming','gay pride','oracle','call of duty','none of the above'),  //15
    "math",'face','devils',array('e','o','C','r','q','B','a','none of the above'),
    'apple',	//20
    'that','math',array("J R R Tolkien", "J C R Tolkien", "Bob Saget", "Lewis and Clark", "R Dahl", "J K Rowling", "None of the above"),
    );
    return $answers[$question_num];

}

/**
 * @function math
 * @return array
 *
 * simple function to create simple math problems.
 */
function math(){
	$first = mt_rand(2,199);
	$operator = mt_rand(0,3);
	$second = mt_rand(10,999);
	switch($operator){
        case 0:  //+
            $ans=$first+$second;
            $oper="?=".$first." + ".$second;
            break;
        case 1: // -
            $ans=$first-$second;
            $oper="?=".$first." - ".$second;
            break;
        case 2: //*
            $ans=$first*$second;
            $oper="?=".$first." * ".$second;
            break;
        default:  // /
            $ans=$first*$second;
            $temp=$first;
            $first=$ans;
            $ans=$temp;
            $oper="?=".$first."/".$second;
	};
	return array($oper, $ans);
}

/**
 * @function build_answer
 * @param $question_nu
 * @param null $answer
 * @return string
 *
 * Takes a question number, and possible an answer to form the form input for the question.
 */
function build_answer($question_nu, $answer=NULL){
    global $sql,$e107;
    $type=array("math",'n','m','m','m','n','w','w','w','w','n','n','w','n','m','m',"math",'w','w','m','w','w',"math","m",);
	//n=numberical, m=multichoice, w=word
	switch($type[$question_nu]){
        case "math":
            $id = session_id();
            //insert into something with session_id as the key.
            if($answer!=NULL){
                $sql->db_Insert("session", session_id().",time()+300,time(),$e107->getip(),{$answer}");
            }
            else{
                //error
            }
        case 'n':
        case 'w':
            $output="<td class='forumheader3'>
                <input type=\"text\" class=\"tbox\" name=\"deinergebnis\" />
            </td>";
            break;
        case 'm':
            $ansr=shuffle(answer($question_nu));
            $count=0;
            $output ="";
            foreach($ansr as $anw){
                $output .= "<td class='forumheader3'>
                    <input=\"radio\" name=\"ans{$count}\" value=\"{$anw}\">
                </td>";
		    }
		$output .= "<input type=\"hidden\" name=\"count\" value==\"{$count}\">";
		break;
	}
	$output .="<input type=\"hidden\" name=\"anw\" value==\"{$question_nu}\">";
	return $output;
}

/**
 * @function getAnswer
 * @return bool
 *
 * Gets the information from the post and proves that it is correct.
 */
function getAnswer(){
    global $sql, $e107;
	$deinergebnis = (isset($_POST["deinergebnis"]))? $_POST["deinergebnis"]: '';
	$question = $_POST['anw'];
	$answer=answer($question);
	if($answer=='math'){  //math
		$id=session_id();
        $ip = $e107->getip();
		$ranswer= $sql->db_Select('session', 'session_data', "session_id={$id} and session_ip={$ip}");
          //select session_data from session where session_id=$id and session_ip=ip;
        $sql->db_Delete("session", "session_id={$id} and session_ip={$ip}" );
        if($ranswer[0]==$deinergebnis){
			return true;
		}
	}
	elseif(isarray($answer)){ //multiple choice
		for($i=0; $i<$_POST['count']; $i++){
			if(isset($_POST['ans'.$i])){
				$deinergebnis=$_POST['ans'.$i];
				$i=$_POST['count'];
			}
		}
		if($answer[0]==$deinergebnis){
			return true;
		}
	}
	else{  // all others.
		if(strtolower($answer)==$deinergebnis){
			return true;
		}
	}
	return false;
}
?>