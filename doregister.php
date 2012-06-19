<?php
	include 'functions.php';
	opendb();

	$email=mysql_real_escape_string(htmlentities($_POST['email']));
	$pass=mysql_real_escape_string(htmlentities($_POST['password']));
	$repeatpass=mysql_real_escape_string(htmlentities($_POST['repeatpassword']));

	if($pass==$repeatpass){
		if($email!=NULL&&$pass!=NULL&&$repeatpass!=NULL){
			$time = time() + 604800;
			$salt = geneeratesalt();
			$query="INSERT INTO users (Email, Password, Salt, ValidatedTimeout) VALUES ('$email', '$password', '$salt', '$time')";
			
		}else{

		}
	}

	$id=mysql_real_escape_string(htmlentities($_GET['id'])); //If an id argument is passed in the URL then we'll show that Quote
			if($id==NULL){ //otherwise find a random one
				$query="SELECT * FROM quotes";
				$result=mysql_query($query) or die(mysql_error());
				$rows=mysql_num_rows($result); //Get the number of rows in the table
				$num=rand(0, $rows-1); //Find a random row
				$query="SELECT * FROM quotes LIMIT ".$num.",1"; //Select that row from the table
				//This is to test GIT is set up correctly...
			}else{
				$query="SELECT * FROM quotes WHERE QuoteID='$id'";
			}





	if($_POST['email'])

	
?>