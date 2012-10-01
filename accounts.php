<?php
	include 'header.php';
	
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
		}
		echo "<div id='accountspage'>
			<div id='accounts'>";
			accountList($user);
			accountForm();
			echo "</div>";
			currencyPrefForm($user);
			echo "<br>";
			paymentPrefForm($user);
			echo "<br>";
			changePasswordForm();
			echo "<div id='changepasswordcontainer'>".$msg."</div>
			</div>";
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>