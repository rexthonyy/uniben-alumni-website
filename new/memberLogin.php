<?php
	session_start();
	$error = "";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		include_once "database/DB.const.php";
		include_once "database/Table.const.php";
		include_once "database/Column.const.php";
		include_once "database/Database.cls.php";
		include_once "database/DbTable.cls.php";
		include_once "database/DbTableQuery.cls.php";
		include_once "database/DbTableOperator.cls.php";
		
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		//check whether code exists
		$properties['columns'] = Column::ID;
		$properties['condition'] = "WHERE email='$email' AND password='$password'";
		$properties['orderBy'] = "";
		$properties['limit'] = "LIMIT 1";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
		
		if($row == null){
			$error = 'Incorrect email or password';
		}else{
			$user_id = $row[0][Column::ID];
			$session_id = md5(uniqid(rand(), true));
			$expire = time() + (60 * 60 * 60 * 24 * 7); //1 week session length

			$columns = "(".Column::USER_ID.",".Column::SESSION_ID.",".Column::EXPIRE.")";
			$tokens = "(?,?,?)";
			$values[] = $user_id;
			$values[] = $session_id;
			$values[] = $expire;

			$properties['columns'] = $columns;
			$properties['tokens'] = $tokens;
			$properties['values'] = $values;

			$database = new Database(DB::INFO, DB::USER, DB::PASS);
			$dbTable = new DbTable($database, Table::SESSION_TB); 
			$dbTableQuery = new DbTableQuery($properties);
			$dbTableOperator = new DbTableOperator();
			$dbTableOperator->insert($dbTable, $dbTableQuery);

			$_SESSION['session_id'] = $session_id;
			setcookie('session_id', $session_id, $expire, '/');
			
			header("Location: dashboardMyProfile.php");
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>Member Login</title>
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
					<h1 class="rex-center-relative-div-vertical">Member Login</h1>
				</div>
			</div>

			<div class="rex-space-8px"></div>

			<div class="rex-background-white rex-pad16px">

				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-width-80pp rex-center-div-horizontal">
					<label for="email" class="rex-fs-extra-small rex-weight-bold">Email</label>
					<input required type="email" name="email" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-32px"></div>

					<label for="email" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Password</label>
					<input required type="password" name="password" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="" />

					<div class="rex-space-16px"></div>

					<div class="rex-center-text rex-color-red rex-fs-normal rex-display-gone">
						Error
					</div>

					<div class="rex-space-16px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Login</button>
				</form>

				<div class="rex-space-32px"></div>
			</div>

			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>