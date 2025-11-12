<?php
session_start();
require_once 'config/database.php';

$requests = [];
$error = '';
$searchNumber = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchNumber = trim($_POST['request_number'] ?? '');
    
    if (empty($searchNumber)) {
        $error = 'الرجاء إدخال رقم الطلب';
    } else {
        try {
            $request = fetchOne(
                "SELECT * FROM maintenance_requests WHERE request_number = :number",
                ['number' => $searchNumber]
            );
            
            if ($request) {
                $requests = [$request];
            } else {
                $error = 'لم يتم العثور على طلب بهذا الرقم';
            }
        } catch (Exception $e) {
            error_log("Track Request Error: " . $e->getMessage());
            $error = 'حدث خطأ أثناء البحث';
        }
    }
}

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
    <title>تتبع الطلبات - HOLF</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/track.css">
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
                <li><a href="track-requests.php" class="active">تتبع الطلبات</a></li>
                <li><a href="about.php">من نحن</a></li>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">لوحة التحكم</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <section class="container track-container">
            <div class="card search-card">
                <div class="search-header">
                    <div class="card-icon">🔍</div>
                    <h1>تتبع طلب الصيانة</h1>
                    <p>أدخل رقم الطلب للاستعلام عن حالته</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="track-requests.php" class="search-form">
                    <div class="search-input-group">
                        <input 
                            type="text" 
                            name="request_number" 
                            placeholder="مثال: REQ-12345" 
                            required
                            value="<?= htmlspecialchars($searchNumber) ?>"
                            class="search-input"
                        >
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $req): ?>
                <div class="card request-details-card">
                    <div class="request-header">
                        <h2>تفاصيل الطلب</h2>
                        <span class="status-badge status-<?= $req['status'] ?>">
                            <?= $statusTranslation[$req['status']] ?? $req['status'] ?>
                        </span>
                    </div>

                    <div class="request-info-grid">
                        <div class="info-item">
                            <span class="info-label">رقم الطلب:</span>
                            <span class="info-value"><strong><?= htmlspecialchars($req['request_number']) ?></strong></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">التاريخ:</span>
                            <span class="info-value"><?= date('Y-m-d H:i', strtotime($req['created_at'])) ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">الاسم:</span>
                            <span class="info-value"><?= htmlspecialchars($req['name']) ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">القسم:</span>
                            <span class="info-value"><?= htmlspecialchars($req['department']) ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">الموقع:</span>
                            <span class="info-value"><?= htmlspecialchars($req['location']) ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">نوع الصيانة:</span>
                            <span class="info-value"><?= $categoryTranslation[$req['category']] ?? $req['category'] ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">الأولوية:</span>
                            <span class="priority-<?= $req['priority'] ?>"><?= $priorityTranslation[$req['priority']] ?? $req['priority'] ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">البريد الإلكتروني:</span>
                            <span class="info-value"><?= htmlspecialchars($req['email']) ?></span>
                        </div>

                        <div class="info-item full-width">
                            <span class="info-label">وصف المشكلة:</span>
                            <p class="description-text"><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                        </div>

                        <?php if ($req['admin_notes']): ?>
                        <div class="info-item full-width">
                            <span class="info-label">ملاحظات الإدارة:</span>
                            <p class="admin-notes"><?= nl2br(htmlspecialchars($req['admin_notes'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="status-timeline">
                        <h3>مراحل الطلب</h3>
                        <div class="timeline">
                            <div class="timeline-item <?= in_array($req['status'], ['pending', 'in_progress', 'completed']) ? 'active' : '' ?>">
                                <div class="timeline-icon">📝</div>
                                <div class="timeline-content">
                                    <h4>تم الاستلام</h4>
                                    <p><?= date('Y-m-d H:i', strtotime($req['created_at'])) ?></p>
                                </div>
                            </div>

                            <div class="timeline-item <?= in_array($req['status'], ['in_progress', 'completed']) ? 'active' : '' ?>">
                                <div class="timeline-icon">🔧</div>
                                <div class="timeline-content">
                                    <h4>قيد التنفيذ</h4>
                                    <p><?= $req['status'] === 'in_progress' || $req['status'] === 'completed' ? 'جاري العمل' : 'في الانتظار' ?></p>
                                </div>
                            </div>

                            <div class="timeline-item <?= $req['status'] === 'completed' ? 'active' : '' ?>">
                                <div class="timeline-icon">✅</div>
                                <div class="timeline-content">
                                    <h4>مكتمل</h4>
                                    <p><?= $req['completed_at'] ? date('Y-m-d H:i', strtotime($req['completed_at'])) : 'لم يكتمل بعد' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© 2025 الكلية التقنية بجازان - جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>
</html>