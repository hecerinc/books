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
	<aside class="main-sidebar">
		<a class="menu-toggle" href="#">
			<img src="img/hamburger.svg" alt="Toggle menu">
		</a>
		<nav class="side-menu">
			<ul>
				<li class="nav-link"><span class="link-text"><a href="#">Books</a></span></li>
				<li class="nav-link"><span class="link-text"><a href="#">Lists</a></span></li>
			</ul>
		</nav>
	</aside>
	<main class="main-content">
		<div class="container-fluid mt-4">
			<h1>Books</h1>
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
					<tr>
						<td>1</td>
						<td>Educated</td>
						<td>Tara Westover</td>
						<td>978-0399590504</td>
						<td>Python_Scripting_For_ArcGIS.pdf</td>
					</tr>
				</tbody>
			</table>
		</div>
	</main>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script>
		$('.menu-toggle').click(function(e) {
			e.preventDefault();
			$('.main-sidebar').toggleClass('collapsed');
			$('.main-content').toggleClass('fw');
		});
	</script>
</body>
</html>
