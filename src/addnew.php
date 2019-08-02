<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Books Database</title>
	<link rel="stylesheet" href="css/bootstrap-reboot.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/style.css">

	<link rel="stylesheet" href="css/selectize.min.css">
	<link rel="stylesheet" href="css/selectize-default.min.css">
</head>
<body>
	<?php require_once 'nav.php' ?>
	<div class="main-content fw">
		<div class="container-fluid mt-5">
			<?php if(isset($_SESSION['booksave_success'])): ?>
				<p style="color: green;">Successfully saved new book.</p>
			<?php unset($_SESSION['booksave_success']); endif; ?>
			<h1>New book</h1>
			<form action="process.php" class="new-book-form mt-5" method="post" enctype="multipart/form-data">
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
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="js/selectize.min.js"></script>
	<script>
		$('.menu-toggle').click(function(e) {
			e.preventDefault();
			$('.main-sidebar').toggleClass('collapsed');
			$('.main-content').toggleClass('fw');
		});

		$("#lists").selectize({placeholder: 'Associate lists'});
	</script>
</body>
</html>
