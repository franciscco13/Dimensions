<?php	  
	if(isset($_GET['do'])){
		switch($_GET['do']){

			case 'getDatabases':
				require_once('DatabaseDriver.php');		
				$dd = DatabaseDriver::getInstance(); 
				print_r(json_encode($dd -> getDatabases())); 
				break;

			case 'getTables': 
				require_once('DatabaseDriver.php');		
				$dd = DatabaseDriver::getInstance(); 
				print_r(json_encode($dd -> getTables($_GET['database']))); 
				break;  

			case 'getTableDesc': 
				require_once('DatabaseDriver.php');		
				$dd = DatabaseDriver::getInstance(); 
				print_r(json_encode($dd -> getTableDesc($_GET['database'], $_GET['table']))); 
				break;  
		}
	}
?>