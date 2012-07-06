<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$currency=mysql_real_escape_string(htmlentities($_GET['currency']));
	$user=mysql_real_escape_string(htmlentities($_GET['id']));
	if($currency==pound){
		$currency='&pound;';
	} elseif ($currency==dollar){
		$currency='&dollar;';
	} elseif ($currency==euro){
		$currency='&euro;';
	}

	//need to change query:
	if(isset($currency)){
		$query="UPDATE users SET PrefCurrency='$currency' WHERE UserID='$user'";
		mysql_query($query) or die(mysql_error());
	
		echo "Sucess!!";
	}
	
	closedb($conn);
?>