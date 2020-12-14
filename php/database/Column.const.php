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
		const IS_ADMIN = "isAdmin";
		const IS_VERIFIED = "isVerified";
		//const EMAIL = "email";
		const PASSWORD = "password";
		const FIRST_NAME = "firstName";
		const LAST_NAME = "lastName";
		const IMAGELINK = "imageLink";
		const TITLE = "title";
		const PHONENUMBER = "phoneNumber";
		const ADDRESS = "address";
		const COUNTRY = "country";
		const STATE = "state";
		const LOCALGOV = "localgov";
		const OCCUPATION = "occupation";
		const RELIGION = "religion";
		//const CREATED = "createdAt";

		//pages_tb
		//const TITLE = "title";
		const CONTENT = "content";

		//national_excos_tb
		const IS_DELETABLE = "isDeletable";
		const NAME = "name";
		//const TITLE = "title";
		//const IMAGELINK = "imageLink";
	}
?>