<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	echo "<select id='otherparty' name='otherparty'>";
	
	$query="SELECT * FROM accounts WHERE UserID='$user' AND Archived='0' ORDER BY AccountName ASC";
	$result=mysql_query($query) or die(mysql_error());
	
	while($row=mysql_fetch_assoc($result)){
		echo "<option value='ThisAccount".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
	}
	
	echo "</select><span onclick=\"otherParty()\" class='clickable'>Not between accounts?</span>";
	
	closedb($conn);
?>