<div class="row">
	<div class="col-6">
		<form action="process.php" class="new-book-form mt-5 form-group" method="post" enctype="multipart/form-data">
			<?php if($is_edit): ?>
				<input type="hidden" name="edit_id" value="<?= $book_id ?>">
			<?php endif; ?>
			<input autofocus placeholder="Title" name="title" id="title" type="text" value="<?= isset($is_edit) ? $book['name']: '' ?>" />
			<input placeholder="Author(s)" name="authors" id="authors" type="text" value="<?= isset($is_edit) ? $book['authors'] : '' ?>" />
			<input placeholder="ISBN" name="isbn" id="isbn" type="text" value="<?= isset($is_edit) ? $book['isbn'] : '' ?>" />
			<input placeholder="Where is it backuped up?" name="backup" id="backup" type="text" value="<?= isset($is_edit) ? $book['where_backup'] : '' ?>" />
			<input placeholder="Local path" name="path" id="path" type="text" value="<?= isset($is_edit) ? $book['path'] . '/' . $book['filename'] : ''  ?>" />
			<input placeholder="Amazon link" name="amazon" id="amazon" type="text" value="<?= isset($is_edit) ? $book['amazon_link'] : '' ?>" />
			<select name="lists[]" id="lists" multiple>
				<option value="1">List 1</option>
				<option value="2">List 2</option>
			</select>
			<input placeholder="Cover" name="cover" id="cover" type="file" />
			<input type="submit" value="Submit" class="submit-btn">
		</form>
	</div>
	<?php if(isset($is_edit)): ?>
		<div class="col-6 pl-5 mt-5">
			<img class="coverimg--bookform" src="covers/<?= $book['coverimg'] ?>" alt="<?= $book['name'] ?> cover">
		</div>
	<?php endif; ?>
</div>
<?php $vblock->start('scripts'); ?>
	<link rel="stylesheet" href="css/selectize.min.css">
	<link rel="stylesheet" href="css/selectize-default.min.css">
	<script src="js/selectize.min.js"></script>
	<script>
		$("#lists").selectize({placeholder: 'Associate lists'});
	</script>
<?php $vblock->end(); ?>
