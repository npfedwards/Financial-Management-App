<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$account=sanitise('account');
	checkAccount($user,$account,0);
	statement(20,$user,1, $account);
	
	closedb($conn);
?>