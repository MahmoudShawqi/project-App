<?php
session_start();
$conn = new mysqli("localhost", "root", "", "projectdb");

$id = $_GET['id'];
$news = $conn->query("SELECT * FROM news WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $details = $_POST['details'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp_name, "uploads/$image_name");
        $conn->query("UPDATE news SET title='$title', category_id=$category_id, details='$details', image='$image_name' WHERE id=$id");
    } else {
        $conn->query("UPDATE news SET title='$title', category_id=$category_id, details='$details' WHERE id=$id");
    }

    header("Location: view_news.php");
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الخبر</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">تعديل الخبر</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>عنوان الخبر</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>الفئة</label>
            <select name="category_id" class="form-control" required>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $news['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>تفاصيل الخبر</label>
            <textarea name="details" class="form-control" rows="5"><?= htmlspecialchars($news['details']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>تحديث الصورة (اختياري)</label>
            <input type="file" name="image" class="form-control">
            <img src="uploads/<?= $news['image'] ?>" width="100" class="mt-2">
        </div>
        <button type="submit" class="btn btn-success">حفظ التعديلات</button>
        <a href="view_news.php" class="btn btn-secondary">الرجوع</a>
    </form>
</div>
</body>
</html>