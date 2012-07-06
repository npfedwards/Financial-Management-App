<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$currency='&'.sanitise('currency').';';

	if(isset($currency)){
		$query="UPDATE users SET PrefCurrency='$currency' WHERE UserID='$user'";
		mysql_query($query) or die(mysql_error());
	
		echo "Success!!";
	}
	
	closedb($conn);
?>