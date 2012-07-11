<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$id=sanitise('id');
	if($loggedin==1){
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' AND PaymentID='$id'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		
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
			  <td><input type='checkbox' id='reconciled' onclick=\"reconcile(this, ".$row['PaymentID'].")\"";
				if($row['Reconciled']==1){
						echo "checked='checked'";
				}
				echo		"></td>
			  <td>
				  <button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
				  <button onclick=\"editForm('".$row['PaymentID']."')\">Edit</button>
			  </td>";
		
	}else{
		loginform();
	}
	
	closedb($conn);
?>