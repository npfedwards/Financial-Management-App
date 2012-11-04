<?php
	include_once 'functions.php';
	checklogin();
	if($loggedin==1){
		if(!isset($user)){
			$user=mysql_real_escape_string($_COOKIE['userid']);
			//$user="1";
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

			// Set the headers for the CSV
			$downloadcontent = "Date,To/From,Description,Amount,Type,Account,Reconciled?\n";
			
			while($row=mysql_fetch_assoc($result)){
				if($row['Timestamp']<time()){
					// Add each field (comma seperated) to the output
					$downloadcontent .= date("d/m/y", $row['Timestamp']).",".stripslashes($row['PaymentName']).",".stripslashes($row['PaymentDesc']).",".stripslashes($row['PaymentAmount']).",".stripslashes($row['PaymentType']).",".stripslashes($row['AccountName']).",";
					if($row['Reconciled']==1){
						$downloadcontent .= "Yes";
					}else{
						$downloadcontent .= "No";
					}
						// add newline character to start next row.
						$downloadcontent .= "\n";
				}
			}
			$query="SELECT * FROM users WHERE UserID='$user'";
			$result=mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result)==1){
				$row=mysql_fetch_assoc($result);
				$email=$row['Email'];
			}

			$downloadfilename = date("Y/m/d",time())."-".$email."-"."Finacial_Management_App_export.csv";
			
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			//header("Content-Length: ". filesize("$filename").";");
			header("Content-Disposition: attachment; filename=$downloadfilename");
			header("Content-Type: application/octet-stream; "); 
			header("Content-Transfer-Encoding: binary");
			echo $downloadcontent;
	}
?>