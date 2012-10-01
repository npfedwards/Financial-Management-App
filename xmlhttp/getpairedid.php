<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$id=sanitise('id');
	if($loggedin==1){
		$query="SELECT * FROM payments WHERE UserID='$user' AND PaymentID='$id'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		
		echo $row['PairedID'];
		
	}else{
		loginform();
	}
	
	
?>