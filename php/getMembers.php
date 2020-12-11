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

		$searchEntry = strtolower($_POST['search']);
		if(!isset($searchEntry)){
			$searchEntry = '';
		}

		$properties['columns'] = Column::ID.",".Column::IS_ADMIN.",".Column::IS_VERIFIED.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::IMAGELINK.",".Column::TITLE.",".Column::PHONENUMBER.",".Column::ADDRESS.",".Column::COUNTRY.",".Column::STATE.",".Column::LOCALGOV.",".Column::OCCUPATION.",".Column::RELIGION.",".Column::CREATED;
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
			$response['err'] = 'no users';
		}else{
			$users = array();
			foreach($row as $member){
				$username = strtolower($member[Column::FIRST_NAME].' '.$member[Column::LAST_NAME]);

				if(strpos($username, $searchEntry) !== false) {	
					$users[] = $member;
				}
			}

			$response['status'] = 'success';
			$response['users'] = $users;
		}

		echo json_encode($response);
	}
?>