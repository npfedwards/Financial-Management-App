<?php
	paymentForm($user);
	echo $msg;
	echo "<br>";
	
	accountPicker($user);
	echo "<div id='statementhold'>";
	statement(20, $user);
	echo "</div>";
?>