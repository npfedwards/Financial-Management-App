<?php
	paymentForm($user);
	echo $msg;
	echo "<br>";
	
	echo "<div id='statementcontrols'>";
	accountPicker($user);
	numperpage();
	statementdatepicker();
	echo "</div>";
	echo "<div id='statementhold'>";
	statement(20, $user);
	echo "</div>";
	budgeter($user);
?>