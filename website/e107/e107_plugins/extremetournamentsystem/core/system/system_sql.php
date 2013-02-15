<?php
###############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Kris Sherrerd 2008-2010
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
###############################################################


/*###################################
Function:MakeItemString
Needs:String $item,
Returns:
What does it do: Takes item and using the adodb system makes it a string.
####################################*/
function MakeItemString($item){
	global $xdb;
	return $xdb->qstr($item);
}

/*###################################
Function:GetTotalCountof
Needs:String $item, String $databasename, String $whereinfo
Returns:int of the number of rows in the selected database
What does it do: Calls count on the selected table getting a total number of rows in a table.
####################################*/
function GetTotalCountOf($item, $databasename,$whereinfo=""){
	global $xdb;
	$count=$xdb->GetRow("SELECT count($item) FROM ".X1_prefix."$databasename $whereinfo");	
//	echo 	"SELECT count($item) FROM ".X1_prefix."$databasename $whereinfo";
	return $count[0];
}

/*###################################
Function:ModifySql
Needs:string $option, string $table, string $info
Returns:bool
What does it do:The option is what needs to happen to the database including update, insert into, delete from, (more to come?)
, and using the table provided, does the action, using info as the data to modify, find to delete or insert.
####################################*/
function ModifySql($option, $table, $info){
	global $xdb;
//	echo "$option ".X1_prefix."$table $info";
	$result = $xdb->Execute("$option ".X1_prefix."$table $info");
	if($result){
		return true;
	}
	return false;
}

/*####################################
 * Name: SqlGetRow
 * Needs:string, $item, string $table, string $whereinfo, string sortinfo=""
 * returns:array $databaseinfo
 * What does it do:Returns the database info as rows in an array
 *###################################*/
function SqlGetRow($item,$table,$whereinfo="", $sortinfo=""){
	global $xdb;
	//echo "SELECT $item FROM ".X1_prefix."$table $whereinfo $sortinfo";
	return $xdb->GetRow("SELECT $item FROM ".X1_prefix."$table $whereinfo $sortinfo");
}

/*####################################
 * Name: SqlGetAll
 * Needs:string, $item, string $table, string $whereinfo, string sortinfo=""
 * returns:array $databaseinfo
 * What does it do:Returns the database info as arrays of arrays
 *###################################*/
function SqlGetAll($item,$table,$whereinfo="", $sortinfo=""){
	global $xdb;
	//echo "SELECT $item FROM ".X1_prefix."$table $whereinfo $sortinfo <br />";
	return $xdb->GetAll("SELECT $item FROM ".X1_prefix."$table $whereinfo $sortinfo");
}

/*####################################
 * Name: GetAffectedRows
 * Needs:N/A
 * returns:int affectedrows
 * What does it do:Returns the number of rows that were affected by the last db call.
 *###################################*/
function GetAffectedRows(){
	global $xdb;
	return $xdb->Affected_Rows();
}

/*####################################
 * Name: SqlGetRowPre
 * Needs:string, $item, string $table, string $whereinfo, string sortinfo=""
 * returns:array $databaseinfo
 * What does it do:Returns the database info as rows in an array
 *###################################*/

function SqlGetRowPre($item,$table,$whereinfo="", $sortinfo=""){
	global $xdb;
	return $xdb->GetRow("SELECT $item FROM $table $whereinfo $sortinfo");
}

/*####################################
 * Name: SqlGetAllPre
 * Needs:string, $item, string $table, string $whereinfo, string sortinfo=""
 * returns:array $databaseinfo
 * What does it do:Returns the database info as arrays of arrays
 *###################################*/
function SqlGetAllpre($item,$table,$whereinfo="", $sortinfo=""){
	global $xdb;
	return $xdb->GetAll("SELECT $item FROM $table $whereinfo $sortinfo");
}

?>
