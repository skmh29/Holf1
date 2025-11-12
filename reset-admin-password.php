<?php
// إعادة تعيين كلمة مرور المدير
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // كلمة المرور الجديدة
        $newPassword = 'admin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // تحديث كلمة المرور في قاعدة البيانات
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = 'admin@tvtc.gov.sa'");
        $stmt->execute(['password' => $hashedPassword]);
        
        echo "<!DOCTYPE html>";
        echo "<html lang='ar' dir='rtl'>";
        echo "<head><meta charset='UTF-8'><title>تم إعادة التعيين</title></head>";
        echo "<body style='font-family: Arial, sans-serif; padding: 50px; text-align: center;'>";
        echo "<h1 style='color: green;'>✓ تم إعادة تعيين كلمة المرور بنجاح!</h1>";
        echo "<p><strong>البريد الإلكتروني:</strong> admin@tvtc.gov.sa</p>";
        echo "<p><strong>كلمة المرور الجديدة:</strong> admin123</p>";
        echo "<br>";
        echo "<a href='login.php' style='padding: 10px 20px; background: #0070f3; color: white; text-decoration: none; border-radius: 5px;'>الذهاب لصفحة تسجيل الدخول</a>";
        echo " ";
        echo "<a href='test-connection.php' style='padding: 10px 20px; background: #666; color: white; text-decoration: none; border-radius: 5px;'>اختبار الاتصال مرة أخرى</a>";
        echo "</body>";
        echo "</html>";
        
    } catch (Exception $e) {
        echo "<!DOCTYPE html>";
        echo "<html lang='ar' dir='rtl'>";
        echo "<head><meta charset='UTF-8'><title>خطأ</title></head>";
        echo "<body style='font-family: Arial, sans-serif; padding: 50px; text-align: center;'>";
        echo "<h1 style='color: red;'>✗ حدث خطأ!</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<a href='test-connection.php'>العودة</a>";
        echo "</body>";
        echo "</html>";
    }
} else {
    header('Location: test-connection.php');
    exit;
}
?>