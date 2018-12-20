<div class="form">
	<form method="post">
		<div>
			Your current email address is: <b><?php echo($_SESSION['email']);?></b><br/><br/>
			<label for="new email">Please enter new email address </label>
			<input type="text" name="new email" class="input" id="new email" placeholder="new email" value="<?=isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : '';?>">
		</div>
		<div>
			<label for="check password">Please enter password for confirmation</label>
			<input type="password" name="check password" class="input" id="password" placeholder="password">
		</div>
		<button type="submit" class="btn">Apply</button>
		<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
	</form>
</div>
