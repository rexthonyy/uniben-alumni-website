<!-- side navigation -->
<input type="checkbox" id="check" class="rex-display-gone">
<div>
	<div id="small-nav-head" class="rex-display-grid2">
		<div>
			<a href="index.php">
				<img class="rex-width-30px rex-height-30px rex-display-flex rex-center-flex-items" src="images/icons/website_icon.png"/>
			</a>
		</div>
		<div>
			<label for="check" class="checkbtn rex-float-right rex-center-relative-div-vertical rex-fs-medium rex-hover">
				<i class="fas fa-bars"></i>
			</label>
		</div>
	</div>
	<div id="large-nav-head" >
		<div class="rex-height-60px">
			<a href="index.php">
				<img class="rex-width-60px rex-height-60px" src="images/icons/website_icon.png"/>
			</a>
		</div>
	</div>
</div>
<div id="menu">
	<h1 class="rex-fs-normal rex-mt-16px">
		UNIBEN Alumni Association Asaba Branch Dashboard
	</h1>
	<div class="rex-space-32px"></div>
	<a href="dashboardMyProfile.php">
		<div class="rex-selectable-item-background rex-pt-8px rex-pb-8px rex-fs-small rex-color-black">
			My Profile
		</div>
	</a>

	<div class="rex-space-8px"></div>

	<a href="dashboardManageAccount.php">
		<div class="rex-selectable-item-background rex-pt-8px rex-pb-8px rex-fs-small rex-color-black">
			Manage account
		</div>
	</a>
	
	<div class="rex-space-8px"></div>

	<a href="dashboardAdmin.php" class="<?php if($user['isAdmin']){echo 'rex-display-block';}else{echo 'rex-display-gone';}?>">
		<div class="rex-selectable-item-background rex-pt-8px rex-pb-8px rex-fs-small rex-color-black">
			Admin
		</div>
	</a>
	
	<div class="rex-space-8px"></div>

	<a href="memberLogin.php">
		<div class="rex-selectable-item-background rex-pt-8px rex-pb-8px rex-fs-small rex-color-black">
			Logout
		</div>
	</a>

	<div class="rex-space-32px"></div>
	<div class="rex-space-32px"></div>
</div>