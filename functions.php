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
					<input type='submit' value='Login'>
				</form>";	
	}

	function generatesalt($max = 16){
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
	}
	
	function checklogin(){
		opendb();
		if(isset($loggedin)){
			if($loggedin!=1){
				global $loggedin;
				$user=mysql_real_escape_string($_COOKIE['user']);
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
					setcookie("user", $userid, $time);
					setcookie("sessionkey", $sessionkey, $time);
					$loggedin=1;	
				}
			}
		}else{
			global $loggedin;
			$user=mysql_real_escape_string($_COOKIE['user']);
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
				setcookie("user", $userid, $time);
				setcookie("sessionkey", $sessionkey, $time);
				$loggedin=1;	
			}	
		}
		closedb($conn);
	}
	
	function paymentForm(){
		echo 	"<form action='addpayment.php' method='post'>
					<label for='from'><input type='text' name='from' id='from'>
					<label for='to'><input type='text' name='to' id='to'>
					<label for='type'><input type='text' name='type' id='type'>
					<label for='amount'><input type='number' name='amount' id='amount'>
				</form>";	
	}
	
	function statement($display){	
	}
?>