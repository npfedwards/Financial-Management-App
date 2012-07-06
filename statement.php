<?php
	paymentForm($user);
	echo $msg;
	echo "<br>";
	if (isset($_GET['order'])) {
		$order=$_GET['order'];
	} else {
		$order=0;
	}
	accountPicker($user);
	echo "<div id='statementhold'>";
	statement(10, $user, $order);
	echo "</div>";
?>