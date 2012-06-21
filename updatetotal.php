<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	
	$query="SELECT * FROM payments WHERE UserID='$user'";
	$result=mysql_query($query) or die(mysql_error());
	$total=0;
	
	while($row=mysql_fetch_assoc($result)){
		$total=$total+$row['PaymentAmount'];
	}
	if($total<0){
		$total="<span class='red'>".$total."</span>";
	}
	
	echo $total;
	
	closedb($conn);
?>