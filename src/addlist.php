<?php
	require_once 'header.php';
?>
<?php if(isset($_SESSION['listsave_success'])): ?>
	<p style="color: green;">Successfully saved new collection.</p>
<?php unset($_SESSION['listsave_success']); endif; ?>


<h1>New collection</h1>

<?php require_once '_listform.php'; ?>
<?php require_once 'footer.php'; ?>
