window.onload = function(){
	//set listeners
	setVerificationInfo();
	setVerifyButtonClickListener();
}

function setVerificationInfo(){
	getInfoParagraph().textContent = 
	"We just sent you a message via email to "+sessionStorage.getItem("email")+" with your authentication code. "+
	"Enter the code in the form below to verify your identity.";
}

function setVerifyButtonClickListener(){
	getVerifyButton().onclick = buttonClick;

	getCodeInput().addEventListener("keyup", (event) => {
		event.preventDefault();
		if(event.keyCode === 13){
			buttonClick();
		}
	});
}

function buttonClick(){
	let code = getCodeInput().value.trim();

	if(code){
		if(!isNaN(code)){
			if(code.length == 4){
				verify(code);
			}else{
				showError("Code is 4 digits in length");
			}
		}else{
			showError("Please enter a numeric code");
		}
	}else{
		showError("Please enter your verification code");
	}
}

function verify(code){

	getMainContainer().style.display = "none";
	getProgressBar().style.display = "block";

	let firstName = sessionStorage.getItem("firstName");
	let lastName = sessionStorage.getItem("lastName");
	let email = sessionStorage.getItem("email");
	let password = sessionStorage.getItem("password");

	let data = { firstName: firstName, lastName: lastName, email: email, password: password, code: code };

	let url = "php/registerVerifyCode.php";

	sendPostRequest(url, data)
	.then(json => {
		if(json.status == "success"){
			getProgressBar().style.display = "none";
			window.open("registered.html", "_self");
		}else{
			getMainContainer().style.display = "block";
			getProgressBar().style.display = "none";
			showError(json.error, 5000);
			getCodeInput().focus();
		}
	}).catch(err => {
		console.error(err);
	});
}

function showError(error, duration = 3000){
	let errorMessage = getErrorMessage();
	errorMessage.textContent = error;
	errorMessage.style.display = "block";
	setTimeout(() => {
		errorMessage.style.display = "none";
	}, duration);
}

function getInfoParagraph(){
	return document.getElementById("infoParagraph");
}

function getCodeInput(){
	return document.getElementById("codeInput");
}

function getVerifyButton(){
	return document.getElementById("verifyButton");
}

function getErrorMessage(){
	return document.getElementById("errorMessage");
}

function getMainContainer(){
	return document.getElementById("mainContainer");
}

function getProgressBar(){
	return document.getElementById("progressBar");
}