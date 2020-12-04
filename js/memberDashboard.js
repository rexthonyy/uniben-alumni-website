var tabs;

var activeTabIndex = 0;

window.onload = () => {
	setup();
	handleClickListeners();
};

function setup(){
	setupTabs();
	setupDisplay();
}

function setupTabs(){
	tabs = [
		new MyProfileTab(0),
		new PaymentHistoryTab(1),
		new ManageAccountTab(2),
		new AdminTab(3),
		new LogoutTab(4)
	];
}

function setupDisplay(){
	let imageLink = undefined;
	if(sessionStorage.getItem('imageLink')){
		imageLink = sessionStorage.getItem('imageLink');
	}else{
		imageLink = 'images/icons/ic_avatar.PNG';
	}
	getUserImage().src = imageLink;
	getUserName().textContent = sessionStorage.getItem('firstName') + " " + sessionStorage.getItem('lastName');
}

function handleClickListeners(){
	handleSideNavClickListener();
}

function handleSideNavClickListener(){
	let sidebarTabs = getSidebarTabs();
	for(let i = 0; i < sidebarTabs.length; i++){
		sidebarTabs[i].onclick = () => {
			if(activeTabIndex != i){
				tabs[activeTabIndex].close();
				tabs[i].click();
				activeTabIndex = i;
			}
		};
	}
	tabs[activeTabIndex].click();
}


function tabCallback(data){
	tabs[data.index].tabCallback(data);
}
