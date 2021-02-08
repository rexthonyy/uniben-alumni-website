<?php
	//check whether the session is set
	include_once "utility.php";
	checkSession();

	include_once "database/DB.const.php";
	include_once "database/Table.const.php";
	include_once "database/Column.const.php";
	include_once "database/Database.cls.php";
	include_once "database/DbTable.cls.php";
	include_once "database/DbTableQuery.cls.php";
	include_once "database/DbTableOperator.cls.php";

	//get the user from the session table
	$userId = getUserIdFromSessionId();
	$error = "";
	$confirm = "";
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['email'])){
			$email = strtolower(trim($_POST['email']));
			$password = $_POST['password'];
			
			$properties['columns'] = Column::ID;
			$properties['condition'] = "WHERE id=$userId AND password='$password'";
			$properties['orderBy'] = "";
			$properties['limit'] = "LIMIT 1";
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::USERS_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

			if($row == null){
				$error = 'Password is incorrect';
			}else{
				$equality = Column::EMAIL."=?";
				$values[] = $email;

				$condition = "WHERE id=$userId";
				
				$properties['equality'] = $equality;
				$properties['values'] = $values;
				$properties['condition'] = $condition;
				
				$database = new Database(DB::INFO, DB::USER, DB::PASS);
				$dbTable = new DbTable($database, Table::USERS_TB); 
				$dbTableQuery = new DbTableQuery($properties);
				$dbTableOperator = new DbTableOperator();
				$dbTableOperator->update($dbTable, $dbTableQuery);
				
				$confirm = "Email changed successfully";
			}
		}else{
			$currentPassword = $_POST['currentPassword'];
			$newPassword = $_POST['newPassword'];
			$confirmNewPassword = $_POST['confirmNewPassword'];
			
			if($newPassword != $confirmNewPassword){
				$error = "Passwords do not match";
			}else{
				$properties['columns'] = Column::ID;
				$properties['condition'] = "WHERE id=$userId AND password='$currentPassword'";
				$properties['orderBy'] = "";
				$properties['limit'] = "LIMIT 1";
				$database = new Database(DB::INFO, DB::USER, DB::PASS);
				$dbTable = new DbTable($database, Table::USERS_TB); 
				$dbTableQuery = new DbTableQuery($properties);
				$dbTableOperator = new DbTableOperator();
				$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

				if($row == null){
					$error = 'Password is incorrect';
				}else{
					$equality = Column::PASSWORD."=?";
					$values[] = $newPassword;

					$condition = "WHERE id=$userId";
					
					$properties['equality'] = $equality;
					$properties['values'] = $values;
					$properties['condition'] = $condition;
					
					$database = new Database(DB::INFO, DB::USER, DB::PASS);
					$dbTable = new DbTable($database, Table::USERS_TB); 
					$dbTableQuery = new DbTableQuery($properties);
					$dbTableOperator = new DbTableOperator();
					$dbTableOperator->update($dbTable, $dbTableQuery);

					$confirm = 'Password changed successfully';
				}
			}
		}
	}

	$properties['columns'] = Column::IS_ADMIN.",".Column::IS_VERIFIED.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::IMAGELINK.",".Column::TITLE.",".Column::PHONENUMBER.",".Column::ADDRESS.",".Column::COUNTRY.",".Column::STATE.",".Column::LOCALGOV.",".Column::OCCUPATION.",".Column::RELIGION.",".Column::CREATED;
	$properties['condition'] = "WHERE id=$userId";
	$properties['orderBy'] = "";
	$properties['limit'] = "LIMIT 1";
	$database = new Database(DB::INFO, DB::USER, DB::PASS);
	$dbTable = new DbTable($database, Table::USERS_TB); 
	$dbTableQuery = new DbTableQuery($properties);
	$dbTableOperator = new DbTableOperator();
	$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
	
	if($row == null){
		header("Location: memberLogin.php");
	}else{
		$user['isAdmin'] = $row[0][Column::IS_ADMIN] == 'y';
		$user['isVerified'] = $row[0][Column::IS_VERIFIED] == 'y';
		$user['firstName'] = $row[0][Column::FIRST_NAME];
		$user['lastName'] = $row[0][Column::LAST_NAME];
		$user['imageLink'] = $row[0][Column::IMAGELINK];
		$user['title'] = $row[0][Column::TITLE];
		$user['phoneNumber'] = $row[0][Column::PHONENUMBER];
		$user['address'] = $row[0][Column::ADDRESS];
		$user['country'] = $row[0][Column::COUNTRY];
		$user['state'] = $row[0][Column::STATE];
		$user['localgov'] = $row[0][Column::LOCALGOV];
		$user['occupation'] = $row[0][Column::OCCUPATION];
		$user['religion'] = $row[0][Column::RELIGION];
		$user['created'] = $row[0][Column::CREATED];
		
		if($user['title'] == '') $user['title'] = 'Mrs';
		if($user['imageLink'] == '') $user['imageLink'] = "images/icons/ic_avatar.png";
		if($user['religion'] == '') $user['religion'] = 'Christianity';
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>Dashboard | Manage account</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('dashboardSidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto rex-background-white">
			
			<div class="rex-width-90pp rex-center-div-horizontal">
				<h1 class="rex-mt-32px">Manage account</h1>
				
				<h3 class="rex-center-text rex-color-green rex-mt-32px">
					<?php echo $confirm; ?>
				</h3>
				
				<div class="rex-space-32px"></div>
								
				<form method="POST" action="#" class="rex-border rex-pad32px">
				
					<h2 class="rex-center-text">Change email</h2>
					
					<div class="rex-space-32px"></div>

					<label for="email" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">New Email</label>
					<input required type="text" name="email" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" />
					
					<div class="rex-space-32px"></div>

					<label for="password" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Current password</label>
					<input required type="password" name="password" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small"/>
					
	
					<div class="rex-space-32px"></div>
					<div class="rex-center-text rex-color-red">
						<?php echo $error; ?>
					</div>
					<div class="rex-space-32px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Change Email</button>
					
				</form>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-border rex-pad32px">
				
					<h2 class="rex-center-text">Change password</h2>
					
					<div class="rex-space-32px"></div>

					<label for="currentPassword" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Current password</label>
					<input required type="password" name="currentPassword" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" />
					
					<div class="rex-space-32px"></div>

					<label for="newPassword" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">New password</label>
					<input required type="password" name="newPassword" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small"/>
					
					<div class="rex-space-32px"></div>

					<label for="confirmNewPassword" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Confirm New password</label>
					<input required type="password" name="confirmNewPassword" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small"/>
					
					<div class="rex-space-32px"></div>
					<div class="rex-center-text rex-color-red">
						<?php echo $error; ?>
					</div>
					<div class="rex-space-32px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Change Password</button>
					
				</form>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>