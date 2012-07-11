<?php
	include_once 'functions.php';
	checklogin();

	if($loggedin==0){
		$email=sanitise('email','p');

		$query="SELECT * FROM users WHERE Email='$email'";
		
		$result=mysql_query($query) or die(mysql_error());

		if(mysql_num_rows($result)==1) {

			$row=mysql_fetch_array($result);
			
			$UserID=$row['UserID'];
			$key=$row['ValidationKey'];

			if($row['Validated']==0){
				resendvalidationkey($email, $key, $UserID);
				$msg .= "We've resent your validation link to your email address. Make sure you check your spam folder too. ";
			} else {
				$msg .= "You've already validated your account, try logging in instead ";
			}


		} elseif(mysql_num_rows($result)>1) {

			$msg .= "We couldn't find your email on file. Are you sure you've registered? ";

		}
	}

	include 'index.php';

?>