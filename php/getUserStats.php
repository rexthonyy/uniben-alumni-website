<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$_POST = json_decode(file_get_contents('php://input'), true);

		include_once "database/DB.const.php";
		include_once "database/Table.const.php";
		include_once "database/Column.const.php";
		include_once "database/Database.cls.php";
		include_once "database/DbTable.cls.php";
		include_once "database/DbTableQuery.cls.php";
		include_once "database/DbTableOperator.cls.php";

		$response = array();

		$index = $_POST['index'];
		if(isset($index)){
			$response['index'] = $index;
		}
		$viewIndex = $_POST['viewIndex'];
		if(isset($viewIndex)){
			$response['viewIndex'] = $viewIndex;
		}

		$userId = $_POST['userId'];

		$properties['columns'] = Column::IS_VERIFIED;
		$properties['condition'] = "";
		$properties['orderBy'] = "";
		$properties['limit'] = "";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$response['status'] = 'failed';
			$response['err'] = 'user unavailable';
		}else{
			$numMembers = 0;
			$numVerified = 0;
			$numUnverified = 0;

			foreach($row as $member){
				$numMembers++;

				if($member[Column::IS_VERIFIED] === 'y'){
					$numVerified++;
				}
			}

			$numUnverified = $numMembers - $numVerified;

			$response['status'] = 'success';
			$response['numMembers'] = $numMembers;
			$response['numVerified'] = $numVerified;
			$response['numUnverified'] = $numUnverified;
		}

		echo json_encode($response);
	}
?>