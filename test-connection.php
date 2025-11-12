<?php
// ملف اختبار الاتصال بقاعدة البيانات
echo "<h1>اختبار الاتصال بقاعدة البيانات</h1>";

// اختبار الاتصال
try {
    require_once 'config/database.php';
    echo "<p style='color: green;'>✓ الاتصال بقاعدة البيانات ناجح!</p>";
    
    // اختبار جدول المستخدمين
    $users = fetchAll("SELECT id, username, email, role FROM users");
    echo "<h2>المستخدمين في قاعدة البيانات:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>اسم المستخدم</th><th>البريد الإلكتروني</th><th>الصلاحية</th></tr>";
    
    if (empty($users)) {
        echo "<tr><td colspan='4' style='color: red;'>لا يوجد مستخدمين! يجب تشغيل ملف SQL أولاً</td></tr>";
    } else {
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // اختبار كلمة المرور
    echo "<h2>اختبار كلمة المرور:</h2>";
    $testPassword = 'admin123';
    $hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
    echo "<p><strong>كلمة المرور:</strong> $testPassword</p>";
    echo "<p><strong>الهاش الجديد:</strong> $hashedPassword</p>";
    
    // التحقق من كلمة المرور للمدير
    $admin = fetchOne("SELECT * FROM users WHERE email = 'admin@tvtc.gov.sa'");
    if ($admin) {
        $passwordMatch = password_verify($testPassword, $admin['password']);
        echo "<p><strong>التحقق من كلمة المرور:</strong> ";
        if ($passwordMatch) {
            echo "<span style='color: green;'>✓ كلمة المرور صحيحة!</span>";
        } else {
            echo "<span style='color: red;'>✗ كلمة المرور غير صحيحة!</span>";
        }
        echo "</p>";
    } else {
        echo "<p style='color: red;'>✗ المستخدم الإداري غير موجود!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ خطأ: " . $e->getMessage() . "</p>";
}
?>

<hr>
<h2>خطوات الحل:</h2>
<ol>
    <li>تأكد من تشغيل Apache و MySQL في XAMPP</li>
    <li>تأكد من تشغيل ملف SQL: <code>scripts/create_database.sql</code></li>
    <li>إذا كانت كلمة المرور غير صحيحة، استخدم النموذج أدناه لتحديثها</li>
</ol>

<h2>تحديث كلمة المرور للمدير:</h2>
<form method="POST" action="reset-admin-password.php">
    <button type="submit" style="padding: 10px 20px; background: #0070f3; color: white; border: none; border-radius: 5px; cursor: pointer;">
        إعادة تعيين كلمة المرور للمدير
    </button>
</form>