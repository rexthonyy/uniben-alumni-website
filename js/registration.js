window.onload = () => {
	getSubmitBtn().onclick = () => {
		let firstName = getFirstName().value.trim();
		let lastName = getLastName().value.trim();
		let email = getEmail().value.toLowerCase().trim();
		let password1 = getPassword1().value.trim();
		let password2 = getPassword2().value.trim();

		if(!firstName || !lastName || !email || !password1 || !password2){
			showError("Please fill in all fields");
		}else{
			if(isEmailValid(email)){
				if(password1 === password2){
					if(password1.length >= 6){
						register(firstName, lastName, email, password1);
					}else{
						showError("Password should be at least 6 characters");
					}
				}else{
					showError("Passwords do not match");
				}
			}else{
				showError("Email is not valid");
			}
		}
	};
}

function register(firstName, lastName, email, password){
	getMainContainer().style.display = "none";
	getProgressContainer().style.display = "block";

	let data = { email: email };
	let url = "php/register.php";

	sendPostRequest(url, data)
	.then(json => {
		if(json.status == "success"){
			sessionStorage.setItem("firstName", firstName);
			sessionStorage.setItem("lastName", lastName);
			sessionStorage.setItem("email", email);
			sessionStorage.setItem("password", password);
			getProgressContainer().style.display = "none";
			window.open("registerVerification.html", "_self");
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

function getFirstName(){
	return document.getElementById("firstName");
}

function getLastName(){
	return document.getElementById("lastName");
}

function getEmail(){
	return document.getElementById("email");
}

function getPassword1(){
	return document.getElementById("password1");
}

function getPassword2(){
	return document.getElementById("password2");
}

function getSubmitBtn(){
	return document.getElementById("submit");
}

function getErrorMessage(){
	return document.getElementById("errorMsg");
}