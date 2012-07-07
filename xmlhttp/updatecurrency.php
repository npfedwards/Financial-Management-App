<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){	
		$currency='&'.sanitise('currency').';';
	
		if(isset($currency)){
			$query="UPDATE users SET PrefCurrency='$currency' WHERE UserID='$user'";
			mysql_query($query) or die(mysql_error());
		
			echo "Success!!";
		}
	}else{
		loginform();
	}
	closedb($conn);
?>