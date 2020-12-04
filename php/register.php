<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$_POST = json_decode(file_get_contents('php://input'), true);

		$email = $_POST['email'];

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
			include_once "utility.php";

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
				$properties['columns'] = Column::ID;
				$properties['condition'] = "WHERE type='register' AND email='".$email."'";
				$properties['orderBy'] = "";
				$properties['limit'] = "LIMIT 1";
				$database = new Database(DB::INFO, DB::USER, DB::PASS);
				$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
				$dbTableQuery = new DbTableQuery($properties);
				$dbTableOperator = new DbTableOperator();
				$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
				
				$code = 0;

				//	if the email is in the verification table
				if($row != null){
					// update the row in the list
					$equality = Column::CODE."=?, ".Column::CREATED."=?";
					$values[] = $code = rand(1000, 9999);	// the random code
					$values[] = time();	// the row expires after 10 minutes
					$condition = "WHERE id=".$row[0][Column::ID];
					
					$properties['equality'] = $equality;
					$properties['values'] = $values;
					$properties['condition'] = $condition;
					
					$database = new Database(DB::INFO, DB::USER, DB::PASS);
					$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
					$dbTableQuery = new DbTableQuery($properties);
					$dbTableOperator = new DbTableOperator();
					$dbTableOperator->update($dbTable, $dbTableQuery);
				}else{
					// add the email to the verification table
					$columns = "(".Column::TYPE.",".Column::EMAIL.",".Column::CODE.",".Column::CREATED.")";
					$tokens = "(?,?,?,?)";
					$values[] = 'register';
					$values[] = $email;	// email
					$values[] = $code = rand(1000, 9999);	// the random code
					$values[] = time(); // the row expires after 10 minutes
					
					$properties['columns'] = $columns;
					$properties['tokens'] = $tokens;
					$properties['values'] = $values;
					
					$database = new Database(DB::INFO, DB::USER, DB::PASS);
					$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
					$dbTableQuery = new DbTableQuery($properties);
					$dbTableOperator = new DbTableOperator();
					$dbTableOperator->insert($dbTable, $dbTableQuery);
				}

				$name = "UNIBEN ALUMNI ASSOCIATION";
				$from = "mycolistechnology@gmail.com";
				$to = $email;
				$subject = 'Uniben Alumni Verification Code';
				$message = "
				<table width='100%' border='0'>
					<thead>
						<tr>
							<th style='padding:30px 30px; background-color:rgb(220, 220, 220); color:rgb(150, 150, 150); font-family:Verdana;'>$name</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style='padding:30px 30px; color:rgb(50, 50, 50); font-family:Verdana; line-height:1.4em;'>
							Hi,<br/></br>Your verification code is <b>$code</b>.<br/><br/>You are receiving this message because you are trying to register as an alumni of University of Benin Asaba Branch. If this is not you, please ignore this message.<br/><br/>Thanks,<br/>Support Team
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td style='padding:30px 30px; background-color:rgb(220, 220, 220); color:rgb(150, 150, 150); font-family:Verdana; text-align: center;'>&copy;".date("Y").". All Rights reserved.</td>
						</tr>
					</tfoot>
				</table>
				";
				
				$params = array(
			       "name" => $name,
			       "from" => $from,
			       "to" => $to,
			       "subject" => $subject,
			       "message" => $message
			    );
			 	
			 	if(sendEmail($params)){
			 		$status = 'success';
			 		$error = 'The email was sent';
			 	}else{
			 		$status = 'failed';
			 		$error = 'The email was not sent';
			 	}
			}
		}
		
		$response['status'] = $status;
		$response['error'] = $error;

		echo json_encode($response);
	}
?>