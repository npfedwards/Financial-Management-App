<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	$id=sanitise('id');
	$budget=sanitise('budget');
	
	$query="UPDATE labels SET Budget='$budget' WHERE LabelID='$id' AND UserID='$user'";
	mysql_query($query) or die(mysql_error());
?>