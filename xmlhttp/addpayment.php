<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$getorgive=sanitise('getorgive'); //sanitise assumes $_GET
	$otherparty=sanitise('o');
	$desc=sanitise('d');
	$type=sanitise('t');
	$amount=sanitise('a');
	$amount=$getorgive*$amount;
	$d=sanitise('day');
	$m=sanitise('month');
	$y=sanitise('year');
	$account=sanitise('account');
	
	
	$time=strtotime($m."/".$d."/".$y);
	
	if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
		$account=checkAccount($user, $account);
		
		$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type')";
		mysql_query($query) or die(mysql_error());
		$msg="Added!";
	}else{
		$msg="All fields are required and the amount must be a number!";
	}
	
	statement(10,$user); //Needs to keep ordering?
	
	closedb($conn);
?>