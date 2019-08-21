<?php
	session_start();
	require_once 'header.php'
?>

<?php if(isset($_SESSION['booksave_success'])): ?>
	<p style="color: green;">Successfully saved new book.</p>
<?php unset($_SESSION['booksave_success']); endif; ?>

<h1>New book</h1>

<?php require_once '_bookform.php' ?>
<?php require_once 'footer.php' ?>
