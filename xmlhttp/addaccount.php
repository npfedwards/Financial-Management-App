<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	if($loggedin==1){
		$account=mysql_real_escape_string(htmlentities($_GET['account']));
		
		$query="INSERT INTO accounts (UserID, AccountName) VALUES ('$user', '$account')";
		mysql_query($query) or die(mysql_error());
		
		accountList($user);
	}else{
		loginform();
	}
	
	closedb($conn);
?>