<div class="form">
	<form method="post">
		<div>
			<label class='label' for="radio button"><b>Receive a notification anytime someone likes my photo:</b></label>
			<div class="notification">
				<div><input type="radio" name="choice_like" value="1" checked>yes</input>
				<input type="radio" name="choice_like" value="0">no</input></div>
			</div><br/>
			<label class='label' for="radio"><b>Receive a notification anytime someone comments my photo:</b></label>
			<div class="notification">
				<div><input type="radio" name="choice_comment" value="1" checked>yes</input>
				<input type="radio" name="choice_comment" value="0">no</input></div>
			</div><br/>
			<div>
				<label for="check password">Please enter password for confirmation</label>
				<input type="password" name="check password" class="input" id="password" placeholder="password">
			</div>
		</div>
		<button type="submit" class="btn">Apply</button>
		<?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']);?>
	</form>
</div>
