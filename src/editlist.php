<?php
	session_start();
	require_once 'header.php';
?>
<?php if(isset($_SESSION['listsave_success'])): ?>
	<p style="color: green;">Successfully saved collection.</p>
<?php unset($_SESSION['listsave_success']); endif; ?>

<h1>Edit collection</h1>

<?php
	$is_edit = true;
	$list_id = $_GET['id'];
?>
<?php require_once '_listform.php'; ?>
<?php require_once 'footer.php'; ?>
