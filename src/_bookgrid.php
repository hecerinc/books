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
			<a href="editbook.php?id=<?= $book['id'] ?>" class="editbtn"></a>
		</article>
	</div>
<?php endforeach; ?>
