<?php
session_start();

// --- 1. Database Connection and Error Check ---
$conn = new mysqli("localhost", "root", "", "projectdb");

if ($conn->connect_error) {
    // Log the error and stop execution gracefully
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // --- 2. Check and Sanitize Input ---
    // Use the name attribute from the input field
    $name = trim($_POST['name'] ?? ''); 

    if (!empty($name)) {
        
        // --- 3. Use Prepared Statements to Prevent SQL Injection (The Fix!) ---
        // Use a placeholder '?' for the user input.
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        
        if ($stmt) {
            // Bind the parameter (s = string)
            $stmt->bind_param("s", $name);
            
            // Execute the statement
            if ($stmt->execute()) {
                // Success: Redirect
                $stmt->close();
                $conn->close();
                header("Location: view_categories.php");
                exit();
            } else {
                // Failure to execute query
                $error_message = "Failed to insert category: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Failure to prepare statement
            $error_message = "Database error: Failed to prepare statement.";
        }
    } else {
        $error_message = "الرجاء إدخال اسم الفئة.";
    }
}

// If an error occurred, set a variable to display it
// This variable will be used in the HTML section
$error = $error_message ?? '';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة فئة</title>
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
    <h3 class="mb-4">إضافة فئة جديدة</h3>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="add_category.php" >
        <div class="mb-3">
            <label>اسم الفئة</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">حفظ</button>
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