<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	$id=mysql_real_escape_string($_GET['id']);
	
	$otherparty=mysql_real_escape_string(htmlentities($_GET['o']));
	$desc=mysql_real_escape_string(htmlentities($_GET['d']));
	$amount=mysql_real_escape_string(htmlentities($_GET['a']));
	$type=mysql_real_escape_string(htmlentities($_GET['t']));
	$d=mysql_real_escape_string(htmlentities($_GET['day']));
	$m=mysql_real_escape_string(htmlentities($_GET['month']));
	$y=mysql_real_escape_string(htmlentities($_GET['year']));

	$time=strtotime($m."/".$d."/".$y);
	
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
	$query=$query." Timestamp='$time' WHERE UserID='$user' AND PaymentID='$id'";
	mysql_query($query) or die(mysql_error());
	
	$query="SELECT * FROM payments WHERE UserID='$user' AND PaymentID='$id'";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_assoc($result);
	
	$amount=$row['PaymentAmount'];
	if($amount<0){
		$amount="</td><td><span class='red'>".$amount*(-1)."</span>";
	}else{
		$amount=$amount."</td><td>";
	}
	
	echo "<td>".date("d/m/y", $row['Timestamp'])."</td>
		  <td>".$row['PaymentName']."</td>
		  <td>".$row['PaymentDesc']."</td>
		  <td>".$amount."</td>
		  <td>".$row['PaymentType']."</td>
		  <td>
			  <button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
			  <button onclick=\"editForm('".$row['PaymentID']."')\">Edit</button>
		  </td>";
	
	closedb($conn);
?>