<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	
	$getorgive=mysql_real_escape_string(htmlentities($_POST['getorgive']));
	$otherparty=mysql_real_escape_string(htmlentities($_POST['otherparty']));
	$type=mysql_real_escape_string(htmlentities($_POST['type']));
	$amount=mysql_real_escape_string(htmlentities($_POST['amount']));
	$amount=$getorgive*$amount;
	
	if($amount!=NULL && $otherparty !=NULL){
		
		$query="INSERT INTO payments (UserID, Timestamp, PaymentName, PaymentAmount, PaymentType) VALUES ('$user', '".time()."', '$otherparty', '$amount', '$type')";
		mysql_query($query) or die(mysql_error());
	}else{
		
	}
	
	closedb($conn);
	include 'index.php';
?>