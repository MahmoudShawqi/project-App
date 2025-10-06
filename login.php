<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectdb";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log the error for security, but display a generic message
    error_log("Connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later."); 
}

// Initialize error variable
$error = ""; 

if (isset($_POST['login'])) {
    // 1. Sanitize/Validate input (optional but good practice)
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    // Check if fields are empty
    if (empty($email) || empty($pass)) {
        $error = "الرجاء إدخال البريد الإلكتروني وكلمة المرور.";
    } else {
        // 2. Use Prepared Statements to prevent SQL Injection
        $sql = "SELECT id, password FROM users WHERE email = ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Check if preparation succeeded
        if ($stmt === false) {
             error_log("Prepare failed: " . $conn->error);
             $error = "حدث خطأ غير متوقع. الرجاء المحاولة لاحقًا.";
        } else {
            // Bind the email parameter (s = string)
            $stmt->bind_param("s", $email);
            
            // Execute the query
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                
                // 3. Verify password hash
                if (password_verify($pass, $data['password'])) {
                    $_SESSION['user_id'] = $data['id'];
                    $_SESSION['username'] = $data['name'];
                    $stmt->close();
                    $conn->close();
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "كلمة المرور غير صحيحة.";
                }
            } else {
                $error = "المستخدم غير موجود.";
            }
            
            // Close statement
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">تسجيل الدخول</h3>
            <?php if (!empty($error)) echo "<div class='alert alert-danger text-center' role='alert'>$error</div>"; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-success w-100">دخول</button>
                <p class="text-center mt-3">ليس لديك حساب؟ <a href="createaccountregister.php">إنشاء حساب</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>