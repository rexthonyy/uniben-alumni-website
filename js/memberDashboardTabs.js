class Tab {
	constructor(tabIndex, display){
		this.tabIndex = tabIndex;
		this.display = display;
	}

	click(){
		this.deselectAllSideNavTabs();
		this.selectSideNavTab(this.tabIndex);
		this.sendRequest(this.getRouteUrl(), tabCallback, this.getData());
	}

	getRouteUrl(){}
	getData(){}
	clearData(){}
	setup(data){}

	//this is called from dashboard.js
	tabCallback(data){
		this.setup(data);
		this.display.setup();
	}

	close(){
		this.clearData();
		this.display.close();
	}

	sendRequest(url, callback, data){
		sendPostRequest(url, data)
		.then(json => {
			callback(json);
		}).catch(err => {
			console.error(err);
		});
	}

	deselectAllSideNavTabs(){
		let sidebarTabs = getSidebarTabs();
		for(let i = 0; i < sidebarTabs.length; i++){
			sidebarTabs[i].className = "sidebarItem";
		}
	}

	selectSideNavTab(index){
		let sideNavTabs = getSidebarTabs();
		sideNavTabs[index].className = "sidebarItem activeSidebarItem";
	}
}

class MyProfileTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new MyProfileDisplay(tabIndex));
	}

	getRouteUrl(){
		return "php/getProfile.php";
	}

	getData(){
		return { 'index': this.tabIndex, 'userId': sessionStorage.getItem("userId") };
	}

	setup(data){
		if(data.status == 'success'){
			this.loadData(data);
			this.setClickListener();
		}else{
			window.open("index.html", '_self');
		}
	}

	loadData(data){
		let memberImageSrc = data.imageLink == '' ? 'images/icons/ic_avatar.PNG' : data.imageLink;
		getUserImage().src = memberImageSrc;
		getUserId().value = sessionStorage.getItem("userId");
		getVerificationStatus().textContent = data.isVerified=='y'?'Verified':'Unverified';
		getVerificationStatus().className = data.isVerified=='y'?'greenColor':'redColor';
		getTitle().value = data.title;
		getFirstName().value = data.firstName;
		getLastName().value = data.lastName;
		getPhoneNumber().value = data.phoneNumber;
		getAddress().value = data.address;
		getCountry().value = data.country;
		getState().value = data.state;
		getLocalGovernment().value = data.localgov;
		getOccupation().value = data.occupation;
		getReligion().value = data.religion;

		//get the side bar tabs
		getSidebarTabs()[1].style.display = 'none';
		getSidebarTabs()[3].style.display = data.isAdmin=='y'?'':'none';
	}

	setClickListener(){
		this.handleUploadImageClick();
		this.handleUpdateProfileBtnClick();
	}

	handleUploadImageClick(){
		let canUpload = true;
		getUserImage().onclick = function(event){
			let fileUploader = getUploadFile();
			fileUploader.addEventListener("change", function(event){
				if(canUpload){
					canUpload = false;
					ajaxRequest = new AjaxRequest();
					ajaxRequest.initialize();

					Display.CloseDisplayView();

					ajaxRequest.upload(getUploadForm(), "php/uploadProfileImage.php", uploadProfileImageCallback, e => {
						const percent = e.lengthComputable ? (e.loaded / e.total) * 100 : 0;
						console.log("Upload percent : " + percent + "%");
						if(percent >= 90){
							canUpload = true;
						}
					});
				}else{
					console.log("trying to upload again");
				}
			});
			fileUploader.click();
		};
	}

	uploadProfileImageCallback(){
		if(ajaxRequest.getReadyState() == 4 && ajaxRequest.getStatus() == 200){
			let jsonString = ajaxRequest.getResponseText();

			console.log(jsonString);

			let jsonObj = JSON.parse(jsonString);

			Display.OpenDisplayView();

			getUserImage().src = jsonObj.imageLink;
		}
	}

	handleUpdateProfileBtnClick(){
		getUpdateProfileBtn().onclick = () => {
			if(!getFirstName().value || !getLastName().value){
				alert("Please enter your name");
			}else{
				let title = getTitle().value;
				let firstName = getFirstName().value;
				let lastName = getLastName().value;
				let phoneNumber = getPhoneNumber().value;
				let address = getAddress().value;
				let country = getCountry().value;
				let state = getState().value;
				let localgov = getLocalGovernment().value;
				let occupation = getOccupation().value;
				let religion = getReligion().value;

				Display.CloseDisplayView();

				let url = 'php/updateProfile.php';

				let data = { userId: sessionStorage.getItem('userId'), title: title, firstName: firstName, lastName: lastName, phoneNumber: phoneNumber, address: address, country: country, state: state, localgov: localgov, occupation: occupation, religion: religion };

				sendPostRequest(url, data)
				.then(json => {
					Display.OpenDisplayView();
					alert("Profile updated successfully");
				}).catch(err => {
					console.error(err);
				});
			}
		};
	}

	clearData(){
		console.log("Clear profile data");
	}
}

class PaymentHistoryTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new PaymentHistoryDisplay(tabIndex));
	}

	click(){
		super.click();
		super.tabCallback(undefined);
	}

	sendRequest(url, callback, data){}

	setup(data){
		console.log("Payment history tab");
	}

	clearData(){
		console.log("Clear payment history data");
	}
}

class ManageAccountTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new ManageAccountDisplay(tabIndex));
	}

	click(){
		super.click();
		super.tabCallback(undefined);
		this.setClickListener();
	}

	sendRequest(url, callback, data){}

	setClickListener(){
		this.handleChangeEmailBtnClick();
		this.handleChangePasswordBtnClick();
	}

	handleChangeEmailBtnClick(){
		getChangeEmailBtn().onclick = () => {
			let newEmail = getNewEmail().value;
			let currentPassword = getChangeEmailPassword().value;

			if(!newEmail || !currentPassword){
				alert("Please enter your new email and current password");
			}else{
				Display.CloseDisplayView();

				let url = 'php/changeEmail.php';

				let data = { userId: sessionStorage.getItem('userId'), email: newEmail, password: currentPassword };

				sendPostRequest(url, data)
				.then(json => {
					Display.OpenDisplayView();
					if(json.status == 'success'){
						alert("Email updated successfully");
						getNewEmail().value = '';
						getChangeEmailPassword().value = '';
					}else{
						alert(json.error);
					}
				}).catch(err => {
					console.error(err);
				});
			}
		};
	}

	handleChangePasswordBtnClick(){
		getChangePasswordBtn().onclick = () => {
			let currentPassword = getChangePasswordCurrentPassword().value;
			let newPassword1 = getChangePasswordNewPassword().value;
			let newPassword2 = getChangePasswordRetypePassword().value;

			if(!currentPassword || !newPassword1 || !newPassword2){
				alert("Please fill in the empty fields");
			}else{
				if(newPassword1 !== newPassword2){
					alert("Passwords do not match");
				}else{
					if(newPassword1.length < 6){
						alert("Password must be greater than 6 characters");
					}else{
						Display.CloseDisplayView();

						let url = 'php/changePassword.php';

						let data = { userId: sessionStorage.getItem('userId'), currentPassword: currentPassword, newPassword: newPassword1 };

						sendPostRequest(url, data)
						.then(json => {
							Display.OpenDisplayView();
							if(json.status == 'success'){
								alert("Password updated successfully");
								getChangePasswordCurrentPassword().value = '';
								getChangePasswordNewPassword().value = '';
								getChangePasswordRetypePassword().value = '';
							}else{
								alert(json.error);
							}
						}).catch(err => {
							console.error(err);
						});
					}
				}
				
			}
		};
	}

	clearData(){
		console.log("Clear manage account data");
	}
}

class AdminTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new AdminDisplay(tabIndex));
		this.activeView = 0;
		this.views = [
			new AdminView(this, 0),
			new SearchMembersView(this, 1),
			new MemberProfileView(this, 2),
			new EditPageView(this, 3),
			new NationalExcosView(this, 4)
		];
	}

	click(){
		this.deselectAllSideNavTabs();
		this.selectSideNavTab(this.tabIndex);
		this.display.setup();
		this.views[this.activeView].click();//display the admin view
	}

	//overloaded function
	tabCallback(data){
		this.views[data.viewIndex].tabCallback(data);
	}

	clearData(){
		this.views[this.activeView].close();
		this.activeView = 0;
	}
}

class LogoutTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new LogoutDisplay(tabIndex));
	}

	click(){
		window.open('index.html', '_self');
	}

	clearData(){
		console.log("Clear logout data");
	}
}

