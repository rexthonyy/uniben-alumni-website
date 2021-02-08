<?php
	$error = "";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		include_once "database/DB.const.php";
		include_once "database/Table.const.php";
		include_once "database/Column.const.php";
		include_once "database/Database.cls.php";
		include_once "database/DbTable.cls.php";
		include_once "database/DbTableQuery.cls.php";
		include_once "database/DbTableOperator.cls.php";
		include_once "utility.php";
		
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = strtolower(trim($_POST['email']));
		$password = $_POST['password'];
		$confirmPassword = $_POST['confirmPassword'];
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid email address";
		}else{
			if($password != $confirmPassword){
				$error = "Passwords do not match";
			}else{
				//check whether email is already registered
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
					$error = 'Email is already registered';
				}else{
					// check whether email is already in the verification table
					$properties['columns'] = Column::ID;
					$properties['condition'] = "WHERE type='register' AND email='$email'";
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
						$equality = Column::CODE."=?, ".Column::CREATED."=?, ".Column::FIRST_NAME."=?, ".Column::LAST_NAME."=?, ".Column::PASSWORD."=?";
						$values[] = $code = rand(1000, 9999);	// the random code
						$values[] = time();	// the row expires after 10 minutes
						$values[] = $firstName;
						$values[] = $lastName;
						$values[] = $password;
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
						$columns = "(".Column::TYPE.",".Column::EMAIL.",".Column::CODE.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::PASSWORD.",".Column::CREATED.")";
						$tokens = "(?,?,?,?,?,?,?)";
						$values[] = 'register';
						$values[] = $email;	// email
						$values[] = $code = rand(1000, 9999);	// the random code
						$values[] = $firstName;
						$values[] = $lastName;
						$values[] = $password;
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
						header("Location: registerVerification.php");
					}else{
						$error = 'Failed to send the email. Please try again';
					}
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>UNIBEN Alumni Registration</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('sidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto">
			
			<div class="rex-pad16px">
				<div class="rex-center-text rex-background-gray rex-height-200px">
					<h1 class="rex-center-relative-div-vertical">Member Registration</h1>
				</div>
			</div>

			<div class="rex-space-8px"></div>

			<div class="rex-background-white rex-pad16px">

				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-width-80pp rex-center-div-horizontal">
					<label for="firstName" class="rex-fs-extra-small rex-weight-bold">First name</label>
					<input required type="text" name="firstName" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-32px"></div>

					<label for="lastName" class="rex-fs-extra-small rex-weight-bold">Last name</label>
					<input required type="text" name="lastName" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-32px"></div>

					<label for="email" class="rex-fs-extra-small rex-weight-bold">Email</label>
					<input required type="email" name="email" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-32px"></div>

					<label for="password" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Password</label>
					<input required type="password" name="password" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-32px"></div>

					<label for="confirmPassword" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Confirm Password</label>
					<input required type="password" name="confirmPassword" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-16px"></div>

					<div class="rex-center-text rex-color-red rex-fs-normal rex-display-block">
						<?php echo $error; ?>
					</div>

					<div class="rex-space-16px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Create account</button>
				</form>

				<div class="rex-space-32px"></div>
			</div>

			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>