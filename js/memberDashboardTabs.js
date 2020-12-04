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
		this.loadData(data);
		this.setClickListener();
	}

	loadData(data){
		getUserImage().src = data.imageLink;
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
					console.log(json.status);
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

	getRouteUrl(){
		return "php/dashboardTab.php";
	}

	getData(){
		return { 'index': this.tabIndex, 'userId': sessionStorage.getItem("userId") };
	}

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

	getRouteUrl(){
		return "php/getProfile.php";
	}

	getData(){
		return { 'index': this.tabIndex, 'userId': sessionStorage.getItem("userId") };
	}

	setup(data){
		console.log("Manage account tab");
	}

	clearData(){
		console.log("Clear manage account data");
	}
}

class AdminTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new AdminDisplay(tabIndex));
	}

	getRouteUrl(){
		return "php/getProfile.php";
	}

	getData(){
		return { 'index': this.tabIndex, 'userId': sessionStorage.getItem("userId") };
	}

	setup(data){
		console.log("Admin tab");
	}

	clearData(){
		console.log("Clear admin data");
	}
}

class LogoutTab extends Tab {
	constructor(tabIndex){
		super(tabIndex, new LogoutDisplay(tabIndex));
	}

	getRouteUrl(){
		return "php/getProfile.php";
	}

	getData(){
		return { 'index': this.tabIndex, 'userId': sessionStorage.getItem("userId") };
	}

	setup(data){
		console.log("Logout tab");
	}

	clearData(){
		console.log("Clear logout data");
	}
}

