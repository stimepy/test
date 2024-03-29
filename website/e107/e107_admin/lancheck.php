<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_admin/lancheck.php $
|     $Revision: 12561 $
|     $Id: lancheck.php 12561 2012-01-16 10:21:47Z e107coders $
|     $Author: e107coders $
|	  With code from Izydor and Lolo.
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("0")) {
	header("location:".e_BASE."index.php");
	exit;
}



class lancheck
{
	
	var $core_plugins = array(
		"alt_auth","banner_menu","blogcalendar_menu","calendar_menu","chatbox_menu",
		"clock_menu","comment_menu","compliance_menu","content","counter_menu",
		"featurebox","forum","gsitemap","integrity_check","lastseen","links_page",
		"linkwords","list_new","log","login_menu","newforumposts_main","newsfeed",
		"newsletter","online_extended_menu","online_menu","other_news_menu","pdf",
		"pm","poll","powered_by_menu","rss_menu","search_menu","sitebutton_menu",
		"trackback","tree_menu","userlanguage_menu","usertheme_menu"
		);
		
	var $core_themes = array("crahan","core","e107v4a","human_condition","interfectus","jayya",
		"khatru","kubrick","lamb","leaf","sebes","vekna_blue");
		
	var $errorsOnly = FALSE;
	
	var $coreImage = array();

	
	function init()
	{
		global $ns,$tp;
		
		$acceptedLans = explode(",",e_LANLIST);
	
		
		if(!isset($_SESSION['lancheck-core-image']))
		{
			$core = array();	

			$coredir = array('admin' => 'e107_admin', 'files' => 'e107_files', 'images' => 'e107_images', 'themes' => 'e107_themes', 'plugins' => 'e107_plugins', 'handlers' => 'e107_handlers', 'languages' => 'e107_languages', 'downloads' => 'e107_downloads', 'docs' => 'e107_docs');
			
			require_once(e_ADMIN."core_image.php");
			
			unset($core_image['e107_images'],$core_image['e107_files'],$core_image['e107_admin']);
			
			$_SESSION['lancheck-core-image'] = $core_image;	
		}
		

			
		if(isset($_POST['language_sel'])) // Verify
		{
			
			$_SESSION['lancheck-errors-only'] 	= ($_POST['errorsonly']==1 ) ?  1 : 0;	
			$this->errorsOnly 					= ($_POST['errorsonly']==1) ?  TRUE : FALSE;
			$this->check_all();
			return TRUE;
		}
		
		// Write the language file.
		if(isset($_POST['submit']) && varsettrue($_POST['lan']) && in_array($_POST['lan'],$acceptedLans))
		{
			
			$this->write_lanfile($_POST['lan']);	
			return TRUE;	
		} 
		
		// Edit the Language File.
		if(varsettrue($_GET['f']) && varsettrue($_GET['lan']) && in_array($_GET['lan'],$acceptedLans))
		{
			
			if (!$_GET['mode'])
			{
				$dir1 =  e_LANGUAGEDIR."English/";
				$f1= $tp->toDB($_GET['f']);
				$dir2 =  e_LANGUAGEDIR.$_GET['lan']."/";
				$f2= $tp->toDB($_GET['f']);
			}
			else
			{
				$fullpath_orig = $tp->toDB($_GET['f']);
				$fullpath_trans = str_replace("English",$_GET['lan'],$tp->toDB($_GET['f']));
		
				$f1 = basename($fullpath_orig);
				$f2 = basename($fullpath_trans);
				$dir1 = dirname($fullpath_orig)."/";
				$dir2 = dirname($fullpath_trans)."/";
			}
			
			$this->edit_lanfiles($dir1,$dir2,$f1,$f2,$_GET['lan']);	
			return TRUE;	
		}	
		
		return FALSE;
	}
	
	
	function countFiles($array)
	{
		foreach($array as $k=>$val)
		{
			if(is_array($val))
			{
				$key = key($val);
				$this->coreImage[$key] = $val;
			}
			elseif($val)
			{
			//	$this->totalFiles++;		
			}	
			
		}	
	}
	
	
	
	function check_all($mode='render')
	{
		global $ns,$tp;
			
		$_POST['language'] = key($_POST['language_sel']);

		$_SESSION['lancheck'][$_POST['language']] = array();
		$_SESSION['lancheck'][$_POST['language']]['file']	= 0;
		$_SESSION['lancheck'][$_POST['language']]['def']	= 0;
		$_SESSION['lancheck'][$_POST['language']]['bom']	= 0;
		$_SESSION['lancheck'][$_POST['language']]['utf']	= 0;
		$_SESSION['lancheck'][$_POST['language']]['total']	= 0;
	
	
		$core_text 	= $this->check_core_lanfiles($_POST['language']);
		$core_admin = $this->check_core_lanfiles($_POST['language'],"admin/");
		$plug_text = "";
		$theme_text = "";
	
	
		// Plugins -------------
		$plug_header = "<table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
		<td class='fcaption'>".LAN_PLUGIN."</td>
		<td class='fcaption'>".LAN_CHECK_16."</td>
		<td class='fcaption'>".$_POST['language']."</td>
		<td class='fcaption'>".LAN_OPTIONS."</td></tr>";
	
		foreach($this->core_plugins as $plugs)
		{
			if(is_readable(e_PLUGIN.$plugs))
			{
				$plug_text .= $this->check_lanfiles('P',$plugs,"English",$_POST['language']);
			}
		}
		
		$plug_footer = "</table>";
	
		// Themes  -------------
		$theme_header = "<table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
		<td class='fcaption'>".LAN_CHECK_22."</td>
		<td class='fcaption'>".LAN_CHECK_16."</td>
		<td class='fcaption'>".$_POST['language']."</td>
		<td class='fcaption'>".LAN_OPTIONS."</td></tr>";
		foreach($this->core_themes as $them)
		{
			if(is_readable(e_THEME.$them))
			{
				$theme_text .= $this->check_lanfiles('T',$them,"English",$_POST['language']);
			}
		}
		$theme_footer = "</table>";
		
		// -------------------------
		

		
		
		if($mode != 'render')
		{
			 return;
		}
	
		$message .= "<div style='".ADMIN_WIDTH.";text-align:center;padding:20px'>
		<form id='lancheck' method='post' action='".e_ADMIN."language.php?tools'>
		<div>\n";
		
		$icon = ($_SESSION['lancheck'][$_POST['language']]['total']>0) ? ADMIN_FALSE_ICON : ADMIN_TRUE_ICON;	
		
		
		$errors_diz = (defsettrue('LAN_CHECK_23')) ? LAN_CHECK_23 : "Errors Found";
		
		$message .= "<div>".$icon." ".$errors_diz.": ".$_SESSION['lancheck'][$_POST['language']]['total']."</div>";	
	
		$just_go_diz = (defsettrue('LAN_CHECK_20')) ? LAN_CHECK_20 : "Generate Language Pack";
		$lang_sel_diz = (defsettrue('LAN_CHECK_21')) ? LAN_CHECK_21 : "Verify Again";
		$lan_pleasewait = (defsettrue('LAN_PLEASEWAIT')) ?  $tp->toJS(LAN_PLEASEWAIT) : "Please Wait";
		
		$message .= "
		<br /><br />
		<input type='hidden' name='language' value='".$_POST['language']."' />
		<input type='hidden' name='errorsonly' value='".$_SESSION['lancheck-errors-only']."' />    
	    <input type='submit' name='ziplang[".$_POST['language']."]' value=\"".$just_go_diz."\" class='button' onclick=\"this.value = '".$lan_pleasewait."'\" />
	    <input type='submit' name='language_sel[".$_POST['language']."]' value=\"".$lang_sel_diz."\" class='button' />
		</div>
	    </form>
		</div>";
		
//	print_a($_SESSION['lancheck'][$_POST['language']]);

		$plug_text = ($plug_text) ? $plug_header.$plug_text.$plug_footer : "<div>".LAN_OK."</div>";	
		$theme_text = ($theme_text) ? $theme_header.$theme_text.$theme_footer : "<div>".LAN_OK."</div>";	

			
		$ns -> tablerender(LAN_CHECK_24.": ".$_POST['language'],$message);
	
		$ns -> tablerender(LAN_CHECK_3.": ".$_POST['language'], $core_text);
		$ns -> tablerender(LAN_CHECK_3.": ".$_POST['language']."/admin", $core_admin);
		$ns -> tablerender(ADLAN_CL_7, $plug_text);
		$ns -> tablerender(LAN_CHECK_25, $theme_text);	
		
	}
	
	
	function write_lanfile($lan='')
	{
		if(!$lan){ 	return; }
		
		global $ns;
		
		unset($input);
		$kom_start = chr(47)."*";
		$kom_end = "*".chr(47);
	
		if(varsettrue($_SESSION['lancheck-edit-file']))
		{
			$writeit = $_SESSION['lancheck-edit-file'];
		}
		else
		{
			return;	
		}
	
		$old_kom = "";
		$in_kom=0;
		
		if(is_readable($writeit)) // File Exists; 
		{
			$data = file($writeit);
			foreach($data as $line)
			{
		
				if (strpos($line,$kom_start) !== False && $old_kom == "")
				{
					$in_kom=1;
				}
				if ($in_kom) { $old_kom .= $line; }
				if (strpos($line,$kom_end) !== False && $in_kom) {$in_kom = 0;}
			}	
		}
		
	
	
		$message = "<div style='text-align:left'><br />";
		$input .= chr(60)."?php\n";
		if ($old_kom == "")
		{
			// create CVS compatible description.
			$diz = chr(47)."*\n";
			$diz .= "+---------------------------------------------------------------+\n";
			$diz .= "|        e107 website content management system ".$lan." Language File\n";
			$diz .= "|        Released under the terms and conditions of the\n";
			$diz .= "|        GNU General Public License (http://gnu.org).\n";
			$diz .= "|\n";
			$diz .= "|        ".chr(36)."URL: $writeit ".chr(36)."\n";
			$diz .= "|        ".chr(36)."Revision: 1.0 ".chr(36)."\n";
			$diz .= "|        ".chr(36)."Id: ".date("Y/m/d H:i:s")." ".chr(36)."\n";
			$diz .= "|        ".chr(36)."Author: ".USERNAME." ".chr(36)."\n";
			$diz .= "+---------------------------------------------------------------+\n";
			$diz .= "*".chr(47)."\n\n";
		}
		else
		{
			$diz = $old_kom;
		}
	
		$input .= $diz;
		$message .= str_replace("\n","<br />",$diz);
	
		for ($i=0; $i<count($_POST['newlang']); $i++)
		{
			$notdef_start = "";
			$notdef_end = "\n";
			$deflang = (MAGIC_QUOTES_GPC === TRUE) ? stripslashes($_POST['newlang'][$i]) : $_POST['newlang'][$i];
			$func = "define";
			$quote = chr(34);
	
			if (strpos($_POST['newdef'][$i],"ndef++") !== FALSE )
			{
				$defvar = str_replace("ndef++","",$_POST['newdef'][$i]);
				$notdef_start = "if (!defined(".chr(34).$defvar.chr(34).")) {";
				$notdef_end = "}\n";
			}
			else
			{
				$defvar = $_POST['newdef'][$i];
			}
	
			if($_POST['newdef'][$i] == "LC_ALL" && varsettrue($_SESSION['lancheck-edit-file']))
			{
				$message .= $notdef_start.'setlocale('.htmlentities($defvar).','.$deflang.');<br />'.$notdef_end;
				$input .= $notdef_start."setlocale(".$defvar.",".$deflang.");".$notdef_end;
			}
			else
			{
				$message .= $notdef_start.$func.'('.$quote.htmlentities($defvar).$quote.',"'.$deflang.'");<br />'.$notdef_end;
				$input .= $notdef_start.$func."(".$quote.$defvar.$quote.", ".chr(34).$deflang.chr(34).");".$notdef_end;
			}
		}
	
		$message .="<br />";
		$message .="</div>";
		$input .= "\n\n?>";
			//<?
		// Write to file.
		
		$writeit = str_replace("//","/",$writeit); // Quick Fix. 
		
		$fp = @fopen($writeit,"w");
		if(!@fwrite($fp, $input))
		{
			$caption = LAN_ERROR;
			$message = LAN_CHECK_17;
		}
		else
		{
			$caption = LAN_SAVED." <b>".$lan."/".$writeit."</b>";
		}
		fclose($fp);
	
		$message .= "<form method='post' action='".e_SELF."?tools' id='select_lang'>
		<div style='text-align:center'><br />";
		$message .= "<br /><br /><input class='button' type='submit' name='language_sel[".$lan."]' value=\"".LAN_BACK."\" />
		</div></form>";
	
		unset($_SESSION['lancheck-edit-file']);
		$ns -> tablerender($caption, $message);
	}
	
		
		
		
	function check_core_lanfiles($checklan,$subdir='')
	{
		global $lanfiles,$_POST,$sql;
	
	//	$sql->db_Mark_Time('Start Get Core Lan Phrases English');
		$English = $this->get_comp_lan_phrases(e_LANGUAGEDIR."English/".$subdir,$checklan);
		
	//	$sql->db_Mark_Time('End Get Core Lan Phrases English');
		
		$check = $this->get_comp_lan_phrases(e_LANGUAGEDIR.$checklan."/".$subdir,$checklan);
		
	//	print_a($check);
	//	return;
		$text = "";
	
		$header = "<table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
		<td class='fcaption'>".LAN_CHECK_16."</td>
		<td class='fcaption'>".$_POST['language']." ".LAN_CHECK_26."</td>
		<td class='fcaption'>".LAN_OPTIONS."</td></tr>";
	
		$keys = array_keys($English);
	
		sort($keys);
		$er = "";
	
		foreach($keys as $k)
		{
			if($k != "bom")
			{
				$lnk = $k;
				$k_check = str_replace("English",$checklan,$k);
				if(array_key_exists($k,$check))
				{
					// $text .= "<tr><td class='forumheader3' style='width:45%'>{$lnk}</td>";
					$subkeys = array_keys($English[$k]);
	
					$er="";
					$utf_error = "";
	
					$bomkey = str_replace(".php","",$k_check);
				//	$bom_error = ($check['bom'][$bomkey]) ? "<i>".LAN_CHECK_15."</i><br />" : ""; // illegal chars
	
					if($check['bom'][$bomkey])
					{
						$bom_error = "<i>".LAN_CHECK_15."</i><br />";
						$this->checkLog('bom',1);;	
					}
					else
					{
						$bom_error = "";	
					}
	
					foreach($subkeys as $sk)
					{
						if($utf_error == "" && !$this->is_utf8($check[$k][$sk]))
						{
							$utf_error = "<i>".LAN_CHECK_19."</i><br />";
							$this->checkLog('utf',1);
						}
	
						if($sk == "LC_ALL"){
							$check[$k][$sk] = str_replace(chr(34).chr(34),"",$check[$k][$sk]);
						}
					
						$er .= $this->check_lan_errors($English[$k],$check[$k],$sk);
					}
					
					if($this->errorsOnly == TRUE && !$er && !$utf_error && !$bom_error)
					{
						continue;		
					}
						
					$text .= "<tr><td class='forumheader3' style='width:45%'>{$lnk}</td>";
					$style = ($er) ? "forumheader2" : "forumheader3";
					$text .= "<td class='{$style}' style='width:50%'><div class='smalltext'>";
					$text .= $bom_error . $utf_error;
					$text .= (!$er && !$bom_error && !$utf_error) ? "<img src='".e_IMAGE."fileinspector/integrity_pass.png' alt='".LAN_OK."' />" : $er."<br />";
					$text .= "</div></td>";
				}
				else
				{
					$this->checkLog('file',1);
					$text .= "<tr>
					<td class='forumheader3' style='width:45%'>{$lnk}</td>
					<td class='forumheader' style='width:50%'>".LAN_CHECK_4."</td>"; // file missing.
				}
				// Leave in EDIT button for all entries - to allow re-translation of bad entries.
				$subpath = ($subdir!='') ? $subdir.$k : $k;
				$text .="<td class='forumheader3' style='width:5%;text-align:center'>
				<input class='tbox' type='button' style='width:60px' name='but_$i' value=\"".LAN_EDIT."\" onclick=\"window.location='".e_SELF."?f=".$subpath."&amp;lan=".$_POST['language']."'\" /> ";
				$text .="</td></tr>";
			}
		}

		$footer = "</table>";
		
		if($text)
		{
			return $header.$text.$footer;	
		}
		else
		{
		 	return "<div>".LAN_OK."</div>";
		}
	}



	
	function check_lan_errors($english,$translation,$def)
	{
		$eng_line = $english[$def];
		$trans_line = $translation[$def];
		
		// return $eng_line."<br />".$trans_line."<br /><br />";
			
		$error = array();
			
		if((!array_key_exists($def,$translation) && $eng_line != "") || (trim($trans_line) == "" && $eng_line != ""))
		{
			$this->checkLog('def',1);
			return $def.": ".LAN_CHECK_5."<br />";
		}
		
		if((strpos($eng_line,"[link=")!==FALSE && strpos($trans_line,"[link=")===FALSE) || (strpos($eng_line,"[b]")!==FALSE && strpos($trans_line,"[b]")===FALSE))
		{
			$error[] = $def. ": Missing bbcodes";
		}
		elseif((strpos($eng_line,"[")!==FALSE && strpos($trans_line,"[")===FALSE) || (strpos($eng_line,"]")!==FALSE && strpos($trans_line, "]")===FALSE))
		{
			$error[] = $def. ": Missing [ and/or ] character(s)";
		}
		
		if((strpos($eng_line,"--LINK--")!==FALSE && strpos($trans_line,"--LINK--")==FALSE))
		{
			$error[] = $def. ": Missing --LINK--";
		}
		
		if((strpos($eng_line,"e107.org")!==FALSE && strpos($trans_line,"e107.org")==FALSE))
		{
			$error[] = $def. ": Missing e107.org URL";
		}
		
		if((strpos($eng_line,"e107coders.org")!==FALSE && strpos($trans_line,"e107coders.org")==FALSE))
		{
			$error[] = $def. ": Missing e107coders.org URL";
		}
		
		if(strip_tags($eng_line) != $eng_line)
		{
			$stripped = strip_tags($trans_line);
					
			if(($stripped == $trans_line))
			{					
				// echo "<br /><br />".$def. "<br />".$stripped."<br />".$trans_line;
				$error[] = $def. ": Missing HTML tags" ; 		
			}
		}
		
		$this->checkLog('def',count($error));
	
		return ($error) ? implode("<br />",$error)."<br />" : "";
		
	}
	
	
	
	
	function checkLog($type='error',$count)
	{
		$_SESSION['lancheck'][$_POST['language']][$type] += $count;
		$_SESSION['lancheck'][$_POST['language']]['total'] += $count;
	}
	
	
	
	function get_lan_file_phrases($dir1,$dir2,$file1,$file2){
	
		$ret = array();
		$fname = $dir1.$file1;
		$type='orig';
	
		if(is_file($fname))
		{
			$data = file_get_contents($fname);
			$ret= $ret + $this->fill_phrases_array($data,$type);
			if(substr($data,0,5) != "<?php")
			{
				$key = str_replace(".php","",$fname);
				$ret['bom'][$key] = $fname;
			}
		}
	
		$fname = $dir2.$file2;
		$type='tran';
	
		if(is_file($fname))
		{
			$data = file_get_contents($fname);
			$ret=$ret + $this->fill_phrases_array($data,$type);
			if(substr($data,0,5) != "<?php")
			{
				$key = str_replace(".php","",$fname);
				$ret['bom'][$key] = $fname;
			}
		}
		
	
		return $ret;
	}
	
	
	
	
	function get_comp_lan_phrases($comp_dir,$lang,$depth=0)
	{
		if(!is_dir($comp_dir))
		{
			return array();
		}
		
		
		require_once(e_HANDLER."file_class.php");
		$fl = new e_file;
		$ret = array();
			
		if($lang_array = $fl->get_files($comp_dir, ".php$","standard",$depth)){
			sort($lang_array);
		}
	
		$regexp = (strpos($comp_dir,e_LANGUAGEDIR) !== FALSE) ? "#.php#" : "#".$lang."#";
	
		foreach($lang_array as $f)
		{
			if(preg_match($regexp,$f['path'].$f['fname']) && is_file($f['path'].$f['fname']))
			{
				$allData = file_get_contents($f['path'].$f['fname']);
				$data = explode("\n",$allData);
				// $data = file($f['path'].$f['fname']);
				$relpath = str_replace($comp_dir,"",$f['path']);
				
				$key = str_replace(".php","",$relpath.$f['fname']);
				
				if(substr($data[0],0,5) != "<?php")
				{
					
					$ret['bom'][$key] = $f['fname'];
				}
						
				$end_of_file = 0;
							
				foreach($data as $line)
				{
					if($end_of_file == 1)
					{
						$ret['bom'][$key] = $f['fname'];
					}
											
					$line = trim($line);
					if($line == "?>")
					{
						$end_of_file = 1;  	
					}
				}
					
			
				
				if($f['path'].$f['fname'] == e_LANGUAGEDIR.$lang."/".$lang.".php")
				{
					$f['fname'] = "English.php";  // change the key for the main language file.
				}
	
				if($f['path'].$f['fname'] == e_LANGUAGEDIR.$lang."/".$lang."_custom.php")
				{
					$f['fname'] = "English_custom.php";  // change the key for the main language file.
				}
	
				$ret=$ret + $this->fill_phrases_array($allData,$relpath.$f['fname']);
	
			}
		}
	
		return $ret;
	
	}
	
	
	
	// for plugins and themes - checkes what kind of language files directory structure we have
	function check_lanfiles($mode,$comp_name,$base_lan="English",$target_lan){
		global $ns,$sql;
	
		$folder['P'] = e_PLUGIN.$comp_name;
		$folder['T'] = e_THEME.$comp_name;
		$comp_dir = $folder[$mode];
	
		$baselang 	= $this->get_comp_lan_phrases($comp_dir."/languages/","English",1);
		$check 		= $this->get_comp_lan_phrases($comp_dir."/languages/",$target_lan,1);	
	
		$text = "";
		$keys = array_keys($baselang);
		sort($keys);
	
		foreach($keys as $k)
		{
			
			if($k == 'bom')
			{
				continue;
			}
			
			$lnk = $k;
			//echo "klucz ".$k."<br />";
			$k_check = str_replace("English",$target_lan,$k);
			if(array_key_exists($k_check,$check))
			{
				
	
				$subkeys = array_keys($baselang[$k]);
				$er="";
				$utf_error = "";
	
				$bomkey = str_replace(".php","",$k_check);
				if($check['bom'][$bomkey])
				{
					$bom_error = "<i>".LAN_CHECK_15."</i><br />";
					$this->checkLog('bom',1); 
				}
				else
				{
					$bom_error = "";	
				}
			// 	$bom_error = ($check['bom'][$bomkey]) ? "<i>".LAN_CHECK_15."</i><br />" : ""; // illegal chars
			
				foreach($subkeys as $sk)
				{
					if($utf_error == "" && !$this->is_utf8($check[$k_check][$sk]))
					{
						$utf_error = "<i>".LAN_CHECK_19."</i><br />";
						$this->checkLog('utf',1);
					}
					
					/*
					if(!array_key_exists($sk,$check[$k_check]) || (trim($check[$k_check][$sk]) == "" && $baselang[$k][$sk] != ""))
					{
						$er .= ($er) ? "<br />" : "";
						$er .= $sk." ".LAN_CHECK_5;
					}
					*/
					$er .= $this->check_lan_errors($baselang[$k],$check[$k_check],$sk);
				}
	
				if($this->errorsOnly == TRUE && !$er && !$utf_error && !$bom_error)
				{
					continue;		
				}
	
				$text .= "<tr>
				<td class='forumheader3' style='width:20%'>".$comp_name."</td>
				<td class='forumheader3' style='width:25%'>".str_replace("English/","",$lnk)."</td>";
	
				$style = ($er) ? "forumheader2" : "forumheader3";
				$text .= "<td class='{$style}' style='width:50%'><div class='smalltext'>";
				$text .= $bom_error . $utf_error;
				$text .= (!$er && !$bom_error && !$utf_error) ? "<img src='".e_IMAGE."fileinspector/integrity_pass.png' alt='".LAN_OK."' />" : $er."<br />";
				$text .= "</div></td>";
			}
			else
			{
				$this->checkLog('file',1);
				$text .= "<tr>
				<td class='forumheader3' style='width:20%'>".$comp_name."</td>
				<td class='forumheader3' style='width:25%'>".str_replace("English/","",$lnk)."</td>
				<td class='forumheader' style='width:50%'><span style='cursor:pointer' title=\"".str_replace("English",$target_lan,$lnk)."\">".LAN_CHECK_4."</span></td>";
			}
	
			$text .="<td class='forumheader3' style='width:5%;text-align:center'>
			<input class='tbox' type='button' style='width:60px' name='but_$i' value=\"".LAN_EDIT."\" onclick=\"window.location='".e_SELF."?f=".$comp_dir."/languages/".$lnk."&amp;lan=".$target_lan."&amp;mode={$mode}'\" /> ";
			$text .="</td></tr>";
		}
	
	
	
		// if (!$known) {$text = LAN_CHECK_18." : --> ".$fname." :: ".$dname;}
		return $text;
	}
	
	
	
	function edit_lanfiles($dir1,$dir2,$f1,$f2,$lan)
	{
		if($lan == '')
		{
			echo "Language selection was lost. ";
			return;
		}
		
		global $ns,$sql;
	
		/*    echo "<br />dir1 = $dir1";
		echo "<br />file1 = $f1";
	
		echo "<br />dir2 = $dir2";
		echo "<br />file2 = $f2";*/
		
	
	
		if($dir2.$f2 == e_LANGUAGEDIR.$lan."/English.php") // it's a language config file.
		{
			$f2 = $lan.".php";
			$root_file = e_LANGUAGEDIR.$lan."/".$lan.".php";
		}
		else
		{
			$root_file = $dir2.$f2;
		}
	
		if($dir2.$f2 == e_LANGUAGEDIR.$lan."/English_custom.php") // it's a language config file.
		{
			$f2 = $lan."_custom.php";
			$root_file = e_LANGUAGEDIR.$lan."/".$lan."_custom.php";
		}
	
	
		$writable = (is_writable($dir2)) ? TRUE : FALSE;
		$trans = $this->get_lan_file_phrases($dir1,$dir2,$f1,$f2);
		$keys = array_keys($trans);
		sort($keys);
	
		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."' id='transform'>
		<table style='".ADMIN_WIDTH."' class='fborder'>";
	
		$subkeys = array_keys($trans['orig']);
		foreach($subkeys as $sk)
		{
			$rowamount = round(strlen($trans['orig'][$sk])/34)+1;
			$hglt1=""; $hglt2="";
			if ($trans['tran'][$sk] == "" && $trans['orig'][$sk]!="") {
				$hglt1="<span style='font-style:italic;font-weight:bold;color:red'>";
				$hglt2="</span>";
			}
			$text .="<tr>
			<td class='forumheader3' style='width:10%;vertical-align:top'>".$hglt1.htmlentities($sk).$hglt2."</td>
			<td class='forumheader3' style='width:40%;vertical-align:top'>".htmlentities(str_replace("ndef++","",$trans['orig'][$sk])) ."</td>";
			$text .= "<td class='forumheader3' style='width:50%;vertical-align:top'>";
			$text .= ($writable) ? "<textarea  class='tbox' name='newlang[]' rows='$rowamount' cols='45' style='height:100%'>" : "";
			$text .= str_replace("ndef++","",$trans['tran'][$sk]);
			$text .= ($writable) ? "</textarea>" : "";
			//echo "orig --> ".$trans['orig'][$sk]."<br />";
			if (strpos($trans['orig'][$sk],"ndef++") !== False)
			{
				//echo "+orig --> ".$trans['orig'][$sk]." <> ".strpos($trans['orig'][$sk],"ndef++")."<br />";
				$text .= "<input type='hidden' name='newdef[]' value='ndef++".$sk."' />";
			}
			else
			{
				$text .= "<input type='hidden' name='newdef[]' value='".$sk."' />";
			}
			$text .="</td></tr>";
		}
	
		unset($_SESSION['lancheck-edit-file']);
	
		//Check if directory is writable
		
		if($writable)
		{
			$text .="<tr style='vertical-align:top'>
			<td colspan='3' style='text-align:center' class='forumheader'>
			<input type='hidden' name='lan' value='{$lan}' />
			<input class='button' type='submit' name='submit' value=\"".LAN_SAVE." ".str_replace($dir2,"",$root_file)." \" />";
	
			if($root_file)
			{			
				$_SESSION['lancheck-edit-file'] = $root_file;
			}
	
			$text .= "</td></tr>";
		}
	
		$text .= "
		</table>
		</form>
		</div>";
	
		$text .= "<form method='post' action='".e_SELF."?tools' id='select_lang'>
		<div style='text-align:center'><br />";
		$text .= (!$writable) ? "<br />".$dir2.$f2.LAN_NOTWRITABLE : "";
		$text .= "<br /><br /><input class='button' type='submit' name='language_sel[{$lan}]' value=\"".LAN_BACK."\" />
		</div></form>";
	
	
		$caption = LAN_CHECK_3." <b>".$dir2.$f2."</b> -> <b>".$lan."</b>";
		$ns -> tablerender($caption, $text);
		require_once(e_ADMIN."footer.php");
		exit;
	
	}
	
	
	
	function fill_phrases_array($data,$type)
	{	
		$retloc = array();
		
		if(preg_match_all('/(\/\*[\s\S]*?\*\/)/i',$data, $multiComment))
		{
			$data = str_replace($multiComment[1],'',$data);	// strip multi-line comments. 	
		}
					
		if(preg_match('/^\s*?setlocale\s*?\(\s*?([\w]+)\s*?,\s*?(.+)\s*?\)\s*?;/im',$data,$locale)) // check for setlocale();
		{
			$retloc[$type][$locale[1]]= $locale[2];	
		}
				
		if(preg_match_all('/^\s*?define\s*?\(\s*?(\'|\")([\w]+)(\'|\")\s*?,\s*?(\'|\")([\s\S]*?)\s*?(\'|\")\s*?\)\s*?;/im',$data,$matches))
		{
			$def = $matches[2];
			$values = $matches[5];	
	
			foreach($def as $k=>$d)
			{
				$retloc[$type][$d]= $values[$k];
			}	
		}
			
		return $retloc;
		
		/*
		echo "<h2>Raw Data ".$type."</h2><pre>";
		echo htmlentities($data);
		echo "</pre>";	
	
		*/
			
	}
	
	
	
	//--------------------------------------------------------------------
	
	
	function is_utf8($str) {
		/*
		* @see http://hsivonen.iki.fi/php-utf8/   validation.php
		*/
		if(strtolower(CHARSET) != "utf-8" || $str == "")
		{
			return TRUE;
		}
	
		return (preg_match('/^.{1}/us',$str,$ar) == 1);
	}
	
	
}

