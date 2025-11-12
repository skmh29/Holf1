<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'البريد الإلكتروني وكلمة المرور مطلوبان';
    } else {
        try {
            $user = fetchOne(
                "SELECT id, username, email, password, role FROM users WHERE email = :email",
                ['email' => $email]
            );
            
            if (!$user || !password_verify($password, $user['password'])) {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: submit-request.php');
                }
                exit;
            }
        } catch (Exception $e) {
            error_log("Login Error: " . $e->getMessage());
            $error = 'حدث خطأ أثناء تسجيل الدخول';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/HF.png" type="image/x-icon">
    <title>تسجيل الدخول - HOLF</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h2 class="logo">TVTC</h2>
    <nav class="nav1">
        <a href="index.php">الرئيسية</a>
        <a href="about.php">من نحن</a>
        <a href="submit-request.php">خدماتنا</a>
        <a href="about.php">اتصل بنا</a>
        <button class="btnLogin-popup">تسجيل دخول</button>
    </nav>
</header>

<div class="wrapper">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <div class="form-box login">
        <h2>تسجيل الدخول</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <label>البريد الإلكتروني</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input type="password" name="password" required>
                <label>كلمة المرور</label>
            </div>
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember"> تذكرني
                </label>
                <a href="#">نسيت كلمة المرور؟</a>
            </div>
            <button type="submit" class="btn">تسجيل الدخول</button>
            <div class="logon-register">
                <p>ليس لديك حساب؟ 
                    <a href="register.php" class="register-link">سجل الآن</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="script/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>