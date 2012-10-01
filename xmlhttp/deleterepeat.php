<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$id=sanitise('id');
	$paymentid=sanitise('id');
	$query="SELECT * FROM repeats WHERE RepeatID='$id' AND UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	
	$row=mysql_fetch_assoc($result);
	$paired=$row['PairedID'];
	$query="DELETE FROM repeats WHERE RepeatID='$id' OR RepeatID='$paired'";
	mysql_query($query) or die(mysql_error());
	
	if($paired==0){
		$paired=NULL;	
	}
		
	//Delete later repeats
	$query="SELECT * FROM payments WHERE PaymentID='$paymentid' AND RepeatID='$id' AND UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_assoc($result);
	
	$query="UPDATE payments SET Deleted='1' WHERE (RepeatID='$id' OR RepeatID='$paired') AND UserID='$user' AND Timestamp>'".$row['Timestamp']."'";
	mysql_query($query) or die(mysql_error());
	
	
?>