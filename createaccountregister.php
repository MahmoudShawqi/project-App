<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>تسجيل حساب جديد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">تسجيل حساب جديد</h3>
                <form method="POST" action="createaccountregister.php">
                    <div class="mb-3">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <input type="submit" class="btn btn-primary w-100" value="إنشاء حساب" name="createaccountregister">
                    <p class="text-center mt-3">هل لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php

$conn = new mysqli("localhost", "root", "", "projectdb");
function validateData($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($conn->error == false) {
    if (isset($_POST['createaccountregister'])) {
        $name  = validateData($_POST['name']);
        $email = validateData($_POST['email']);
        $pass  = password_hash(validateData($_POST['password']), PASSWORD_BCRYPT);



        $sql = "INSERT INTO users (email,password) VALUES ('$email','$pass')";
        $result = $conn->query($sql);
        if ($result == true) {
            header("Location: login.php");
            echo "<p class='success'>تم إنشاء الحساب بنجاح </p>";
        } else {
            echo "<p class='error'>خطأ: " . $conn->error . "</p>";
        }
    }
}



?>
