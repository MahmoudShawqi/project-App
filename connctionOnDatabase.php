<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'projectdb';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die( "connection falied:". $conn->connect_error);
}

?>