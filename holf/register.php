<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $agree = isset($_POST['agree']);
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'جميع الحقول مطلوبة';
    } elseif (!$agree) {
        $error = 'يجب الموافقة على الشروط والأحكام';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صحيح';
    } else {
        try {
            $existingUser = fetchOne(
                "SELECT id FROM users WHERE username = :username OR email = :email",
                ['username' => $username, 'email' => $email]
            );
            
            if ($existingUser) {
                $error = 'اسم المستخدم أو البريد الإلكتروني مستخدم بالفعل';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                insert('users', [
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => 'user'
                ]);
                
                $success = 'تم التسجيل بنجاح! سيتم تحويلك لصفحة تسجيل الدخول...';
                header('Refresh: 2; URL=login.php');
            }
        } catch (Exception $e) {
            error_log("Registration Error: " . $e->getMessage());
            $error = 'حدث خطأ أثناء التسجيل';
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
    <title>التسجيل - HOLF</title>
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
    <div class="form-box register">
        <h2>التسجيل</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <div class="input-box">
                <span class="icon"><ion-icon name="person"></ion-icon></span>
                <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <label>اسم المستخدم</label>
            </div>
            <div class="input-box">
                <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <label>البريد الإلكتروني</label>
            </div>
            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="password" required>
                <label>كلمة المرور</label>
            </div>
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="agree" required> أوافق على الشروط والأحكام
                </label>
            </div>
            <button type="submit" class="btn">تسجيل</button>
            <div class="logon-register">
                <p>لديك حساب بالفعل؟
                    <a href="login.php" class="login-link">تسجيل دخول</a>
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