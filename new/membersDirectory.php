<!DOCTYPE html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<title>Member's Directory</title>
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
					<h1 class="rex-center-relative-div-vertical">Member's Directory</h1>
				</div>
			</div>

			<div class="rex-space-8px"></div>

			<div class="rex-background-white rex-pad16px">

				<div class="rex-space-32px"></div>
				
				<form method="POST" action="#" class="rex-width-70pp rex-center-div-horizontal">
					<input required type="text" name="name" class="rex-responsive-paragragh rex-input-primary rex-width-100pp rex-pad8px rex-curDiv-4px rex-mt-8px rex-fs-small" placeholder="Search for members" />

					<div class="rex-space-16px"></div>

					<div class="rex-center-text rex-color-red rex-fs-small rex-display-gone">
						Error
					</div>

					<div class="rex-space-16px"></div>

					<button class="rex-responsive-btn rex-btn-primary rex-pad16px rex-color-white rex-curDiv-8px rex-fs-small rex-width-100pp"/>Search</button>
				</form>

				<div class="rex-space-32px"></div>
			</div>

			<?php include_once('footer.php'); ?>
		</div>
	</div>
</body>
</html>