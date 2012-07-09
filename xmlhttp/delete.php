<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$id=sanitise('id');
		$query="SELECT * FROM payments WHERE PaymentID='$id' AND UserID='$user'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		if($row['Repeated']==0){
			$query="DELETE FROM payments WHERE PaymentID='$id' AND UserID='$user'";
			mysql_query($query) or die(mysql_error());
		}else{
			$query="UPDATE payments SET Deleted='1' WHERE PaymentID='$id' AND UserID='$user'";
			mysql_query($query) or die(mysql_error());
		}
		
		$query="SELECT * FROM payments WHERE UserID='$user' AND Deleted='0'";
		$result=mysql_query($query) or die(mysql_error());
		$total=0;
		
		while($row=mysql_fetch_assoc($result)){
			$total=$total+$row['PaymentAmount'];
		}
		if($total<0){
			$total="<span class='red'>".$total."</span>";
		}
		
		echo $total;
	}else{
		loginform();
	}
	
	closedb($conn);
?>