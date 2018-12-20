<div class="form">
	<form method="post" action="/user/signup">
		<div>
			<label for="login">Login</label>
			<input type="text" name="login" class="input" id="login" placeholder="Login" value="<?=isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : '';?>">
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" class="input" id="password" placeholder="Password">
		</div>
		<div>
			<label for="email">Email</label>
			<input type="text" name="email" class="input" id="email" placeholder="Email" value="<?=isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : '';?>">
		</div>
		<button type="submit" class="btn">Register</button>
	</form>
	<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
</div>
