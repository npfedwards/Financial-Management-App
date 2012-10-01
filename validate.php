<?php
	include_once 'functions.php';
	$conn=opendb();
	
	//TODO: check that v has been submitted,  and it's of the correct length (64)
	$submittedkey=sanitise('k');
	$UserID=sanitise('id');

	$msg=NULL;

	$query="SELECT * FROM users WHERE UserID='$UserID' AND ValidationKey='$submittedkey'";
	$result=mysql_query($query) or die(mysql_error());

	if(mysql_num_rows($result)==1){
		$row=mysql_fetch_array($result);

		if($row['Validated']==0){

			if(time()<$row['ValidatedTimeout']){
				$query="UPDATE users SET Validated='1' WHERE UserID='$UserID'";
				$result=mysql_query($query) or die (mysql_error());
			}else{
				$msg = $msg."Your key is too old! Validation keys are only valid for 1 week from registration. Try <a href=\"register.php\">registering again.</a> ";
			}
		}else{
			$msg = $msg."You've already validated your account! Try <a href=\"index.php\">logging in</a> instead";
		}
	}else{
		$msg = $msg . "Invalid Key! ";
	}

	
	if($msg==NULL){
		echo "Sucessfully Validated! Now just <a href='index.php'>login!</a>";
	}else{
		echo $msg;
	}
?>