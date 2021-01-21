<?php
if($_SERVER['REQUEST_METHOD'] != 'POST') {
	http_response_code(405);
	die('Wrong method');
}


$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['isExpanded'])) {
	$isExpanded = $data['isExpanded'];
	session_start();
	$_SESSION['is_nav_expanded'] = $isExpanded === true ? true : false;
	header('Content-Type: application/json');
	echo json_encode(['msg' => 'success']);
}

