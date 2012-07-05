<?php
	paymentForm();
	echo $msg;
	echo "<br>";
	if (isset($_GET['order'])) {
		$order=$_GET['order'];
	} else {
		$order=0;
	}
	statement(10, $user, $order);
?>