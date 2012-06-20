<?php
	include_once 'functions.php';
	checklogin();
	opendb();
	$id=mysql_real_escape_string($_GET['id']);
	$query="DELETE FROM payments WHERE PaymentID='$id' AND UserID='$user'";
	mysql_query($query) or die(mysql_error());
	
	closedb($conn);
?>