<?php
	include 'dbconstants.php';
	
	function opendb(){
		$conn = mysql_connect(dbhost, dbuser, dbpass) or die(mysql_error());
		$dbc = mysql_select_db(dbname) or die("Failed to connect");
	}
	
	function closedb($conn){
		if($conn!=NULL){
			mysql_close($conn);	
		}
	}
	
	function loginform(){
		echo	"<form action='dologin.php' method='post'>
					<label for='email'>Email</label><input type='text' name='email' id='email'><br>
					<label for='password'>Password</label><input type='password' name='password' id='password'><br>
					<input type='submit' value='Login'> or <a href='register.php'>Register</a>
				</form>";	
	}

	function sendvalidationkey($email, $key, $UserID){
		mail($email, "Your validation key", "http://example.com/validate.php?k=" . $key . "&id=" . $UserID, "from: noreply@example.com");
	}

	function generatesalt($max = 16){
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%*&?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
	}
	
	function checklogin(){
		global $user, $sessionkey;
		opendb();
		if(isset($loggedin)){
			if($loggedin!=1){
				global $loggedin;
				if(!isset($user)){
					$user=mysql_real_escape_string($_COOKIE['userid']);
				}
				$sessionkey=mysql_real_escape_string($_COOKIE['sessionkey']);
				$time=time();
				$ip=$_SERVER['REMOTE_ADDR'];
				$query="SELECT * FROM sessions WHERE UserID='$user' AND SessionKey='$sessionkey' AND IP='$ip' AND SessionTimeout>'$time'";
				$result=mysql_query($query) or die(mysql_error());
				if(mysql_num_rows($result)==1){
					$row=mysql_fetch_assoc($result);
					$sid=$row['SessionID'];
					$time=$time+3600;
					$query="UPDATE sessions SET SessionTimeout='$time' WHERE SessionID='$sid'";
					mysql_query($query) or die(mysql_error());
					setcookie("userid", $user, $time);
					setcookie("sessionkey", $sessionkey, $time);
					$loggedin=1;	
				}
			}
		}else{
			global $loggedin;
			if(!isset($user)){
				$user=mysql_real_escape_string($_COOKIE['userid']);
			}
			$sessionkey=mysql_real_escape_string($_COOKIE['sessionkey']);
			$time=time();
			$ip=$_SERVER['REMOTE_ADDR'];
			$query="SELECT * FROM sessions WHERE UserID='$user' AND SessionKey='$sessionkey' AND IP='$ip' AND SessionTimeout>'$time'";
			$result=mysql_query($query) or die(mysql_error()."a");
			if(mysql_num_rows($result)==1){
				$row=mysql_fetch_assoc($result);
				$sid=$row['SessionID'];
				$time=$time+3600;
				$query="UPDATE sessions SET SessionTimeout='$time' WHERE SessionID='$sid'";
				mysql_query($query) or die(mysql_error());
				setcookie("userid", $user, $time);
				setcookie("sessionkey", $sessionkey, $time);
				$loggedin=1;	
			}	
		}
		closedb($conn);
	}
	
	function paymentForm($user){
		echo 	"<div id='paymentform'>
				On <select name='day' id='day'>";
				$i=0;
				while($i<31){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("j")){
						echo " selected='selected'";
					}
					echo ">".$i.date("S", strtotime("01/".$i."/2000"))."</option>";
				}
					
		echo		"</select><select name='month' id='month'>";
				$i=0;
				while($i<12){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("n")){
						echo " selected='selected'";
					}
					echo ">".date("M", strtotime($i."/01/2000"))."</option>";
				}
					
		echo		"</select><select name='year' id='year'>";
				$i=2009;
				while($i<date("Y")+2){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("Y")){
						echo " selected='selected'";
					}
					echo ">".$i."</option>";
				}
					
		echo		"</select>
					<select name='getorgive' id='getorgive'>
						<option value='-1'>Pay</option>
						<option value='1'>Receive From</option>
					</select>
					<input type='text' name='otherparty' id='otherparty'>
					<label for='desc'>Description</label><input type='text' name='desc' id='desc'>
					<label for='type'>Type</label>
					<select name='type' id='type'>
						<option>Cheque</option>
						<option>Card</option>
						<option>Cash</option>
						<option>Transfer</option>
					</select>
					<label for='amount'>Amount</label><input type='number' step='0.01' name='amount' id='amount' onkeypress=\"addPaymentEnter(event)\">
					<select name='account' id='account'>";
					$query="SELECT * FROM accounts WHERE UserID='$user'";
					$result=mysql_query($query) or die(mysql_error());
					
					while($row=mysql_fetch_assoc($result)){
						echo "<option value='".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
					}
		echo		"</select>
					<button onclick=\"addPayment()\">Add Payment</button>
				</div>";	
	}
	
	function statement($display, $user, $order = 0){
		if ($order!=1) {
			$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' ORDER BY Timestamp ASC Limit 0,".$display;
		} else {
			$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' ORDER BY Timestamp DESC Limit 0,".$display;
		}

		$result=mysql_query($query) or die(mysql_error());
		
		echo 	"<table>
					<thead>
						<tr>
							<th>Date <a href='index.php?order=1'>&uarr;</a><a href='index.php?order=0'>&darr;</a></th>
							<th>To/From</th>
							<th>Description</th>
							<th>In</th>
							<th>Out</th>
							<th>Type</th>
							<th>Account</th>
							<th>Operations</th>
						</tr>
					</thead>
					<tbody>";
		
		while($row=mysql_fetch_assoc($result)){
			$amount=$row['PaymentAmount'];
			if($amount<0){
				$amount="</td><td class='align_right'><span class='red'>".forcedecimals($amount*(-1))."</span>";
			}else{
				$amount=forcedecimals($amount)."</td><td>";
			}
			echo 	"<tr id='payment".$row['PaymentID']."'>
						<td>".date("d/m/y", $row['Timestamp'])."</td>
						<td>".stripslashes($row['PaymentName'])."</td>
						<td>".stripslashes($row['PaymentDesc'])."</td>
						<td class='align_right'>".$amount."</td>
						<td>".$row['PaymentType']."</td>
						<td>".$row['AccountName']."</td>
						<td>
							<button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
							<button onclick=\"editForm('".$row['PaymentID']."')\">Edit</button>
						</td>
					</tr>";	
		}
		$query="SELECT * FROM payments WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysql_error());
		$total=0;
		
		while($row=mysql_fetch_assoc($result)){
			$total=$total+$row['PaymentAmount'];
		}
		if($total<0){
			$total="<span class='red'>".forcedecimals($total)."</span>";
		}else{
			$total=forcedecimals($total);	
		}
		
		
		echo		"<tr><td colspan='2'</td><td>Balance</td><td id='balance'>".$total."</td><tr></tbody>
				</table><div id='responsetext'></div>";
	
	}

	function forcedecimals($number, $decplaces=2, $decpoint='.', $thousandseparator=''){
		/*
		syntax: number_format(<number>,<decimalplaces>,<decimalpointsymbol>,<thousandseparator>)
		*/
		return number_format($number, $decplaces, $decpoint, $thousandseparator);
	}
	
	function checkAccount($user, $account){
		$query="SELECT * FROM accounts WHERE UserID='$user' AND AccountID='$account'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)!=1){ // Check if the account is not connected to this user
			$query="SELECT * FROM accounts WHERE UserID='$user' LIMIT 0,1"; //This needs to at some point just select the default
			$result=mysql_query($query) or die(mysql_error());
			$row=mysql_fetch_assoc($result);
			$account=$row['AccountID'];
		}
		return $account;	
	}
	
	function accountList($user){
		$query="SELECT * FROM accounts WHERE UserID='$user' ORDER BY AccountName ASC";
		$result=mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_assoc($result)){
			echo stripslashes($row['AccountName'])."<br>";	
		}
	}
	
	function accountForm(){
		echo "<input type='text' name='account' id='account' placeholder='Account Name' onkeypress=\"addAccountEnter(event)\"><button onclick=\"addAccount()\">Add Account</button>";
	}
	
	function sanitise($fetch, $g='g'){
		opendb();
		if($g=='g'){
			return mysql_real_escape_string(htmlentities($_GET[$fetch]));
		}elseif($g=='p'){
			return mysql_real_escape_string(htmlentities($_POST[$fetch]));
		}
		closedb($conn);
	}


?>