<?php
session_start();
$conn = new mysqli("localhost", "root", "", "projectdb");

$id = $_GET['id'];
$conn->query("UPDATE news SET is_deleted = 1 WHERE id = $id");
header("Location: view_news.php");
?>