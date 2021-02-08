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
		$title = $_POST['title'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$phoneNumber = $_POST['phoneNumber'];
		$address = $_POST['address'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$localgov = $_POST['localgov'];
		$occupation = $_POST['occupation'];
		$religion = $_POST['religion'];
		
		$equality = Column::FIRST_NAME."=?, ".Column::LAST_NAME."=?, ".Column::TITLE."=?, ".Column::PHONENUMBER."=?, ".Column::ADDRESS."=?, ".Column::COUNTRY."=?, ".Column::STATE."=?, ".Column::LOCALGOV."=?, ".Column::OCCUPATION."=?, ".Column::RELIGION."=?";
		$values[] = $firstName;
		$values[] = $lastName;
		$values[] = $title;
		$values[] = $phoneNumber;
		$values[] = $address;
		$values[] = $country;
		$values[] = $state;
		$values[] = $localgov;
		$values[] = $occupation;
		$values[] = $religion;
		$condition = "WHERE id=$userId";
		
		$properties['equality'] = $equality;
		$properties['values'] = $values;
		$properties['condition'] = $condition;
		
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::USERS_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$dbTableOperator->update($dbTable, $dbTableQuery);
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
	<title>Dashboard | My Profile</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('dashboardSidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto rex-background-white">
			
			<div class="rex-width-90pp rex-center-div-horizontal">
				<h1 class="rex-mt-32px">My Profile</h1>
				
				<div class="rex-space-32px"></div>
				
				<div>
					<a href="dashboardUploadProfilePic.php" class="rex-vertical-align">
						<img src="<?php echo $user['imageLink']; ?>" class="rex-width-100px rex-height-100px rex-curDiv-50px"/>
					</a>
					
					<?php
						if($user['isVerified']){
							echo "<span class='rex-color-green rex-weight-bold rex-ml-32px'>Verified</span>";
						}else{
							echo "<span class='rex-color-red rex-weight-bold rex-ml-32px'>Unverified</span>";
						}
					?>
				</div>

				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#">
					<label for="title" class="rex-fs-extra-small rex-weight-bold">Title</label>
					<select class="rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" name="title">
						<option value="Mr" <?php if($user['title'] == 'Mr') echo 'selected';?>>Mr</option>
						<option value="Mrs" <?php if($user['title'] == 'Mrs') echo 'selected';?>>Mrs</option>
					</select>
					
					<div class="rex-space-32px"></div>

					<label for="firstName" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">First name</label>
					<input required type="text" name="firstName" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['firstName']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="lastName" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Last name</label>
					<input required type="text" name="lastName" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['lastName']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="phoneNumber" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Phone number</label>
					<input type="text" name="phoneNumber" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['phoneNumber']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="address" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Address</label>
					<input type="text" name="address" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['address']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="country" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Country</label>
					<input type="text" name="country" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['country']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="state" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">State</label>
					<input type="text" name="state" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['state']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="localgov" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Local government</label>
					<input type="text" name="localgov" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['localgov']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="occupation" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Occupation</label>
					<input type="text" name="occupation" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" value="<?php echo $user['occupation']; ?>" />
					
					<div class="rex-space-32px"></div>

					<label for="religion" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Religion</label>
					<select class="rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" name="religion">
						<option value="Christianity" <?php if($user['religion'] == 'Christianity') echo 'selected';?>>Christianity</option>
						<option value="Islam" <?php if($user['religion'] == 'Islam') echo 'selected';?>>Islam</option>
						<option value="Others" <?php if($user['religion'] == 'Others') echo 'selected';?>>Others</option>
					</select>
					
	
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Update Profile</button>
					
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>
					
					<div class="rex-space-32px"></div>
					<div class="rex-space-32px"></div>

				</form>
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>