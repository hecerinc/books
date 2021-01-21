<?php
$path = $_SERVER['REQUEST_URI'];
if($path == "/index.php" or $path == "/") {
	$path = 'home';
}
else if($path == "/lists.php") {
	$path = "lists";
}
?>
<aside class="main-sidebar <?= $is_expanded ? '' : 'collapsed' ?>" >
	<a class="menu-toggle" href="#">
		<img src="img/hamburger.svg" alt="Toggle menu">
	</a>
	<nav class="side-menu <?= $is_expanded ? '' : 'collapsed' ?>">
		<ul>
			<li class="nav-link <?= $path == "home" ? 'active' : '' ?>">
				<a class="link-text" href="index.php"><span class="ico book"></span>Books</a>
				<ul class="">
					<li class="nav-link"><a href="addnew.php">+ Add new</a></li>
				</ul>
			</li>
			<li class="nav-link <?= $path == "lists" ? 'active' : '' ?>">
				<a class="link-text" href="lists.php"><span class="ico lists"></span>Collections</a>
			</li>
		</ul>
	</nav>
</aside>
