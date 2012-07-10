<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$value=sanitise('value');
		$account=sanitise('account');
		$order=sanitise('order');
		$field=sanitise('field');
		$perpage=intval(sanitise('perpage'));
		if($perpage==0){
			$perpage=20;
		}
		$offset=intval(sanitise('offset'));
		checkAccount($user,$account,0);
		statement($perpage,$user,$order, $account, $offset, $value, $field);
	}else{
		loginform();
	}
	closedb($conn);
?>