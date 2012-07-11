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
		$offset=sanitise('offset');
		$recvalue=sanitise('recvalue');
		$perpage=sanitise('perpage');
		$field=sanitise('field');
		
		$time=strtotime($m."/".$d."/".$y);
		
		if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
			$account=checkAccount($user, $account);
			if(substr($otherparty,0,11)=='ThisAccount'){
				$toaccount=substr($otherparty,11);
				$toaccount=checkAccount($user, $toaccount,0);
				if($toaccount!=0){
					$theotherparty=getaccountname($account);
					$toamount=-$amount;
					$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account')";
					mysql_query($query) or die(mysql_error());
					$insertid=mysql_insert_id();
					$otherparty=getaccountname($toaccount);
				}
			}
			
			if($insertid==NULL){
				$insertid=0;	
			}
			
			$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid')";
			mysql_query($query) or die(mysql_error());
			
			
			if($insertid!=0){
				$paymentid=mysql_insert_id();
				$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
				mysql_query($query) or die(mysql_error());
			}
			
			if($rt!=NULL && $rf!=NULL){
				if($rf=='m'){ //If it's monthly we do it based on day of the month
					$paymentid=mysql_insert_id();
					$expiretime=$time+($rt*31*86400);
					$query="INSERT INTO repeats (PaymentID, Frequency, Times, ExpireTime) VALUES ('$paymentid', '$rf', '$rt', '$expiretime')";
					mysql_query($query) or die(mysql_error());	
					$repeatid=mysql_insert_id();
					$m=intval($m);
					echo $m;
					$y=intval($y);
					if($m==12){
						$m=1;
						$y++;
					}else{
						$m++;	
					}
					echo $m;
					$time=strtotime($m."/".$d."/".$y);
					$i=2;
					
					while($time<time()+86400 && $i<=$rt){
						if($insertid!=0){
							$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account')";
							mysql_query($query) or die(mysql_error());
						}
						$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid')";
						mysql_query($query) or die(mysql_error);
						
						if($insertid!=0){
							$paymentid=mysql_insert_id();
							$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
							mysql_query($query) or die(mysql_error());
						}
						if($m==12){
							$m=1;
							$y++;
						}else{
							$m++;	
						}
						$time=strtotime($m."/".$d."/".$y);
						$i=2;
					}
					
				}else{
					$paymentid=mysql_insert_id();
					$expiretime=$time+($rt*$rf*86400);
					$query="INSERT INTO repeats (PaymentID, Frequency, Times, ExpireTime) VALUES ('$paymentid', '$rf', '$rt', '$expiretime')";
					mysql_query($query) or die(mysql_error());	
					$repeatid=mysql_insert_id();
					$time=$time+$rf*86400;
					$i=2;
					while($time<time()+604800 && $i<=$rt){
						if($insertid!=0){
							$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account')";
							mysql_query($query) or die(mysql_error());
						}
						$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid')";
						mysql_query($query) or die(mysql_error);
						if($insertid!=0){
							$paymentid=mysql_insert_id();
							$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
							mysql_query($query) or die(mysql_error());
						}
						$i++;
						$time=$time+$rf*86400;
					}
				}
				$msg="Added!";
			}
		}else{
			$msg="All fields are required and the amount must be a number!";
		}
		
		statement($perpage,$user,$order,$accsel, $offset, $recvalue, $field);
	}else{
		loginform();
	}
	
	closedb($conn);
?>