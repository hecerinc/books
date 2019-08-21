<?php

$_INSERTBOOK_WIMG = 'INSERT INTO books(name, authors, isbn, where_backup, amazon_link, path, filename, coverimg) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
$_BASEUPDATE = 'UPDATE books SET name = ?, authors = ?, isbn = ?, where_backup = ?, amazon_link = ?, path = ?, filename = ?';

require_once 'conex.php';

$db = DataConnection::getDBConnection();

if($_SERVER['REQUEST_METHOD'] != 'POST') {
	http_response_code(405);
	die('Wrong method');
}

$title = $_POST['title'];
$authors = $_POST['authors'];
$isbn = isset($_POST['isbn']) ? $_POST['isbn'] : NULL;
$backup = $_POST['backup'];
$path = $_POST['path'];
$amazon = $_POST['amazon'];
$lists = isset($_POST['lists']) ? $_POST['lists'] : NULL;
$is_edit = isset($_POST['edit_id']);

$has_img = is_uploaded_file($_FILES['cover']['tmp_name']);

// Prepare file path
if(substr($path, 0, 8) == 'file:///') {
	$path = substr($path, 8);
}
// Remove trailing forward slashes (/)
if($path[strlen($path)-1] == '/') {
	$path = substr($path, 0, strlen($path)-1);
}
$pivot = strrpos($path, '/');
$filename = substr($path, $pivot+1);
$dir = substr($path, 0, $pivot);

$args = [$title, $authors, $isbn, $backup, $amazon, $dir, $filename];

if($has_img) {
	$image = $_FILES['cover'];
	$new_name = save_image($image);
	$args[] = $new_name;
}


if($is_edit) {
	$edit_id = $_POST['edit_id'];
	$redirect_url = 'editbook.php?id='.$edit_id;
	$query = $_BASEUPDATE;
	$query_str = 'sssssss';
	if($has_img) {
		$query .= ', coverimg = ?';
		$query_str .= 's'; // coverimg
	}
	$query_str .= 'i'; // WHERE id
	$query .= ' WHERE id = ?';

	$args[] = $edit_id;
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param($query_str, ...$args);
}
else {
	$redirect_url = 'addnew.php';
	$stmt = $db->conn->prepare($_INSERTBOOK_WIMG);
	$stmt->bind_param('ssssssss', ...$args);
}


if($stmt->execute()) {
	$stmt_id = $stmt->insert_id;
	$stmt->close();
	session_start();
	$_SESSION['booksave_success'] = 'success';
	header("Location: $redirect_url");
	exit();
}
$stmt->close();
echo "Uh-oh. Something went wrong. Failed to save new book";


/*
 * save_image
 *
 * @desc Saves the book cover image
 * @param $image: $_FILE entry
 * @returns coverimg:str - the final file path for the image to be saved to the DB
 */
function save_image($image) {
	// Save image first
	$index = strrpos($image['name'], ".");
	$file_ext = substr($image['name'], $index);
	$new_name = md5(date('YmdHis').$image['name'].$isbn).$file_ext;

	$res = move_uploaded_file($image['tmp_name'], "./covers/" . $new_name);
	if(!$res) {
		http_response_code(500);
		die('Could not move image');
	}

	return $new_name;
}
