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
		
		$userId = $_POST['userId'];

		$response = array();
		
		$img_path = "";

		if(isset($_FILES['profileImage']['tmp_name'])){
			$fileType = $_FILES['profileImage']['type'];
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
						$img_path = $img->processUploadedImage($_FILES['profileImage']);
						
						//delete the previous imagelink
						$properties['columns'] = Column::IMAGELINK;
						$properties['condition'] = "WHERE id=$userId";
						$properties['orderBy'] = "";
						$properties['limit'] = "LIMIT 1";
						$database = new Database(DB::INFO, DB::USER, DB::PASS);
						$dbTable = new DbTable($database, Table::USERS_TB); 
						$dbTableQuery = new DbTableQuery($properties);
						$dbTableOperator = new DbTableOperator();
						$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

						$previousImageLink = $row[0][Column::IMAGELINK];

						deleteImage(Consts::PATH_TO_IMAGE_ROOT.$previousImageLink);

						//save the new imagelink
						$equality = Column::IMAGELINK."=?";
						$values = array();
						$values[] = substr($img_path, 3);
						$condition = "WHERE id=$userId";
						
						$properties['equality'] = $equality;
						$properties['values'] = $values;
						$properties['condition'] = $condition;
						
						$database = new Database(DB::INFO, DB::USER, DB::PASS);
						$dbTable = new DbTable($database, Table::USERS_TB); 
						$dbTableQuery = new DbTableQuery($properties);
						$dbTableOperator = new DbTableOperator();
						$dbTableOperator->update($dbTable, $dbTableQuery);

						$response['status'] = 'success';
						$response['imageLink'] = substr($img_path, 3);
					}
					catch(Exception $e){
						// If an error occured, output your custom error message
						//die($e->getMessage());
						$response['status'] = 'failed';
						$response['err'] = $e->getMessage();
					}
				break;
				
				default:
					echo "Error uploading image : Unknown image extension";
					$response['status'] = 'failed';
					$response['err'] = 'Invalid data type';
			}
		}else{
			$response['status'] = 'failed';
			$response['err'] = 'No data sent';
		}

		echo json_encode($response);
	}
?>