<?php

if(!empty($_GET['q'])):
	$is_search_page = true;
	$search_query = trim($_GET['q']);
	$query = "SELECT * from books WHERE name LIKE ? OR authors LIKE ? OR isbn LIKE ? ORDER by name";
	$stmt = $conn->conn->stmt_init();
	$stmt->prepare($query);
	$param = "%{$search_query}%";
	$stmt->bind_param('sss', $param, $param, $param);

	

endif;
