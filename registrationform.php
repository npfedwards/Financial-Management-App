<form action='doregister.php' method='post'>
	<label for='email'>Email</label><input type='email' name='email' id='email' placeholder='user@example.com' required autofocus><br>
    <label for='password'>Password</label><input type='password' name='password' id='password' placeholder='6+ characters please!' pattern=".{6}.*" required><br>
    <label for='repeatpassword'>Password again</label><input type='password' name='repeatpassword' id='password' placeholder='again! again!' pattern=".{6}.*" required><br>
    <label for='prefcurrency'>Preferred Currency</label>
    	<select name='prefcurrency' id='currency'>
    		<option value="&pound;">&pound;</option>
    		<option value="&dollar;">&dollar;</option>
    		<option value="&euro;">&euro;</option>
    	</select><br>
    <input type='submit' value='Register'>
</form>