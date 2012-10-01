<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	if($loggedin==1){
		$account=sanitise('id');
		checkAccount($user,$account,0);
		$query="DELETE FROM accounts WHERE AccountID='$account'";
		mysql_query($query) or die(mysql_error());
		
		$query="DELETE FROM payments WHERE AccountID='$account'";
		mysql_query($query) or die(mysql_error());
		
	}
	
?>