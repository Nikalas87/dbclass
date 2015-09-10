<?php 
error_reporting(0 | E_ERROR | E_PARSE);

class DB {
	
	private $host;
	private $username;
	private $password;
	private $dbname;
	private $connect;
	private $selectdb;	
	private $connected;
	
	 public $query;
	 	    
	private function trowError (){
		
	    if ( !$this->connected )
		 return ;
		 
		if ( mysql_errno($this->connect) )
		 echo mysql_errno($this->connect) . ": " . mysql_error($this->connect);
	}
	
	
	function __construct ( $host, $username, $password, $dbname, $connectNOW  ) {
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->dbname = $dbname;
		
		$this->connected = false;
		
		if ( $connectNOW ) {
			$this->connect();
		}
	}
	
	function __destruct (){
			
	   if ( $this->connected )
		 mysql_close($this->connect);		
	}
	
	function connect() { 
	
	   if ( $this->connected )
		 return ;
	
		$this->connect = mysql_connect($this->host, $this->username, $this->password, $this->dbname) or die("Connection Failure to Database");
		$this->selectdb = mysql_select_db($this->dbname, $this->connect) or die ($this->dbname . " Database not found." . $this->dbuser);
		mysql_query("SET NAMES 'UTF8'");
		
		$this->connected = true;
	}
	
	function query ( $querySTR ) {
	    if ( !$this->connected )
		 return ;
		  
		$this->query = mysql_query ( $querySTR ); 
		
		//echo $querySTR;
		$this->trowError();
		return $this->query;
	}

	function insert( $table, $values, $where='' ) {
		
	    if ( !$this->connected )
		 return ;
		  
		mysql_query('INSERT INTO '.$table.' VALUES('.$values.')');
		
		$this->trowError();
	}
	
	function select( $field, $table, $column, $where='' ){
	    if ( !$this->connected )
		 return ;
		
		$sql = mysql_query('SELECT '.$field.' FROM '.$table.' '.$where.'');
		$row = mysql_fetch_assoc($sql);
		
		echo $row[$column];
		
		$this->trowError();
	}	
	function delete( $table, $column, $value, $limit=0 ){
		
		if ( !$this->connected )
		 return ;
		
		#if ( !$limit ) $limitSTR = ""; 
		 #else $limitSTR = " LIMIT".$limit;
		
		$limitSTR = (!$limit) ? "" : " LIMIT ".$limit;
		
		mysql_query('DELETE FROM '.$table.' WHERE '.$column.'=\''.$value.'\''.$limitSTR);
		
		$this->trowError();
	}
	function update($table, $fields, $where){
		if ( !$this->connected )
		 return ;
		 
		mysql_query('UPDATE '.$table.' SET '.$fields.' '.$where.' ');
		$this->trowError();
	}
		
	public function getData($sqlResource)
	{
		$out = array() ;
		$cnt = 0 ;
		while ($row = mysql_fetch_assoc($sqlResource)) {
			foreach ($row as $key=>$value) {
				$out[$cnt][$key] = stripslashes($value) ;
			}
			$cnt++ ;
		}
		return $out ;
	}
}
	$dbWork = new DB ('localhost', 'root', '', 'c_ount', true);	
	
?>
