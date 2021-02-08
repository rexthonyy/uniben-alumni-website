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
	$memberId = $_GET['user_id'];
	
	if(isset($_GET['cancel']) || isset($_GET['verify']) || isset($_GET['delete'])){
		echo "refresh";
	}
	
	
	
	$user = [];
	$member = [];
	
	$properties['columns'] = Column::IS_ADMIN.",".Column::IS_VERIFIED.",".Column::FIRST_NAME.",".Column::LAST_NAME.",".Column::IMAGELINK.",".Column::TITLE.",".Column::PHONENUMBER.",".Column::ADDRESS.",".Column::COUNTRY.",".Column::STATE.",".Column::LOCALGOV.",".Column::OCCUPATION.",".Column::RELIGION.",".Column::CREATED;
	$properties['condition'] = "WHERE id=$memberId";
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
		$member['isAdmin'] = $row[0][Column::IS_ADMIN] == 'y';
		$member['isVerified'] = $row[0][Column::IS_VERIFIED] == 'y';
		$member['firstName'] = $row[0][Column::FIRST_NAME];
		$member['lastName'] = $row[0][Column::LAST_NAME];
		$member['imageLink'] = $row[0][Column::IMAGELINK];
		$member['title'] = $row[0][Column::TITLE];
		$member['phoneNumber'] = $row[0][Column::PHONENUMBER];
		$member['address'] = $row[0][Column::ADDRESS];
		$member['country'] = $row[0][Column::COUNTRY];
		$member['state'] = $row[0][Column::STATE];
		$member['localgov'] = $row[0][Column::LOCALGOV];
		$member['occupation'] = $row[0][Column::OCCUPATION];
		$member['religion'] = $row[0][Column::RELIGION];
		$member['created'] = $row[0][Column::CREATED];
		
		if($member['title'] == '') $member['title'] = 'Mrs';
		if($member['imageLink'] == '') $member['imageLink'] = "images/icons/ic_avatar.png";
		if($member['religion'] == '') $member['religion'] = 'Christianity';
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
	<title>Dashboard | Manage Member</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('dashboardSidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto rex-background-white">
			
			<div class="rex-width-90pp rex-center-div-horizontal">
				<h1 class="rex-mt-32px">Member Profile</h1>
				
				<div class="rex-space-32px"></div>
				
				<div>
					<img src="<?php echo $member['imageLink']; ?>" class="rex-vertical-align rex-width-100px rex-height-100px rex-curDiv-50px"/>
					
					<?php
						if($member['isVerified']){
							echo "<span class='rex-color-green rex-weight-bold rex-ml-32px'>Verified</span>";
						}else{
							echo "<span class='rex-color-red rex-weight-bold rex-ml-32px'>Unverified</span>";
						}
					?>
				</div>

				<div class="rex-space-32px"></div>
				
				<div>
					<div class="rex-fs-extra-small rex-weight-bold">Title</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['title']; ?></p>
					
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">First name</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['firstName']; ?></p>
					
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Last name</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['lastName']; ?></p>
					
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Phone number</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['phoneNumber']; ?></p>
					
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Address</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['address']; ?></p>
										
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Country</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['country']; ?></p>
										
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">State</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['state']; ?></p>
										
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Local government</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['localgov']; ?></p>
										
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Occupation</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['occupation']; ?></p>
										
					<div class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Religion</div>
					<p class="rex-mt-8px rex-fs-small"><?php echo $member['religion']; ?></p>
	
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>
					
					<form class="rex-display-grid3" action="#" method="GET">
						<input type="hidden" name="user_id" value="<?php echo $memberId; ?>"/>
						<div class="rex-center-text rex-pr-8px">
							<input type="submit" name="cancel" value="Cancel" class="rex-width-100pp rex-btn-secondary rex-pad16px rex-curDiv-8px"/>
						</div>
						<div class="rex-center-text rex-pr-8px rex-pl-8px">
							<input type="submit" name="verify" value="Verify" class="rex-color-white rex-width-100pp rex-btn-primary rex-pad16px rex-curDiv-8px"/>
						</div>
						<div class="rex-center-text rex-pl-8px">
							<input type="submit" name="delete" value="Delete member" class="rex-color-white rex-width-100pp rex-btn-danger rex-pad16px rex-curDiv-8px"/>
						</div>
					</form>
					
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>
					
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>

				</div>
			</div>
			
			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>