<?php if(!isset($_SESSION['user'])) :?>
	<script type="text/javascript">
		var datas = document.getElementsByClassName("data");
		for(var i = 0; i < datas.length; i++){
		   datas[i].style.height = '450px';
		}
	</script>
<?php endif; ?>

<?php if(!isset($_SESSION['user'])) :?>
		<div id="slides">
			<div class="slide">
				<img src="pics/image1.png" >
		  	</div>
			<div class="slide">
				<img src="pics/image4.png" >
			</div>
			<div class="slide">
				<img src="pics/image3.png">
			</div>
		</div>
	<br>
	<div id="devider"></div>
	<script type="text/javascript" src="../public/js/slides.js"></script>
<?php endif; ?>

<div id='frame'>
	<?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>
	<div id='frame-flex'>
	<?php if (!empty($photos)) : ?>
	<?php foreach ($photos as $photo) : ?>
		<div id='<?php echo $photo['path']; ?>' class='data' style='height:550px'>
		<?php $owner = $photo['user_id']; ?>
			<div><?php echo $owner; ?></div>
			<img class="img" height='300px' width='400px' src="http://localhost:8100/<?php echo $photo['path']; ?>"/>
			<div id='<?php echo $photo['path'] . '_like'; ?>'>
			<?php if(isset($_SESSION['user'])) :?>
				<button class="<?php echo $photo['status']; ?>"  onclick="likeFunct('<?php echo $photo['path']; ?>')"></button>
				<button class='bubble' onclick="modalFunct('<?php echo $photo['path']; ?>'), moveCursor('<?php echo $photo['path']; ?>')"></button>
			<?php endif; ?>
				<div class="div-likes"><?php echo $photo['likes'];?> <?php echo ($photo['likes'] == 1) ? 'like' : 'likes'; ?></div>
			</div>	

			<button class='view-all-comm' id='<?php echo $photo['path'] . '_modal_btn'; ?>' onclick="modalFunct('<?php echo $photo['path']; ?>')">View all comments</button>
			
			<div id='<?php echo $photo['path'] . '_comments'; ?>' class='comment'>
				<?php $last = count($photo['comments']) - 1 ; ?>
				<?php $i = 0; ?>
				<?php foreach ($photo['comments'] as $comment) : ?>
				<div id='<?php echo $comment['id']; ?>' style='display: none;'>
					<b><?php echo $comment['user'];?></b>
					<?php if (strlen($comment['comment']) > 26):?>
						<?php echo substr($comment['comment'], 0, 26) . '...';?>
					<?php else: ?>
						<?php echo substr($comment['comment'], 0, 26);?>
					<?php endif; ?>
					<br><?php if (isset($_SESSION['user']) && $_SESSION['user'] == $comment['user']):?>
						<button class='cross-comm' onclick="deleteComment('<?php echo $comment['id']; ?>')" '></button>
					<?php endif; ?>
					<?php if ($i == $last):?>
					<script type="text/javascript"> document.getElementById('<?php echo $comment['id']; ?>').style.display='';
					
					</script>
					<?php endif; ?>
				</div>	
				<?php $i++;  ?>
				<?php endforeach;?>
			</div>
			<?php if(isset($_SESSION['user'])) :?>
			<div id='<?php echo $photo['path'] . '_comment_write'; ?>' class='box'>
				<form method="post" onsubmit="addComment('<?php echo $photo['path']; ?>', comment.value); return false;">
					<div class="form-comment">
						<input type="hidden" name="path" value="<?php echo $photo['path']; ?>" />
						<textarea id='<?php echo $photo['path'] . '_text'; ?>' type="text" name="comment" value="" placeholder="Add a comment..." autocomplete="off" class='text-comment' onkeypress="enter(event.key, '<?php echo $photo['path']; ?>', comment.value)"></textarea>
						<input class='com-button' type='submit' disabled="" ></input>
					</div>
				</form>
			</div>
			<?php endif; ?>
			<div id='<?php echo $photo['path'] . '_modal'; ?>' class="modal" >
				<div id='<?php echo $photo['path'] . '_modal_content'; ?>' class="modal-content">
					<span class="close" onclick="closeModal('<?php echo $photo['path']; ?>')">&times;</span>
					<div><?= $owner; ?></div>
					<img class="img" src="http://localhost:8100/<?php echo $photo['path']; ?>"/>
					<div id='<?php echo $photo['path'] . '_like_modal'; ?>'>
					<?php if(isset($_SESSION['user'])) :?>
						<button class="<?php echo $photo['status']; ?>"  onclick="likeFunct('<?php echo $photo['path']; ?>')"></button>
						<button class='bubble' onclick="modalFunct('<?php echo $photo['path']; ?>'), moveCursor('<?php echo $photo['path']; ?>')"></button>
					<?php endif; ?>
						<div class="div-likes"><?php echo $photo['likes'];?> <?php echo ($photo['likes'] == 1) ? 'like' : 'likes'; ?></div>
					</div>
					<div class="parent-mod">
						<div id='<?php echo $photo['path'] . '_comments_modal'; ?>' class='comment-mod'>
							<?php $i = 0; ?>
							<?php foreach ($photo['comments'] as $comment) : ?>
								<div id='<?php echo 'mod_' .  $comment['id']; ?>'><b>
									<?php echo $comment['user'];?></b> <?php echo $comment['comment'];?><br>
								<?php if (isset($_SESSION['user']) && $_SESSION['user'] == $comment['user']):?>
										<button class='cross-comm-mod' onclick="deleteComment('<?php echo 'mod_' . $comment['id']; ?>')"></button>
								<?php endif; ?>
								</div>
							<?php $i++;  ?>
							<?php endforeach;?>
						</div>
						<?php if(isset($_SESSION['user'])) :?>
						<div id='<?php echo $photo['path'] . '_comment_write_modal'; ?>' class='box-mod'>
							<form method="post" onsubmit="addComment('<?php echo $photo['path']; ?>', comment.value); return false;">
								<div class="form-comment-mod">
									<input type="hidden" name="path" value="<?php echo $photo['path']; ?>" />
									<textarea id='<?php echo $photo['path'] . '_text_modal'; ?>' type="text" name="comment" value="" placeholder="Add a comment..." autocomplete="off" class='text-comment-mod' onkeypress="enter(event.key, '<?php echo $photo['path']; ?>', comment.value)"></textarea>
									<input class='com-button-mod' type='submit' disabled=""></input>
								</div>
							</form>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>	
	<?php endforeach; ?>	

	<?php endif; ?>
	</div>
	<div>
			<?php if ($pagination->countPages > 1): ?>
				<?=$pagination;?>
			<?php endif; ?>
	</div>
</div>


<?php if(isset($_SESSION['user'])): ?>		
	<script type="text/javascript" src="../public/js/camera.js"></script>
<?php endif; ?>
<script type="text/javascript" src="../public/js/save.js"></script>
<script type="text/javascript" src="../public/js/like.js"></script>
<script type="text/javascript" src="../public/js/deletephoto.js"></script>
<script type="text/javascript" src="../public/js/comment.js"></script>
<script type="text/javascript" src="../public/js/modal.js"></script>
