<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';
$requestNumber = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($name) || empty($email) || empty($phone) || empty($department) || 
        empty($location) || empty($category) || empty($priority) || empty($description)) {
        $error = 'جميع الحقول المطلوبة يجب ملؤها';
    } else {
        try {
            $attachmentPath = null;
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $fileExtension;
                $attachmentPath = $uploadDir . $fileName;
                
                move_uploaded_file($_FILES['attachment']['tmp_name'], $attachmentPath);
            }
            
            $requestNumber = 'REQ-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            
            while (fetchOne("SELECT id FROM maintenance_requests WHERE request_number = :num", ['num' => $requestNumber])) {
                $requestNumber = 'REQ-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            }
            
            $requestId = insert('maintenance_requests', [
                'request_number' => $requestNumber,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'department' => $department,
                'location' => $location,
                'category' => $category,
                'priority' => $priority,
                'description' => $description,
                'attachment' => $attachmentPath,
                'status' => 'pending'
            ]);
            
            $success = "تم تقديم طلبك بنجاح! رقم الطلب: $requestNumber";
            header('Refresh: 3; URL=track-requests.php');
        } catch (Exception $e) {
            error_log("Submit Request Error: " . $e->getMessage());
            $error = 'حدث خطأ أثناء تقديم الطلب';
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
    <title>تقديم طلب صيانة - HOLF</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/submit-request.css">
</head>
<body>
    <nav class="nav-header">
        <div class="container nav-content">
            <a href="index.php" class="logo">
                <span>HOLF</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="submit-request.php" class="active">تقديم طلب</a></li>
                <li><a href="track-requests.php">تتبع الطلبات</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="admin.php">لوحة التحكم</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="container form-container">
            <div class="form-wrapper">
                <div class="card">
                    <div class="form-header">
                        <div class="card-icon">📝</div>
                        <h1>تقديم طلب صيانة جديد</h1>
                        <p>املأ النموذج أدناه وسيتم معالجة طلبك في أقرب وقت</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="submit-request.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">الاسم الكامل *</label>
                            <input type="text" id="name" name="name" required placeholder="أدخل اسمك الكامل" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني *</label>
                            <input type="email" id="email" name="email" required placeholder="example@tvtc.gov.sa" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="phone">رقم الجوال *</label>
                            <input type="tel" id="phone" name="phone" required placeholder="05xxxxxxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="department">القسم / الإدارة *</label>
                            <select id="department" name="department" required>
                                <option value="">اختر القسم</option>
                                <option value="it">تقنية المعلومات</option>
                                <option value="engineering">الهندسة</option>
                                <option value="admin">الإدارة</option>
                                <option value="finance">المالية</option>
                                <option value="hr">الموارد البشرية</option>
                                <option value="student-affairs">شؤون الطلاب</option>
                                <option value="library">المكتبة</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="location">الموقع / رقم القاعة *</label>
                            <input type="text" id="location" name="location" required placeholder="مثال: المبنى الرئيسي - الطابق الثاني - قاعة 205" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="category">نوع الصيانة *</label>
                            <select id="category" name="category" required>
                                <option value="">اختر نوع الصيانة</option>
                                <option value="computer">صيانة الأجهزة (حاسب، طابعات)</option>
                                <option value="electrical">صيانة كهربائية</option>
                                <option value="building">صيانة المباني</option>
                                <option value="network">صيانة الشبكات</option>
                                <option value="furniture">صيانة الأثاث</option>
                                <option value="plumbing">السباكة</option>
                                <option value="ac">التكييف</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority">الأولوية *</label>
                            <select id="priority" name="priority" required>
                                <option value="">اختر الأولوية</option>
                                <option value="low">منخفضة - يمكن الانتظار</option>
                                <option value="medium">متوسطة - خلال أسبوع</option>
                                <option value="high">عالية - خلال يومين</option>
                                <option value="urgent">عاجلة - فوري</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المشكلة *</label>
                            <textarea id="description" name="description" required placeholder="اشرح المشكلة بالتفصيل..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="attachment">إرفاق صورة (اختياري)</label>
                            <input type="file" id="attachment" name="attachment" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary submit-button">
                            إرسال الطلب
                        </button>
                    </form>
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