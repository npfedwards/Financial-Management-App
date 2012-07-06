<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$currency=sanitise($_GET['currency']);
	
	if($currency==pound){
		$currency='&pound;';
	} elseif ($currency==dollar){
		$currency='&dollar;';
	} elseif ($currency==euro){
		$currency='&euro;';
	}

	if(isset($currency)){
		$query="UPDATE users SET PrefCurrency='$currency' WHERE UserID='$user'";
		mysql_query($query) or die(mysql_error());
	
		echo "Success!!";
	}
	
	closedb($conn);
?>