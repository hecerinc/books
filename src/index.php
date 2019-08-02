<?php
	require_once './conex.php';

	$conn = DataConnection::getDBConnection();
	if(!$conn) {
		die('Failed to connect to DB.');
	}

	$result = $conn->conn->query('SELECT * FROM books ORDER by name');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Books Database</title>
	<link rel="stylesheet" href="css/bootstrap-reboot.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<?php require_once 'nav.php' ?>
	<main class="main-content fw">
		<div class="container-fluid mt-5">
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
								<th style="width: 40%;">Name</th>
								<th>Author(s)</th>
								<th>ISBN</th>
								<th>Filename</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($books as $key => $book): ?>
								<tr>
									<td><?= $key+1  ?></td>
									<td><?= $book['name'] ?></td>
									<td><?= $book['authors'] ?></td>
									<td><?= $book['isbn'] ?></td>
									<td><a href="#"><?= $book['filename'] ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</section>
				<section class="GridView">
					<div class="row">
						<?php foreach($books as $book): ?>
							<?php $url = 'file:///'.$book['path'] . '/' . $book['filename']; ?>
							<div class="test">
								<article class="Book">
									<a class="coverimg" href="<?= $url ?>" target="_blank">
										<img src="covers/<?= $book['coverimg'] ?>" alt="<?= $book['name'] ?>">
									</a>
									<div class="info">
										<p><a href="<?= $url ?>" target="_blank" class="name"><?= $book['name'] ?></a></p>
										<p><a href="<?= $url ?>" target="_blank" class="authors"><?= $book['authors'] ?></a></p>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</main>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script>
		$('.menu-toggle').click(function(e) {
			e.preventDefault();
			$('.main-sidebar').toggleClass('collapsed');
			setTimeout(function() {
				$('.main-sidebar .side-menu').toggleClass('collapsed');
			}, 300);
			$('.main-content').toggleClass('fw');
		});
	</script>
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
</body>
</html>
<?php $result->close(); ?>
