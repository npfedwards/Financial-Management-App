<?php
	include 'dbconstants.php';
	
	function opendb(){
		$conn = mysql_connect(dbhost, dbuser, dbpass) or die(mysql_error());
		$dbc = mysql_select_db(dbname) or die("Failed to connect");
	}
	
	function loginform(){
		echo	"<form action='dologin.php' method='post'>
					<label for='email'>Email</label><input type='text' name='email' id='email'><br>
					<label for='password'>Password</label><input type='password' name='password' id='password'><br>
					<input type='submit' value='Login'>
				</form>";	
	}
	
	function checklogin(){
		if($loggedin!=1){
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
				$query="UPDATE sessions SET SessionTimeout='$time' WHERE SessionID='$sid' LIMIT 0,1";
				mysql_query($query) or die(mysql_error());
				setcookie("user", $userid, $time);
				setcookie("sessionkey", $sessionkey, $time);
				$loggedin=1;	
			}
		}
	}
?>