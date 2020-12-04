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
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$title = $_POST['title'];
		$phoneNumber = $_POST['phoneNumber'];
		$address = $_POST['address'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$localgov = $_POST['localgov'];
		$occupation = $_POST['occupation'];
		$religion = $_POST['religion'];

		$equality = Column::FIRST_NAME."=?, ".Column::LAST_NAME."=?, ".Column::TITLE."=?, ".Column::PHONENUMBER."=?, ".Column::ADDRESS."=?, ".Column::COUNTRY."=?, ".Column::STATE."=?, ".Column::LOCALGOV."=?, ".Column::OCCUPATION."=?, ".Column::RELIGION."=?";
		$values[] = $firstName;
		$values[] = $lastName;
		$values[] = $title;
		$values[] = $phoneNumber;
		$values[] = $address;
		$values[] = $country;
		$values[] = $state;
		$values[] = $localgov;
		$values[] = $occupation;
		$values[] = $religion;
		$condition = "WHERE id=$userId";
		
		$properties['equality'] = $equality;
		$properties['values'] = $values;
		$properties['condition'] = $condition;
		
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$dbTableOperator->update($dbTable, $dbTableQuery);
		
		$response = array();
		$response['status'] = 'success';
		echo json_encode($response);
	}
?>