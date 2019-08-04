<?php
	session_start();
	require_once 'header.php'
?>

<?php if(isset($_SESSION['booksave_success'])): ?>
	<p style="color: green;">Successfully saved new book.</p>
<?php unset($_SESSION['booksave_success']); endif; ?>

<h1>New book</h1>

<form action="process.php" class="new-book-form mt-5 form-group" method="post" enctype="multipart/form-data">
	<input autofocus placeholder="Title" name="title" id="title" type="text" />
	<input placeholder="Author(s)" name="authors" id="authors" type="text" />
	<input placeholder="ISBN" name="isbn" id="isbn" type="text" />
	<input placeholder="Where is it backuped up?" name="backup" id="backup" type="text" />
	<input placeholder="Local path" name="path" id="path" type="text" />
	<input placeholder="Amazon link" name="amazon" id="amazon" type="text" />
	<select name="lists[]" id="lists" multiple>
		<option value="1">List 1</option>
		<option value="2">List 2</option>
	</select>
	<input placeholder="Cover" name="cover" id="cover" type="file" />
	<input type="submit" value="Submit" class="submit-btn">
</form>

<?php $vblock->start('scripts'); ?>
	<link rel="stylesheet" href="css/selectize.min.css">
	<link rel="stylesheet" href="css/selectize-default.min.css">
	<script src="js/selectize.min.js"></script>
	<script>
		$("#lists").selectize({placeholder: 'Associate lists'});
	</script>
<?php $vblock->end(); ?>
<?php require_once 'footer.php' ?>
