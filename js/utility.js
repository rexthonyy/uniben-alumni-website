function stopClickPropagation(e){
	if(!e) e = window.event;
	if(e.stopPropagation){
		e.stopPropagation();
	}else{
		e.cancelBubble = true;
	}
}

function isEmailValid(email){
	const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function wait(time, func){
	setTimeout(() => {
		func();
	}, time);
}

function getRandom(min, max){
	return myMap(Math.random(), 0, 1, min, max);
}

function myMap(val, minF, maxF, minT, maxT){
	return minT + (((val - minF)/(maxF - minF)) * (maxT - minT));
}

async function sendPostRequest(url, data){
	let response = await fetch(url, {
		method: "POST",
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});
	
	let json = await response.json();

	return json;
}

async function sendFormDataRequest(url, form){
	let response = await fetch(url, {
		method: 'post',
		body: new FormData(form)
	});

	let json = await response.json();

	return json;
}

function AjaxRequest(){
	this.request = null;
	
	this.initialize = function (){
		if(window.XMLHttpRequest){
			try{
				this.request = new XMLHttpRequest();
			}catch(e){
				this.request = null;
			}
		//Now try the ActiveX (IE) version
		}else if(window.ActiveXObject){
			try{
				this.request = new ActiveXObject("Msxml2.XMLHTTP");
			//Try the older ActiveX object for older versions of IE
			}catch(e){
				try{
					this.request = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(e){
					this.request = null;
				}
			}
		}
	};
	
	this.getReadyState = function (){
		return this.request.readyState;
	};
	
	this.getStatus = function (){
		return this.request.status;
	};
	
	this.getResponseText = function (){
		return this.request.responseText;
	};
	
	this.getResponseXML = function (){
		return this.request.responseXML;
	};
	
	this.abort = function (){
		this.request.abort();
	};
	
	this.send = function (type, url, handler, postDataType, postData){
		if(this.request != null){
			//Kill the previous request
			this.abort();
			
			//Tack on a dummy parameter to override browser caching
			url += "?dummy=" + new Date().getTime();
			
			try{
				this.request.onreadystatechange = handler;
				this.request.open(type, url, true);	//always asynchronous (true)
				if(type.toLowerCase() == "get"){
					//Send a GET request
					this.request.send(null);
				}else{
					//Send a POST request
					this.request.setRequestHeader("Content-Type", postDataType);
					this.request.send(postData);
				}
			}catch(e){
				alert("Ajax error communicating with the server.\n"+"Details:"+e);
			}
		}
	};

	this.upload = function (form, url, handler, progressListener){
		if(this.request != null){
			this.abort();

			try{
				this.request.onreadystatechange = handler;
				this.request.open("POST", url, true);
				this.request.upload.addEventListener("progress", progressListener);
				//this.request.setRequestHeader("Content-Type", "multipart/form-data; charset=utf-8; boundary="+Math.random().toString().substr(2));
				//this.request.setRequestHeader("Content-Type", "multipart/form-data");
				let formData = new FormData(form);
				console.log(formData);
				this.request.send(formData);
			}catch(e){
				alert("Ajax error communicating with the server.\n"+"Details:"+e);
			}
		}
	};
}

var months = ["January", "Febuary", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function copyToClipboard(text){
	var dummy = document.createElement("textarea");
	document.body.appendChild(dummy);
	dummy.value = text;
	dummy.select();
	document.execCommand("copy");
	document.body.removeChild(dummy);
}