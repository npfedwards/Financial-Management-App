<?php

	include_once 'functions.php';
	$conn=opendb();

	$submittedkey=sanitise('k');
	$UserID=sanitise('id');
	$pass=sanitise('password', 'p');
	$repeatpass=sanitise('repeat','p');
	
	$msg=NULL;
	
	
	if($pass==$repeatpass){
		if(strlen($pass)>5){

			$query="SELECT * FROM users WHERE UserID='$UserID' AND ResetKey='$submittedkey'";
			$result=mysql_query($query) or die(mysql_error());


			if(mysql_num_rows($result)==1){
				$row = mysql_fetch_array($result);
				if(time()<$row['ResetTimeout']){
					$salt = generatesalt();
					$hash = sha1($pass . $salt);

					$query="UPDATE users SET Password='$hash', ResetKey='', ResetTimeout='', Salt='$salt' WHERE UserID='$UserID'";
					$result=mysql_query($query) or die (mysql_error());
					$msg = $msg."Sucess! Now you just need to <a href=\"index.php\">log back in!</a>";
				} else {
					$msg = $msg."Your key's expired - they're only valid for 1 week! Try getting another one! ";
				}
			} else {
				$msg = $msg."Invalid key! ";
			}

			
		}else{
			$msg = $msg."Your password's too short! It needs to be at least 6 characters long! ";
		}
	}else{
		$msg = $msg."Password's don't match! ";
	}
	echo $msg;
?>