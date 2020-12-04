<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$_POST = json_decode(file_get_contents('php://input'), true);

		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$code = $_POST['code'];

		$status = '';
		$error = '';

		$response = array();

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    // invalid emailaddress
		    $status = 'failed';
		    $error = 'Email format not supported';
		}else{
			include_once "database/DB.const.php";
			include_once "database/Table.const.php";
			include_once "database/Column.const.php";
			include_once "database/Database.cls.php";
			include_once "database/DbTable.cls.php";
			include_once "database/DbTableQuery.cls.php";
			include_once "database/DbTableOperator.cls.php";

			// check whether email is already registered			
			$properties['columns'] = Column::ID;
			$properties['condition'] = "WHERE email='".$email."'";
			$properties['orderBy'] = "";
			$properties['limit'] = "LIMIT 1";
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::USERS_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
			
			if($row != null){
				$status = 'failed';
				$error = 'Email is already registered';
			}else{
				// if the email is not registered
				// check whether email is already in the verification table
				$properties['columns'] = Column::ID.','.Column::CODE;
				$properties['condition'] = "WHERE type='register' AND email='".$email."'";
				$properties['orderBy'] = "";
				$properties['limit'] = "LIMIT 1";
				$database = new Database(DB::INFO, DB::USER, DB::PASS);
				$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
				$dbTableQuery = new DbTableQuery($properties);
				$dbTableOperator = new DbTableOperator();
				$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

				//	if the email is not in the verification table
				if($row == null){
					$status = 'failed';
					$error = 'Verification code has expired';
				}else{
					if($code == "".$row[0][Column::CODE]){



						// create a new user
						$columns = "(".Column::EMAIL.",".Column::PASSWORD.",".Column::FIRST_NAME.",".Column::LAST_NAME.")";
						$tokens = "(?,?,?,?)";
						$values[] = $email;
						$values[] = $password;
						$values[] = $firstName;
						$values[] = $lastName;
						
						$properties['columns'] = $columns;
						$properties['tokens'] = $tokens;
						$properties['values'] = $values;
						
						$database = new Database(DB::INFO, DB::USER, DB::PASS);
						$dbTable = new DbTable($database, Table::USERS_TB); 
						$dbTableQuery = new DbTableQuery($properties);
						$dbTableOperator = new DbTableOperator();
						$dbTableOperator->insert($dbTable, $dbTableQuery);

						$status = 'success';
					}else{
						$status = 'failed';
						$error = 'Incorrect code';
					}
				}
			}
		}
		
		$response['status'] = $status;
		$response['error'] = $error;

		echo json_encode($response);
	}
?>