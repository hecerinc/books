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
$_DELETEASSOC = 'DELETE FROM books_collections WHERE collection_id = ?';

// Check what the form sent and store them
$has_associated_books = isset($_POST['books']);
if($has_associated_books) {
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

// Check if we're editing instead of inserting a new one.
$is_edit = isset($_POST['edit_id']);
if($is_edit) {
	return is_edit();
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


if($has_associated_books) {
	// Associate books
	$stmt = generate_book_stmt($books, $collection_id);

	if(!$stmt->execute()) {
		$stmt->close();
		http_response_code(500);
		die('Something went wrong when saving the list');
	}
	$stmt->close();
}

session_start();
$_SESSION['listsave_success'] = 'success';
header('Location: addlist.php');



function generate_book_stmt($books, $collection_id) {
	global $db;

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

	return $stmt;
}


function is_edit() {
	global $db;
	global $new_name;
	global $name;
	global $parent_cat;
	global $books;
	global $_DELETEASSOC;

	$parent_cat = $parent_cat === '' ? NULL : $parent_cat;
	$_UPDATE = 'UPDATE collections SET name = ?, parent_id = ?, modified = NOW()';
	if(isset($new_name)) { // do we have an image?
		$_UPDATE .= ', icon = ?';
	}
	$_UPDATE .= ' WHERE id = ?';

	var_dump($_UPDATE);
	$update_stmt = $db->conn->prepare($_UPDATE);
	$edit_id = $_POST['edit_id'];
	if(isset($new_name)) {
		$update_stmt->bind_param('sisi', $name, $parent_cat, $new_name, $edit_id);
	}
	else {
		$update_stmt->bind_param('sii', $name, $parent_cat, $edit_id);
	}
	if(!$update_stmt->execute()) {
		// $update_stmt->close();
		http_response_code(500);
		die("Failed to update the entry. Please try again. <br>". $update_stmt->error);
	}
	$update_stmt->close();

	// Delete and reinsert all associated books
	$delete_stmt = $db->conn->prepare($_DELETEASSOC);
	$delete_stmt->bind_param('i', $edit_id);
	if(!$delete_stmt->execute()) {
		$delete_stmt->close();
		http_response_code(500);
		die('Failed to delete previous associations');
	}
	$delete_stmt->close();

	if(isset($books)) {
		$book_stmt = generate_book_stmt($books, $edit_id);
		if(!$book_stmt->execute()) {
			$book_stmt->close();
			http_response_code(500);
			die('Failed to associate books.');
		}
		$book_stmt->close();
	}

	session_start();
	$_SESSION['listsave_success'] = 'success';
	header('Location: editlist.php?id='.$edit_id);

}
