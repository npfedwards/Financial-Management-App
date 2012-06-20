<?php
	paymentForm();
	echo $msg;
	echo "<br>";
	$user=mysql_real_escape_string($_COOKIE['userid']);
	statement(10, $user);
?>