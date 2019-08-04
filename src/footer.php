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
<?= $vblock->get('scripts'); ?>
</body>
</html>
