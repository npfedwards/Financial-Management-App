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
	
	function paymentForm(){
		echo 	"<form action='addpayment.php' method='post'>
					On <select name='day'>";
				$i=0;
				while($i<31){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("j")){
						echo " selected='selected'";
					}
					echo ">".$i.date("S", strtotime("01/".$i."/2000"))."</option>";
				}
					
		echo		"</select><select name='month'>";
				$i=0;
				while($i<12){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("n")){
						echo " selected='selected'";
					}
					echo ">".date("M", strtotime($i."/01/2000"))."</option>";
				}
					
		echo		"</select><select name='year'>";
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
					<label for='amount'>Amount</label><input type='number' step='0.01' name='amount' id='amount'>
					<input type='submit' value='Add Payment'>
				</form>";	
	}
	
	function statement($display, $user, $order = 0){
		if ($order!=1) {
			$query="SELECT * FROM payments WHERE UserID='$user' ORDER BY Timestamp ASC Limit 0,".$display;
		} else {
			$query="SELECT * FROM payments WHERE UserID='$user' ORDER BY Timestamp DESC Limit 0,".$display;
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
						<td>".$row['PaymentName']."</td>
						<td>".$row['PaymentDesc']."</td>
						<td class='align_right'>".$amount."</td>
						<td>".$row['PaymentType']."</td>
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
			$total="<span class='red'>".$total."</span>";
		}
		
		
		echo		"<tr><td colspan='2'</td><td>Balance</td><td id='balance'>".forcedecimals($total)."</td><tr></tbody>
				</table><div id='responsetext'></div>";
	
	}

	function forcedecimals($number, $decplaces=2, $decpoint='.', $thousandseparator=''){
		/*
		syntax: number_format(<number>,<decimalplaces>,<decimalpointsymbol>,<thousandseparator>)
		*/
		return number_format($number, $decplaces, $decpoint, $thousandseparator);
	}


?>