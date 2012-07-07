<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$account=sanitise('account');
		$order=sanitise('order');
		$field=sanitise('field'); //Currently unused, but will need to be built into statement
		$perpage=intval(sanitise('perpage'));
		if($perpage==0){
			$perpage=20;
		}
		$offset=intval(sanitise('offset'));
		checkAccount($user,$account,0);
		statement($perpage,$user,$order, $account, $offset);
	}else{
		loginform();
	}
	closedb($conn);
?>