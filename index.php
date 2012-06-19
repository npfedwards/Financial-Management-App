<?php
	include 'header.php';
	
	if($loggedin==1){
		include 'statement.php';	
	}else{
		loginform();
	}
	
	include 'footer.php';
?>