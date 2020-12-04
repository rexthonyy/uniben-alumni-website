<?php	
	abstract class Column {
		//all
		const ID = "id";
		
		//verification_tb
		const TYPE = "type";
		const EMAIL = "email";
		const CODE = "code";
		const CREATED = "created";

		//users_tb
		//const EMAIL = "email";
		const PASSWORD = "password";
		const FIRST_NAME = "firstName";
		const LAST_NAME = "lastName";
		const IMAGELINK = "imageLink";
		//const CREATED = "createdAt";
	}
?>