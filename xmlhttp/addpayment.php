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
		$order=sanitise('order');
		$rt=sanitise('rt');
		$rf=sanitise('rf');
		
		$time=strtotime($m."/".$d."/".$y);
		
		if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
			$account=checkAccount($user, $account);
			
			$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type')";
			mysql_query($query) or die(mysql_error());
			$msg="Added!";
			
			if($rt!=NULL && $rf!=NULL){
				if($rt>1){
					$paymentid=mysql_insert_id();
					$expiretime=$time+($rt*$rf*86400);
					$query="INSERT INTO repeats (PaymentID, Frequency, Times, ExpireTime) VALUES ('$paymentid', '$rf', '$rt', '$expiretime')";
					mysql_query($query) or die(mysql_error());	
					$repeatid=mysql_insert_id();
				}
				$time=$time+$rf*86400;
				$i=2;
				while($time<time()+604800 && $i<=$rt){
					$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, Repeated) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$repeatid')";
					mysql_query($query) or die(mysql_error);
					$i++;
					$time=$time+$rf*86400;
				}
			}
		}else{
			$msg="All fields are required and the amount must be a number!";
		}
		
		statement(20,$user,$order,$accsel);
	}else{
		loginform();
	}
	
	closedb($conn);
?>