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
		
		$code = $_POST['code'];
		
		//check whether code exists
		$properties['columns'] = Column::ID.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::EMAIL.",".Column::PASSWORD;
		$properties['condition'] = "WHERE type='register' AND code=$code";
		$properties['orderBy'] = "";
		$properties['limit'] = "LIMIT 1";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$error = 'Incorrect verification code';
		}else{
			// create a new user
			$columns = "(".Column::EMAIL.",".Column::PASSWORD.",".Column::FIRST_NAME.",".Column::LAST_NAME.")";
			$tokens = "(?,?,?,?)";
			$values[] = $row[0][Column::EMAIL];
			$values[] = $row[0][Column::PASSWORD];
			$values[] = $row[0][Column::FIRST_NAME];
			$values[] = $row[0][Column::LAST_NAME];
			
			$properties['columns'] = $columns;
			$properties['tokens'] = $tokens;
			$properties['values'] = $values;
			
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::USERS_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$dbTableOperator->insert($dbTable, $dbTableQuery);
	
			$properties = array();
			$properties['condition'] =  "WHERE id = ".$row[0][Column::ID];
			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::VERIFICATION_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$dbTableOperator->delete($dbTable, $dbTableQuery);
		
			header("Location: registrationSuccessful.php");
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>UNIBEN Alumni Verify Email</title>
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
					<h1 class="rex-center-relative-div-vertical">Email Verification</h1>
				</div>
			</div>

			<div class="rex-space-8px"></div>

			<div class="rex-background-white rex-pad16px">
				
				<p class="rex-center-text rex-fs-normal">An verification code was sent to your email. Please enter it in the field below</p>
				
				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-width-80pp rex-center-div-horizontal">
					
					<label for="code" class="rex-fs-extra-small rex-weight-bold">Verification code</label>
					<input required type="number" name="code" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />
					
					<div class="rex-space-16px"></div>

					<div class="rex-center-text rex-color-red rex-fs-normal rex-display-block">
						<?php echo $error; ?>
					</div>

					<div class="rex-space-16px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Confirm</button>
				</form>

				<div class="rex-space-32px"></div>
			</div>

			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>