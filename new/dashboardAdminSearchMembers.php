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
	if(!isset($_GET['searchEntry'])){
		header("Location: dashboardAdmin.php");
	}
	
	$searchEntry = strtolower($_GET['searchEntry']);

	$properties['columns'] = Column::ID.",".Column::IS_ADMIN.",".Column::IS_VERIFIED.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::IMAGELINK.",".Column::TITLE.",".Column::PHONENUMBER.",".Column::ADDRESS.",".Column::COUNTRY.",".Column::STATE.",".Column::LOCALGOV.",".Column::OCCUPATION.",".Column::RELIGION.",".Column::CREATED;
	$properties['condition'] = "";
	$properties['orderBy'] = "";
	$properties['limit'] = "";
	$database = new Database(DB::INFO, DB::USER, DB::PASS);
	$dbTable = new DbTable($database, Table::USERS_TB); 
	$dbTableQuery = new DbTableQuery($properties);
	$dbTableOperator = new DbTableOperator();
	$rows = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());
	
	$users = array();

	if($rows != null){
		foreach($rows as $member){
			$username = strtolower($member[Column::FIRST_NAME].' '.$member[Column::LAST_NAME]);

			if(strpos($username, $searchEntry) !== false) {	
				$users[] = $member;
			}
		}
	}
	
	$info = "";
	if(count($users) == 0){
		$info = "No result found";
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
				<h1 class="rex-mt-32px">Search Results</h1>
				
				<div class="rex-space-32px"></div>
				
				<form method="GET" action="#" class="rex-pad32px">
				
					<input required type="text" name="searchEntry" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="Search for members" value="<?php echo $_GET['searchEntry']; ?>"/>	
					
					<div class="rex-space-16px"></div>
					
					<button class="rex-responsive-btn rex-btn-primary rex-pad8px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Search</button>
				</form>
				
				<div class="rex-space-32px"></div>
				
				<h3 class="rex-center-text">
					<?php echo $info;?>
				</h3>
				
				<div class="rex-space-32px"></div>
				
				<?php
					foreach($users as $user){
				?>
				
					<a href="dashboardAdminManageMember.php?user_id=<?php echo $user[Column::ID]; ?>">
					<div class="rex-pad16px rex-selectable-item-background">
						<span class="rex-vertical-align">
							<img src="<?php echo $user['imageLink']; ?>" class="rex-width-60px rex-height-60px rex-curDiv-30px"/>
						</span>
						
						<span>
							<span class="rex-fs-normal rex-ml-32px rex-color-black">
								<?php echo $user[Column::FIRST_NAME]." ".$user[Column::LAST_NAME]; ?>
							</span>
							<?php
								if($user[Column::IS_VERIFIED] == 'y'){
									echo "<span class='rex-color-green rex-weight-bold rex-ml-32px'>Verified</span>";
								}else{
									echo "<span class='rex-color-red rex-weight-bold rex-ml-32px'>Unverified</span>";
								}
							?>
						</span>
					</div>
					</a>
				<?php } ?>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-32px"></div>
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>