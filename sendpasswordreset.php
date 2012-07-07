<?php
	include_once 'functions.php';
	opendb();

	$email = sanitise('email', 'p');

	$query = "SELECT * FROM users WHERE Email='$email'";
	$result = mysql_query($query) or die(mysql_error());

	if(mysql_num_rows($result)==1){
		$row=mysql_fetch_assoc($result);
		if($row['Validated']==1) {
			if($row['ResetKey']==NULL||time()>$row['ResetTimeout']){
				$resetkey = sha1(generatesalt(64));
				$timeout = time() + 604800; //One Week
				$UserID = $row['UserID'];

				$query = "UPDATE users SET ResetKey='$resetkey', ResetTimeout='$timeout' WHERE Email='$email'";
				$result = mysql_query($query) or die(mysql_error());

				sendpasswordreset($email, $resetkey, $UserID);
				echo "email: ".$email." resetkey: ".$resetkey." UserID: ".$UserID;
			} else {
				echo "You've already tried to reset";
			}
		} else {
			echo "You haven't validated your account yet! Check your emails, including the spam folder";
		}
	} else {
		echo "your email wasn't found :(";
	}

?>