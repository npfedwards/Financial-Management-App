<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$account=sanitise('account');
	$value=sanitise('value');
	
	updatereconcile($user, $account, $value);
	
	closedb($conn);
?>