<?php
	include 'header.php';
	
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
		}
		include 'statement.php';	
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>