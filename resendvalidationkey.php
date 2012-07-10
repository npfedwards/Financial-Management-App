<?php
	include 'header.php';

	if($loggedin==0) {
		resendvalidationkeyform();
	} else {
		include 'statement.php';
	}


?>