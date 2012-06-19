<form action='doregister.php' method='post'>
	<label for='email'>Email</label><input type='email' name='email' id='email' placeholder='user@example.com' required autofocus><br>
    <label for='password'>Password</label><input type='password' name='password' id='password' placeholder='6+ characters please!' pattern=".{6}.*" required><br>
    <label for='repeatpassword'>Password again</label><input type='password' name='repeatpassword' id='password' placeholder='again! again!' pattern=".{6}.*" required><br>
    <input type='submit' value='Login'>
</form>