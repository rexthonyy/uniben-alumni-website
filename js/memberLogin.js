window.onload = () => {
	getSubmitBtn().onclick = () => {
		let email = getEmail().value.toLowerCase().trim();
		let password = getPassword().value.trim();

		if(!email || !password){
			showError("Please fill in all fields");
		}else{
			if(isEmailValid(email)){
				if(password.length >= 6){
					login(email, password);
				}else{
					showError("Password should be at least 6 characters");
				}
			}else{
				showError("Email is not valid");
			}
		}
	};
}

function login(email, password){
	getMainContainer().style.display = "none";
	getProgressContainer().style.display = "block";

	let data = { email: email, password: password };
	let url = "php/login.php";

	sendPostRequest(url, data)
	.then(json => {
		if(json.status == "success"){
			sessionStorage.setItem('userId', json.userId);
			getProgressContainer().style.display = "none";
			window.open("memberDashboard.html", "_self");
		}else{
			getMainContainer().style.display = "block";
			getProgressContainer().style.display = "none";
			showError(json.error, 5000);
		}
	}).catch(err => {
		console.error(err);
	});
}

function showError(msg, duration = 5000){
	getErrorMessage().textContent = msg;
	getErrorMessage().style.display = "block";
	setTimeout(function(){
		getErrorMessage().style.display = "none";
	}, duration);
}

function getMainContainer(){
	return document.getElementById("mainContainer");
}

function getProgressContainer(){
	return document.getElementById("progressContainer");
}

function getEmail(){
	return document.getElementById("email");
}

function getPassword(){
	return document.getElementById("password");
}

function getSubmitBtn(){
	return document.getElementById("submit");
}

function getErrorMessage(){
	return document.getElementById("errorMsg");
}