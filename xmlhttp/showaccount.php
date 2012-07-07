<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$account=sanitise('account');
		$order=sanitise('order');
		$field=sanitise('field'); //Currently unused, but will need to be built into statement
		checkAccount($user,$account,0);
		statement(20,$user,$order, $account);
	}else{
		loginform();
	}
	closedb($conn);
?>