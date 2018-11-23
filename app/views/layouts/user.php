<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php \vendor\core\base\View::getMeta() ?>
		<link href="/css/gallery.css" rel="stylesheet">
		<link href="/css/user.css" rel="stylesheet">
	</head>
	<body>
		<header id='header'>
			<div id="username">
				<?php if(isset($_SESSION['user'])): ?>
					Welcome, <?php echo $_SESSION['user'];?>
				<?php else: ?>
					Welcome, guest
				<?php endif; ?>
			</div>
			<nav id="pills">
				<a href="/">Home</a>
				<?php if(!isset($_SESSION['user'])): ?>
					<a href="/user/login">Sign in</a>
					<a href="/user/signup">Register</a>
				<?php endif; ?>
				<?php if(isset($_SESSION['user'])): ?>
					<a href="/gallery/myphotos">My gallery</a>
					<a href="/user/logout">Logout</a>
					<li class="dropdown">
					    <a href="javascript:void(0)" class="dropbtn">Preferences</a>
					    <div class="dropdown-content">
					      <a href="/user/editlogin">Change username</a></br>
					      <a href="/user/editpassword">Change password</a></br>
					      <a href="/user/editemail">Change email address</a></br>
					      <a href="/user/editnotifications">Change notification preferences</a>
					    </div>
					</li>
				<?php endif; ?>
			</nav>
		</header>
		<div id='all-user'>
		<?php if(isset($_SESSION['error'])): ?>
		<div class="error">
			<?= $_SESSION['error']; unset($_SESSION['error'])?>
		</div>
		<?php endif; ?>
		<?php if(isset($_SESSION['success'])): ?>
		<div class="success">
			<?= $_SESSION['success']; unset($_SESSION['success'])?>
		</div>
		<?php endif; ?>
		<?=$content?>
		<?php
			foreach ($scripts as $script) {
				echo $script;
			}
		?>
		</div>
		<footer id="footer">
			COPYRIGHT 2018		
		</footer>
	</body>
</html>