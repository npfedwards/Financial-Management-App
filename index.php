<?php
	include 'header.php';
	
	if($loggedin==1){
		include 'statement.php';	
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>