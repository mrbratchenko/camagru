<div class="form">
	<form method="post">
		<div>
			Your current login is: <b><?php echo $_SESSION['user'];?></b><br/><br/>
			<label for="new login">Please enter new login</label>
			<input type="text" name="new login" class="input" id="new login" placeholder="new login" value="<?=isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : '';?>">
		</div>
		<div>
			<label for="password">Please enter password for confirmation</label>
			<input type="password" name="check password" class="input" id="password" placeholder="password">
		</div>
		<button type="submit" class="btn">Apply</button>
		<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
	</form>
</div>
