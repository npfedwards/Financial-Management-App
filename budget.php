<?php
	include 'header.php';
	
	if($loggedin==1){
		echo "<div id='budgetpage'>";
		labellist($user);
		echo "</div>";
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>