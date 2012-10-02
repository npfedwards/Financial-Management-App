<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
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
		$label=sanitise('label');
		$label=checklabel($user, $label);
		
		$time=strtotime($m."/".$d."/".$y);
		
		if($amount!=NULL && $otherparty !=NULL && $desc != NULL){
			$account=checkAccount($user, $account);
			if(substr($otherparty,0,11)=='ThisAccount'){
				$toaccount=substr($otherparty,11);
				$toaccount=checkAccount($user, $toaccount,0);
				if($toaccount!=0){
					$theotherparty=getaccountname($account);
					$toamount=-$amount;
					$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, LabelID) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account', '$label')";
					mysql_query($query) or die(mysql_error()." addpayment#001");
					$insertid=mysql_insert_id();
					$otherparty=getaccountname($toaccount);
				}
			}
			
			if($insertid==NULL){
				$insertid=0;	
			}
			
			$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, LabelID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid', '$label')";
			mysql_query($query) or die(mysql_error()." addpayment#002");
			$paymentid=mysql_insert_id();
			
			if($insertid!=0){
				$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
				mysql_query($query) or die(mysql_error()." addpayment#003");
			}
			
			if($rt!=NULL && $rf!=NULL && $rt!="undefined" && $rf!="undefined"){
				if($rf=='m'){ //If it's monthly we do it based on day of the month
					$paymentid=mysql_insert_id();
					$expiretime=$time+($rt*31*86400);
					
					if($insertid!=0){ //Repeat Paired Payment
						$query="INSERT INTO repeats (Frequency, Times, ExpireTime, UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, LabelID) VALUES ('$rf', '$rt', '$expiretime', '$user', '$toaccount', '$time', '$theotherparty', '$desc', '$amount', '$type', '$account', '$label')";
						mysql_query($query) or die(mysql_error()." addpayment#004-1");
						$repeatinsertid=mysql_insert_id();
						$query="UPDATE payments SET RepeatID='$repeatinsertid' WHERE PaymentID='$insertid'";
						mysql_query($query) or die(mysql_error()." addpayment#004-2");
					}
					
					$query="INSERT INTO repeats (Frequency, Times, ExpireTime, UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, LabelID) VALUES ('$rf', '$rt', '$expiretime', '$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$repeatinsertid', '$label')";
					mysql_query($query) or die(mysql_error()." addpayment#004");	
					$repeatid=mysql_insert_id();
					$query="UPDATE payments SET RepeatID='$repeatid' WHERE PaymentID='$paymentid'";
					mysql_query($query) or die(mysql_error()." addpayment#004-3");
					
					if($repeatinsertid!=NULL){
						$query="UPDATE repeats SET PairedID='$repeatid' WHERE RepeatID='$repeatinsertid'";
						mysql_query($query) or die(mysql_error()." addpayment#004-4");
					}
					
					$m=intval($m);
					$y=intval($y);
					if($m==12){
						$m=1;
						$y++;
					}else{
						$m++;	
					}
					$time=strtotime($m."/".$d."/".$y);
					$i=2;
					
					while($time<time()+86400 && $i<=$rt){
						if($insertid!=0){
							$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, RepeatID, LabelID) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account', '$repeatinsertid', '$label')";
							mysql_query($query) or die(mysql_error()." addpayment#005");
							$insertid=mysql_insert_id();
						}
						$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, RepeatID, LabelID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid', '$repeatid', '$label')";
						mysql_query($query) or die(mysql_error()." addpayment#006");
						
						if($insertid!=0){
							$paymentid=mysql_insert_id();
							$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
							mysql_query($query) or die(mysql_error()." addpayment#007");
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
					if($insertid!=0){ //Repeat Paired Payment
						
						$query="INSERT INTO repeats (Frequency, Times, ExpireTime, UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, LabelID) VALUES ('$rf', '$rt', '$expiretime', '$user', '$toaccount', '$time', '$theotherparty', '$desc', '$amount', '$type', '$account', '$label')";
						mysql_query($query) or die(mysql_error()." addpayment#008-1");
						$repeatinsertid=mysql_insert_id();
						$query="UPDATE payments SET RepeatID='$repeatinsertid' WHERE PaymentID='$insertid'";
						mysql_query($query) or die(mysql_error()." addpayment#008-2");
					}
					
					$query="INSERT INTO repeats (Frequency, Times, ExpireTime, UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, LabelID) VALUES ('$rf', '$rt', '$expiretime', '$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$repeatinsertid', '$label')";
					mysql_query($query) or die(mysql_error()." addpayment#008");	
					$repeatid=mysql_insert_id();
					$query="UPDATE payments SET RepeatID='$repeatid' WHERE PaymentID='$paymentid'";
					mysql_query($query) or die(mysql_error()." addpayment#008-3");
					
					if($repeatinsertid!=NULL){
						$query="UPDATE repeats SET PairedID='$repeatid' WHERE RepeatID='$repeatinsertid'";
						mysql_query($query) or die(mysql_error()." addpayment#008-4");
					}
						
					$time=$time+$rf*86400;
					$i=2;
					while($time<time()+604800 && $i<=$rt){
						if($insertid!=0){
							$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, RepeatID, LabelID) VALUES ('$user', '$toaccount', '$time', '$theotherparty', '$desc', '$toamount', '$type', '$account', '$repeatinsertid', '$label')";
							mysql_query($query) or die(mysql_error()." addpayment#009");
							$insertid=mysql_insert_id();
						}
						$query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, RepeatID, LabelID) VALUES ('$user', '$account', '$time', '$otherparty', '$desc', '$amount', '$type', '$toaccount', '$insertid', '$repeatid', '$label')";
						mysql_query($query) or die(mysql_error()." addpayment#010");
						
						if($insertid!=0){
							$paymentid=mysql_insert_id();
							$query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
							mysql_query($query) or die(mysql_error()." addpayment#011");
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
	
	
?>