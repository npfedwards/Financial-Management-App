<?php
	setcookie("userid", "", time()-3600);
	setcookie("sessionkey", "", time()-3600);
	
	//Maybe should do something to the db too
	
	$loggedin=0;
?>
Logged out. Go to <a href='index.php'>Home</a>