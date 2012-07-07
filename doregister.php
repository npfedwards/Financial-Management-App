<?php
	include 'functions.php';
	opendb();

	$email=mysql_real_escape_string(htmlentities($_POST['email']));
	$pass=mysql_real_escape_string(htmlentities($_POST['password']));
	$repeatpass=mysql_real_escape_string(htmlentities($_POST['repeatpassword']));
	$prefcurrency=mysql_real_escape_string(htmlentities($_POST['prefcurrency']));
	
	$msg = NULL;

	if(strlen($pass)<6){
		$msg = $msg . "Your passsword is too short! ";
	}

	if($pass!=$repeatpass){
		$msg = $msg . "Your passwords don't match! ";
	}

	if($email==NULL||$pass==NULL||$repeatpass==NULL){
		$msg = $msg . "One of the fields is empty! ";
	}

	if($msg==NULL){
		$query="SELECT * FROM users WHERE Email='$email'";
		$result = mysql_query($query) or die(mysql_error());

		if(mysql_num_rows($result)==0){			
			$time = time() + 604800;
			$salt = generatesalt();
			$hash = sha1($pass . $salt);
			$validationkey = sha1(generatesalt(64));
			$query="INSERT INTO users (Email, Password, Salt, ValidatedTimeout, ValidationKey, PrefCurrency) VALUES ('$email', '$hash', '$salt', '$time', '$validationkey', '&pound;')";
			//insrt
			mysql_query($query) or die(mysql_error());
			$UserID = mysql_insert_id();
			$query="INSERT INTO accounts (UserID, AccountName) VALUES ('$UserID', 'Current')";
			mysql_query($query) or die(mysql_error());
			sendvalidationkey($email, $validationkey, $UserID);
			$msg = $msg . "Success";
			}else{
				$msg = $msg . "You've already registered with that email! ";
			}
	}

	echo $msg;
?>