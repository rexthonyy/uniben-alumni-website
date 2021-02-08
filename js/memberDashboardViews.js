class Views {
	constructor(tab, viewIndex, display){
		this.tab = tab;
		this.viewIndex = viewIndex;
		this.display = display;
	}

	click(){
		this.sendRequest(this.getRouteUrl(), tabCallback, this.getData());
	}

	close(){
		this.clearData();
		this.display.close();
	}

	getRouteUrl(){}
	getData(){}
	clearData(){}
	setup(data){}

	//this is called from dashboardTabs.js
	tabCallback(data){
		this.setup(data);
		this.display.setup();
	}

	sendRequest(url, callback, data){
		sendPostRequest(url, data)
		.then(json => {
			callback(json);
		}).catch(err => {
			console.error(err);
		});
	}
}

class AdminView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new AdminViewDisplay(viewIndex));
	}

	getRouteUrl(){
		return "php/getUserStats.php";
	}

	getData(){
		return { 'index': this.tab.tabIndex, 'viewIndex': this.viewIndex, 'userId': sessionStorage.getItem("userId") };
	}

	setup(data){
		this.loadData(data);
		this.setClickListener();
	}

	loadData(data){
		getNumRegisteredMembers().textContent = data.numMembers;
		getNumVerifiedMembers().textContent = data.numVerified;
		getNumUnverifiedMembers().textContent = data.numUnverified;
	}

	setClickListener(){
		getSearchMembersBtn().onclick = () => {
			let searchEntry = getSearchMembersInput().value.trim();;
			if(searchEntry){

				sessionStorage.setItem("search", searchEntry);

				this.tab.views[this.viewIndex].close();
				this.tab.views[1].click();
				this.tab.activeView = 1;
			}
		};

		getEditHistoryLink().onclick = () => {
			sessionStorage.setItem("pageId", 1);//history

			this.tab.views[this.viewIndex].close();
			this.tab.views[3].click();
			this.tab.activeView = 3;
		};

		getEditConstitutionLink().onclick = () => {
			sessionStorage.setItem("pageId", 2);//constitution

			this.tab.views[this.viewIndex].close();
			this.tab.views[3].click();
			this.tab.activeView = 3;
		};

		getEditMissionAndVisionLink().onclick = () => {
			sessionStorage.setItem("pageId", 3);//mission and vision

			this.tab.views[this.viewIndex].close();
			this.tab.views[3].click();
			this.tab.activeView = 3;
		};

		getEditNationalChairmanLink().onclick = () => {
			sessionStorage.setItem("pageId", 4);//speech by the national chairman

			this.tab.views[this.viewIndex].close();
			this.tab.views[3].click();
			this.tab.activeView = 3;
		};

		getEditNationalExcosLink().onclick = () => {
			this.tab.views[this.viewIndex].close();
			this.tab.views[4].click();
			this.tab.activeView = 4;
		};

		getEditPastNationalChairmenLink().onclick = () => {
			alert("Edit past national chairmen");
		};

		getEditManagePostsLink().onclick = () => {
			alert("Edit manage posts");
		};

		getEditPhotoGalleryLink().onclick = () => {
			alert("Edit photo gallery");
		};
	}

	clearData(){
		console.log("Close Admin view");
	}
}

class SearchMembersView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new SearchMembersViewDisplay(viewIndex));
		this.userList = new Array();
	}

	getRouteUrl(){
		return "php/getMembers.php";
	}

	getData(){
		return { 'index': this.tab.tabIndex, 'viewIndex': this.viewIndex, 'search': sessionStorage.getItem("search") };
	}

	setup(data){
		this.loadData(data);
		this.setClickListener();
	}

	loadData(data){

		//set the input search
		getSearchResultMembersInput().value = sessionStorage.getItem("search");

		//get the search items
		this.userList = data.users;

		if(this.userList.length > 0){

			getSearchResultMembersContainer().style.display = 'block';
			getNoResultMembersContainer().style.display = 'none';

			let html = '';

			this.userList.forEach(user => {
				//update the ui with the search results
				let memberId = user.id;
				let imageLink = user.imageLink == '' ? 'images/icons/ic_avatar.PNG' : user.imageLink;
				let username = user.firstName + " " + user.lastName;
				let isVerified = user.isVerified === 'y' ? true : false;
				let colorClass = isVerified ? 'greenColor' : 'redColor';
				let verifyStatus = isVerified ? 'Verified' : 'Unverified';

				html += `
					<table id="${memberId}" class="width-100-pp pad16px hover gray-background-onhover searchMemberResult">
						<tr id="${memberId}">
							<td id="${memberId}" class="width-30px">
								<img id="${memberId}" class="width-60px height-60px curDiv-30px mr-16px hover" src="${imageLink}"/>
							</td>
							<td id="${memberId}">
								<div id="${memberId}" class="table-alignMiddle">
									<div id="${memberId}" class="fs-normal bold">
										${username}
									</div>
									<div id="${memberId}" class="${colorClass}">
										${verifyStatus}
									</div>
								</div>
							</td>
						</tr>
					</table>
				`;
			});
			getSearchResultMembersContainer().innerHTML = html;
		}else{
			getSearchResultMembersContainer().style.display = 'none';
			getNoResultMembersContainer().style.display = 'block';
		}
	}

	setClickListener(){
		getSearchResultMembersBtn().onclick = () => {
			let searchEntry = getSearchResultMembersInput().value.trim();;
			if(searchEntry){

				sessionStorage.setItem("search", searchEntry);

				this.tab.views[this.viewIndex].close();
				this.tab.views[1].click();
				this.tab.activeView = 1;
			}
		};

		for(let i = 0; i < getSearchResultMembers().length; i++){
			getSearchResultMembers()[i].onclick = e => {
				let memberId = e.target.id;

				sessionStorage.setItem("memberId", memberId);

				//open user profile display view;
				this.tab.views[this.viewIndex].close();
				this.tab.views[2].click();
				this.tab.activeView = 2;
			};
		}
	}
}

class MemberProfileView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new MemberProfileViewDisplay(viewIndex));
	}

	getRouteUrl(){
		return "php/getProfile.php";
	}

	getData(){
		return { 'index': this.tab.tabIndex, 'viewIndex': this.viewIndex, 'userId': sessionStorage.getItem("memberId") };
	}

	setup(data){
		this.loadData(data);
		this.setClickListener();
	}

	loadData(data){
		this.user = data;
		let memberImageSrc = data.imageLink == '' ? 'images/icons/ic_avatar.PNG' : data.imageLink;
		getMemberImage().src = memberImageSrc;
		getMemberVerificationStatus().textContent = data.isVerified=='y'?'Verified':'Unverified';
		getMemberVerificationStatus().className = data.isVerified=='y'?'greenColor':'redColor';
		getMemberTitle().textContent = data.title;
		getMemberFirstName().textContent = data.firstName;
		getMemberLastName().textContent = data.lastName;
		getMemberPhoneNumber().textContent = data.phoneNumber;
		getMemberAddress().textContent = data.address;
		getMemberCountry().textContent = data.country;
		getMemberState().textContent = data.state;
		getMemberLocalGovernment().textContent = data.localgov;
		getMemberOccupation().textContent = data.occupation;
		getMemberReligion().textContent = data.religion;

		getVerifyMemberBtn().textContent = data.isVerified=='y'?'UNVERIFY':'VERIFY';
	}

	setClickListener(){
		getCancelMemberProfileBtn().onclick = () => {
			this.tab.views[this.viewIndex].close();
			this.tab.views[1].click();
			this.tab.activeView = 1;
		};

		getVerifyMemberBtn().onclick = () => {

			let verifyStatus = this.user.isVerified=='y' ? 'n':'y';

			let url = 'php/updateMemberVerification.php';

			let data = { userId: sessionStorage.getItem('memberId'), isVerified: verifyStatus };

			this.tab.views[this.viewIndex].close();

			sendPostRequest(url, data)
			.then(json => {
				this.tab.views[2].click();
				this.tab.activeView = 2;
			}).catch(err => {
				console.error(err);
			});
		};

		deleteMemberBtn().onclick = () => {
			let url = 'php/deleteMember.php';

			let data = { userId: sessionStorage.getItem('memberId') };

			this.tab.views[this.viewIndex].close();

			sendPostRequest(url, data)
			.then(json => {
				this.tab.views[1].click();
				this.tab.activeView = 1;
			}).catch(err => {
				console.error(err);
			});
		};
	}
}

class EditPageView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new EditPageViewDisplay(viewIndex));
	}

	getRouteUrl(){
		return "php/getPage.php";
	}

	getData(){
		return { 'index': this.tab.tabIndex, 'viewIndex': this.viewIndex, 'pageId': sessionStorage.getItem("pageId") };
	}

	setup(data){
		this.loadData(data);
		this.setTabPressListener();
		this.setInputChangeListener();
		this.setClickListener();
	}

	loadData(data){
		getPageTitle().value = data.title;
		getPostContent().value = data.content;
		this.updatePreview();
	}

	setTabPressListener(){
		getPostContent().onkeydown = function(e){
			if(e.keyCode == 9 || e.which == 9){
				e.preventDefault();
				let s = this.selectionStart;
				this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
				this.selectionEnd = s + 1;
			}
		};
	}

	setInputChangeListener(){
		let func = () => {
			this.updatePreview();
		}

		getPageTitle().addEventListener("input", func);
		getPostContent().addEventListener("input", func);
	}

	updatePreview(){
		getPageTitlePreview().textContent = getPageTitle().value;
		getPostContentPreview().innerHTML = getPostContent().value;
	}

	setClickListener(){

		getCancelPageBtn().onclick = () => {
			this.tab.views[this.viewIndex].close();
			this.tab.views[0].click();
			this.tab.activeView = 0;
		};

		getSavePageBtn().onclick = () => {
			let pageTitle = getPageTitle().value;
			let pageContent = getPostContent().value;

			if(!pageTitle) return;
			if(!pageContent) return;

			let url = 'php/updatePage.php';

			let data = { pageId: sessionStorage.getItem('pageId'), title: pageTitle, content: pageContent };

			this.tab.views[this.viewIndex].close();

			sendPostRequest(url, data)
			.then(json => {
				alert("Page updated successfully");
				this.tab.views[0].click();
				this.tab.activeView = 0;
			}).catch(err => {
				console.error(err);
			});
		};
	}

	clearData(){
		getPostContent().onkeydown = () => {};
	}
}

class NationalExcosView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new NationalExcosViewDisplay(viewIndex));
		this.excosList = new Array();
	}

	getRouteUrl(){
		return "php/getNationalExcos.php";
	}

	getData(){
		return { 'index': this.tab.tabIndex, 'viewIndex': this.viewIndex };
	}

	setup(data){
		this.loadData(data);
		this.setClickListener();
	}

	loadData(data){
		console.log(data);
		if(data.status == 'success'){
			this.excosList = data;

			let html = '';

			data.excos.forEach(exco => {

				let excoId = exco.id;
				let isDeletable = exco.isDeletable == 'y';
				let name = exco.name;
				let title = exco.title;
				let imageLink = exco.imageLink;

				let buttonHTML = '';

				if(isDeletable){
					buttonHTML = `
						<div class="grid-2 grid-column-gap-5px">
							<div>
								<button class="width-100-pp hover" id="editExcoBtn${excoId}">Edit</button>
							</div>
							<div>
								<button class="width-100-pp hover" id="deleteExcoBtn${excoId}">Delete</button>
							</div>
						</div>
					`;
				}else{
					buttonHTML = `
						<button class="width-100-pp hover" id="editExcoBtn${excoId}">Edit</button>
					`;
				}

				html += `
					<div class="pad8px">
						<div class="width-100pp height-200px mb-16px" id="excoImage${excoId}"></div>
						<div class="fs-normal bold">${name}</div>
						<div class="fs-normal mb-16px">${title}</div>
						${buttonHTML}
					</div>
				`;
			});

			getExcosContainer().innerHTML = html;

			getNoExcosContainer().style.display = 'none';
			getExcosContainer().style.display = 'grid';

			data.excos.forEach(exco => {
				let excoId = exco.id;
				let imageLink = exco.imageLink == '' ? 'images/icons/ic_avatar.PNG' : exco.imageLink;


				let excoImage = document.getElementById(`excoImage${excoId}`);

				excoImage.style.backgroundImage = `url("${imageLink}")`;
				excoImage.style.backgroundPosition = 'center center';
				excoImage.style.backgroundSize = 'cover';
			});

		}else{
			getNoExcosContainer().style.display = 'block';
			getExcosContainer().style.display = 'none';
		}
	}

	setClickListener(){
		getAddNationalExcosBtn().onclick = () => {
			this.tab.views[this.viewIndex].close();
			this.tab.views[5].click();
			this.tab.activeView = 5;
		};

		this.excosList.forEach(exco =>{
			document.getElementById(`editExcoBtn${excoId}`).onclick = () => {

			};
		});
	}

	clearData(){
		
	}
}

class AddNationalExcoView extends Views {
	constructor(tab, viewIndex){
		super(tab, viewIndex, new ViewsDisplay(viewIndex));
	}

	click(){
		this.setup();
		this.display.setup();
	}

	setup(){
		this.setClickListener();
	}

	setClickListener(){

		getAddExcoImage().onclick = () => {
			getAddExcoUploadFile().addEventListener("change", (event) => {
				getAddExcoImage().src = URL.createObjectURL(event.target.files[0]);
				getAddExcoImage().onload = () => URL.revokeObjectURL(getAddExcoImage().src);
			});

			getAddExcoUploadFile().click();
		};

		getCancelAddExcoBtn().onclick = () => {
			this.tab.views[this.viewIndex].close();
			this.tab.views[4].click();
			this.tab.activeView = 4;
		};

		getSaveExcoBtn().onclick = () => {
			let excoName = getAddExcoNameInput().value;
			let excoPosition = getAddExcoPositionInput().value;

			if(!excoName || !excoPosition) return;

			let form = new FormData();
			let file = getAddExcoUploadFile().files[0];
			form.append('file', file);
			form.append('name', excoName);
			form.append('position', excoPosition);

			let url = 'php/addNationalExecutive.php';

			this.tab.views[this.viewIndex].close();

			sendFormDataRequest(url, form)
			.then(json => {
				alert("Executive added successfully");
				this.tab.views[4].click();
				this.tab.activeView = 4;
			}).catch(err => {
				console.error(err);
			});
		};
	}

	clearData(){
		getAddExcoUploadForm().reset();
	}
}

