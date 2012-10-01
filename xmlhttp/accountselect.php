<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	$index = sanitise("i");


	echo "<select id='otherparty' name='otherparty'>";
	
	$query="SELECT * FROM accounts WHERE UserID='$user' AND Archived='0' ORDER BY AccountName ASC";
	$result=mysql_query($query) or die(mysql_error());
	
	while($row=mysql_fetch_assoc($result)){
		echo "<option value='ThisAccount".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
	}
	
	echo "</select><span onclick=\"otherParty(".$index.")\" class='clickable'>Not between accounts?</span>";
	
	
?>