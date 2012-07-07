<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$account=sanitise('account');
		checkAccount($user,$account,0);
		statement(20,$user,1, $account);
	}else{
		loginform();
	}
	closedb($conn);
?>