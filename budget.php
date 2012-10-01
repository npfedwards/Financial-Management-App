<?php
	include 'header.php';
	
	if($loggedin==1){
		echo "<div id='budgetpage'>
			<table id='labels'>";
		labellist($user);
		echo "</table>
		<input type='text' id='addlabel'><button onClick=\"addLabel()\">Add Label</button>
		</div>";
	}else{
		loginform();
		echo $msg;
	}
	
	include 'footer.php';
?>