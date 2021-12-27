<?php
	require_once 'header.php';
	require_once './conex.php';

	$is_edit = true;
	$db = DataConnection::getDBConnection();

	$_GETBOOK = 'SELECT * FROM books WHERE id = ?';
	$stmt = $db->conn->prepare($_GETBOOK);
	$book_id = isset($_GET['id']) ? $_GET['id'] : NULL;
	if($book_id === NULL) {
		die('No ID found');
	}
	$stmt->bind_param('i', $book_id);
	if(!$stmt->execute()) {
		die('Something went wrong');
	}
	$result = $stmt->get_result();
	$book = $result->fetch_all(MYSQLI_ASSOC);
	$book = $book[0];

?>
<?php if(isset($_SESSION['booksave_success'])): ?>
	<p style="color: green;">Successfully saved book.</p>
<?php unset($_SESSION['booksave_success']); endif; ?>

<a href="index.php"><small>view book</small></a>
<h1>Edit book</h1>

<?php require_once '_bookform.php'; ?>
<?php require_once 'footer.php'; ?>
