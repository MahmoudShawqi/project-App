<?php
session_start();

$conn = new mysqli("localhost", "root", "", "projectdb");

// --- 1. Corrected Session Check and ID Retrieval ---
// Assuming your login sets $_SESSION['user_id'] directly, which is the most common practice.
// If not, adjust the variable name to match your login script (e.g., $_SESSION['user']['id']).
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); // Redirect to login page
    exit;
}
$user_id = $_SESSION['user_id'];
// --- End Session Check ---

// --- 2. Fetch Categories (Error checking added) ---
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$categories = $conn->query("SELECT id, name FROM categories");
if ($categories === false) {
    die("Error fetching categories: " . $conn->error);
}
// --- End Category Fetch ---


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Basic input cleaning
    $title = trim($_POST['title']);
    // Ensure category_id is treated as an integer
    $category_id = intval($_POST['category_id']); 
    $details = trim($_POST['details']);

    // --- File Upload Handling ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']); // Use basename for security
        $tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = "uploads/";
        $path = $upload_dir . $image_name;
        
        // Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($tmp_name, $path)) {
            // File moved successfully
        } else {
            // Handle file move failure
            // Consider setting an error message and stopping execution
            die("Failed to move uploaded file.");
        }
    } else {
        // Handle case where image upload failed or was not provided
        // Consider setting an error message and stopping execution
        $image_name = NULL; // Set image name to NULL or empty string if upload is mandatory
        // header("Location: add_news.php?error=no_image");
        // exit;
    }

    // --- 3. Corrected Prepared Statement Execution ---
    $sql = "INSERT INTO news (title, category_id, details, image, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // The corrected bind_param with 'sssis' type string
        // s=title, i=category_id, s=details, s=image_name, i=user_id
        $stmt->bind_param("sisss", $title, $category_id, $details, $image_name, $user_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: view_news.php");
            exit();
        } else {
            // Handle database execution error
            die("Error executing statement: " . $stmt->error);
        }
    } else {
        // Handle prepared statement error
        die("Error preparing statement: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة خبر</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
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
    <h3 class="mb-4">إضافة خبر جديد</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>عنوان الخبر</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الفئة</label>
            <select name="category_id" class="form-control" required>
                <option value="">اختر فئة</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>تفاصيل الخبر</label>
            <textarea name="details" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label>صورة الخبر</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">حفظ الخبر</button>
        <a href="dashboard.php" class="btn btn-secondary">الرجوع</a>
    </form>
</div>
<!-- الفوتر -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <p class="mb-0">جميع الحقوق محفوظة &copy; <?= date('Y') ?> - نظام إدارة الأخبار</p>
    </div>
</footer>
</body>
</html>