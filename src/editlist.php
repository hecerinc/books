<?php
	require_once 'header.php';
?>
<?php if(isset($_SESSION['listsave_success'])): ?>
	<p style="color: green;">Successfully saved collection.</p>
<?php unset($_SESSION['listsave_success']); endif; ?>
<?php
	$is_edit = true;
	$list_id = isset($_GET['id']) ? $_GET['id'] : NULL;
?>
<a href="lists.php?id=<?= $list_id ?>"><small>view collection</small></a>
<h1>Edit collection</h1>

<?php require_once '_listform.php'; ?>
<?php require_once 'footer.php'; ?>
