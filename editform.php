<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	$id=mysql_real_escape_string($_GET['id']);
	
	$query="SELECT * FROM payments WHERE UserID='$user' AND PaymentID='$id'";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_assoc($result);
	
	$amount=$row['PaymentAmount'];
	if($amount<0){
		$amount="<input type='number' step='0.01' name='in'></td><td><span class='red'><input type='number' step='0.01' name='out' value='".$amount*(-1)."'></span>";
	}else{
		$amount="<input type='number' step='0.01' name='in' value='".$amount."'></td><td><span class='red'><input type='number' step='0.01' name='out'></span>";
	}
	
	echo "<td>".date("d/m/y", $row['Timestamp'])."</td>
		  <td><input type='text' name='otherparty' value='".$row['PaymentName']."'></td>
		  <td><input type='text' name='otherparty' value='".$row['PaymentDesc']."'></td>
		  <td>".$amount."</td>
		  <td>
		  	<select name='type'>
				<option";
			  	if($row['PaymentType']=="Cheque"){ echo "selected='selected'";}
	echo		">Cheque</option>
				<option";
				if($row['PaymentType']=="Card"){ echo "selected='selected'";}
	echo		">Card</option>
				<option";
				if($row['PaymentType']=="Cash"){ echo "selected='selected'";}
	echo		">Cash</option>
				<option";
				if($row['PaymentType']=="Transfer"){ echo "selected='selected'";}
	echo		">Transfer</option>
			</select>
		  </td>
		  <td>
			  <button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
			  <button onclick=\"doEdit('".$row['PaymentID']."')\">Confirm Edit</button>
		  </td>";
	
	closedb($conn);
?>