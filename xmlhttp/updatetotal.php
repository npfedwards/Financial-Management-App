<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$endtime=time()+604800;
	$query="SELECT * FROM payments WHERE UserID='$user' AND Deleted='0' AND Timestamp<'$endtime'";
	$result=mysql_query($query) or die(mysql_error());
	$total=0;
	
	$cs=currencysymbol($user);
	
	while($row=mysql_fetch_assoc($result)){
		$total=$total+$row['PaymentAmount'];
	}
	if($total<0){
		$total="<span class='red'>".$cs.$total."</span>";
	}else{
		$total=$cs.$total;	
	}
	
	echo $total;
	
	closedb($conn);
?>