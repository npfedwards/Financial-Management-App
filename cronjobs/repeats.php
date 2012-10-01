<?php
	include_once '../functions.php';
	$conn=opendb();
	
	$query="SELECT * FROM users";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_assoc($result)){
		dorepeats($row['UserID']);	
	}
	
?>