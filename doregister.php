<?php
	include 'functions.php';
	opendb();

	$email=mysql_real_escape_string(htmlentities($_POST['email']));
	$pass=mysql_real_escape_string(htmlentities($_POST['password']));
	$repeatpass=mysql_real_escape_string(htmlentities($_POST['repeatpassword']));

	if($pass==$repeatpass){
		if($email!=NULL&&$pass!=NULL&&$repeatpass!=NULL){
			$time = time() + 604800;
			$salt = generatesalt();
			$hash = sha1($pass . $salt);
			$query="INSERT INTO users (Email, Password, Salt, ValidatedTimeout) VALUES ('$email', '$hash', '$salt', '$time')";
			mysql_query($query) or die(mysql_error());
			echo "Success!";
		}else{

		}
	}


	//if($_POST['email'])

	
?>