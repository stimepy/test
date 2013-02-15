<?php
/*
+---------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_handlers/mysql_class.php $
|     $Revision: 12047 $
|     $Id: mysql_class.php 12047 2011-01-16 22:07:13Z e107coders $
|     $Author: e107coders $
|
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$db_time = 0.0;				// Global total time spent in all db object queries

/**
 * MySQL Abstraction class
 *
 * @package e107
 * @version $Revision: 1 $
 * @original_author $Author: e107coders $
 * @modifiedby $Modified_by: Angelofdoom $
 * #modified $date: 11/19/2012
 */
class db {

    private $db_mySQLQueryCount = 0;
 /*   var $mySQLserver;
    var $mySQLuser;
    var $mySQLpassword;*/
    private $mySQLdefaultdb;
    private $mySQLaccess;
    private $mySQLresult;
    private $mySQLrows;
    private $mySQLerrorreport;
    private $mySQLcurTable;
    private $mySQLlanguage;
    private $mySQLcharset;
    private $mySQLtablelist;
    private $mySQLmtablelist = false;
    private $mySQLinsertid;
    private $mySQLQueryopen = false;


    /**
     * db constructor gets language options from the cookie or session
     * @access public
     * @return void
     */
   public function __construct()
    {
        global $pref, $eTraffic;
        $eTraffic->BumpWho('Create db object', 1);
        if (!isset($_SESSION['e_language']))
        {
            return;
        }

        $this->mySQLlanguage = ($_SESSION['e_language'] != $pref['sitelanguage']) ? $_SESSION['e_language'] : '';
        print_r($this->mySQLlanguage);
        die();

    }

    /**
     * @access public
     * Connects to mySQL server and selects database - generally not required if your table is in the main DB.<br />
     * <br />
     * Example using e107 database with variables defined in e107_config.php:<br />
     * <code>$sql = new db;
     * $sql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);</code>
     * <br />
     * OR to connect an other database:<br />
     * <code>$sql = new db;
     * $sql->db_Connect('url_server_database', 'user_database', 'password_database', 'name_of_database');</code>
     *
     * @param string $mySQLserver IP Or hostname of the MySQL server
     * @param string $mySQLuser MySQL username
     * @param string $mySQLpassword MySQL Password
     * @param string $mySQLdefaultdb The database schema to connect to
     * @return null|string error code
     */
    public function db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb){
        global $eTraffic;
        if(empty($mySQLserver) && empty($mySQLuser) && empty($mySQLpassword) && empty($mySQLdefaultdb)){
            message_handler('CRITICAL_ERROR', '', 91, 'mysqli_class.php');
        }
        $eTraffic->BumpWho('db Connect', 1);
        $this->mySQLdefaultdb = $mySQLdefaultdb;
        /*  No reason to save info....
        $this->mySQLserver = $mySQLserver;
        $this->mySQLuser = $mySQLuser;
        $this->mySQLpassword = $mySQLpassword;

        */
        /*
            $temp = $this->mySQLerror;
        */
        if(!isset($this->mySQLerrorreport)){
            $this->mySQLerrorreport = false;
        }
        if(defined("USE_PERSISTANT_DB") && USE_PERSISTANT_DB == TRUE){
            //Use a persistant database connection.
            $this->mySQLaccess = new mysqli( "p:".$mySQLserver, $mySQLuser, $mySQLpassword,$mySQLdefaultdb );
            if($this->connection_error()){return false;}
            /*else
            {
                if ( ! @mysql_select_db($this->mySQLdefaultdb, $this->mySQLaccess))
                {
                    return 'e2';// need to locate e2;
                }
                else
                {
                    $this->dbError('dbConnect/SelectDB');
                }
            }*/
        }
        else
        {  // Non persistent
            $this->mySQLaccess = new mysqli( $mySQLserver, $mySQLuser, $mySQLpassword,$mySQLdefaultdb );
            if ( connection_error()){return false;}
        }
        // Set utf8 connection?
        //@TODO: simplify when yet undiscovered side-effects will be fixed
        $this->mySQLaccess->set_charset("utf8");
    }


    /**
     * @return bool
     * @access private
     * @desc If the connection is not made, return true, else return false
     */
    private function connection_error(){
        if ( ! $this->mySQLaccess->connect_errno){
            $this->mySQLerrorreport = true;
            message_handler("CRITICAL_ERROR", "Datbase message:".$this->mySQLaccess->connect_error.""
                .$this->mySQLaccess->connect_errno,112, "mysqli_class.php");
            return true;
        }
        return false;
    }


    /**
     * @return void
     * @param unknown $sMarker
     * @access public
     * @desc Simple function used to mark time IF debug level is > 0
     *
     */
    public function db_Mark_Time($sMarker) {
        if (E107_DEBUG_LEVEL > 0) {
            global $db_debug;
            $db_debug->Mark_Time($sMarker);
        }
    }

    /**
     * @return void
     * @access private
     * @desc calls the debug preformance.
     * @deprecated killed 11/20/2012
     */

    private function db_Show_Performance() {
    //    return $db_debug->Show_Performance();
    }

    /**
     * @return void
     * @access private
     * @desc add query to dblog table
     */
    private function db_Write_log($log_type = '', $log_remark = '', $log_query = '') {
        global $tp, $e107;
        $d = time();
        $uid = (USER) ? USERID : '0';
        $ip = $e107->getip();
        $qry = $tp->toDB($log_query);
        $this->db_Insert('dblog', "0, '{$log_type}', {$d}, {$uid}, '{$ip}', '{$qry}', '{$log_remark}'");
    }

     /**
     * @param string $query
     * @param null $rli
     * @param string $qry_from
     * @param bool $debug
     * @param string $log_type
     * @param string $log_remark
     * @return resource
     * @access public     todo: make it private!
     * @desc This is the 'core' routine which handles much of the interface between other functions and the DB
     */
    function db_Query($query, $rli = NULL, $qry_from = '', $debug = FALSE, $log_type = '', $log_remark = '') {
        global $db_time,$queryinfo, $eTraffic;

        if($this->mySQLQueryopen==true){
            $this->freeQuery();
        }
        $this->mySQLQueryopen==true;
        $this->db_mySQLQueryCount++;

        if ($debug == 'now') {
            echo "** $query";
        }
        if ($debug !== FALSE || strstr(e_QUERY, 'showsql')) //debug not equivalent to false, or e_QUERY=showsql
        {
            $queryinfo[] = "<b>{$qry_from}</b>: $query";
        }
        if ($log_type != '') {
            $this->db_Write_log($log_type, $log_remark, $query);
        }

        $this->checkConnection();

        $b = microtime();
        $sQryRes =  $this->mySQLaccess->query($query);// : @mysql_query($query, $rli); //todo: Get rid of $rli
        $e = microtime();

        $eTraffic->Bump('db_Query', $b, $e);
        $mytime = $eTraffic->TimeDelta($b,$e);
        $db_time += $mytime;
        $this->mySQLresult = $sQryRes;
        if (E107_DEBUG_LEVEL) {
            global $db_debug;
            $aTrace = debug_backtrace();
            $pTable = $this->mySQLcurTable;
            if (!strlen($pTable)) {
                $pTable = '(complex query)';
            } else {
                $this->mySQLcurTable = ''; // clear before next query
            }
            if(is_object($db_debug)) {
                $buglink = $this->mySQLaccess; //is_null($rli) ? $this->mySQLaccess : $rli;
                $nFields = $db_debug->Mark_Query($query, $buglink, $sQryRes, $aTrace, $mytime, $pTable);
            } else {
                message_handler('ALERT', "what happened to db_debug??!!<br />",237,'mysqli_class.php'); ;
            }
        }
        return $sQryRes;
    }

    /**
     * @return int Number of rows or false on error
     *
     * @param string $table Table name to select data from
     * @param string $fields Table fields to be retrieved, default * (all in table)
     * @param string $arg Query arguments, default null
     * @param string $mode Argument has WHERE or not, default=default (WHERE)
     *
     * @param bool $debug Debug mode on or off
     *
     * @desc Perform a mysql_query() using the arguments suplied by calling db::db_Query()<br />
     * <br />
     * If you need more requests think to call the class.<br />
     * <br />
     * Example using a unique connection to database:<br />
     * <code>$sql->db_Select("comments", "*", "comment_item_id = '$id' AND comment_type = '1' ORDER BY comment_datestamp");</code><br />
     * <br />
     * OR as second connection:<br />
     * <code>$sql2 = new db;
     * $sql2->db_Select("chatbox", "*", "ORDER BY cb_datestamp DESC LIMIT $from, ".$view, 'no_where');</code>
     *
     * @access public
     */
    function db_Select($table, $fields = '*', $arg = '', $mode = 'default', $debug = FALSE, $log_type = '', $log_remark = '') {
        $this->mySQLcurTable = $this->db_IsLang($table);
        if ($arg != '' && $mode == 'default')
        {
            $query = "SELECT {$fields} FROM ".MPREFIX."{$table} WHERE {$arg}";
        }
        elseif ($arg != '' && $mode != 'default') {
            $query = "SELECT {$fields} FROM ".MPREFIX."{$table} {$arg}";
        }
        else {
            $query = "SELECT {$fields} FROM ".MPREFIX."{$table}";
        }

        if ($this->mySQLresult = $this->db_Query($query, NULL, 'db_Select', $debug, $log_type, $log_remark)) {
            $this->dbError('dbQuery');
            return $this->db_Rows();
        }
        else {
            $this->dbError("db_Select ({$query})");
            return FALSE;
        }
    }

    /**
     * @return int Last insert ID or false on error
     * @param string $table
     * @param string $arg
     * @param string $debug
     * @desc Insert a row into the table<br />
     * <br />
     * Example:<br />
     * <code>$sql->db_Insert("links", "0, 'News', 'news.php', '', '', 1, 0, 0, 0");</code>
     *
     * @access public
     */
    function db_Insert($table, $arg, $debug = FALSE, $log_type = '', $log_remark = '') {
        $this->mySQLcurTable = $this->db_IsLang($table);

        if(is_array($arg))
        {
            $keyList= "`".implode("`,`", array_keys($arg))."`";
            $valList= "'".implode("','", $arg)."'";
            $valList = str_replace(",'NULL'",",NULL",$valList); // Handle NULL correctly.
            $query = "INSERT INTO `".MPREFIX."{$table}` ({$keyList}) VALUES ({$valList})";
        }
        else
        {
            $query = 'INSERT INTO '.MPREFIX."{$table} VALUES ({$arg})";

        }

        $this->checkConnection();

        if ($result = $this->mySQLresult = $this->db_Query($query, NULL, 'db_Insert', $debug, $log_type, $log_remark )) {
            $this->mySQLinsertid = $this->mySQLaccess->insert_id;
            $this->dbError("db_Insert");
            return ($this->mySQLinsertid) ? $this->mySQLinsertid : TRUE; // return true even if table doesn't have auto-increment.
        }
        else {
            $this->dbError("db_Insert ($query)");
            return FALSE;
        }
    }

    /**
     * @return int number of affected rows, or false on error
     * @param string $table
     * @param string $arg
     * @param bool $debug
     * @desc Update fields in ONE table of the database corresponding to your $arg variable<br />
     * <br />
     * Think to call it if you need to do an update while retrieving data.<br />
     * <br />
     * Example using a unique connection to database:<br />
     * <code>$sql->db_Update("user", "user_viewed='$u_new' WHERE user_id='".USERID."' ");</code>
     * <br />
     * OR as second connection<br />
     * <code>$sql2 = new db;
     * $sql2->db_Update("user", "user_viewed = '$u_new' WHERE user_id = '".USERID."' ");</code><br />
     *
     * @access public
     */
    function db_Update($table, $arg, $debug = FALSE, $log_type = '', $log_remark = '') {
        $this->mySQLcurTable = $this->db_IsLang($table);

        $this->checkConnection();

        $query = 'UPDATE '.MPREFIX.$table.' SET '.$arg;
        if ($this->mySQLresult = $this->db_Query($query, NULL, 'db_Update', $debug, $log_type, $log_remark)) {
            $result = $this->mySQLrows = $this->mySQLaccess->affected_rows;
            $this->dbError("db_Update");
            return ($result == -1)? FALSE : $result;	// Error return from mysql_affected_rows
        }
        else {
            $this->dbError("db_Update ($query)");
            return FALSE;
        }
    }

    /**
     * @return array MySQL row
     * @param string $mode
     * @desc Fetch an array containing row data (see PHP's mysql_fetch_array() docs)<br />
     * <br />
     * Example :<br />
     * <code>while($row = $sql->db_Fetch()){
     *  $text .= $row['username'];
     * }</code>
     *
     * @access public
     */
    function db_Fetch($type = MYSQL_BOTH) {
        global $eTraffic;
        if (!(is_int($type))) {
            $type=MYSQL_BOTH;
        }
        $b = microtime();
        $row = $this->mySQLresult->fetch_array($type);
        $eTraffic->Bump('db_Fetch', $b);
        if ($row) {
            $this->dbError('db_Fetch');
            return $row;
        }
        else {
            $this->dbError('db_Fetch');
            return FALSE;
        }
    }

    /**
     * @return int number of affected rows or false on error
     * @param string $table
     * @param string $fields
     * @param string $arg
     * @desc Count the number of rows in a select<br />
     * <br />
     * Example:<br />
     * <code>$topics = $sql->db_Count("forum_t", "(*)", " WHERE thread_forum_id='".$forum_id."' AND thread_parent='0' ");</code>
     *
     * @access public
     */
    function db_Count($table, $fields = '(*)', $arg = '', $debug = FALSE, $log_type = '', $log_remark = '') {
        $table = $this->db_IsLang($table);

        if ($fields == 'generic') {
            $query=$table;
            $this->db_Write_log('Generic count', 'db_count called with fields of generic', $query);
            /*if ($this->mySQLresult = $this->db_Query($query, NULL, 'db_Count', $debug, $log_type, $log_remark)) {
                $rows = $this->db_Fetch();
                return $rows['COUNT(*)'];
            } else {
                $this->dbError("dbCount ($query)");
                return FALSE;
            }*/
        }

        $this->mySQLcurTable = $table;
        $query='SELECT COUNT'.$fields.' FROM '.MPREFIX.$table.' '.$arg;
        if ( $this->db_Query($query, NULL, 'db_Count', $debug, $log_type, $log_remark)) {
            $rows = $this->db_Fetch();  // @mysql_fetch_array($this->mySQLresult);
            $this->mySQLrows = $rows[0];
            return $rows[0];
        } else {
            $this->dbError("dbCount ($query)");
            return FALSE;
        }
    }

    /**
     * @return void
     * @desc Closes the mySQL server connection.<br />
     * <br />
     * Only required if you open a second connection.<br />
     * Native e107 connection is closed in the footer.php file<br />
     * <br />
     * Example :<br />
     * <code>$sql->db_Close();</code>
     *
     * @access public
     */
    function db_Close() {
        global $eTraffic;
        $this->checkConnection();
        $eTraffic->BumpWho('db Close', 1);
        $this->mySQLaccess->close();
   //     = NULL; // correct way to do it when using shared links.
        $this->dbError('dbClose');
    }

    /**
     * @return int number of affected rows, or false on error
     * @param string $table
     * @param string $arg
     * @desc Delete rows from a table<br />
     * <br />
     * Example:
     * <code>$sql->db_Delete("tmp", "tmp_ip='$ip'");</code><br />
     * <br />
     * @access public
     */
    function db_Delete($table, $arg = '', $debug = FALSE, $log_type = '', $log_remark = '') {
        $this->mySQLcurTable = $this->db_IsLang($table);

        $this->checkConnection();
        if (!$arg) {
            $query = 'DELETE FROM '.MPREFIX.$table;
         }
        else {
            $query = 'DELETE FROM '.MPREFIX.$table.' WHERE '.$arg;
        }

        if ($result = $this->db_Query($query, NULL, 'db_Delete', $debug, $log_type, $log_remark)) {
            return $this->db_Rows();
        }
        else {
            $this->dbError('db_Delete ('.$arg.')');
            return FALSE;
        }
    }

    /**
     * @return int or bool
     * @access public
     * @desc sets the number of rows that have been affected.
     *
     *
     */
    function db_Rows() {
        $this->mySQLrows = $this->mySQLresult->num_rows;
        return $this->mySQLrows;
    }

    /**
     * @return unknown
     * @param unknown $from
     * @desc figures out if an error has happened, then send error info to message handler returning the error.
     * @access private
     */
    private function dbError($from) {
        if ($this->getSqlErrorno()) {
            if ($this->mySQLerrorreport == TRUE) {
                message_handler('ADMIN_MESSAGE', '<b>mySQL Error!</b> Function: '.$from.'. ['.$this->mySQLaccess->errno.' - '.$this->mySQLaccess->error.']', __LINE__, __FILE__);
                return $this->mySQLaccess->error;
            }
        }
    }

    /**
     * @return void
     * @param bool $mode
     * @desc Enter description here...
     * @access public
     */
    function db_SetErrorReporting($mode) {
        $this->mySQLerrorreport = $mode;
    }


    /**
     * @return unknown
     * @param string $arg
     * @desc Generate sql queries
     * @access public
     */
    function db_Select_gen($query, $debug = FALSE, $log_type = '', $log_remark = '')
    {
        /*
          changes by jalist 19/01/05:
          added string replace on table prefix to tidy up long database queries
          usage: instead of sending "SELECT * FROM ".MPREFIX."table", do "SELECT * FROM #table"
          Returns result compatible with mysql_query - may be TRUE for some results, resource ID for others
          */

        $this->tabset = FALSE;
        //todo get rid of `#table in queries replace with #table
        if(strpos($query,'`#') !== FALSE)
        {
            $query = preg_replace_callback("/\s`#([\w]*?)`\W/", array($this, 'ml_check'), $query);
        }
        elseif(strpos($query,'#') !== FALSE)
        {
            $query = preg_replace_callback("/\s#([\w]*?)\W/", array($this, 'ml_check'), $query);
        }

        if ($this->db_Query($query, NULL, 'db_Select_gen', $debug, $log_type, $log_remark)){
            $this->dbError('db_Select_gen');
            if ($rows = $this->db_Rows()){
                return $rows;
            }
            return TRUE;
        }
        else{	// Failed query
            $this->dbError('dbQuery ('.$query.')');
            return FALSE;
        }
    }

    /**
     * @param array $matches
     * @return string
     */
    function ml_check($matches)
    {
        $table = $this->db_IsLang($matches[1]);
        if($this->tabset == false)
        {
            $this->mySQLcurTable = $table;
            $this->tabset = true;
        }
        return ' `'.MPREFIX.$table.'`'.substr($matches[0],-1);
    }


    /**
     * @return unknown
     * @param string $table
     * @param bool $multiple
     * @desc verifies the table exists before going after it.
     * @access private
     */
    private function db_IsLang($table,$multiple=FALSE) {
        global $pref;
        if ((!$this->mySQLlanguage || !$pref['multilanguage']) && $multiple==FALSE) {
            return $table;
        }

        /*if (!$this->mySQLtablelist) {//No list of tables, get a list of tables.
            $this->checkConnection();
            $tablist = $this->db_QueryFreeForm("SHOW TABLES FROM {$this->mySQLdefaultdb}");
            while (list($temp) = $tablist->fetch_array()) {
                $this->mySQLtablelist[] = $temp;
            }
        }*/
        $this->getTablelist();

        // ---- Find all multi-language tables.

        if($multiple == TRUE){ // return an array of all matching language tables. eg [french]->e107_lan_news
            if($this->mySQLmtablelist == false){
                if(!is_array($table)){
                    $table = array($table);
                }
                $lanlist = array();

                foreach($this->mySQLtablelist as $tab){
                    if(stristr($tab, MPREFIX."lan_") !== FALSE){
                        $tmp = explode("_",str_replace(MPREFIX."lan_","",$tab));
                        $lng = $tmp[0];
                        foreach($table as $t){
                            if(preg_match('/'.$t.'$/',$tab)){
                                $lanlist[$lng][MPREFIX.$t] = $tab;
                            }
                        }
                    }
                }
                $this->mySQLmtablelist = ($lanlist) ? $lanlist : -1;
            }

            return ($this->mySQLmtablelist == -1)? false : $this->mySQLmtablelist;
        }
        // -------------------------
        $mltable = "lan_".strtolower($this->mySQLlanguage.'_'.$table);
        if (in_array(MPREFIX.$mltable, $this->mySQLtablelist)) {
            return $mltable;
        }
        return $table;
    }

    /**
     * @return array
     * @param string fields to retrieve
     * @desc returns fields as structured array
     * @access public
     */
    function db_getList($fields = 'ALL', $amount = FALSE, $maximum = FALSE, $ordermode=FALSE) {
        $list = array();
        $counter = 1;
        while ($row = $this->db_Fetch()) {
            foreach($row as $key => $value) {
                if (is_string($key)) {
                    if (strtoupper($fields) == 'ALL' || in_array ($key, $fields)) {

                        if(!$ordermode)
                        {
                            $list[$counter][$key] = $value;
                        }
                        else
                        {
                            $list[$row[$ordermode]][$key] = $value;
                        }
                    }
                }
            }
            if ($amount && $amount == $counter || ($maximum && $counter > $maximum)) {
                break;
            }
            $counter++;
        }
        return $list;
    }

    /**
     * @return integer
     * @desc returns total number of queries made so far
     * @access public
     */
    function db_QueryCount() {
        return $this->db_mySQLQueryCount;
    }


    /*
    	Multi-language Query Function.
	*/
    /**
     * @param string $query
     * @param string $debug
     * @return bool
     * @access public
     */
    //todo: Installs plugins, look into more later.
    function db_Query_all($query,$debug=""){
        $error = "";

        $query = str_replace("#",MPREFIX,$query);

        if(!$this->db_Query($query)){  // run query on the default language first.
            $error .= $query. " failed";
        }

        $tmp = explode(" ",$query);
        foreach($tmp as $val){
            if(strpos($val,MPREFIX) !== FALSE){
                $table[] = str_replace(MPREFIX,"",$val);
                $search[] = $val;
            }
        }

        // Loop thru relevant language tables and replace each tablename within the query.
        if($tablist = $this->db_IsLang($table,TRUE)){  // array(boobs=>pre_boobs, butt=>pre_butt)
            foreach($tablist as $key=>$tab){
                $querylan = $query;  //sel * from pre_boobs
                foreach($search as $find){
                    $lang = $key;
                    $replace = ($tab[$find] !="") ? $tab[$find] : $find;
                    $querylan = str_replace($find,$replace,$querylan);
                }

                if(!$this->db_Query($querylan)){ // run query on other language tables.
                    $error .= $querylan." failed for language";
                }
                if($debug){ echo "<br />** lang= ".$querylan; }
            }
        }


        return ($error)? FALSE : TRUE;
    }





    /**
     *	Return a list of the field names in a table.
     *
     *	@param string $table - table name (no prefix)
     *	@param string $prefix - table prefix to apply. If empty, MPREFIX is used.
     *	@param boolean $retinfo = FALSE - just returns array of field names. TRUE - returns all field info
     *	@return array|boolean - FALSE on error, field list array on success
     *  @access Public
     */
    public function db_FieldList($table, $prefix = MPREFIX, $retinfo = FALSE){
        $this->checkConnection();

        if (FALSE === ($this->db_Query('SHOW COLUMNS FROM '.$prefix.$table))){
            return FALSE;		// Error return
        }
        $ret = array();
        if ($this->db_Rows() > 0){
            while ($row = $this->db_Fetch()){
                if ($retinfo)
                {
                    $ret[$row['Field']] = $row['Field'];
                }
                else
                {
                    $ret[] = $row['Field'];
                }
            }
        }
        return $ret;
    }


    /**
     *	@desc Determines if a plugin field (and key) exist. OR if fieldid is numeric - return the field name in that position.
     *
     *	@param string $table - table name (no prefix)
     *	@param string $fieldid - Numeric offset or field/key name
     *	@param string $key - PRIMARY|INDEX|UNIQUE - type of key when searching for key name
     *	@return string|boolean - FALSE on error, field name on success, TRUE if key exists
     *
     * Deprecated
     */
    function db_Field($table,$fieldid="",$key="")
    {/*
        $convert = array("PRIMARY"=>"PRI","INDEX"=>"MUL","UNIQUE"=>"UNI");
        $key = ($convert[$key]) ? $convert[$key] : "OFF";

        $this->checkConnection();

        $result = $this->db_Query("SHOW COLUMNS FROM ".MPREFIX.$table);
        if ($this->db_Rows() > 0) {
            $c=0;
            while ($row = $this->db_Fetch()) {
                if(is_numeric($fieldid))
                {
                    if($c == $fieldid)
                    {
                        return $row['Field']; // field number matches.
                    }
                }
                else
                {
                    if(($key == "OFF") && ($fieldid == $row['Field']))
                    {
                        return TRUE;  // key not in use, but field matches.
                    }
                    elseif(($fieldid == $row['Field']) && $key == $row['Key'])
                    {
                        return TRUE;
                    }

                }
                $c++;
            }
        }
        return FALSE;  */
    }

    /**
     * A pointer to mysql_real_escape_string() - see http://www.php.net/mysql_real_escape_string
     *
     * @param string $data
     * @return string
     * @access public
     */
    public function escape($data, $strip = true)
    {
        if ($strip)
        {
            $data = strip_if_magic($data);
        }

        $this->checkConnection();

        return  $this->mySQLaccess->escape_string($data);
    }

    /**
     * Check if MySQL version is utf8 compatible and may be used as it accordingly to the user choice
     *
     * @access public
     * @param string    MySQL charset may be forced in special occasion.
     *                  UTF-8 encoding and decoding is left to the progammer
     * @param bool      TRUE enter debug mode. default FALSE
     * @return string   hardcoded error message
     */
    function db_Set_Charset($charset = '', $debug = FALSE)
    {
        // Get the default user choice
        if (varset($this->mySQLcharset) != 'utf8')
        {
            // Only utf8 is accepted
            $this->mySQLcharset = '';
        }
        $charset = ($charset ? $charset : $this->mySQLcharset);
        $message = (( ! $charset && $debug) ? 'Empty charset!' : '');
        if($charset)
        {
            if ( ! $debug)
            {
                $this->db_QueryFreeForm("SET NAMES `$charset`");
            }
            else
            {
                // Check if MySQL version is utf8 compatible
                preg_match('/^(.*?)($|-)/', $this->mySQLaccess->server_info, $mysql_version);
                if (version_compare($mysql_version[1], '4.1.2', '<'))
                {
                    // reset utf8
                    $message      = 'MySQL version is not utf8 compatible!';
                }
                else
                {
                    // Use db_Query() debug handler
                    $this->db_Query("SET NAMES `$charset`", NULL, '', $debug);
                }
            }
        }

        // Save mySQLcharset for further uses within this connection
        $this->mySQLcharset = $charset;
        return $message;
    }

    /**
     * @return int Last insert ID or false on error
     * @param string $table
     * @param string $arg
     * @param string $debug
     * @desc Insert a row into the table<br />
     * <br />
     * Example:<br />
     * <code>$sql->db_Insert("links", "0, 'News', 'news.php', '', '', 1, 0, 0, 0");</code>
     *
     * @access public
     */
    public function sql_nextid()
    {
       return ($this->mySQLresult) ? $this->mySQLaccess->insert_id : false;
    }


    /**
     * @desc returns the actual SQL database pointer.
     * @return mixed
     *
     * @access Public
     */
    public function get_sqlstuff(){
        if (!getperms("P")){ //Must be an administrator inorder to call this function;
            die ("Access Denied");
        }
        return $this->mySQLaccess;
    }

    /**
     * @name db_QueryFreeForm;
     * @return mixed
     * @param $query
     * @param $other
     *
     * @access public
     *
     * for queries that require a more then a regular ddl or dml.
     */
    function db_QueryFreeForm($query, $other=''){
        if(!isset($query)){
            message_handler("CRITICIAL_ERROR", 'Empty Query');
        }
        $words = explode(' ', $query);

        switch(tolower($words[1])){
            case 'explain':
                $answer=$this->mySQLaccess->db_query($query);
                if(isset($other)){
                    switch(tolower($other)){
                        case 'fieldcount':
                            return $this->mySQLaccess->field_count;
                            break;
                    }
                }
                break;
            case 'show':
            case 'set':
            case 'create':
            case 'alter':
            case 'drop':
            case 'update':
            case 'delete':
                $answer = $this->mySQLaccess->db_query($query);
                break;
            case 'fetch_a':
                $query = stri_replace('FREE', '', $query);
                $answer = $query->fetch_assoc();
                break;
        }
        return $answer;
    }

    /**
     * @desc Checks to see if the connection variable is still there.
     */
    function checkConnection(){
        if(!$this->mySQLaccess){
            message_handler('CRITICAL_ERROR', 'Datbase not connected!',215,'mysqli_class.php');
            /*   global $db_ConnectionID;
          $this->mySQLaccess = $db_ConnectionID;*/
        }
    }

    /**
     * @desc Frees memory from query result
     * @access public;
     * @return bool;
     */

    public function freeQuery(){
        if($this->mySQLQueryopen){
            $this->mySQLresult->free();
            $this->mySQLresult = NULL;
            $this->mySQLrows = 0;
            $this->mySQLQueryopen = false;
            return true;
        }
        return false;
    }

    /**
     * @desc Gets the list of tables.
     * @return array
     */
    public function getTablelist(){
       if($this->mySQLtablelist){
           return $this->mySQLtablelist;
       }
       $this->checkConnection();
       $this->db_QueryFreeForm("SHOW TABLES FROM {$this->mySQLdefaultdb}");
       while (list($temp) = $this->db_Fetch()) {
           $this->mySQLtablelist[] = $temp;
       }
       return $this->mySQLtablelist;
   }


   public function getSqlErrorno(){
       if($this->mySQLaccess->errno){
           return $this->mySQLaccess->errno;
       }
       return false;
   }
}  // end class




?>
