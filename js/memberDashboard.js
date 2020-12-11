var tabs;

var activeTabIndex = 0;

var ajaxRequest;

window.onload = () => {
	setup();
	handleClickListeners();
};

function setup(){
	setupTabs();
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

function handleClickListeners(){
	handleSideNavClickListener();
}

function handleSideNavClickListener(){
	let sidebarTabs = getSidebarTabs();
	for(let i = 0; i < sidebarTabs.length; i++){
		sidebarTabs[i].onclick = () => {
			if(activeTabIndex != i || activeTabIndex == 3){
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

function uploadProfileImageCallback(){
	tabs[0].uploadProfileImageCallback();
}
