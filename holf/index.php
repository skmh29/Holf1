<?php
session_start();
require_once 'config/database.php';

// جلب الإحصائيات من قاعدة البيانات
$totalCompleted = 0;
$totalInProgress = 0;

try {
    $stats = fetchAll("SELECT status, COUNT(*) as count FROM maintenance_requests GROUP BY status");
    foreach ($stats as $stat) {
        if ($stat['status'] === 'completed') {
            $totalCompleted = $stat['count'];
        } elseif ($stat['status'] === 'in_progress') {
            $totalInProgress = $stat['count'];
        }
    }
    
    $satisfactionRate = 95; // يمكن حسابها من استبيانات لاحقاً
} catch (Exception $e) {
    error_log("Stats Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/HF.png" type="image/x-icon">
    <title>HOLF - نظام طلبات الصيانة</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <nav class="nav-header">
        <div class="container nav-content">
            <a href="index.php" class="logo">
                <span>HOLF</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">الرئيسية</a></li>
                <li><a href="submit-request.php">تقديم طلب</a></li>
                <li><a href="track-requests.php">تتبع الطلبات</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="game.php">لعبة الصيانة</a></li>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">لوحة التحكم</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">تسجيل خروج</a></li>
                <?php else: ?>
                    <li><a href="login.php">تسجيل دخول</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <h1>HOLF</h1>
                <p>الكلية التقنية بجازان - نظام متطور وسهل الاستخدام لإدارة جميع طلبات الصيانة والدعم الفني بكفاءة عالية</p>
                <div class="hero-buttons">
                    <a href="submit-request.php" class="btn btn-primary">تقديم طلب صيانة جديد</a>
                    <a href="track-requests.php" class="btn btn-secondary">تتبع طلباتي</a>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="grid grid-3">
                <div class="card">
                    <div class="card-icon">📝</div>
                    <h3>تقديم الطلبات</h3>
                    <p>قم بتقديم طلبات الصيانة بسهولة من خلال نموذج إلكتروني بسيط ومباشر. احصل على رقم متابعة فوري لطلبك.</p>
                </div>

                <div class="card">
                    <div class="card-icon">🔍</div>
                    <h3>تتبع الحالة</h3>
                    <p>تابع حالة طلبك في الوقت الفعلي من خلال رقم الطلب. احصل على تحديثات فورية عن تقدم العمل.</p>
                </div>

                <div class="card">
                    <div class="card-icon">⚡</div>
                    <h3>استجابة سريعة</h3>
                    <p>فريق الصيانة لدينا يعمل على مدار الساعة لضمان معالجة طلبك بأسرع وقت ممكن وبأعلى جودة.</p>
                </div>
            </div>
        </section>

        <section class="container service-types-section">
            <div class="card service-types-card">
                <h2>أنواع الصيانة المتاحة</h2>
                <p>نقدم خدمات صيانة شاملة لجميع مرافق الكلية</p>
                
                <div class="grid grid-3 service-types-grid">
                    <div class="card">
                        <h4>🖥️ صيانة الأجهزة</h4>
                        <p>أجهزة الحاسب، الطابعات، الشاشات</p>
                    </div>
                    <div class="card">
                        <h4>🔌 الصيانة الكهربائية</h4>
                        <p>الإضاءة، المقابس، التكييف</p>
                    </div>
                    <div class="card">
                        <h4>🏢 صيانة المباني</h4>
                        <p>الأبواب، النوافذ، الدهانات</p>
                    </div>
                    <div class="card">
                        <h4>🌐 صيانة الشبكات</h4>
                        <p>الإنترنت، الشبكات الداخلية</p>
                    </div>
                    <div class="card">
                        <h4>🪑 صيانة الأثاث</h4>
                        <p>المكاتب، الكراسي، الخزائن</p>
                    </div>
                    <div class="card">
                        <h4>🚰 السباكة</h4>
                        <p>دورات المياه، الصنابير، التسريبات</p>
                    </div>
                </div>
            </div>
        </section>
<br>
        <section class="container stats-section">
            <div class="card stats-card">
                <h2>إحصائيات النظام</h2>
                <div class="grid grid-3 stats-grid">
                    <div>
                        <div class="stats-number accent"><?= $totalCompleted ?></div>
                        <p>طلب تم إنجازه</p>
                    </div>
                    <div>
                        <div class="stats-number gold"><?= $totalInProgress ?></div>
                        <p>طلب قيد التنفيذ</p>
                    </div>
                    <div>
                        <div class="stats-number dark"><?= $satisfactionRate ?>%</div>
                        <p>نسبة رضا المستخدمين</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© 2025 الكلية التقنية بجازان - جميع الحقوق محفوظة</p>
            <p style="margin-top: 0.5rem; font-size: 0.875rem;">نظام طلبات الصيانة الإلكتروني</p>
        </div>
    </footer>
</body>
</html>