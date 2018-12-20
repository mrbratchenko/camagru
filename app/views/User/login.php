<div class="form">
	<form method="post" action="/user/login">
		<div>
			<label for="login">Login</label>
			<input type="text" name="login" class="input" id="login" placeholder="Login">
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" class="input" id="password" placeholder="Password">
		</div>
		<button type="submit" class="btn">Submit</button>	
	</form>
	<form method="post" action="/user/emailforreset">
		<button type="submit" class="btn-reset">Forgot password?</button>
	</form>
</div>

