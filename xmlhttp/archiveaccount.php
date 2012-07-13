<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$account=sanitise('id');
		$archive=sanitise('archive');
		checkAccount($user,$account,0);
		$query="UPDATE accounts SET Archived='$archive' WHERE AccountID='$account'";
		mysql_query($query) or die(mysql_error());
		
		$query="SELECT * FROM accounts WHERE AccountID='$account'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		
		accountInList($row['AccountID'],$row['AccountName'],$archive);
		
	}
	closedb($conn);
?>