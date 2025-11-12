<?php
session_start();

// حذف جميع متغيرات الجلسة
$_SESSION = array();

// حذف ملف تعريف الارتباط الخاص بالجلسة
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// إنهاء الجلسة
session_destroy();

// إعادة التوجيه إلى الصفحة الرئيسية
header('Location: index.php');
exit;
?>