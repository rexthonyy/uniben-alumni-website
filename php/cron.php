<?php
	function checkExpiredCode(){
		// is code in the verification table
		$sql = "
		SELECT id, created
		FROM verification_tb";

		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTableOperator = new DbTableOperator();
		$ver = $dbTableOperator->readRawSQL($sql, $database, new DbPrepareResult());

		if($ver != null){
			for($i = 0; $i < count($ver); $i++){
				
				$created = $ver[$i]['created'];
				$now = time();
				$expirationTime = 1000 * 60 * 30;

				if($now > ($created + $expirationTime)){
					$properties['condition'] = "WHERE id = " . $ver[$i]['id'];
					$database = new Database(DB::INFO, DB::USER, DB::PASS);
					$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
					$dbTableQuery = new DbTableQuery($properties);
					$dbTableOperator = new DbTableOperator();
					$dbTableOperator->delete($dbTable, $dbTableQuery);
				}
			}
		}
	}

	checkExpiredCode();
?>