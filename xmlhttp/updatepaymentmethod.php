<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	if($loggedin==1){	
		$method=sanitise('pm');
	
		if(isset($method)){
			$query="UPDATE users SET PrefPaymentMethod='$method' WHERE UserID='$user'";
			mysql_query($query) or die(mysql_error());
		
			echo "Success! Your default payment method has been changed to ".$method;
		}
	}else{
		loginform();
	}
	
?>