<?php

//include_once("/includes/database.class.php");

class E107DatabaseTrans implements DatabaseTrans{
	private $row_count;
	private $last_id;
		
	function SqlGetRow($select, $from, $where='', $other= 'default'){
		global $sql;
		//echo $select." from ".$from." where ".$where;
		$data = $sql->db_Select($from, $select, $where, $other);
		$row_count = $data;
		return $sql->db_Fetch();
	}

	function SqlGetAll($select, $from, $where='', $other='default'){
		global $sql;
		//echo $select." from ".$from." where ".$where;
		$data = $sql->db_Select($from, $select, $where, $other);
		$row_count = $data;
		$count=0;
		if($data>1){
			$item=$sql->db_fetch();
			while($item!=NULL || $item!=false){
				$info[$count++]=$item;
				$item = $sql->db_fetch();
			}
			return $info;
		}
		elseif($data==1){
			$info[0]=$sql->db_fetch();
			return $info;
		}
		return false;
	}
	
	//<code>$sql->db_Insert("links", "0, 'News', 'news.php', '', '', 1, 0, 0, 0");</code>
	function SqlInsert($table, $values){
		global $sql;
		if($data = $sql->db_Insert($table, $values)){
			$this->last_id = $data;
			return true;
		}
		else{
			return false;
		}
	}
	
	function SqlUpdate($table, $values, $where=''){
		global $sql;
		if($data=$sql->db_Update($table, $values.$where)){
			$row_count = $data;
			return true;
		}
		return false;
	}
	
	function SqlGetRowCount($select, $table, $where=''){
		global $sql;
		$select = " count(".$select.") ";
		$sql->db_Select($table, $select, $where);
        $count=$sql->db_fetch();
		return $count[0];
	}
	
	function SqlDelete($table, $where=''){
		global $sql;
		if($sql->db_Delete($table, $where)){
			return true;
		}
		return false;
	}
	
	function getrows(){
		return $this->row_count;
	}
	
	function LastInsertId(){
		return $this->last_id;
	}

}

?>