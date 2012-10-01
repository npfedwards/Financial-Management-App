<?php
	include_once 'functions.php';
	checklogin();
	//make sure the user's logged in
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
		}
		
		$conn=opendb();

		$pass = sanitise('currentpassword', 'p');
		$newpass1 = sanitise('newpassword1', 'p');
		$newpass2 = sanitise('newpassword2', 'p');


		$query = "SELECT * FROM users WHERE UserID='$user'";
		$result = mysql_query($query) or die(mysql_error());


		if(mysql_num_rows($result)==1){
			$row = mysql_fetch_array($result);
			$hash = sha1($pass.$row['Salt']);

			//check that they've provided the correct password:
			if($hash==$row['Password']){
				//check that provided passwords match:
				if($newpass1==$newpass2){
					//check that new password is long enough:
					if(strlen($newpass1)>5){
						//generating some salty hashes:
						$salt=generatesalt();
						$hash=sha1($newpass1.$salt);

						//now we change their password...
						$query="UPDATE users SET Password='$hash', Salt='$salt' WHERE UserID='$user'";
						$result=mysql_query($query) or die(mysql_error());
						//tell the user that it has been sucessfull:
						$msg = $msg."Successfully changed your password!";
						$success=True;

					}else{
						$msg = $msg."New Password is too short - make sure it's at least 6 characters long. ";
						$sucess=False;
					}
				}else{
					//passwords didn't match:
					$msg = $msg . "New Passwords don't match. Please try again. ";
					$sucess=False;
				}

			}else{
				//Password provided was incorrect:
				$msg = $msg . "Password Incorrect. Please try again. ";
				$sucess=False;
			}
		}
		
	}
	
	include_once 'accounts.php';

?>