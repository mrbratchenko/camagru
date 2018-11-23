<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php \vendor\core\base\View::getMeta() ?>
		<link href="/css/gallery.css" rel="stylesheet">
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
			<?php if(isset($_SESSION['error'])): ?>
				<div class="error-fade">
					<?= $_SESSION['error']; unset($_SESSION['error'])?>
				</div>
				<?php endif; ?>
				<?php if(isset($_SESSION['success'])): ?>
				<div class="success-fade">
					<?= $_SESSION['success']; unset($_SESSION['success'])?>
				</div>
			<?php endif; ?>
		<?php if(isset($_SESSION['user'])): ?>
			<div id='all'>
			<section id="main-section">
				
				<div id='movepics'></div>
				<video id="video" width="640" height="480" autoplay poster="/pics/default.jpg"></video>
				<div  id='load'></div>	
				<div>
				<input type="checkbox" id="switch" onchange="video()"/>Camera:<label for="switch" id="switch-label"> </label>
				&nbsp;&nbsp;&nbsp;Or choose an external file:
				<input type='file' id='getval' accept="image/jpeg, image/bmp, image/png" name="background-image" size="60"></input><br/>	
				<div id='buttons'>
					<button id="snap" disabled>Take photo!</button>
					<?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>
					<button method='post' type="button" id="save" onclick="saveImg('<?php echo $page;?>')" disabled>Save photo!</button>
				<div id="tools">
					<button id="enlarge" class="tooltip">+<span>Enlarge superposable</span></button>
					<button id="shrink" class="tooltip">-<span>Shrink superposable</span></button>
					<button id="delete" class="tooltip">Delete<span>Delete superposable</span></button><br/>
				</div>	
				</div>
				<div id="canvas2"></div>
				<div class="pics">
					<img onclick="clone(this)" width="70px" height="auto" src="/pics/beer.png" />
					<img onclick="clone(this)" width="70px" height="auto" src="/pics/cat.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/beard.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/trump.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/squirrel.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/jobs.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/mask.png" />
					<img onclick="clone(this)" width="70px" height="auto" src="/pics/dino.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/rat.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/bieber.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/hat.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/glasses.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/batman.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/jaguar.png" />
					<img onclick="clone(this)" width="100px" height="auto" src="/pics/musk.png" />
				</div>
				<canvas id="canvas" width="640" height="480"></canvas>
			</section>
		<?php endif; ?>
			<?=$content?>
			<?php
				foreach ($scripts as $script) {
					echo $script;
				}
			?>
			<?php if(isset($_SESSION['user'])): ?>
				<script type="text/javascript" src="../public/js/save.js"></script>
				<script type="text/javascript" src="../public/js/like.js"></script>
				<script type="text/javascript" src="../public/js/deletephoto.js"></script>
				<script type="text/javascript" src="../public/js/comment.js"></script>
			<?php endif; ?>
				<script type="text/javascript" src="../public/js/modal.js"></script>
		</div>	
		<footer id="footer">
			COPYRIGHT 2018		
		</footer>	
	</body>
</html>