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
			echo "<br>";
			exportForm();
			echo "<br>";

			if (isset($msg)) {
				$safemessage=htmlspecialchars($msg, ENT_QUOTES);
				echo "<script type='text/javascript'>displayFeedback('$safemessage'";
				if (!$success){ 
					echo " , 'error'";
				}
			echo ");</script>";
			}
			echo "</div>";
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>