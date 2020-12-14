<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		include_once "database/DB.const.php";
		include_once "database/Table.const.php";
		include_once "database/Column.const.php";
		include_once "database/Database.cls.php";
		include_once "database/DbTable.cls.php";
		include_once "database/DbTableQuery.cls.php";
		include_once "database/DbTableOperator.cls.php";
		include_once "helper/ImageHandler.cls.php";
		include_once "helper/Consts.php";

		$name = $_POST['name'];
		$position = $_POST['position'];
		
		$response = array();
		
		$img_path = "";

		if(isset($_FILES['file']['tmp_name'])){
			$fileType = $_FILES['file']['type'];
			switch($fileType){
				case 'image/gif':
				case 'image/jpeg':
				case 'image/jpg':
				case 'image/pjpeg':
				case 'image/png':
					try
					{
						//set the save directory
						$img = new ImageHandler(Consts::IMAGE_UPLOAD_DIR, array(1000, 1000));
						//store the image in the directory and get the local path from local host
						$img_path = $img->processUploadedImage($_FILES['file']);
					}
					catch(Exception $e){
						// If an error occured, output your custom error message
						$response['err'] = $e->getMessage();
					}
				break;
				
				default:
					$response['err'] = 'Invalid data type';
			}
		}
		
		$columns = "(".Column::IS_DELETABLE.",".Column::NAME.",".Column::TITLE.",".Column::IMAGELINK.")";
		$tokens = "(?,?,?,?)";
		$values[] = 'y';
		$values[] = $name;
		$values[] = $position;
		$values[] = substr($img_path, 3);
		
		$properties['columns'] = $columns;
		$properties['tokens'] = $tokens;
		$properties['values'] = $values;
		
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::NATIONAL_EXCOS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$dbTableOperator->insert($dbTable, $dbTableQuery);
	
		$response['status'] = 'success';
		echo json_encode($response);
	}
?>