<?php

require_once 'conex.php';

$db = DataConnection::getDBConnection();
if(!$db) {
	die('Failed to connect to DB.');
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
	http_response_code(405);
	die('Wrong method');
}

$title = $_POST['title'];
$authors = $_POST['authors'];
$isbn = $_POST['isbn'];
$backup = $_POST['backup'];
$path = $_POST['path'];
$amazon = $_POST['amazon'];
$lists = $_POST['lists'];


if(!isset($_FILES['cover'])) {
	http_response_code(400);
	die('No cover image uploaded');
}

$image = $_FILES['cover'];

// Save image first
$index = strrpos($image['name'], ".");
$file_ext = substr($image['name'], $index);
$new_name = md5(date('YmdHis').$image['name'].$isbn).$file_ext;

$res = move_uploaded_file($image['tmp_name'], "./covers/" . $new_name);
if(!$res) {
	http_response_code(500);
	die('Could not move image');
}

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


$stmt = $db->conn->prepare('INSERT INTO books(name, authors, isbn, where_backup, amazon_link, coverimg, path, filename) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');

$stmt->bind_param('ssssssss', $title, $authors, $isbn, $backup, $amazon, $new_name, $dir, $filename);

if($stmt->execute()) {
	$stmt_id = $stmt->insert_id;
	$stmt->close();
	session_start();
	$_SESSION['booksave_success'] = 'success';
	header('Location: addnew.php');
	exit();
}
$stmt->close();
echo "Uh-oh. Something went wrong. Failed to save new book";




