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

		$properties['columns'] = Column::ID.",".Column::IS_DELETABLE.",".Column::NAME.",".Column::TITLE.",".Column::IMAGELINK;
		$properties['condition'] = "";
		$properties['orderBy'] = "";
		$properties['limit'] = "";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::NATIONAL_EXCOS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$response['status'] = 'failed';
			$response['err'] = 'No data available';
			$response['excos'] = array();
		}else{
			$response['status'] = 'success';
			$response['excos'] = $row;
		}

		echo json_encode($response);
	}
?>