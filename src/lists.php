<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Books Database - Lists</title>
	<link rel="stylesheet" href="css/bootstrap-reboot.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<?php require_once 'nav.php' ?>
	<div class="main-content fw">
		<div class="container-fluid mt-5 ListDB">
			<h1>Lists</h1>
			<section class="ListGrid mt-5">
				<div class="row">
					<div class="col-2">
						<a href="#">
							<article class="List d-flex align-items-center">
								<div class="middle">
									<span class="ico mb-2" data-bg="img/electricity.svg"></span>
									<span class="name">Electrical Engineering</span>
								</div>
							</article>
						</a>
					</div>
					<div class="col-2">
						<a href="#">
							<article class="List d-flex align-items-center">
								<div class="middle">
									<span class="ico mb-2" data-bg="img/math.svg"></span>
									<span class="name">Math</span>
								</div>
							</article>
						</a>
					</div>
				</div>
			</section>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script>
		$(function() {
			$('.List .ico').each(function() {
				const bg = $(this).data('bg');
				$(this).css({'background-image': `url(${bg})`});
			});
		});
	</script>
	<script>
		$('.menu-toggle').click(function(e) {
			e.preventDefault();
			$('.main-sidebar').toggleClass('collapsed');
			$('.main-content').toggleClass('fw');
		});
	</script>
</body>
</html>
