<?php

require_once './conex.php';

$db = DataConnection::getDBConnection();

$_ASSOCBOOKS = 'SELECT book_id FROM books_collections WHERE collection_id = ?';
$book_query = $db->conn->query('SELECT id,name from books');
$coll_query = $db->conn->query('SELECT id, name, parent_id, icon from collections');

if($coll_query) {
	$collections = $coll_query->fetch_all(MYSQLI_ASSOC);
}
$is_edit = isset($is_edit);
if($is_edit) {
	// $list_id is provided when editing by the script that requires this one i.e. editlist.php
	$key = array_search($list_id, array_column($collections, 'id'));
	if($key === false) {
		echo "<p class='mt-3'>Collection not found. Please try again.</p>";
		die();
	}
	$edit_collection = $collections[$key];

	// Get associated books
	$associated_books_stmt = $db->conn->prepare($_ASSOCBOOKS);
	$associated_books_stmt->bind_param('i', $list_id);
	if(!$associated_books_stmt->execute()) {
		http_response_code(500);
		$associated_books_stmt->close();
		die('Failed to retreive associated books');
	}
	$ab_result = $associated_books_stmt->get_result();
	$associated_books = array_column($ab_result->fetch_all(MYSQLI_ASSOC), 'book_id');
	$associated_books_stmt->close();
}
?>

<form action="process_lists.php" class="newlist-form form-group mt-5" method="post" enctype="multipart/form-data">
	<?php if($is_edit): ?>
		<input type="hidden" name="edit_id" value="<?= $list_id ?>">
	<?php endif; ?>
	<div>
		<input autofocus type="text" name="name" id="name" placeholder="Name of collection" value="<?= $is_edit ? $edit_collection['name'] : '' ?>">
	</div>
	<div>
		<select name="parent" id="parent">
			<option value="">-- Parent collection --</option>
			<?php if($coll_query): ?>
				<?php foreach($collections as $collection): ?>
					<?php if($is_edit && $collection['id'] == $edit_collection['id']) {continue;} ?>
					<?php if($is_edit && $collection['parent_id'] == $edit_collection['id']) {continue;} ?>
					<option
						value="<?= $collection['id'] ?>"
						<?= $is_edit && $edit_collection['parent_id'] == $collection['id'] ?  'selected' : '' ?>
					>
						<?= $collection['name'] ?>
					</option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</div>
	<div>
		<select name="books[]" id="bookassoc" multiple class="selectize">
			<?php if($book_query): $books = $book_query->fetch_all(MYSQLI_ASSOC); ?>
				<?php foreach($books as $book): ?>
					<option <?= $is_edit && in_array($book['id'], $associated_books) ? 'selected' : ''  ?> value="<?= $book['id'] ?>"><?= $book['name'] ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</div>
	<div>
		<label for="icon"><small><strong>Icon for this collection</strong></small></label>
		<div class="row no-gutters <?= $is_edit ? 'my-3' : '' ?>">
			<?php if($is_edit && $edit_collection['icon'] != null): ?>
				<div class="col-2">
					<img class="collico--preview" src="covers/ico/<?= $edit_collection['icon'] ?>" alt="Collection icon">
				</div>
			<?php endif; ?>
			<div class="col">
				<input type="file" name="icon" id="icon" accept=".svg">
			</div>
		</div>
	</div>
	<div>
		<input type="submit" value="<?= $is_edit ? 'Save' : 'Add new' ?> collection" class="submit-btn">
	</div>
</form>


<?php $vblock->start('scripts'); ?>

<link rel="stylesheet" href="css/selectize.min.css">
<link rel="stylesheet" href="css/selectize-default.min.css">
<script src="js/selectize.min.js"></script>
<script>
	$(".selectize").selectize({
		placeholder: 'Associate books',
		plugins: ['remove_button']
	});
</script>

<?php $vblock->end(); ?>
<?php $coll_query->close(); ?>
<?php $book_query->close(); ?>
