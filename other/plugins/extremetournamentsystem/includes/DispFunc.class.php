<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################

/*#####################################
Class DispFunc
Description: Where the output happens or is prepared for output.  No real work other then formatting is done in any of these functions.
####################################*/
class DispFunc{
	 
	/*#############################
	Name: X1PluginTitle
	What does it do: Displays a special footere common to some functions	
	Params: string $output: the string t
	Returns: a string
	###############################*/	 
	function X1PluginTitle($output){
		return "<table class='".X1plugin_title."' width='100%' border='0' cellspacing='1' cellpadding='6'>
	        <tr>
	            <td class='tcat'>
	                $output
	            </td>
	        </tr>
	    </table>";
	}
	
	
	/*#############################
	Function: DisplaySpecialFooter
	Needs: Int span:size of the span boolean $break:usually there is a break but everynow and then there isn't var $fillin: For when you need to input 
	Returns: a string
	What does it do: Displays a special footere common to many functions
	###############################*/
	function DisplaySpecialFooter($span, $break=true, $fillin=""){
		$output ="</tbody>
	    	<tfoot class='".X1plugin_tablefoot."'>
	    		<tr>
	    			<td colspan='".$span."'>";
		if(isset($fillin)){
			$output= $fillin;
		}
		else{
	    	$output ="&nbsp";
		}
		$output.="</td>
	    		</tr>
	    	</tfoot>
	    </table>";
	    if($break){
			$output .="<br />";
		}
		return $output;
	}
	
	
	/*#############################
	Function: X1Clean
	What does it do: Displays a special footere common to some functions
	Needs: string $var: A string to be "cleaned." int $mode: The mode.
	Returns: a string
	###############################*/	
	function X1Clean($var, $mode=1, $mode2=1){

		switch($mode){	
			case 3://converts (,),' to html tags, should get rid of php code.
				$var = utf8_decode($var);
				$var = strtr($var, array('(' => '&#40;', ')' => '&#41;'));
				$var = htmlspecialchars($var);
				$var = strip_tags($var);
			break;
			
			case 4:// no html tags, no quotes, just text
				$var = utf8_decode($var);
				$var = strip_tags($var);
				$var = strtr($var, array('(' => '&#40;', ')' => '&#41;', '\'' =>'&#39;'));
				$var = htmlspecialchars($var);
				$var = rtrim($var);
				break;
				
			case 5: //Array cleaning
				if(is_array($var)){
					$keys=array_keys($var);
					$count = 0;
					foreach($var as $item){
						$newvar[$keys[$count]]=DispFunc::X1Clean($item);
						$count++;
					}
					$var=$newvar;
				}
				else{//really should be an error;
					$var=DispFunc::X1Clean($var, $mode2);
				}
				break;
				
			default://remove all php and html tags and make (,),' into hmtl chartext equvalent
				$var = utf8_decode($var);//makes sure it's utf8 decoded.
				$var = strip_tags($var); //strips out all HTML and PHP tags
				//$var = strtr($var, array('(' => '&#40;', ')' => '&#41;')); //translates () into html equivalent
				$var = htmlspecialchars($var, ENT_QUOTES | ENT_HTML5);//takes that equvalent and makes it text
			break;
		}
		return $var;
		
	}
	
	/*#############################
	Function: X1PluginOutput
	What does it do: where the output happens
	Needs: string $c: what is being echo'd  var $f(=X1_output): what mode to use.
	Returns: a string
	###############################*/	
	function X1_HTMLReady($var){
		$var = htmlspecialchars_decode ($var, ENT_QUOTES);
		$var = strtr($var, array( '&#40;'=> '(', '&#41;' => ')')); //translates &#41; and &#40; back to ().
		return $var;
	}
	
	/*#############################
	Function: X1PluginOutput
	What does it do: where the output happens
	Needs: string $c: what is being echo'd  var $f(=X1_output): what mode to use.
	Returns: a string
	###############################*/	
	function X1PluginOutput($c='', $f=X1_output){
		switch($f){
		case 1:
			return $c;
			break;
		case 2:
			echo $c;
			break;
		case "echo":
			echo $c;
			break;
		default:
			//possible error?
			return $c;
		}
	}
	
	/*#############################
	Function: X1PluginLinkback
	What does it do: where the output happens
	Needs: N/A
	Returns: a string
	###############################*/	
	function X1PluginLinkback() {
		if(X1_showlinkback){
			if(X1_showversion){
				$ver = "<br />Version:".X1_release;
			}
			else
			$ver=NULL;
			return "
			<div align='".X1_lbalign."'>
				<a href='".X1_lblink."' target='_blank'><img src='".X1_imgpath."/linkback/".X1_lbimage."' border='0'/>$ver</a>
			</div>";
		}
	}


	/*#############################
	Function: X1Pagination
	What does it do: Sets up page links
	Needs: int $total: $limit $li  $page  $pi   $link
	Returns: a string
	###############################*/
	function X1Pagination($total, $limit, $li, $page, $pi, $link){
		$numofpages = $total / $limit;
		if($numofpages > 1){
			$c = "<center>"; 
			if($page > 1){ 
				$pageprev=$page-1; 
				$c .= "<a href=\"$link&$pi=$pagenext&$li=$limit\">
								<img src='".X1_imgpath."/icons/left.gif' border='0' title='Previous' align='absmiddle' />
							</a>";
			}else{ 
				$c .= "&nbsp;"; 
			} 
			for($i = 1; $i <= $numofpages; $i++){ 
				if($i == $page){ 
					$c .= $i."&nbsp;"; 
				}else{ 
					$c .= "<a href=\"$link&$pi=$i&$li=$limit\">$i</a>&nbsp;"; 
				} 
			}  
			if(($total % $limit) != 0){ 
				if($i == $page){ 
					$c .= $i."&nbsp;"; 
				}else{ 
					$c .= "<a href=\"$link&$pi=$i&$li=$limit\">$i</a>&nbsp;"; 
				} 
			}
			if($page < $numofpages){ 
				$pagenext=$page+1; 
				$c .= "&nbsp;<a href=\"$link&$pi=$pagenext&$li=$limit\">
								<img src='".X1_imgpath."/icons/right.gif' border='0' title='Previous' align='absmiddle' valign='middle' />
							</a>";
			}else{ 
				$c .= "&nbsp;"; 
			} 
			return $c.'</center>';
		}else{
			return $c ='&nbsp;';
		}
	}


	/*#############################
	Function: X1PluginStyle
	What does it do: Gets the style sheet if needed
	Needs:N/A
	Returns: a string
	###############################*/
	function X1PluginStyle(){
		if(X1_customstyle){
			return "<LINK REL='StyleSheet'  href='".X1_csspath."/".X1_style."' type='text/css' media='screen' />";
		}
	}
	
	/*#############################
	Function: X1EditTime
	What does it do: Sets up the select boxes for editing times
	Needs:$t, $e(=''):
	Returns: a string
	###############################*/
	function X1EditTime($t, $e=''){
	    $i = getdate($t);
	    $c = "<select name='month$e'>
	        <option value='$i[mon]'>$i[month]</option>
	        <option value='1'>January</option>
	        <option value='2'>Febuary</option>
	        <option value='3'>March</option>
	        <option value='4'>April</option>
	        <option value='5'>May</option>
	        <option value='6'>June</option>
	        <option value='7'>July</option>
	        <option value='8'>August</option>
	        <option value='9'>September</option>
	        <option value='10'>October</option>
	        <option value='11'>November</option>
	        <option value='12'>December</option>
	    </select>";
	    $c .="<select name='day$e'>";
	    $c .="<option value='$i[mday]'>$i[mday]</option>";
	    for($a=1; $a < 32; $a++){
	        $c .="<option value='$a'>$a</option>";
	    }
	    $c .="</select>";
	    $c .="<select name='year$e'>";
	    $c .="<option value='$i[year]'>$i[year]</option>";
	    for($a=2006; $a < 2037; $a++){
	        $c .="<option value='$a'>$a</option>";
	    }
	    $c .="</select>";
	    $c .="<select name='hours$e'>";
	    $c .="<option value='$i[hours]'>$i[hours]</option>";
	    for($a=0; $a < 25; $a++){
	        $c .="<option value='$a'>$a</option>";
	    }
	    $c .="</select>";
	    $c .="<select name='mins$e'>";
	    $c .="<option value='$i[minutes]'>$i[minutes]</option>";
	    for($a=0; $a < 61; $a++){
	        $c .="<option value='$a'>$a</option>";
	    }
	    $c .="</select>";
	    return $c;
	}

	/*#############################
	Function: X1ReadTime
	What does it do: Reads the time and returns it.
	Needs:$e(=''):
	Returns: a string
	###############################*/
	function X1ReadTime($e=''){
		return date('U',mktime($_POST["hours$e"],$_POST["mins$e"], 0, $_POST["month$e"], $_POST["day$e"], $_POST["year$e"]));
	}
	




/*
Copyright (c) 2005 by The Nuke-Evolution Team
Filename      : functions_evo.php
Parts of the following function were copyied.
*/
	/*#############################
	Function: ReDirect
	What does it do: Sets the header for the url
	Needs:string $url
	Returns: true on success, dies otherwise.
	###############################*/
	function Redirect($url) {
		if(empty($url)){
  		die();//error
  	}
	  $type = preg_match('/IIS|Microsoft|WebSTAR|Xitami/', $_SERVER['SERVER_SOFTWARE']) ? 'Refresh: 0; URL=' : 'Location: ';$url = str_replace('&amp;', "&", $url);
    header($type . $url);
    return true;
	}
	
	
	/*#############################
	Function: DispPreLoggedError
	What does it do: returns based on what error type is reported.
	Needs:string $url
	Returns: true on success, dies otherwise.
	###############################*/
	function DispPreLoggedError($return_type){
		switch($return_type){
			case 1:
				return DispFunc::X1PluginOutput(XL_error_sys);
				break;
			case 2:
				die(XL_error_sys);
				break;
			default:
				return;
				break;
		}	
	}
	
	
	/*#############################
	Function: DirectToRefresh
	What does it do: Sets up a url that when displayed will redirect the page to the part of the ladder you called.
	Needs:string $url
	Returns: true on success, dies otherwise.
	###############################*/
	function DirectToRefresh($url, $time=X1_refreshtime) {
		if(empty($url)){
  		die();//error
  	}
  	return "<meta http-equiv='refresh' content='".$time.";URL=".X1_publicpostfile.X1_linkactionoperator."$url'>";
	}
	
} //End Class


?>