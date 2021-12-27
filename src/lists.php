<?php

$bodyClass = "ListDB";

require_once 'conex.php';
require_once 'header.php';

$db = DataConnection::getDBConnection();
if(!$db) {
	die('Failed to connect to DB.');
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
$is_main = $id == null;

$_GETALL = 'SELECT id, name, icon FROM collections WHERE parent_id IS NULL';
$_GETONECAT = 'SELECT id, name, icon FROM collections WHERE parent_id = ?';
$_GETCAT = 'SELECT id, name, icon, parent_id FROM collections WHERE id = ?';
$_GETPARENTCAT = 'SELECT id, name FROM collections WHERE id = ?';
$_GETDESCENDANTS = 'SELECT id FROM collections c JOIN collection_closure closure ON c.id = closure.descendant WHERE closure.ancestor = ?';
$_GETBOOKS = 'SELECT * FROM books_collections JOIN books ON book_id = books.id WHERE collection_id IN';

$query = $is_main ? $_GETALL : $_GETONECAT;

$stmt = $db->conn->prepare($query);

if(!$is_main) {
	$stmt->bind_param('i', $id);

	// Current category
	$bt_stmt =  $db->conn->prepare($_GETCAT);
	$bt_stmt->bind_param('i', $id);
	$bt_stmt->execute();
	$cat = $bt_stmt->get_result();
	$cat = $cat->fetch_all(MYSQLI_ASSOC);
	$cat = $cat[0];
	$bt_stmt->close();

	// Parent category
	if($cat['parent_id'] == null) {
		$parent_cat = ['name' => 'Collections', 'id' => null];
	}
	else {
		$ct_stmt = $db->conn->prepare($_GETPARENTCAT);
		$ct_stmt->bind_param('i', $cat['parent_id']);
		$ct_stmt->execute();
		$ct_result = $ct_stmt->get_result();
		$parent_cat = $ct_result->fetch_assoc();
		$ct_stmt->close();
	}


	// Get descendant categories (to retrieve all descendant books)
	$dd_stmt = $db->conn->prepare($_GETDESCENDANTS);
	$dd_stmt->bind_param('i', $cat['id']);
	if(!$dd_stmt->execute()) {
		$dd_stmt->close();
		http_response_code(500);
		die('Failed to retrieve descendants');
	}

	$result = $dd_stmt->get_result();
	$dd_stmt->close();
	$result = $result->fetch_all(MYSQLI_ASSOC);
	$descendant_ids = array_column($result, 'id');
	$clause = implode(',', array_fill(0, count($descendant_ids), '?'));

	$_GETBOOKS .= " ($clause)";


	// Get associated books
	$book_stmt = $db->conn->prepare($_GETBOOKS);
	$book_stmt->bind_param(str_repeat('i', count($descendant_ids)), ...$descendant_ids);
	if(!$book_stmt->execute()) {
		http_response_code(500);
		die('Failed to retrieve books in this category');
	}
	$book_result = $book_stmt->get_result();
	$books = $book_result->fetch_all(MYSQLI_ASSOC);
	$book_stmt->close();

}

$stmt->execute();
$collquery = $stmt->get_result();
if($collquery) {
	$lists = $collquery->fetch_all(MYSQLI_ASSOC);
}

?>


<div class="row mb-4">
	<div class="col">
		<h1 style="display: inline">
			<?php if(!$is_main): ?>
				<?php if($cat['icon'] != null): ?>
					<img class="h1-ico" src="covers/ico/<?= $cat['icon'] ?>">
				<?php endif; ?>
				<?= $cat['name'] ?>
			<?php else: ?>
				Collections
			<?php endif; ?>
		</h1>
		<?php if(!$is_main): ?>
			<a href="editlist.php?id=<?= $cat['id'] ?>" class="ml-3" style="font-size: 12px; position: relative; top: -5px">Edit</a>
		<?php endif; ?>
	</div>
	<div class="col-3 d-flex justify-content-end">
		<a href="addlist.php" class="add-new-btn pr-5" style="width: 100%; text-align: right;">+ Add new collection</a>
	</div>
</div>
<?php if(!$is_main): ?>
	<a href="lists.php?id=<?= $parent_cat['id'] ?>">&laquo; <?= $parent_cat['name'] ?></a>
	<section class="tabContainer mt-5">
		<ul class="inline">
			<li><a class="tab-toggle <?= empty($lists) ? '': 'active' ?>" href="#">Collections (<?= count($lists) ?>)</a></li>
			<li><a class="tab-toggle <?= empty($lists) ? 'active': '' ?>" href="#">Books (<?= count($books) ?>)</a></li>
		</ul>
	</section>
<?php endif; ?>
<div class="tabContent mt-5">
	<?php if($collquery): ?>
		<section class="ListGrid <?= empty($lists) ? 'hidden': '' ?>">
			<div class="row">
				<?php foreach($lists as $list): ?>
					<div class="col-2">
						<a href="lists.php?id=<?= $list['id'] ?>">
							<article class="List d-flex align-items-center">
								<div class="middle">
									<span class="ico mb-2" data-bg="covers/ico/<?= $list['icon'] ?>"></span>
									<span class="name"><?= $list['name'] ?></span>
								</div>
							</article>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
	<section class="BookGrid GridView <?= empty($lists) ? '' : 'hidden' ?>">
		<div class="row">
			<?php if(empty($books)): ?>
				<div class="col">
					<p style="text-align: center; font-size: 14px;">No books yet. <a href="editlist.php?id=<?= $id ?>">Add books to this list.</a></p>
				</div>
			<?php else: ?>
				<?php require_once '_bookgrid.php' ?>
			<?php endif; ?>
		</div>
	</section>
</div>

<?php $vblock->start('scripts'); ?>
<script>
	$(function() {
		$('.List .ico').each(function() {
			const bg = $(this).data('bg');
			$(this).css({'background-image': `url(${bg})`});
		});
		$('.tab-toggle').click(function(e) {
			e.preventDefault();
			if($(this).hasClass('active'))
				return;
			$('.tab-toggle').toggleClass('active');
			$('.tabContent > section').toggleClass('hidden');
		});
	});
</script>
<?php $vblock->end(); ?>
<?php $collquery->close(); ?>
<?php $stmt->close(); ?>
<?php require_once 'footer.php' ?>
