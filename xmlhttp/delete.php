<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$id=sanitise('id');
	$query="SELECT * FROM payments WHERE PaymentID='$id' AND UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_assoc($result);
	if($row['Repeated']==0){
		$query="SELECT * FROM repeats WHERE PaymentID='$id'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)==1){
			$query="UPDATE payments SET Deleted='1' WHERE PaymentID='$id' AND UserID='$user'";
			mysql_query($query) or die(mysql_error());
			$repeated=1;
			$row=mysql_fetch_assoc($result);
			$repeatid=$row['RepeatID'];
		}else{
			$query="DELETE FROM payments WHERE PaymentID='$id' AND UserID='$user'";
			mysql_query($query) or die(mysql_error());
		}
	}else{
		$query="UPDATE payments SET Deleted='1' WHERE PaymentID='$id' AND UserID='$user'";
		mysql_query($query) or die(mysql_error());
		$repeatid=$row['Repeated'];
		$query="SELECT * FROM repeats WHERE RepeatID='$repeatid'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)==1){
			$repeated=1;
		}
	}
	
	if($repeated==1){
		echo $repeatid;	
	}
	
	closedb($conn);
?>