<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$endtime=time();
	$query="SELECT * FROM payments WHERE UserID='$user' AND Deleted='0' AND Timestamp<'$endtime'";
	$result=mysql_query($query) or die(mysql_error());
	$total=0;
	
	while($row=mysql_fetch_assoc($result)){
		$total=$total+$row['PaymentAmount'];
	}
	$total=displayamount($total,$user,1);
	
	echo $total;
	
	closedb($conn);
?>