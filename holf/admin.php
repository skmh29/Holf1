<?php
session_start();
require_once 'config/database.php';

// التحقق من صلاحيات المدير
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// جلب جميع الطلبات
$requests = fetchAll("SELECT * FROM maintenance_requests ORDER BY created_at DESC");

// جلب الإحصائيات
$stats = [
    'total' => count($requests),
    'pending' => count(array_filter($requests, fn($r) => $r['status'] === 'pending')),
    'in_progress' => count(array_filter($requests, fn($r) => $r['status'] === 'in_progress')),
    'completed' => count(array_filter($requests, fn($r) => $r['status'] === 'completed'))
];

// جلب الفنيين
$technicians = fetchAll("SELECT * FROM technicians");

$statusTranslation = [
    'pending' => 'قيد المراجعة',
    'in_progress' => 'قيد التنفيذ',
    'completed' => 'مكتمل',
    'cancelled' => 'ملغي'
];

$priorityTranslation = [
    'low' => 'منخفضة',
    'medium' => 'متوسطة',
    'high' => 'عالية',
    'urgent' => 'عاجلة'
];

$categoryTranslation = [
    'computer' => 'صيانة الأجهزة',
    'electrical' => 'صيانة كهربائية',
    'building' => 'صيانة المباني',
    'network' => 'صيانة الشبكات',
    'furniture' => 'صيانة الأثاث',
    'plumbing' => 'السباكة',
    'ac' => 'التكييف',
    'other' => 'أخرى'
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/HF.png" type="image/x-icon">
    <title>لوحة التحكم - HOLF</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <nav class="nav-header">
        <div class="container nav-content">
            <a href="index.php" class="logo">               
                <span>HOLF</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="submit-request.php">تقديم طلب</a></li>
                <li><a href="track-requests.php">تتبع الطلبات</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="admin.php" class="active">لوحة التحكم</a></li>
                <li><a href="logout.php">تسجيل خروج</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="container admin-container">
            <div class="card admin-header-card">
                <div class="admin-header-content">
                    <div>
                        <h1>لوحة التحكم</h1>
                        <p>مرحباً <?= htmlspecialchars($_SESSION['username']) ?> - إدارة ومتابعة جميع طلبات الصيانة</p>
                    </div>
                    <div class="admin-actions">
                        <button class="btn btn-secondary" onclick="location.reload()">🔄 تحديث</button>
                    </div>
                </div>
            </div>

            <div class="stats-cards">
                <div class="stat-card total">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">إجمالي الطلبات</p>
                            <h3 class="stat-value accent"><?= $stats['total'] ?></h3>
                            <p class="stat-change accent">جميع الطلبات</p>
                        </div>
                        <div class="stat-icon">📊</div>
                    </div>
                </div>

                <div class="stat-card pending">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">قيد المراجعة</p>
                            <h3 class="stat-value gold"><?= $stats['pending'] ?></h3>
                            <p class="stat-change gold">يحتاج إلى اهتمام</p>
                        </div>
                        <div class="stat-icon">⏳</div>
                    </div>
                </div>

                <div class="stat-card progress">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">قيد التنفيذ</p>
                            <h3 class="stat-value blue"><?= $stats['in_progress'] ?></h3>
                            <p class="stat-change blue">جاري العمل عليها</p>
                        </div>
                        <div class="stat-icon">🔧</div>
                    </div>
                </div>

                <div class="stat-card completed">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">مكتملة</p>
                            <h3 class="stat-value accent"><?= $stats['completed'] ?></h3>
                            <p class="stat-change accent">✓ تم الإنجاز</p>
                        </div>
                        <div class="stat-icon">✅</div>
                    </div>
                </div>
            </div>

            <div class="card requests-management-card">
                <div class="requests-header">
                    <h2>جميع الطلبات</h2>
                </div>

                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>مقدم الطلب</th>
                                <th>القسم</th>
                                <th>نوع الصيانة</th>
                                <th>الأولوية</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($req['request_number']) ?></strong></td>
                                <td><?= htmlspecialchars($req['name']) ?></td>
                                <td><?= htmlspecialchars($req['department']) ?></td>
                                <td><?= $categoryTranslation[$req['category']] ?? $req['category'] ?></td>
                                <td><span class="priority-<?= $req['priority'] ?>"><?= $priorityTranslation[$req['priority']] ?? $req['priority'] ?></span></td>
                                <td><?= date('Y-m-d H:i', strtotime($req['created_at'])) ?></td>
                                <td><span class="status-badge status-<?= $req['status'] ?>"><?= $statusTranslation[$req['status']] ?? $req['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">لا توجد طلبات حالياً</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="analytics-section">
                <div class="card technicians-card">
                    <h3>الفنيون المتاحون</h3>
                    <div class="technician-list">
                        <?php foreach ($technicians as $tech): ?>
                        <div class="technician-item available">
                            <div class="technician-info">
                                <div class="technician-avatar">👨‍🔧</div>
                                <div class="technician-details">
                                    <h4><?= htmlspecialchars($tech['name']) ?></h4>
                                    <p class="technician-tasks"><?= htmlspecialchars($tech['specialization']) ?></p>
                                </div>
                            </div>
                            <span class="status-badge status-progress">متاح</span>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($technicians)): ?>
                        <p>لا يوجد فنيون مسجلون</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© 2025 الكلية التقنية بجازان - جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>
</html>