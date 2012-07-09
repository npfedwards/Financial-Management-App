<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$id=sanitise('id');
	$query="SELECT * FROM repeats LEFT JOIN payments ON repeats.PaymentID=payments.PaymentID WHERE RepeatID='$id' AND UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	
	if(mysql_num_rows($result)==1){
		$query="DELETE FROM repeats WHERE RepeatID='$id'";
		mysql_query($query) or die(mysql_error());
	}
	
	closedb($conn);
?>