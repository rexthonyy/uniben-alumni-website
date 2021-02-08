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

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$searchEntry = $_POST['search'];
		header("Location: dashboardAdminSearchMembers.php?searchEntry=$searchEntry");
		exit;
	}
	
	$properties['columns'] = Column::IS_VERIFIED;
	$properties['condition'] = "";
	$properties['orderBy'] = "";
	$properties['limit'] = "";
	$database = new Database(DB::INFO, DB::USER, DB::PASS);
	$dbTable = new DbTable($database, Table::USERS_TB); 
	$dbTableQuery = new DbTableQuery($properties);
	$dbTableOperator = new DbTableOperator();
	$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
	
	$numMembers = 0;
	$numVerified = 0;
	$numUnverified = 0;
		
	if($row != null){

		foreach($row as $member){
			$numMembers++;

			if($member[Column::IS_VERIFIED] === 'y'){
				$numVerified++;
			}
		}

		$numUnverified = $numMembers - $numVerified;
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
	<title>Dashboard | Admin</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('dashboardSidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto rex-background-white">
			
			<div class="rex-width-90pp rex-center-div-horizontal">
				<h1 class="rex-mt-32px">Admin</h1>
				
				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-pad32px">
				
					<input required type="text" name="search" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="Search for members"/>	
					
					<div class="rex-space-16px"></div>
					
					<button class="rex-responsive-btn rex-btn-primary rex-pad8px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Search</button>
				</form>
								
				<div class="rex-display-grid3">
					<div class="rex-pad16px rex-center-text">
						<div class="rex-border rex-pad16px">
							<h2 class="rex-fs-large rex-weight-bold"><?php echo $numMembers; ?></h2>
							<p class="rex-color-gray rex-fs-normal">Total registered</p>
						</div>
					</div>
					<div class="rex-pad16px rex-center-text">
						<div class="rex-border rex-pad16px">
							<h2 class="rex-fs-large rex-weight-bold"><?php echo $numVerified; ?></h2>
							<p class="rex-color-gray rex-fs-normal">Total verified</p>
						</div>
					</div>
					<div class="rex-pad16px rex-center-text">
						<div class="rex-border rex-pad16px">
							<h2 class="rex-fs-large rex-weight-bold"><?php echo $numUnverified; ?></h2>
							<p class="rex-color-gray rex-fs-normal">Total Unverified</p>
						</div>
					</div>
				</div>
				
				<div class="rex-space-32px"></div>
				
				<h3>About us</h3>
				
				<div class="rex-space-16px"></div>
				
				<div class="rex-display-grid3">
					<div>
						<a href="#" class="rex-fs-normal">History</a>
					</div>
					<div>
						<a href="#" class="rex-fs-normal">Constitution</a>
					</div>
					<div>
						<a href="#" class="rex-fs-normal">Mission and vision</a>
					</div>
				</div>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
				
				<h3>Membership</h3>
				
				<div class="rex-space-16px"></div>
				
				<div class="rex-display-grid3">
					<div>
						<a href="#" class="rex-fs-normal">National Chairman</a>
					</div>
					<div>
						<a href="#" class="rex-fs-normal">National Excos</a>
					</div>
					<div>
						<a href="#" class="rex-fs-normal">Past National Chairmen</a>
					</div>
				</div>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
				
				<h3>Events</h3>
				
				<div class="rex-space-16px"></div>
				
				<div class="rex-display-grid3">
					<div>
						<a href="#" class="rex-fs-normal">Manage Posts</a>
					</div>
					<div>
						<a href="#" class="rex-fs-normal">Photo Gallery</a>
					</div>
				</div>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>