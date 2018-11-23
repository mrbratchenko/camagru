<div class="form">
	<form method="post" action="/user/changepass">
		<div>
			<label for="password">New Password</label>
			<input type="password" name="password" class="input" id="password" placeholder="Password">
		</div>
		<button type="submit" class="btn" >Change password</button>
	</form>
	<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
</div>
