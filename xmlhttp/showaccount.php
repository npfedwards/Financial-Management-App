<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$account=sanitise('account');
	checkAccount($user,$account,0);
	statement(10,$user,0, $account);
	
	closedb($conn);
?>