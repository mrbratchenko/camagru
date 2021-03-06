
<div id='frame'>
	<?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>
	<div id='frame-flex'>
	<?php if (!empty($photos)) : ?>
	<?php foreach ($photos as $photo) : ?>
		<?php $owner = $photo['user_id']; ?>
		<?php if(isset($_SESSION['user']) && !strcmp($_SESSION['user'], $owner)) :?>
		<div id='<?php echo $photo['path']; ?>' class='data' style='height:550px'>
			<div><?php echo $owner; ?></div>
			<img height='300px' width='400px' src="http://localhost:8100/<?php echo $photo['path']; ?>"/>
			<div id='<?php echo $photo['path'] . '_like'; ?>'>
				<button class="<?php echo $photo['status']; ?>"  onclick="likeFunct('<?php echo $photo['path']; ?>')"></button>
				<button class='bubble' onclick="modalFunct('<?php echo $photo['path']; ?>'), moveCursor('<?php echo $photo['path']; ?>')"></button>
			<?php if(!strcmp($_SESSION['user'], $owner)) :?>
				<button class='cross' onclick="deletePhoto('<?php echo $page; ?>','<?php echo $photo['path']; ?>')"></button>
			<?php endif; ?>
				<div><?php echo $photo['likes'];?> <?php echo ($photo['likes'] == 1) ? 'like' : 'likes'; ?></div>
			</div>	

			<button class='view-all-comm' id='<?php echo $photo['path'] . '_modal_btn'; ?>' onclick="modalFunct('<?php echo $photo['path']; ?>')">View all comments</button>
			
			<div id='<?php echo $photo['path'] . '_comments'; ?>' class='comment'>
				<?php $last = count($photo['comments']) - 1 ; ?>
				<?php $i = 0; ?>
				<?php foreach ($photo['comments'] as $comment) : ?>
				<div id='<?php echo $comment['id']; ?>' style='display: none;'>
					<b><?php echo $comment['user'];?></b>
					<?php if (strlen($comment['comment']) > 25):?>
						<?php echo substr($comment['comment'], 0, 25) . '...';?>
					<?php else: ?>
						<?php echo substr($comment['comment'], 0, 25);?>
					<?php endif; ?>
					<br><?php if ($owner == $comment['user']):?>
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
			
			<div id='<?php echo $photo['path'] . '_comment_write'; ?>' class='box'>
				<form method="post" onsubmit="addComment('<?php echo $photo['path']; ?>', comment.value); return false;">
					<div class="form-comment">
						<input type="hidden" name="path" value="<?php echo $photo['path']; ?>" />
						<textarea id='<?php echo $photo['path'] . '_text'; ?>' type="text" name="comment" value="" placeholder="Add a comment..." autocomplete="off" class='text-comment' onkeypress="enter(event.key, '<?php echo $photo['path']; ?>', comment.value)"></textarea>
						<input class='com-button' type='submit' disabled="" ></input>
					</div>
				</form>
			</div>

			<div id='<?php echo $photo['path'] . '_modal'; ?>' class="modal" >
				<div id='<?php echo $photo['path'] . '_modal_content'; ?>' class="modal-content">
					<span class="close" onclick="closeModal('<?php echo $photo['path']; ?>')">&times;</span>
					<div><?= $owner; ?></div>
					<img src="http://localhost:8100/<?php echo $photo['path']; ?>"/>
					<div id='<?php echo $photo['path'] . '_like_modal'; ?>'>
						<button class="<?php echo $photo['status']; ?>"  onclick="likeFunct('<?php echo $photo['path']; ?>')"></button>
						<button class='bubble' onclick="modalFunct('<?php echo $photo['path']; ?>'), moveCursor('<?php echo $photo['path']; ?>')"></button>
					<?php if(!strcmp($_SESSION['user'], $owner)) :?>
						<button class='cross' onclick="deletePhoto('<?php echo $page; ?>','<?php echo $photo['path']; ?>')"></button>
					<?php endif; ?>
						<div><?php echo $photo['likes'];?> <?php echo ($photo['likes'] == 1) ? 'like' : 'likes'; ?></div>
					</div>
					<div class="parent-mod">
						<div id='<?php echo $photo['path'] . '_comments_modal'; ?>' class='comment-mod'>
							<?php $i = 0; ?><?php foreach ($photo['comments'] as $comment) : ?><div id='<?php echo 'mod_' .  $comment['id']; ?>'><b><?php echo $comment['user'];?></b> <?php echo $comment['comment'];?><br><?php if ($owner == $comment['user']):?><button class='cross-comm' onclick="deleteComment('<?php echo 'mod_' . $comment['id']; ?>')"></button><?php endif; ?></div>
							<?php $i++;  ?>
							<?php endforeach;?>
						</div>
						<div id='<?php echo $photo['path'] . '_comment_write_modal'; ?>' class='box-mod'>
							<form method="post" onsubmit="addComment('<?php echo $photo['path']; ?>', comment.value); return false;">
								<div class="form-comment-mod">
									<input type="hidden" name="path" value="<?php echo $photo['path']; ?>" />
									<textarea id='<?php echo $photo['path'] . '_text_modal'; ?>' type="text" name="comment" value="" placeholder="Add a comment..." autocomplete="off" class='text-comment-mod' onkeypress="enter(event.key, '<?php echo $photo['path']; ?>', comment.value)"></textarea>
									<input class='com-button-mod' type='submit' disabled=""></input>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
	<div>
			<?php if ($pagination->countPages > 1): ?>
				<?=$pagination;?>
			<?php endif; ?>
	</div>
	
</div>

<script type="text/javascript" src="../public/js/save.js"></script>
<script type="text/javascript" src="../public/js/like.js"></script>
<script type="text/javascript" src="../public/js/deletephoto.js"></script>
<script type="text/javascript" src="../public/js/comment.js"></script>
<script type="text/javascript" src="../public/js/modal.js"></script>
