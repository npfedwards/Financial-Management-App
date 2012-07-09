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
					<input type='submit' value='Login'> or <a href='register.php'>Register</a><br>
					<a href='iforgot.php'>Forgot your password?</a>
				</form>";	
	}

	function forgotpasswordform(){
		echo 	"<p>Please enter your email address, and we'll send you a link with which you can reset your password:</p>
				<form action='sendpasswordreset.php' method='post'>
					<input type='email' name='email' id='email' placeholder='Enter your email address'>
					<input type='submit' value='Submit'>
				</form>

		";
	}

	function passwordresetform($key, $UserID){
		echo 	"<p>Choose a new password (at least 6 characters long):</p>
				<form action='doresetpassword.php?k=".$key."&id=".$UserID."' method='post'>
					<input type='password' name='password' id='password' placeholder='New Password'>
					<input type='password' name='repeat' id='repeat' placeholder='Repeat'>
					<input type='submit' value='Submit'>
				</form>

		";
	}


	function sendvalidationkey($email, $key, $UserID){
		mail($email, "Your validation key", "http://unihouse.co.uk/beta/money/validate.php?k=" . $key . "&id=" . $UserID, "from: admin@unihouse.co.uk");
	}

	function sendpasswordreset($email, $key, $UserID){
		mail($email, "Click the following link to reset your password. This link is only good for one use, and is only valid for a week.", "http://unihouse.co.uk/beta/money/resetpassword.php?k=" . $key . "&id=" . $UserID, "From: admin@unihouse.co.uk");
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
					$time=$time+86400;
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
				$time=$time+86400;
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
						<option>Direct Debit</option>
						<option>Standing Order</option>
					</select>
					<label for='amount'>Amount</label><input type='number' step='0.01' name='amount' id='amount' onkeypress=\"addPaymentEnter(event)\">
					<select name='account' id='account'>";
					$query="SELECT * FROM accounts WHERE UserID='$user'";
					$result=mysql_query($query) or die(mysql_error());
					
					while($row=mysql_fetch_assoc($result)){
						echo "<option value='".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
					}
		echo		"</select>
					<button onclick=\"addPayment()\">Add Payment</button><br>
					Repeat <select name='repeat' id='repeat' onchange=\"if(this.value==='Yes'){showRepeatOptions()}\">
						<option>No</option>
						<option>Yes</option>
					</select>
					<span id='repeatoptions'></span>
				</div>";	
	}
	
	function statement($display, $user, $order = 1, $account = 0, $offset = 0){
		if($account!=0){
			$account="AND payments.AccountID='".$account."' ";
		}else{
			$account="";	
		}
		
		$currentpage=intval($offset/$display)+1;
		pagination($user,$account,$display,$currentpage);
	
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID WHERE payments.UserID='$user' ".$account."ORDER BY Timestamp DESC Limit ".$offset.",".$display;
		$result=mysql_query($query) or die(mysql_error());
		
		if($order!=0 && mysql_num_rows($result)!=0){ //PLEASE PLEASE find a nicer way to do this!
			while($row=mysql_fetch_assoc($result)){
				$paymentids=" OR PaymentID='".$row['PaymentID']."'".$paymentids;
			}
			$paymentids="WHERE".substr($paymentids, 3);
			$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID ".$paymentids." ORDER BY Timestamp ASC";
			$result=mysql_query($query) or die(mysql_error());
		}
		
		$currencysymbol=currencysymbol($user);


		echo 	"<table>
					<thead>
						<tr>
							<th>Date <input type='radio' name='datesort' value='1' id='datesort1' onchange=\"orderStatement(this, 'date')\"";
							if($order==1){
								echo " checked='checked'";	
							}
		echo				">&uarr;<input type='radio' name='datesort' value='0' id='datesort0' onchange=\"orderStatement(this, 'date')\"";
							if($order==0){
								echo " checked='checked'";	
							}
		echo				">&darr;</th>
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
				$amount="</td><td class='align_right'><span class='red'>".$currencysymbol.forcedecimals($amount*(-1))."</span>";
			}else{
				$amount=$currencysymbol.forcedecimals($amount)."</td><td>";
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
		$query="SELECT * FROM payments WHERE UserID='$user' ".$account;
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
		
		
		echo		"<tr><td colspan='2'</td><td>Balance</td><td id='balance' class='align_right'>".$currencysymbol.$total."</td><tr></tbody>
				</table><div id='responsetext'></div>";
	
	}

	function forcedecimals($number, $decplaces=2, $decpoint='.', $thousandseparator=''){
		/*
		syntax: number_format(<number>,<decimalplaces>,<decimalpointsymbol>,<thousandseparator>)
		*/
		return number_format($number, $decplaces, $decpoint, $thousandseparator);
	}
	
	function checkAccount($user, $account, $default=NULL){
		if($account!=0){
			$query="SELECT * FROM accounts WHERE UserID='$user' AND AccountID='$account'";
			$result=mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result)!=1){ // Check if the account is not connected to this user
				if($default==NULL){
					$query="SELECT * FROM accounts WHERE UserID='$user' LIMIT 0,1"; //This needs to at some point just select the default
					$result=mysql_query($query) or die(mysql_error());
					$row=mysql_fetch_assoc($result);
					$account=$row['AccountID'];
				}else{
					$account=$default;	
				}
			}
		}
		return $account;	
	}
	
	function currencysymbol($user){
		$query="SELECT * FROM users WHERE UserID='$user'";
		$currencyresult=mysql_query($query) or die(mysql_error());
		$currencyarray=mysql_fetch_array($currencyresult);
		return $currencyarray['PrefCurrency'];
	}
	
	function accountList($user){
		$query="SELECT * FROM accounts WHERE UserID='$user' ORDER BY AccountName ASC";
		$result=mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_assoc($result)){
			echo "<div id='account".$row['AccountID']."'>".stripslashes($row['AccountName'])." <button onclick=\"editAccountForm(".$row['AccountID'].",'".stripslashes($row['AccountName'])."')\">Edit</button></div>";	
		}
	}
	
	function accountForm(){
		echo "<input type='text' name='account' id='account' placeholder='Account Name' onkeypress=\"addAccountEnter(event)\"><button onclick=\"addAccount()\">Add Account</button><br>";
		echo "Preffered Currency<select name='prefcurrency' id='currency'><option value='pound'>&pound;</option><option value='dollar'>&dollar;</option><option value='euro'>&euro;</option></select><button onclick=\"updateCurrency()\">Update</button>";
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
	
	function accountPicker($user){
		$query="SELECT * FROM accounts WHERE UserID='$user' ORDER BY AccountName ASC";
		$result=mysql_query($query) or die(mysql_error());
		echo "Account: <select onchange=\"showAccount(this)\" id='accsel'>
				<option value='0'>All</option>";
		while($row=mysql_fetch_assoc($result)){
			echo "<option value='".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
		}
		echo "</select>";
	}
	
	function pagination($user, $account, $perpage, $currentpage){
		if($account!=0){
			$account="AND payments.AccountID='".$account."' ";
		}else{
			$account="";	
		}
		$query="SELECT * FROM payments WHERE UserID='$user' ".$account;
		$result=mysql_query($query) or die(mysql_error());
		$numrows=mysql_num_rows($result);
		
		$pages=ceil($numrows/$perpage);
		$i=0;
		echo "Page <select onchange=\"showPage(this)\" id='page'>";
		while($i<$pages){
			$offset=$i*$perpage;
			$i++;
			echo "<option value='".$offset."'";
			if($currentpage==$i){
				echo " selected='selected'";	
			}
			echo ">".$i."</option>";	
		}
		echo "</select>";
	}
	
	function numperpage(){
		echo 	"Per Page <select onchange=\"numPerPage(this)\" id='numperpage'>
					<option>10</option>
					<option selected='selected'>20</option>
					<option>50</option>
					<option>100</option>
				</select>";
	}

?>