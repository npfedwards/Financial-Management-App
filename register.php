<?php

	include 'header.php';
	
	if($loggedin==1){
		include 'statement.php';	
	}else{
		include 'registrationform.php';
	}

	include 'footer.php';





	#if($loggedin==1){
		#if they're already logged in,  we bounce them back to the homepage - they don't need 2 accounts
	#	include 'header.php';
	#	include 'statement.php';
	#}else{
	#	include 'header.php';
	#	include 'registrationform.php';
	#}
	
	//include 'footer.php';
?>