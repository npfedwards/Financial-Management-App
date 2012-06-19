<?php
	include 'header.php';
	
	if($loggedin==1){
		include 'statement.php';	
	}else{
		include 'login.php';
	}
	
	include 'footer.php';
?>