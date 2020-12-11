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

		$pageId = $_POST['pageId'];
		$title = $_POST['title'];
		$content = $_POST['content'];
	
		$equality = Column::TITLE."=?, ".Column::CONTENT."=?";
		$values[] = $title;
		$values[] = $content;
		$condition = "WHERE id=$pageId";
		
		$properties['equality'] = $equality;
		$properties['values'] = $values;
		$properties['condition'] = $condition;
		
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::PAGES_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$dbTableOperator->update($dbTable, $dbTableQuery);
		
		$response = array();
		$response['status'] = 'success';
		echo json_encode($response);
	}
?>