<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>UNIBEN Alumni Successful Registration</title>
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
					<h1 class="rex-center-relative-div-vertical">Registration successful</h1>
				</div>
			</div>

			<div class="rex-space-8px"></div>

			<div class="rex-background-white rex-pad16px">
				
				<p class="rex-center-text rex-fs-normal"><span class="rex-color-green">You have been successfully registered</span>. Please login to your dashboard</p>
				
				<div class="rex-space-32px"></div>
				<div class="rex-space-16px"></div>

				<a href="memberLogin.php"><button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-normal rex-width-100pp"/>Login to dashboard</button></a>

				<div class="rex-space-32px"></div>
			</div>

			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>