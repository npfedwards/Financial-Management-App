<?php
	ini_set('display_errors', 'Off');
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$id=sanitise('id');
	$query="SELECT * FROM payments WHERE PaymentID='$id' AND UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_assoc($result);
	$pairedid=$row['PairedID'];
	$query="UPDATE payments SET Deleted='1' WHERE (PaymentID='$id' AND UserID='$user') OR (PaymentID='$pairedid' AND UserID='$user')";
	mysql_query($query) or die(mysql_error());
	
	if($row['RepeatID']!=0){
		echo $row['RepeatID'];
	}
	
	
?>