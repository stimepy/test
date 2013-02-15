<?php
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################	
	
	
/*
 * Class X1File
 * Description: Anything to do with getting, listing, or uploading files in a general context.
 */
class X1File{
	var $X1_IMG_IS='img';
	var $X1_DEM_IS='dem';
	
/*################################################
 	name:ListDirectory
	what does it do: Creates an array strings with Directory names 
	needs:string $path:The path to start listing dirs  string $ext(='php'):
	returns:Returns an array of directories on success and false otherwise.
 ##################################################*/	//list_directory
	function ListDirectory($path, $ext='php'){
		if(empty($path))return false;
		$dir = @opendir($path);
		if($dir){
	    	while($file = readdir($dir)){
	    		$ext = substr(strrchr ($file, "."), 1);
	    		if ($ext==="$ext") $dirArray[] = $file;
	    	}
	        closedir($dir);
	    }
		if(isset($dirArray))return $dirArray;
		return false;
	}

	/*################################################
 	name:X1LoadFile
	what does it do: require once
	needs:string $path:The path and file 
	returns:Returns true on a successful file load and false otherwise(die()).
 	##################################################*/	
	function X1LoadFile($file, $path=""){
		if(empty($file)){
			return false;//error	
		}

		if (require_once($path.$file)){
			return true;
    }
		   	//error
        return false;
    }

    
    /*################################################
 	name:X1LoadMultiFiles
	what does it do: require once
	needs:string $path:The path and file 
	returns:Returns an array of directories on success and false otherwise.
 	##################################################*/	
	function X1LoadMultiFiles($files, $path=""){
		if(empty($files) || !is_array($files)){
			return false;//error	
		}
		$count=0;
		foreach($files as $file){
		//	echo $file;
			if(!X1File::X1LoadFile($file, $path)){
        		return false;
			}
		}
  }
    
    

	/*################################################
 	name:CheckExt
	what does it do: Checks the ext of the file(the last .whatever) 
	needs:string $file:The File who's ext is in question   string $ext(=X_fup_imgext) the valid extions.
	returns:if it's a valid file it returs true otherwise it returns false.
 	##################################################*/
	function CheckExt($file){
		$ext=X1File::GetExt($file);
		if(empty($ext)){
			echo "bub";
			return false;
		}
		$fileext=end(explode(".",$file));
		$isext=array_search($fileext,$ext);
		if(empty($isext)){
			return true;
		}
		return false;
	}	
	
	/*############################################
	name:X1UploadFile
	what does it do: uploads files on success returns true on failure returns false or error.
	needs:string $upload_var:The $_FILES variable needed to retrieve the file from it's temp location, databaseinfo $event(=""),$store_path(=""):nondefault storage path(Admin ONLY)
	returns:true on success, false or error on failure.
	###########################################*/
	function X1UploadFile($upload_var, $event="", $store_path=""){//1 
	 	if(empty($upload_var)){
			return false;
		}
	 	if(!empty($store_path)){
	 	 	if(check_admin()){
				$uploaddir = $store_path;
			}
			else{
			 	return false;
			}
		}
		else{
			if(is_array($event)){
				$folder=$event['sid'];
			}
			else{
				$folder=$event;
			}
			if(!is_dir("uploads/".$folder))
			{
				if(!mkdir('uploads/'.$folder, 666)){
					return DispFunc::X1PluginOutput("XL_teamdisputes_error");
				}
			}
		  	$uploaddir = 'uploads/'.$folder.'/'; 
		}
		
		$dir = opendir($uploaddir);
	  $files = array();
		if(isset($_FILES[$upload_var]) && !empty($_FILES[$upload_var]['name'])){ 
			if(!X1File::checkext($_FILES[$upload_var]['name'])){
				return DispFunc::X1Output("XL_teamdisputes_error");	
			}
		    $_FILES[$upload_var]['name'] = $_FILES[$upload_var]['name'].'_'.X1Misc::X1PluginRandid().''.strrchr($_FILES[$upload_var]['name'], '.');
		    $uploadfile = $uploaddir . basename($_FILES[$upload_var]['name']);
			if(!move_uploaded_file($_FILES[$upload_var]['tmp_name'], $uploadfile)) {
				closedir($dir);
				return false;
		    }
			else {
				closedir($dir);
				return "1::/".$uploaddir.$_FILES[$upload_var]['name'];
		    }
		}
		closedir($dir);
		return false;
	}
	
	/*############################################
	name:GetExt  (PRIVATE)
	what does it do: returns the acceptable file extentions to be allowed for upload.
	needs:string $type
	returns:the extentions on success, false on error or failure.
	###########################################*/	
	private function GetExt($file){
		if(!isset($file)){
			return;//error
		}
		
		$type=X1File::DetermineMime($file);

		switch($type){
			case $X1_IMG_IS:
				return explode("::",X1_fup_imgext);
				break;
			case $X1_DEM_IS:
				return explode("::",X1_fup_demoext);
				break;
			default://reserved
				return false;
				break;
		}
		return false; //error
	}
	
	/*############################################
	name:DetermineMime  (PRIVATE)
	what does it do: returns the type of mime the file is.
	needs:string $file
	returns:The type of file on success, false on error or failure.
	###########################################*/	
	private function DetermineMime($file){
		if(!isset($file)){
			return false;
		}
		if(function_exists("finfo_open")){ //Since finfo is 5.3.0 and not all servers are 5.3.0 must check to see if it exsiste,
			if(!defined(FILEINFO_MIME_TYPE)){//Since it was introduced in 5.3.0
				define("FILEINFO_MIME_TYPE",16);
			}
			$file_info=finfo_open(FILEINFO_MIME_TYPE);
			$file_mime=finfo_file($file_info, $file);
			finfo_close($file_info);
		}
		else{
			//since this is deprecated this is only until 5.3.0 becomes more used.
				$file_mime=mime_content_type($file);
			
		}
		$mime=explode('/',$file_mime);
		if(array_search("image",$mime)){
			return $X1_IMG_IS;
		}
		elseif(array_search("application",$mime)){
			return $X1_DEMO_IS;
		}
		else{
			//reserved
			return false;
		}
		return false;//error
	}	
		
	
	function X1GetFile($id){
		set_time_limit(0);

		if (!isset($id) || empty($id)) {
		  die("Please specify a file.");
		}
		
		$history = SqlGetRow('laddername ,demo',X1_DB_teamhistory, "Where id=".MakeItemString($id));
		$file=$history['demo'];
		
		$file_part=substr($file, 0, 1);
		if($file_part==0){
			$file_part=substr($file, 3);
			Redirect($file_part);
			return true;
		}/*
		elseif($file_part==1){
			$folder=$history['laddername'];
			if(!is_dir("uploads/".$folder))
			{
				if(!mkdir('uploads/'.$folder, 666)){
					return DispFunc::X1PluginOutput("XL_teamdisputes_error");
				}
			}
		  	$uploaddir = 'uploads/'.$folder.'/'; 
		}
		
			// Get real file name.
			// Remove any path info to avoid hacking by adding relative path, etc.
			$fname = basename($file_part=substr($file, 3));
			// get full file path (including subfolders)
			if (file_exists(X1_plugpath.'/'.$fname)) {
			  $file_path = $dirname.'/'.$fname;
			  return;
			}
			
			find_file(X1_plugpath, $fname, $file_path);

			if (!is_file($file_path)) {
			  die("File does not exist. Make sure you specified correct file name."); 
			}

			// file size in bytes
			$fsize = filesize($file_path); 

			// file extension
			$fext = strtolower(substr(strrchr($fname,"."),1));

			// check if allowed extension
			if (!array_key_exists($fext, $allowed_ext)) {
			  die("Not allowed file type."); 
			}

			// get mime type
			if ($allowed_ext[$fext] == '') {
			  $mtype = '';
			  // mime type is not set, get from server settings
			  if (function_exists('mime_content_type')) {
				$mtype = mime_content_type($file_path);
			  }
			  else if (function_exists('finfo_file')) {
				$finfo = finfo_open(FILEINFO_MIME); // return mime type
				$mtype = finfo_file($finfo, $file_path);
				finfo_close($finfo);  
			  }
			  if ($mtype == '') {
				$mtype = "application/force-download";
			  }
			}
			else {
			  // get mime type defined by admin
			  $mtype = $allowed_ext[$fext];
			}

			// Browser will try to save file with this filename, regardless original filename.
			// You can override it if needed.

			if (!isset($_GET['fc']) || empty($_GET['fc'])) {
			  $asfname = $fname;
			}
			else {
			  // remove some bad chars
			  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
			  if ($asfname === '') $asfname = 'NoName';
			}

			// set headers
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Type: $mtype");
			header("Content-Disposition: attachment; filename=\"$asfname\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $fsize);

			// download
			// @readfile($file_path);
			$file = @fopen($file_path,"rb");
			if ($file) {
			  while(!feof($file)) {
				print(fread($file, 1024*8));
				flush();
				if (connection_status()!=0) {
				  @fclose($file);
				  die();
				}
			  }
			  @fclose($file);
			}

			// log downloads
			if (!LOG_DOWNLOADS) die();

			$f = @fopen(LOG_FILE, 'a+');
			if ($f) {
			  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
			  @fclose($f);
			}*/
}	
	
	
	
	
// Check if the file exists
// Check in subfolders too
function find_file ($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);

  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file($dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} // find_file	
	
	
}//class end
?>