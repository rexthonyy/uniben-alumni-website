<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$_POST = json_decode(file_get_contents('php://input'), true);

		$email = $_POST['email'];
		$password = $_POST['password'];

		$status = '';
		$error = '';

		$response = array();

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    // invalid emailaddress
		    $response['status'] = 'failed';
			$response['error'] = 'Email format not supported';
		}else{
			include_once "database/DB.const.php";
			include_once "database/Table.const.php";
			include_once "database/Column.const.php";
			include_once "database/Database.cls.php";
			include_once "database/DbTable.cls.php";
			include_once "database/DbTableQuery.cls.php";
			include_once "database/DbTableOperator.cls.php";

			// check whether email is already registered			
			$properties['columns'] = Column::ID.','.Column::FIRST_NAME.','.Column::LAST_NAME.','.Column::IMAGELINK;
			$properties['condition'] = "WHERE email='".$email."' AND password='".$password."'";
			$properties['orderBy'] = "";
			$properties['limit'] = "LIMIT 1";
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::USERS_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
			
			if($row == null){
				$response['status'] = 'failed';
				$response['error'] = 'Incorrect login credentials';
			}else{
				$response['status'] = 'success';
				$response['userId'] = $row[0][Column::ID];
				$response['firstName'] = $row[0][Column::FIRST_NAME];
				$response['lastName'] = $row[0][Column::LAST_NAME];
				$response['imageLink'] = $row[0][Column::IMAGELINK];
			}
		}
		
		echo json_encode($response);
	}
?>