<div class="form">
	<form method="post">
		<div>
			<label for="new password">Please enter new password</label>
			<input type="password" name="new password" class="input" id="new password" placeholder="new password">
		</div>
		<div>
			<label for="confirm new password">Confirm new password</label>
			<input type="password" name="confirm new password" class="input" id="confirm new password" placeholder="confirm new password">
		</div>
		<div>
			<label for="password">Please enter old password for confirmation</label>
			<input type="password" name="check password" class="input" id="password" placeholder="password">
		</div>
		<button type="submit" class="btn">Apply</button>
		<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
	</form>
</div>
