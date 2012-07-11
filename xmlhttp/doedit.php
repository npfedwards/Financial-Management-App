<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$id=sanitise('id');
		
		$otherparty=sanitise('o');
		$toaccount=sanitise('toaccount');
		$toaccount=checkAccount($user, $toaccount, 0);
		$desc=sanitise('d');
		$amount=sanitise('a');
		$type=sanitise('t');
		$d=sanitise('day');
		$m=sanitise('month');
		$y=sanitise('year');
		$account=sanitise('account');
		$account=checkAccount($user, $account);
		
		$time=strtotime($m."/".$d."/".$y);
		
		if($toaccount!=0){
			$otherparty=getaccountname($toaccount);
		}
		
		$query="UPDATE payments SET ";
		if($otherparty!=NULL){
			$query=$query."PaymentName='$otherparty', ";
		}
		if($desc!=NULL){
			$query=$query."PaymentDesc='$desc', ";
		}
		if($amount!=NULL){
			$query=$query."PaymentAmount='$amount', ";
		}
		if($type!=NULL){
			$query=$query."PaymentType='$type', ";
		}
		$query=$query."AccountID='$account', Timestamp='$time', ToAccount='$toaccount' WHERE UserID='$user' AND PaymentID='$id'";
		mysql_query($query) or die(mysql_error());
		
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' AND PaymentID='$id'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		
		if($toaccount!=0){
			$otherparty=getaccountname($account);
			$amount=-$amount;
			$query="UPDATE payments SET ";
			if($otherparty!=NULL){
				$query=$query."PaymentName='$otherparty', ";
			}
			if($desc!=NULL){
				$query=$query."PaymentDesc='$desc', ";
			}
			if($amount!=NULL){
				$query=$query."PaymentAmount='$amount', ";
			}
			if($type!=NULL){
				$query=$query."PaymentType='$type', ";
			}
			$query=$query."AccountID='$toaccount', Timestamp='$time', ToAccount='$account' WHERE UserID='$user' AND PaymentID='".$row['PairedID']."'";
			mysql_query($query) or die(mysql_error());
		}
		
		$cs=currencysymbol($user);
		$amount=$row['PaymentAmount'];
		if($amount<0){
			$amount="</td><td class='align_right'><span class='red'>".$cs.$amount*(-1)."</span>";
		}else{
			$amount=$cs.$amount."</td><td>";
		}
		
		echo "<td>".date("d/m/y", $row['Timestamp'])."</td>
			  <td>".stripslashes($row['PaymentName'])."</td>
			  <td>".stripslashes($row['PaymentDesc'])."</td>
			  <td class='align_right'>".$amount."</td>
			  <td>".$row['PaymentType']."</td>
			  <td>".stripslashes($row['AccountName'])."</td>
			  <td>
				  <button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
				  <button onclick=\"editForm('".$row['PaymentID']."')\">Edit</button>
			  </td>";
	}else{
		loginform();
	}
	
	closedb($conn);
?>