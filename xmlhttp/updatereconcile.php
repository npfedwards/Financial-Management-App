<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$account=sanitise('account');
	$value=sanitise('value');
	
	updatereconcile($user, $account, $value);
	
	
?>