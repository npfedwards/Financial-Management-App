<?php
	include_once 'functions.php';
	$conn=opendb();
	
	$submittedkey=sanitise('k');
	$UserID=sanitise('id');

	$msg=NULL;

	$query="SELECT * FROM users WHERE UserID='$UserID' AND ResetKey='$submittedkey'";
	$result=mysql_query($query) or die(mysql_error());

	if(mysql_num_rows($result)==1){
		$row = mysql_fetch_array($result);

		if(time()<$row['ResetTimeout']) {

			passwordresetform($submittedkey, $UserID);
		}else{
			$msg = $msg."Your key's expired - they're only valid for 1 week! Try getting another one! ";
		}

	}else{
		$msg = $msg . "Invalid Key! ";
	}
	
	
	echo $msg;
?>