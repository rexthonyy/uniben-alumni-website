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

		$pageId = $_POST['pageId'];

		$properties['columns'] = Column::TITLE.",".Column::CONTENT;
		$properties['condition'] = "WHERE id=$pageId";
		$properties['orderBy'] = "";
		$properties['limit'] = "LIMIT 1";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::PAGES_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$response['status'] = 'failed';
			$response['err'] = 'page not found';
		}else{
			$response['status'] = 'success';
			$response['title'] = $row[0][Column::TITLE];
			$response['content'] = $row[0][Column::CONTENT];
		}

		echo json_encode($response);
	}
?>