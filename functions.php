<?php
	include 'dbconstants.php';
	define('fromheader', "From: admin@unihouse.co.uk");
	define('baseurl', "http://money.unihouse.co.uk/");
	
	function opendb(){
		$conn = mysql_connect(dbhost, dbuser, dbpass) or die(mysql_error());
		$dbc = mysql_select_db(dbname) or die("Failed to connect");
	}
	
	function loginform(){
		echo	"<div id='loginform'>
					<form action='dologin.php' method='post'>
						<table>
							<tr>
								<td>
									<label for='email'>Email</label>
								</td><td>
									<input type='text' name='email' id='email'>
								</td>
							</tr><tr>
								<td>
									<label for='password'>Password</label>
								</td><td>
									<input type='password' name='password' id='password'>
								</td>
							</tr><tr>
								<td></td><td>
									<input type='submit' value='Login'> or <a href='register.php'>Register</a>
								</td>
							</tr><tr>
								<td></td><td>
									<a href='iforgot.php'>Forgot your password?</a>
								</td>
							</tr>
						</table>
					</form>
				</div>";	
	}

	function forgotpasswordform(){
		echo 	"<p>Please enter your email address, and we'll send you a link with which you can reset your password:</p>
				<form action='sendpasswordreset.php' method='post'>
					<input type='email' name='email' id='email' placeholder='Enter your email address'>
					<input type='submit' value='Submit'>
				</form>
				<p>Haven't recieved an email with your validation link? Click <a href=\"resendvalidationkey.php\">here</a> to resend it.</p>
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

	function resendvalidationkeyform(){
		echo 	"<p>Enter your email address, and we'll resend your validation link to you:</p>
				<form action='doresendvalidationkey.php' method='post'>
					<input type='email' name='email' id='email' placeholder='Email address'>
					<input type='submit' value='Submit'>
				</form>
		";
	}

	function sendvalidationkey($email, $key, $UserID){
		mail($email, "Your validation key", "Thanks for signing up! Before you can use your account, you'll need to activate it. To do that, just click on this link: ".baseurl."validate.php?k=" . $key . "&id=" . $UserID, fromheader);
	}

	function sendpasswordreset($email, $key, $UserID){
		mail($email, "Passowrd Reset Link", "Click the following link to reset your password. This link is only good for one use, and is only valid for a week. ".baseurl."resetpassword.php?k=" . $key . "&id=" . $UserID, fromheader);
	}

	function resendvalidationkey($email, $key, $UserID){
		mail($email, "Your validation key", "Here's your validation key again: ".baseurl."validate.php?k=" . $key . "&id=" . $UserID."<br>Remember that it's only valid for the a week since you first registered!", fromheader);
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
					setcookie("userid", $user, $time, "/xmlhttp/");
					setcookie("sessionkey", $sesskey, $time, "/xmlhttp/");
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
				setcookie("userid", $user, $time, "/xmlhttp/");
				setcookie("sessionkey", $sesskey, $time, "/xmlhttp/");
				$loggedin=1;	
			}	
		}
		
	}
	
	function paymentForm($user){
		echo 	"<div id='paymentform'>
				<span>On <select name='day' id='day'>";
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
					</span>
					<span id='tofrom'>
						<input type='text' name='otherparty' id='otherparty'>
						<span onclick=\"otherAccountSelect()\" class='clickable'>Another of your accounts?</span>
					</span>
					<span>
					<label for='desc'>Description</label><input type='text' name='desc' id='desc'>
					<label for='type'>Type</label>";
		$pm=paymentMethod($user);
		echo 		"<select name='type' id='type'>
						<option";
					if($pm=="Cheque"){
						echo " selected='selected'";	
					}
		echo 		">Cheque</option>
						<option";
					if($pm=="Card"){
						echo " selected='selected'";	
					}
		echo		">Card</option>
						<option";
					if($pm=="Cash"){
						echo " selected='selected'";	
					}
		echo		">Cash</option>
						<option";
					if($pm=="Transfer"){
						echo " selected='selected'";	
					}
		echo		">Transfer</option>
						<option";
					if($pm=="Direct Debit"){
						echo " selected='selected'";	
					}
		echo		">Direct Debit</option>
						<option";
					if($pm=="Standing Order"){
						echo " selected='selected'";	
					}
		echo		">Standing Order</option>
					</select>
					<label for='amount'>Amount</label><input type='number' step='0.01' name='amount' id='amount' onkeypress=\"addPaymentEnter(event)\">
					<select name='account' id='account'>";
					$query="SELECT * FROM accounts WHERE UserID='$user' AND Archived='0'";
					$result=mysql_query($query) or die(mysql_error());
					
					while($row=mysql_fetch_assoc($result)){
						echo "<option value='".$row['AccountID']."'>".stripslashes($row['AccountName'])."</option>";	
					}
		echo		"</select>
					Repeat <select name='repeat' id='repeat' onchange=\"showHideRepeatOptions(this)\">
						<option>No</option>
						<option>Yes</option>
					</select>
					<span id='repeatoptions'></span>
					<label for='labelselect'>Label</span>
					<select name='labels' id='labelselect'>
						<option value='0'>No Label</option>
						";
						$query="SELECT * FROM labels WHERE UserID='$user'";
						$result=mysql_query($query) or die(mysql_error());
					
					while($row=mysql_fetch_assoc($result)){
						echo "<option value='".$row['LabelID']."' style='color:".$row['Colour']."'>".stripslashes($row['LabelName'])."</option>";	
					}
		echo		"</select>
					<button onclick=\"addPayment()\">Add Payment</button></span>
				</div>";	
	}
	
	function statement($display, $user, $order = 1, $account = 0, $offset = 0, $recvalue = 0, $currfield = 'date', $startdate=NULL, $enddate=NULL){
		$account=checkAccount($user, $account, 0);
		if($account!=0){
			$accountquery="AND payments.AccountID='".$account."' ";
		}else{
			$accountquery="";	
		}
		if(!is_int($offset)){
			$offset=0;
		}
		
		$currentpage=intval($offset/$display)+1;
		pagination($user,$account,$display,$currentpage);
		
		if($currfield=='otherparty'){
			$field='PaymentName';	
		}elseif($currfield=='desc'){
			$field='PaymentDesc';	
		}elseif($currfield=='out'){
			$field='PaymentAmount';	
		}elseif($currfield=='account'){
			$field='AccountName';	
		}elseif($currfield=='reconciled'){
			$field='Reconciled';	
		}elseif($currfield=='label'){
			$field='payments.LabelID';	
		}else{
			$field='Timestamp';
		}
		
		if($startdate!=NULL){
			$between=" AND Timestamp>'$startdate' ";	
		}
		if($enddate==NULL){
			$enddate=time();
		}
		
	
		$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID LEFT JOIN labels ON labels.LabelID=payments.LabelID WHERE payments.UserID='$user' AND Deleted='0' AND Timestamp<'$enddate' ".$between.$accountquery."ORDER BY ".$field." DESC Limit ".$offset.",".$display;
		$result=mysql_query($query) or die(mysql_error());
		
		if($order!=0 && mysql_num_rows($result)!=0){ //PLEASE PLEASE find a nicer way to do this!
			while($row=mysql_fetch_assoc($result)){
				$paymentids=" OR PaymentID='".$row['PaymentID']."'".$paymentids;
			}
			$paymentids="WHERE".substr($paymentids, 3);
			$query="SELECT * FROM payments LEFT JOIN accounts ON payments.AccountID=accounts.AccountID LEFT JOIN labels ON labels.LabelID=payments.LabelID ".$paymentids." AND Deleted='0' ORDER BY ".$field." ASC";
			$result=mysql_query($query) or die(mysql_error());
		}

		echo 	"<table id='statement'>
					<thead>
						<tr>
							<th>Date</th>
							<th>To/From</th>
							<th>Description</th>
							<th>In</th>
							<th>Out</th>
							<th>Type</th>
							<th>Account</th>
							<th>Label</th>
							<th>Reconciled</th>
							<th>Operations</th>
						</tr>
						<tr>
							<form name='sortbuttons'>
							<th>";
							sortbuttons('date', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('otherparty', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('desc', $order, $currfield);
		echo 				"</th><th></th><th>";
							sortbuttons('out', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('type', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('account', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('label', $order, $currfield);
		echo 				"</th><th>";
							sortbuttons('reconciled', $order, $currfield);
		echo 				"</th><th></th>
							</form>
						</tr>
					</thead>
					<tbody>";
		

		while($row=mysql_fetch_assoc($result)){
			$amount=$row['PaymentAmount'];
			$amount=displayamount($amount,$user,1);
			echo 	"<tr";
			if($row['Timestamp']>time()){
				echo " class='futurepayment'";	
			}
			echo 	" id='payment".$row['PaymentID']."'>
						<td>".date("d/m/y", $row['Timestamp'])."</td>
						<td>".stripslashes($row['PaymentName'])."</td>
						<td>".stripslashes($row['PaymentDesc'])."</td>
						<td class='align_right'>".$amount."</td>
						<td>".stripslashes($row['PaymentType'])."</td>
						<td>".stripslashes($row['AccountName'])."</td>
						<td style='color:".stripslashes($row['Colour'])."'>".stripslashes($row['LabelName'])."</td>
						<td><input type='checkbox' id='reconciled' onclick=\"reconcile(this, ".$row['PaymentID'].")\"";
			if($row['Reconciled']==1){
					echo "checked='checked'";
			}
			echo		"></td>
						<td>
							<button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
							<button onclick=\"editForm('".$row['PaymentID']."')\">Edit</button>
						</td>
					</tr>";	
		}
		$endtime=$enddate;
		if($enddate<time()){
			$endtime=time();
		}
		$query="SELECT * FROM payments WHERE UserID='$user' AND Deleted='0' AND Timestamp<'".$endtime."' ".$accountquery;
		$result=mysql_query($query) or die(mysql_error());
		$total=0;
		
		while($row=mysql_fetch_assoc($result)){
			$total=$total+$row['PaymentAmount'];
		}
		$total=displayamount($total,$user,1);
		
		echo		"<tr><td colspan='2'</td><td>Balance</td><td id='balance' class='align_right'>".$total."</td></tr></tbody>
				</table>";
		echo "<div id='reconcilereport'>";
		reconcilereport($user, $account);
		echo 	"</div><div id='responsetext'></div>";
	
	}
	
	function sortbuttons($field, $order, $currfield){
		echo "<input type='radio' name='sort' value='1".$field."' onchange=\"showWithOffset()\"";
			  if($order==1 && $field==$currfield){
				  echo " checked='checked'";	
			  }
		echo	">&uarr;<input type='radio' name='sort' value='0".$field."' onchange=\"showWithOffset()\"";
			  if($order==0 && $field==$currfield){
				  echo " checked='checked'";	
			  }
		echo	">&darr;";
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
	
	function checklabel($user, $label, $default=0){
		if($label!=0){
			$query="SELECT * FROM labels WHERE UserID='$user' AND LabelID='$label'";
			$result=mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result)!=1){ // Check if the account is not connected to this user
				$label=$default;	
			}
		}
		return $label;	
	}
	
	function currencySymbol($user){
		$query="SELECT * FROM users WHERE UserID='$user'";
		$currencyresult=mysql_query($query) or die(mysql_error());
		$currencyarray=mysql_fetch_array($currencyresult);
		return $currencyarray['PrefCurrency'];
	}
	
	function accountList($user){
		$query="SELECT * FROM accounts WHERE UserID='$user' ORDER BY AccountName ASC";
		$result=mysql_query($query) or die(mysql_error());
		echo "<table>";
		while($row=mysql_fetch_assoc($result)){
			echo "<tr id='account".$row['AccountID']."'>";
			accountInList($row['AccountID'],$row['AccountName'],$row['Archived']);
			echo "</tr>";	
		}
	}
	
	function accountInList($account, $accountname, $archive){
		echo "<td";
		if($archive==1){
			echo " class='archived'";
		}
		echo ">".stripslashes($accountname)."</td><td";
		if($archive==1){
			echo " class='archived'";
		}
		echo "><button onclick=\"editAccountForm(".$account.",'".stripslashes($accountname)."')\">Edit</button><button onclick=\"deleteAccount(".$account.")\">Delete</button><button onclick=\"archiveAccount(".$account.",";
		if($archive==1){
			echo "0";
		}else{
			echo "1";	
		}
		echo ")\">";
		if($archive==1){
			echo "Unarchive";
		}else{
			echo "Archive";	
		}
		echo "</button></td>";
	}
	
	function accountForm(){
		echo "<tr><td><input type='text' name='account' id='account' placeholder='Account Name' onkeypress=\"addAccountEnter(event)\"></td><td><button onclick=\"addAccount()\">Add Account</button></td></tr></table>";
	}

	function currencyPrefForm($user){
		$cs=currencySymbol($user);
		echo "Preffered Currency
				<select name='prefcurrency' id='currency'>
					<option value='pound'";
			if($cs=="&pound;"){
				echo " selected='selected'";	
			}
			echo	">&pound;</option>
					<option value='dollar'";
			if($cs=="&dollar;"){
				echo " selected='selected'";	
			}
			echo	">&dollar;</option>
					<option value='euro'";
			if($cs=="&euro;"){
				echo " selected='selected'";	
			}
			echo	">&euro;</option>
				</select>
				<button onclick=\"updateCurrency()\">Update</button>";
	}

	function changePasswordForm(){
		echo "	<h3>Change Password:</h3>
				<form action='changepassword.php' method='post'>
				<input type='password' name='currentpassword' id='currentpassword' placeholder='Current Password'><br>
				<input type='password' name='newpassword1' id='newpassword1' placeholder='New Password'><br>
				<input type='password' name='newpassword2' id='newpassword2' placeholder='Repeat'><br>
				<input type='submit' value='Change Password'></input>
				</form>";
	}
	function exportForm(){
		echo "	<h3>Download your data:</h3>
				<p>This will export ALL your data to the format of your choice</p>
				<form action='export.php' method='post'>
				Export Format
				<select name='exportformat' id='format'>
					<option value='csv'>CSV (For importing into Excel)</option>
					<option value='pdf' disabled='disabled'>PDF (For printing)</option>
				</select>
				<input type='submit' value='Export Data'></input>
				</form>";
	}

	function makeSafeForCSV($string){
		/*
		Makes a string safe to go into a CSV file by wrapping it in quotes, and changing any
		html chars (e.g. &amp;) to their proper characters.
		*/
		return '"'.str_replace('"','""',html_entity_decode(stripslashes($string))).'"';
	}

	function sanitise($fetch, $g='g'){
		opendb();
		if($g=='g'){
			return mysql_real_escape_string(htmlentities($_GET[$fetch]));
		}elseif($g=='p'){
			return mysql_real_escape_string(htmlentities($_POST[$fetch]));
		}
		
	}
	
	function accountPicker($user){
		$query="SELECT * FROM accounts WHERE UserID='$user' ORDER BY AccountName ASC";
		$result=mysql_query($query) or die(mysql_error());
		echo "Account: <select onchange=\"showStatement(0)\" id='accsel'>
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
		$endtime=time()+604800;
		$query="SELECT * FROM payments WHERE UserID='$user' AND Deleted='0' AND Timestamp<'$endtime' ".$account;
		$result=mysql_query($query) or die(mysql_error());
		$numrows=mysql_num_rows($result);
		
		$pages=ceil($numrows/$perpage);
		$i=0;
		echo "Page <select onchange=\"showWithOffset()\" id='page'>";
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
		echo 	"Per Page <select onchange=\"showWithOffset()\" id='numperpage'>
					<option>10</option>
					<option selected='selected'>20</option>
					<option>50</option>
					<option>100</option>
				</select>";
	}
	
	function dorepeats($user){
		$query="SELECT * FROM repeats WHERE ExpireTime>'".time()."' AND repeats.UserID='$user'";
		$result=mysql_query($query) or die(mysql_error());
		
		while($row=mysql_fetch_assoc($result)){
			$repeatid=$row['RepeatID'];
			$repeatinsertid=$row['PairedID'];
			$account=$row['AccountID'];
			$amount=$row['PaymentAmount'];
			if($row['ToAccount']!=0){
				$theotherparty=getaccountname($account);
				$toamount=-$amount;
			}
			if($row['Frequency']=='m'){
			  $d=date("j",$row['Timestamp']);
			  $m=date("n",$row['Timestamp']);
			  $y=date("Y",$row['Timestamp']);
			  if($m==12){
				  $m=1;
				  $y++;
			  }else{
				  $m++;	
			  }
			  $time=strtotime($m."/".$d."/".$y);
			  $i=2;
			  while($time<time()+604800 && $i<=$rt){
				  $insertid=0;
				  $query="SELECT * FROM payments WHERE Timestamp='$time' AND RepeatID='".$row['RepeatID']."'";
				  $out=mysql_query($query) or die(mysql_error());
				  if(mysql_num_rows($out)==0){
					  if($row['PairedID']!=0){
						  $query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, RepeatID, LabelID) VALUES ('$user', '".$row['ToAccount']."', '$time', '$theotherparty', '".$row['PaymentDesc']."', '$toamount', '".$row['PaymentType']."', '$account', '$repeatinsertid', '".$row['LabelID']."')";
						  mysql_query($query) or die(mysql_error()." dorepeat#001");
						  $insertid=mysql_insert_id();
					  }
					  $query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, RepeatID, LabelID) VALUES ('$user', '$account', '$time', '".$row['PaymentName']."', '".$row['PaymentDesc']."', '$amount', '".$row['PaymentType']."', '".$row['ToAccount']."', '$insertid', '$repeatid', '".$row['LabelID']."')";
					  mysql_query($query) or die(mysql_error()." dorepeat#002");
					  
					  if($insertid!=0){
						  $paymentid=mysql_insert_id();
						  $query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
						  mysql_query($query) or die(mysql_error()." dorepeat#003");
					  }
				  }
				  
				  if($m==12){
					  $m=1;
					  $y++;
				  }else{
					  $m++;	
				  }
				  $time=strtotime($m."/".$d."/".$y);
				  $i=2;
			  }
			}else{
				$time=$row['Timestamp']+$row['Frequency']*86400;
				$i=2;
				while($time<time()+604800 && $i<=$row['Times']){
					$query="SELECT * FROM payments WHERE Timestamp='$time' AND RepeatID='".$row['RepeatID']."'";
					$out=mysql_query($query) or die(mysql_error());
					if(mysql_num_rows($out)==0){
					  if($row['PairedID']!=0){
						  $query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, RepeatID, LabelID) VALUES ('$user', '".$row['ToAccount']."', '$time', '$theotherparty', '".$row['PaymentDesc']."', '$toamount', '".$row['PaymentType']."', '$account', '$repeatinsertid', '".$row['LabelID']."')";
						  mysql_query($query) or die(mysql_error()." dorepeat#004");
						  $insertid=mysql_insert_id();
					  }
					  $query="INSERT INTO payments (UserID, AccountID, Timestamp, PaymentName, PaymentDesc, PaymentAmount, PaymentType, ToAccount, PairedID, RepeatID, LabelID) VALUES ('$user', '$account', '$time', '".$row['PaymentName']."', '".$row['PaymentDesc']."', '$amount', '".$row['PaymentType']."', '".$row['ToAccount']."', '$insertid', '$repeatid', '".$row['LabelID']."')";
					  mysql_query($query) or die(mysql_error()." dorepeat#005");
					  
					  if($insertid!=0){
						  $paymentid=mysql_insert_id();
						  $query="UPDATE payments SET PairedID='$paymentid' WHERE PaymentID='$insertid'";
						  mysql_query($query) or die(mysql_error()." dorepeat#006");
					  }
					}
					$i++;
					$time=$time+$row['Frequency']*86400;
				}
			}
		}	
	}
	
	function reconcilereport($user, $account){
		$account=checkAccount($user, $account, 0);
		if($account!=0){
			$query="SELECT * FROM accounts WHERE AccountID='$account'";
			$result=mysql_query($query) or die(mysql_error());
			$row=mysql_fetch_assoc($result);
			$value=$row['ReconciledTotal'];
			$accountquery="AccountID='$account' AND ";
		}else{
			$query="SELECT * FROM accounts WHERE UserID='$user'";
			$result=mysql_query($query) or die(mysql_error());
			$value=0;
			while($row=mysql_fetch_assoc($result)){
				$value+=$row['ReconciledTotal'];
			}
			$accountquery=NULL;	
		}
		$query="SELECT * FROM payments WHERE ".$accountquery." UserID='$user' AND Reconciled='1'";
		$result=mysql_query($query) or die(mysql_error());
		$recbal=0;
		while($row=mysql_fetch_assoc($result)){
			$recbal=$recbal+$row['PaymentAmount'];
		}
		$diff=$value-$recbal;
		$recbal=displayamount($recbal,$user);
		$diff=displayamount($diff,$user);
		
		echo "<h4>Reconcile Tool</h4>
		Account Balance: ";
		if($account==0){
			echo displayamount($value,$user)."<input type='hidden' value='".$value."' id='accbal'>";	
		}else{
			echo "<input type='number' value='".$value."' step='0.01' name='accountbalance' id='accbal' onKeyUp=\"updateReconcile(this)\">";
		}
		echo "<div id='updaterec'>Reconciled Balance: ".$recbal." Difference: ".$diff."</div>";
			
	}
	
	function updatereconcile($user, $account, $value){
		$account=checkAccount($user, $account, 0);
		$query="UPDATE accounts SET ReconciledTotal='$value' WHERE AccountID='$account'";
		mysql_query($query) or die(mysql_error());
		if($account!=0){
			$account="AccountID='$account' AND ";
		}else{
			$account=NULL;	
		}
		$query="SELECT * FROM payments WHERE ".$account." UserID='$user' AND Reconciled='1'";
		$result=mysql_query($query) or die(mysql_error());
		$recbal=0;
		while($row=mysql_fetch_assoc($result)){
			$recbal=$recbal+$row['PaymentAmount'];
		}
		$diff=$value-$recbal;
		$recbal=displayamount($recbal,$user);
		$diff=displayamount($diff,$user);
		
		echo "Reconciled Balance: ".$recbal." Difference: ".$diff;
	}
	
	function getaccountname($account){
		$query="SELECT * FROM accounts WHERE AccountID='$account'";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		return $row['AccountName'];	
	}

	function statementdatepicker(){
		echo " between <select onchange=\"showWithOffset()\" name='day' id='startday'>";
				$i=0;
				while($i<31){
					$i++;
					echo "<option value='".$i."'";
					if($i==1){
						echo " selected='selected'";
					}
					echo ">".$i.date("S", strtotime("01/".$i."/2000"))."</option>";
				}
					
		echo		"</select><select onchange=\"showWithOffset()\" name='month' id='startmonth'>";
				$i=0;
				while($i<12){
					$i++;
					echo "<option value='".$i."'";
					if($i==1){
						echo " selected='selected'";
					}
					echo ">".date("M", strtotime($i."/01/2000"))."</option>";
				}
					
		echo		"</select><select onchange=\"showWithOffset()\" name='year' id='startyear'>";
				$i=2009;
				while($i<date("Y")+2){
					$i++;
					echo "<option value='".$i."'";
					if($i==2009){
						echo " selected='selected'";
					}
					echo ">".$i."</option>";
				}
					
		echo		"</select> and <select onchange=\"showWithOffset()\" name='day' id='endday'>";
				$i=0;
				while($i<31){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("j", time())){
						echo " selected='selected'";
					}
					echo ">".$i.date("S", strtotime("01/".$i."/2000"))."</option>";
				}
					
		echo		"</select><select onchange=\"showWithOffset()\" name='month' id='endmonth'>";
				$i=0;
				while($i<12){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("n", time())){
						echo " selected='selected'";
					}
					echo ">".date("M", strtotime($i."/01/2000"))."</option>";
				}
					
		echo		"</select><select onchange=\"showWithOffset()\" name='year' id='endyear'>";
				$i=2009;
				while($i<date("Y")+2){
					$i++;
					echo "<option value='".$i."'";
					if($i==date("Y", time())){
						echo " selected='selected'";
					}
					echo ">".$i."</option>";
				}
					
		echo		"</select>
					<button onclick=\"showWithOffset()\">Show</button>";
	}
	
	function displayamount($amount, $user, $instatement=0){
		$currencysymbol=currencysymbol($user);
		$amount=forcedecimals($amount);
		if($amount<0){
			$amount="<span class='red'>-".$currencysymbol.forcedecimals(-$amount)."</span>";
			if($instatement==1){
				$amount="</td><td class='align_right'>".$amount;	
			}
		}else{
			$amount=$currencysymbol.forcedecimals($amount);
			if($instatement==1){
				$amount=$amount."</td><td>";	
			}
		}
		return $amount;
	}


	function paymentMethod($user){
		$query="SELECT * FROM users WHERE UserID='$user'";
		$paymentresult=mysql_query($query) or die(mysql_error());
		$paymentarray=mysql_fetch_array($paymentresult);
		return $paymentarray['PrefPaymentMethod'];
	}

	function paymentPrefForm($user){
		$pm=paymentMethod($user);
		echo "Preffered Currency
				<select name='prefpaymentmethod' id='paymentmethod'>
					<option value='Cheque'";
			if($pm=="Cheque"){
				echo " selected='selected'";	
			}
			echo	">Cheque</option>
					<option value='Card'";
			if($pm=="Card"){
				echo " selected='selected'";	
			}
			echo	">Card</option>
					<option value='Cash'";
			if($pm=="Cash"){
				echo " selected='selected'";	
			}
			echo	">Cash</option>
					<option value='Transfer'";
			if($pm=="Transfer"){
				echo " selected='selected'";	
			}
			echo	">Transfer</option>
					<option value='Direct Debit'";
			if($pm=="Direct Debit"){
				echo " selected='selected'";	
			}
			echo	">Direct Debit</option>
					<option value='Standing Order'";
			if($pm=="Standing Order"){
				echo " selected='selected'";	
			}
			echo	">Standing Order</option>
				</select>
				<button onclick=\"updatePaymentMethod()\">Change</button>";
	}
	
	function labellist($user){
		$query="SELECT * FROM labels WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysqlerror("LL1"));
		
		echo "<thead>
				<tr>
					<th>Label</th>
					<th>Monthly Budget</th>
				</tr>";
			while($row=mysql_fetch_assoc($result)){
				echo "<tr><td><span style='color:".$row['Colour']."'>".stripslashes($row['LabelName'])."</span></td><td><input type='number' step='1' value='".stripslashes($row['Budget'])."' id='budget".$row['LabelID']."' onKeyUp=\"editBudget(".$row['LabelID'].",this.value)\"></td></tr>";	
			}
	}
	
	function mysqlerror($number='0'){
		return "<div class='mysqlerror'>We've had a problem, here's the error message<br>
		".mysql_error()."<br>
		It's on ".$_SERVER['REQUEST_URI']." #".$number."</div>";
	}
	
	function budgeter($user){
		$currencysymbol=currencysymbol($user);
		echo "<h3>Budget</h3>
		<h4>This Month - ".date("F")."</h4>";
		$part=date("j")/date("t");
		$starttime=mktime(0,0,0, date('m'), 1, date('Y'));
		$query="SELECT * FROM labels WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysqlerror('BU1'));
		
		while($row=mysql_fetch_assoc($result)){
			$budget=forcedecimals($part*$row['Budget']);
			$query="SELECT * FROM payments WHERE LabelID='".$row['LabelID']."' AND Timestamp>='$starttime' AND Timestamp<'".time()."'";
			$result2=mysql_query($query) or die(mysqlerror('BU2'));
			$spent=0;
			while($row2=mysql_fetch_assoc($result2)){
				$spent+=$row2['PaymentAmount'];
			}
			$spent=forcedecimals(-$spent);
			echo "<span style='color:".$row['Colour']."'>".stripslashes($row['LabelName'])."</span> Budget: ".$currencysymbol.$budget." Spent: ".$currencysymbol.$spent."<br>";
		}
		
		if(date('n')==1){
			$month=12;
			$year=date("Y")-1;
		}else{
			$year=date("Y");
			$month=date('n')-1;	
		}
		
		$time=mktime(0,0,0, $month, 1, $year);
		
		echo "<h4>Last Month - ".date("F", $time)."</h4>";
		$query="SELECT * FROM labels WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysqlerror('BU3'));
		
		while($row=mysql_fetch_assoc($result)){
			$budget=forcedecimals($row['Budget']);
			$query="SELECT * FROM payments WHERE LabelID='".$row['LabelID']."' AND Timestamp>='$time' AND Timestamp<'$starttime'";
			$result2=mysql_query($query) or die(mysqlerror('BU4'));
			$spent=0;
			while($row2=mysql_fetch_assoc($result2)){
				$spent+=$row2['PaymentAmount'];
			}
			$spent=forcedecimals(-$spent);
			echo "<span style='color:".$row['Colour']."'>".stripslashes($row['LabelName'])."</span> Budget: ".$currencysymbol.$budget." Spent: ".$currencysymbol.$spent."<br>";
		}
	}
?>