<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
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
		$accsel=sanitise('accsel');
		$accsel=checkAccount($user,$accsel,0);
		
		$time=strtotime($m."/".$d."/".$y);
		
		if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
			$account=checkAccount($user, $account);
			
			$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type')";
			mysql_query($query) or die(mysql_error());
			$msg="Added!";
		}else{
			$msg="All fields are required and the amount must be a number!";
		}
		
		statement(20,$user,1,$accsel); //Needs to keep ordering?
	}else{
		loginform();
	}
	
	closedb($conn);
?>