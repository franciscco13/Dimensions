<?php	
	class DatabaseDriver 
	{
		private static $instance = null; 
		private $conn = null; 
		private $conn_vars = [ 
			"host"=> "localhost",
			"user"=> "root",
			"pass"=> "" 
		]; 

		/////////////////////////////////////////////////////////////////
		// Singleton functions
		private function __construct() {}
		public static function getInstance( ) {
    		if (is_null(self::$instance))
      			self::$instance = new DatabaseDriver( );
    		return self::$instance;
  		}

  		/////////////////////////////////////////////////////////////////
  		// Connection methods
		private function startConnection(){  
			$this->conn  = mysqli_connect(
				$this->conn_vars['host'], 
				$this->conn_vars['user'], 
				$this->conn_vars['pass'])
			or die("Connection failed: " . $this->conn->connect_error);  
		}

		/////////////////////////////////////////////////////////////////
		private function closeConnection(){
			$this->conn->close(); 
		}

		///////////////////////////////////////////////////////////////
		public function getDatabases(){
			$this->startConnection();
			$array = [];
			$query = "show databases";
			$results = $this->conn->query($query);	
			while($row = $results->fetch_assoc()){	
				$array [] = $row['Database'];
			}
			return $array;
		}

		///////////////////////////////////////////////////////////////
		public function getTables($database){
			$this->startConnection();
			$array = [];
			$query0 = "use ".$database;
			$results = $this->conn->query($query0);	
			$query = "show tables";
			$results = $this->conn->query($query);	
			while($row = $results->fetch_assoc())
				$array [] = $row["Tables_in_".$database];			
			return $array;
		}

		///////////////////////////////////////////////////////////////
		public function getTableDesc($database, $table){
			$this->startConnection();
			$array = [];
			$query0 = "use ".$database;
			$results = $this->conn->query($query0);	
			$query = "describe ".$table;
			$results = $this->conn->query($query);	
			while($row = $results->fetch_assoc()){
				$row['Table'] = $table;
				$row['Database'] = $database;
				$array [] = $row;		
			}			
			return $array;
		}
 
		/////////////////////////////////////////////////////////////////
		// Parse to UTF valid strings
		private function codifyUTF(&$array){
			foreach($array as &$value){
				$value = utf8_encode($value);
			}
		}
	}
?>
