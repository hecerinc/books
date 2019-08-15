<?php
	require_once './conex.php';
	require_once 'header.php';

	$conn = DataConnection::getDBConnection();
	if(!$conn) {
		die('Failed to connect to DB.');
	}

	$result = $conn->conn->query('SELECT * FROM books ORDER by name');
?>
<div class="row">
	<div class="col-1">
		<h1>Books</h1>
	</div>
	<div class="col-9 pl-5">
		<input class="search-input pl-2" type="search" name="q" id="search" placeholder="Search&hellip;">
	</div>
	<div class="col-2">
		<a href="addnew.php" class="add-new-btn">+ Add new</a>
	</div>
</div>
<?php if($result): $books = $result->fetch_all(MYSQLI_ASSOC); endif; ?>

<div class="d-flex justify-content-between control-row">
	<p class="book-count"><?= isset($books) ? count($books) : 'NA' ?> items</p>
	<ul class="inline view-btns">
		<li><a data-view="list" href="#">List view</a></li>
		<li><a data-view="grid" href="#">Grid view</a></li>
	</ul>
</div>

<?php if($result): ?>
	<section class="ListView">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Author(s)</th>
					<th>ISBN</th>
					<th>File name</th>
					<th>Size</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($books as $key => $book): ?>
					<?php $url = 'file:///'.$book['path'] . '/' . $book['filename']; ?>
					<tr>
						<td style="min-width: 40px"><?= $key+1  ?></td>
						<td><?= $book['name'] ?></td>
						<td><?= $book['authors'] ?></td>
						<td><?= $book['isbn'] ?></td>
						<td style="max-width: 500px; min-width: 500px;"><a target="_blank" href="<?= $url ?>"><?= $book['filename'] ?></a></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<section class="GridView">
		<div class="row">
			<?php require_once '_bookgrid.php' ?>
		</div>
	</section>
<?php endif; ?>
<?php $result->close(); ?>

<?php $vblock->start('scripts'); ?>
<script>
	$('.view-btns a').click(function(e) {
		e.preventDefault();
		const view = $(this).data('view');
		if(view == 'grid') {
			$('.ListView').hide();
			$('.GridView').show();
		}
		else {
			$('.ListView').show();
			$('.GridView').hide();
		}
	});
</script>
<?php $vblock->end(); ?>

<?php require_once 'footer.php' ?>
