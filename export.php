<?php
	include_once 'functions.php';
	checklogin();
	$format=sanitise('exportformat','p');
	//Check that the user's logged in - you can only access your own data.
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
		}
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' AND Deleted='0' ORDER BY Timestamp DESC";
		$result=mysql_query($query) or die(mysql_error());
		
		if(mysql_num_rows($result)!=0){
			while($row=mysql_fetch_assoc($result)){
			$paymentids=" OR PaymentID='".$row['PaymentID']."'".$paymentids;
		}
		$paymentids="WHERE".substr($paymentids, 3);
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID $paymentids AND Deleted='0' ORDER BY Timestamp ASC";
		$result=mysql_query($query) or die(mysql_error());
		}

		
		// Builidng the CSV
		if($format=='csv'){
			// Set the column titles for the CSV
			$downloadcontent = "Date,To/From,Description,Amount,Type,Account,Reconciled?\n";
			//Loop through each payment:
			while($row=mysql_fetch_assoc($result)){
				if($row['Timestamp']<time()){
					// Add each field (comma seperated) to the output
					$downloadcontent .= date("d/m/y", $row['Timestamp']).",".html_entity_decode(stripslashes($row['PaymentName'])).",".html_entity_decode(stripslashes($row['PaymentDesc'])).",".stripslashes($row['PaymentAmount']).",".stripslashes($row['PaymentType']).",".html_entity_decode(stripslashes($row['AccountName'])).",";
					//add details about whether or not it's been reconciled:
					if($row['Reconciled']==1){
						$downloadcontent .= "Yes";
					}else{
						$downloadcontent .= "No";
					}
						// add newline character to start next row.
						$downloadcontent .= "\n";
				}
			}
			//Set base of filename:
			$downloadfilename = "Finacial_Management_App_export.csv";
		}elseif($format=='pdf'){
			//Build a PDF...
		}
		//Get email address of user (for use in download filename)
		$query="SELECT * FROM users WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)==1){
			$row=mysql_fetch_assoc($result);
			$email=$row['Email'];
		}
		//Add date of export, and email address of user to beginning of filename:
		$downloadfilename = date("Y/m/d",time())." ".$email." ".$downloadfilename;
		//Build the Headers:
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=\"$downloadfilename\"");
		header("Content-Type: application/octet-stream; ");
		header("Content-Transfer-Encoding: binary");
		//Output the file:
		echo $downloadcontent;
	}else{
		include 'index.php';
	}
	
?>