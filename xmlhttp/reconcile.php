<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$id=sanitise('id');
	$c=sanitise('c');
	$account=sanitise('account');
	$value=sanitise('value');
	
	if($c=='true'){
		$query="UPDATE payments SET Reconciled='1' WHERE PaymentID='$id' AND UserID='$user'";	
	}else{
		$query="UPDATE payments SET Reconciled='0' WHERE PaymentID='$id' AND UserID='$user'";
	}
	mysql_query($query) or die(mysql_error());
	
	reconcilereport($user, $account);
	
	
?>