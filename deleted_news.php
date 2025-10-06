<?php
session_start();
$conn = new mysqli("localhost", "root", "", "projectdb");
$result = $conn->query("
    SELECT news.*, categories.name AS category_name
    FROM news
    JOIN categories ON news.category_id = categories.id
    WHERE news.is_deleted = 1
");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الأخبار المحذوفة</title>
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
<body>
<div class="container mt-5">
    <div class="d-flex gap-3 justify-content-center flex-wrap">
         <a href="add_category.php" class="btn btn-primary">إضافة فئة➕</a>
                <a href="view_categories.php" class="btn btn-secondary">عرض الفئات📂</a>
                <a href="add_news.php" class="btn btn-success">📰إضافة خبر</a>
                <a href="view_news.php" class="btn btn-info">عرض الأخبار📋</a>
                <a href="deleted_news.php" class="btn btn-warning">عرض الأخبار المحذوفة🗑️</a>
    </div>
    <h3 class="mb-4">الأخبار المحذوفة</h3>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-danger">
            <tr>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الصورة</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td><img src="uploads/<?= $row['image'] ?>" width="80"></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">الرجوع</a>
</div>
<!-- الفوتر -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <p class="mb-0">جميع الحقوق محفوظة &copy; <?= date('Y') ?> - نظام إدارة الأخبار</p>
    </div>
</footer>
</body>
</html>