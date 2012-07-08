<?php
	include 'header.php';
	
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
		}
		echo "<div id='accounts'>";
		accountList($user);
		echo "</div>";
		accountForm();
		currencyPrefForm();
		echo "<div id='currencycontainer'></div>";
		changePasswordForm();
		echo "<div id='changepasswordcontainer'></div>";
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>