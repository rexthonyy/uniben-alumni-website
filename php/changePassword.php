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

		$userId = $_POST['userId'];
		$currentPassword = $_POST['currentPassword'];
		$newPassword = $_POST['newPassword'];

		$response = array();
		$status = '';
		$error = '';

		$properties['columns'] = Column::ID;
		$properties['condition'] = "WHERE id=$userId AND password='$currentPassword'";
		$properties['orderBy'] = "";
		$properties['limit'] = "LIMIT 1";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

		if($row == null){
			$status = 'failed';
			$error = 'Password is incorrect';
		}else{
			$equality = Column::PASSWORD."=?";
			$values[] = $newPassword;

			$condition = "WHERE id=$userId";
			
			$properties['equality'] = $equality;
			$properties['values'] = $values;
			$properties['condition'] = $condition;
			
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::USERS_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$dbTableOperator->update($dbTable, $dbTableQuery);

			$status = 'success';
		}

		$response['status'] = $status;
		$response['error'] = $error;

		echo json_encode($response);
	}
?>