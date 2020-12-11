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

		if(isset($_POST['index'])){
			$response['index'] = $_POST['index'];
		}
		if(isset($_POST['viewIndex'])){
			$response['viewIndex'] = $_POST['viewIndex'];
		}

		$userId = $_POST['userId'];

		$properties['columns'] = Column::IS_ADMIN.",".Column::IS_VERIFIED.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::IMAGELINK.",".Column::TITLE.",".Column::PHONENUMBER.",".Column::ADDRESS.",".Column::COUNTRY.",".Column::STATE.",".Column::LOCALGOV.",".Column::OCCUPATION.",".Column::RELIGION.",".Column::CREATED;
		$properties['condition'] = "WHERE id=$userId";
		$properties['orderBy'] = "";
		$properties['limit'] = "LIMIT 1";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$response['status'] = 'failed';
			$response['err'] = 'user unavailable';
		}else{
			$response['status'] = 'success';
			$response['isAdmin'] = $row[0][Column::IS_ADMIN];
			$response['isVerified'] = $row[0][Column::IS_VERIFIED];
			$response['firstName'] = $row[0][Column::FIRST_NAME];
			$response['lastName'] = $row[0][Column::LAST_NAME];
			$response['imageLink'] = $row[0][Column::IMAGELINK];
			$response['title'] = $row[0][Column::TITLE];
			$response['phoneNumber'] = $row[0][Column::PHONENUMBER];
			$response['address'] = $row[0][Column::ADDRESS];
			$response['country'] = $row[0][Column::COUNTRY];
			$response['state'] = $row[0][Column::STATE];
			$response['localgov'] = $row[0][Column::LOCALGOV];
			$response['occupation'] = $row[0][Column::OCCUPATION];
			$response['religion'] = $row[0][Column::RELIGION];
			$response['created'] = $row[0][Column::CREATED];
		}

		echo json_encode($response);
	}
?>