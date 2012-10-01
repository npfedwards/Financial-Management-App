<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	if($loggedin==1){
		$id=sanitise('id');
		$a=sanitise('a');
		checkaccount($user,$id,0); //Return 0 if not account, therefore affecting no rows in the table AccountID!=0
		if($a!=NULL){
			$query="UPDATE accounts SET AccountName='$a' WHERE UserID='$user' AND AccountID='$id'";
			mysql_query($query) or die(mysql_error());
			
			echo "<div id='account".$id."'>".stripslashes($a)." <button onclick=\"editAccountForm(".$id.",'".stripslashes($a)."')\">Edit</button></div>";
		}
		
	}else{
		loginform();
	}
	
	
?>