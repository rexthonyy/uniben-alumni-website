<?php
	//check whether the session is set
	include_once "utility.php";
	checkSession();

	$error = "";
	
	include_once "database/DB.const.php";
	include_once "database/Table.const.php";
	include_once "database/Column.const.php";
	include_once "database/Database.cls.php";
	include_once "database/DbTable.cls.php";
	include_once "database/DbTableQuery.cls.php";
	include_once "database/DbTableOperator.cls.php";
	include_once "helper/ImageHandler.cls.php";
	include_once "helper/Consts.php";

	//get the user from the session table
	$userId = getUserIdFromSessionId();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$response = array();
		
		$img_path = "";

		if(isset($_FILES['profileImage']['tmp_name'])){
			$fileType = $_FILES['profileImage']['type'];
			switch($fileType){
				case 'image/gif':
				case 'image/jpeg':
				case 'image/jpg':
				case 'image/pjpeg':
				case 'image/png':
					try
					{
						//set the save directory
						$img = new ImageHandler(Consts::IMAGE_UPLOAD_DIR, array(1000, 1000));
						//store the image in the directory and get the local path from local host
						$img_path = $img->processUploadedImage($_FILES['profileImage']);
						
						//delete the previous imagelink
						$properties['columns'] = Column::IMAGELINK;
						$properties['condition'] = "WHERE id=$userId";
						$properties['orderBy'] = "";
						$properties['limit'] = "LIMIT 1";
						$database = new Database(DB::INFO, DB::USER, DB::PASS);
						$dbTable = new DbTable($database, Table::USERS_TB); 
						$dbTableQuery = new DbTableQuery($properties);
						$dbTableOperator = new DbTableOperator();
						$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

						$previousImageLink = $row[0][Column::IMAGELINK];

						deleteImage(Consts::PATH_TO_IMAGE_ROOT.$previousImageLink);

						//save the new imagelink
						$equality = Column::IMAGELINK."=?";
						$values = array();
						$values[] = $img_path;
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
					catch(Exception $e){
						$error = "Failed to upload image";
					}
				break;
				
				default:
					$error = "Image type not supported";
			}
		}else{
			$error = "Image is required for upload";
		}
	}
	
	//load the user details
	
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
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>Dashboard | Upload profile pic</title>
</head>

<body>
	<div class="custom-layout-grid2">
		<nav class="rex-pad16px rex-overflow-auto">
			<?php include_once('dashboardSidenav.php'); ?>
		</nav>
		<!-- main display -->
		<div class="rex-overflow-auto rex-background-white">
			
			<div class="rex-width-90pp rex-center-div-horizontal">
				<h1 class="rex-mt-32px">Upload profile pic</h1>
				
				<div class="rex-space-32px"></div>
				
				<div>
					<span class="rex-vertical-align">
						<img src="<?php echo $user['imageLink']; ?>" class="rex-width-100px rex-height-100px rex-curDiv-50px"/>
					</span>
					
					<?php
						if($user['isVerified']){
							echo "<span class='rex-color-green rex-weight-bold rex-ml-32px'>Verified</span>";
						}else{
							echo "<span class='rex-color-red rex-weight-bold rex-ml-32px'>Unverified</span>";
						}
					?>
				</div>

				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" enctype="multipart/form-data">

					<label for="profileImage" class="rex-mt-32px rex-fs-extra-small rex-weight-bold">Select image</label>	<input required type="file" name="profileImage" id="profileImage" class="rex-responsive-paragragh rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small rex-border" accept="image/jpg, image/jpeg, image/png, image/gif"/>
					
					<div class="rex-space-32px"></div>
					<div class="rex-center-text rex-color-red">
						<?php echo $error; ?>
					</div>
					<div class="rex-space-32px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Upload profile pic</button>
					
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