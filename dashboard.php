<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$name = $_SESSION['username'] ?? 'مستخدم'; 
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
  html, body {
    height: 100%;
    margin: 0;
  }
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }
  main {
    flex: 1;
  }
  footer {
    background: #212529; /* لون داكن مثل Bootstrap bg-dark */
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: auto;
  }
</style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">نظام إدارة الأخبار</a>
        <div class="d-flex">
<span class="navbar-text text-white me-3">مرحبًا، <?= htmlspecialchars($name) ?></span>
  <a href="login.php" class="btn btn-danger">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<!-- شريط الأزرار الأسود -->
<div class="bg-dark py-3">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <div class="col-md-2">
                <a href="add_category.php" class="btn btn-primary w-100">إضافة فئة➕</a>
            </div>
            <div class="col-md-2">
                <a href="view_categories.php" class="btn btn-secondary w-100">عرض الفئات📂</a>
            </div>
            <div class="col-md-2">
                <a href="add_news.php" class="btn btn-success w-100">إضافة خبر📰</a>
            </div>
            <div class="col-md-2">
                <a href="view_news.php" class="btn btn-info w-100">عرض الأخبار📋 </a>
            </div>
            <div class="col-md-2">
                <a href="deleted_news.php" class="btn btn-warning w-100">عرض الأخبار المحذوفة🗑️</a>
            </div>
        </div>
    </div>
</div>

<!-- الفوتر -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <p class="mb-0">جميع الحقوق محفوظة &copy; <?= date('Y') ?> - نظام إدارة الأخبار</p>
    </div>
</footer>

</body>
</html>