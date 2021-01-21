<?php
	session_start();
	$is_expanded = false;
	if(isset($_SESSION['is_nav_expanded'])) {
		$is_expanded = $_SESSION['is_nav_expanded'];
	}
	require_once './utils/ViewBlock.php';
	$vblock = new ViewBlock();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Books Database</title>
	<link rel="stylesheet" href="css/bootstrap-reboot.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="preload" as="image" href="img/book2.svg">
	<link rel="preload" as="image" href="img/lists.svg">
</head>
<body>
	<?php require_once 'nav.php' ?>
	<main class="main-content <?= $is_expanded ? '' : 'fw' ?>">
		<div class="container-fluid mt-5 <?= isset($bodyClass) ? $bodyClass : '' ?>">
