<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	if($loggedin==1){
		$account=sanitise('account');
		
		$query="INSERT INTO accounts (UserID, AccountName) VALUES ('$user', '$account')";
		mysql_query($query) or die(mysql_error());
		
		accountList($user);
		accountForm();
	}else{
		loginform();
	}
	
	closedb($conn);
?>