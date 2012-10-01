<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	if($loggedin==1){
		$account=sanitise('account');
		$sd=sanitise('sd');
		$sm=sanitise('sm');
		$sy=sanitise('sy');
		$ed=sanitise('ed');
		$em=sanitise('em');
		$ey=sanitise('ey');
		$value=sanitise('value');
		$order=sanitise('order');
		$field=sanitise('field');
		$perpage=intval(sanitise('perpage'));
		if($perpage==0){
			$perpage=20;
		}
		$offset=intval(sanitise('offset'));
		checkAccount($user,$account,0);
		$startdate=strtotime($sm."/".$sd."/".$sy)-1;
		$enddate=strtotime($em."/".$ed."/".$ey)+1;
		if($enddate<$startdate){
			$enddate=$startdate+2;	
		}
		statement($perpage,$user,$order, $account, $offset, $value, $field, $startdate, $enddate);
	}else{
		loginform();
	}
	
?>