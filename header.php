<?php
	include_once 'functions.php';
	checklogin();
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>Financial Management App</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    	<script src='js/jquery.min.js'></script>
        <script src='js/functions.js'></script>
   	</head>
	<body>
   		<?php
        	if($loggedin==1){
				echo "<a href='logout.php'>Log Out</a>";	
			}
		?>