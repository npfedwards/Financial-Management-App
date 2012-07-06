<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	
	$getorgive=mysql_real_escape_string(htmlentities($_POST['getorgive']));
	$otherparty=mysql_real_escape_string(htmlentities($_POST['otherparty']));
	$desc=mysql_real_escape_string(htmlentities($_POST['desc']));
	$type=mysql_real_escape_string(htmlentities($_POST['type']));
	$amount=mysql_real_escape_string(htmlentities($_POST['amount']));
	$amount=$getorgive*$amount;
	$d=mysql_real_escape_string(htmlentities($_POST['day']));
	$m=mysql_real_escape_string(htmlentities($_POST['month']));
	$y=mysql_real_escape_string(htmlentities($_POST['year']));
	$account=mysql_real_escape_string(htmlentities($_POST['account']));
	
	$time=strtotime($m."/".$d."/".$y);
	
	if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
		$account=checkAccount($user, $account);
		
		$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type')";
		mysql_query($query) or die(mysql_error());
		$msg="Added!";
	}else{
		$msg="All fields are required and the amount must be a number!";
	}
	
	closedb($conn);
	include 'index.php';
?>