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
		console.log("Setup profile tab");
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
		return "php/getProfile.php";
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

