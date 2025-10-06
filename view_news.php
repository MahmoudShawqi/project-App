<?php
session_start();

// --- 1. Security Check: Ensure user is logged in ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// --- 2. Database Connection and Error Handling ---
$conn = new mysqli("localhost", "root", "", "projectdb");

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات. الرجاء المحاولة لاحقاً."); 
}

// --- 3. The SQL Query (Uses LEFT JOIN to ensure all news show up) ---
$sql = "
    SELECT 
        news.*,     
        categories.name AS category_name, 
        users.name AS user_name
    FROM news
    LEFT JOIN categories ON news.category_id = categories.id
    LEFT JOIN users ON news.user_id = users.id 
    WHERE news.is_deleted = 0
    ORDER BY news.id DESC
";

$result = $conn->query($sql);

// --- 4. Query Error Check ---
if ($result === false) {
    die("خطأ في جلب الأخبار: " . $conn->error);
}

// Optional: Close connection after data retrieval
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="ar"dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض الأخبار</title>
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
    <h3 class="mb-4">جميع الأخبار</h3>
    
    <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الكاتب</th>
                <th>الصورة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                
                <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" width="80" height="50" alt="صورة الخبر"></td>
                <td>
                    <a href="edit_news.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="delete_news.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info text-center">لا توجد أخبار لعرضها حالياً.</div>
    <?php endif; ?>
    
    <a href="dashboard.php" class="btn btn-secondary mt-3">الرجوع</a>
</div>
<!-- الفوتر -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <p class="mb-0">جميع الحقوق محفوظة &copy; <?= date('Y') ?> - نظام إدارة الأخبار</p>
    </div>
</footer>
</body>
</html>