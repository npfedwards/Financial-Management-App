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
			$query="INSERT INTO users (Email, Password, Salt, ValidatedTimeout) VALUES ('$email', '$pass', '$salt', '$time')";
		}else{

		}
	}


	//if($_POST['email'])

	
?>