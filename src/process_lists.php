<?php

// TODO: Move this one depth out?
require_once 'conex.php';

$db = DataConnection::getDBConnection();

// If method not POST, die
if($_SERVER['REQUEST_METHOD'] != 'POST') {
	http_response_code(405);
	die('Wrong method');
}

// SQL stmts
$_INSERTLIST = "INSERT INTO collections(name, icon) VALUES(?, ?)";
$_INSERT_WITH_PARENT = "INSERT INTO collections(name, icon, parent_id) VALUES(?, ?, ?)";


// Check what the form sent and store them
if(isset($_POST['books'])) {
	$books = $_POST['books'];
}
$name = $_POST['name'];
$parent_cat = $_POST['parent'];
$icon = $_FILES['icon'];


// Try to upload the file if exists
if(is_uploaded_file($_FILES['icon']['tmp_name'])) {
	$new_name = md5(date('YmdHis').$icon['name']).'.svg';
	$res = move_uploaded_file($icon['tmp_name'], "./covers/ico/" . $new_name);

	if(!$res) {
		http_response_code(500);
		die('Could not move image');
	}
}

// Decide which insert based on whether this is a child collection or not
if(!isset($parent_cat) || empty($parent_cat)) {
	$stmt = $db->conn->prepare($_INSERTLIST);
	$stmt->bind_param('ss', $name, $new_name);
}
else {
	$stmt = $db->conn->prepare($_INSERT_WITH_PARENT);
	$stmt->bind_param('ssi', $name, $new_name, $parent_cat);
}

// Execute stmt
if(!$stmt->execute()) {
	$error = $stmt->error;
	$stmt->close();
	http_response_code(500);
	die('Something went wrong when saving the list <br />'.$error);
}
$collection_id = $stmt->insert_id;
$stmt->close();



// Associate books (if any)
$_MULTIINSERT = 'INSERT INTO books_collections(book_id, collection_id) VALUES';

// --- First we have to build the query so that we can execute as prepared statements
// --- into a single INSERT SQL statement (dramatically faster)

$ids = [];
$len = count($books);
foreach ($books as $key => $book) {
	$_MULTIINSERT .= ' (?, ?)';
	if($key != $len-1) {
		$_MULTIINSERT .= ',';
	}
	$ids[] = $book;
	$ids[] = $collection_id;
}

// Prepare & bind
$stmt = $db->conn->prepare($_MULTIINSERT);
if($stmt === false) {
	die($db->conn->error);
}

$type_str = str_repeat('ii', count($books));
$stmt->bind_param($type_str, ...$ids);

if(!$stmt->execute()) {
	$stmt->close();
	http_response_code(500);
	die('Something went wrong when saving the list');
}

session_start();
$_SESSION['listsave_success'] = 'success';
header('Location: addlist.php');

