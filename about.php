<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/HF.png" type="image/x-icon">
    <title>من نحن - HOLF</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/about.css">
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
                <li><a href="about.php" class="active">من نحن</a></li>
                <!-- إضافة رابط اللعبة -->
                <li><a href="game.php">لعبة الصيانة</a></li>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">لوحة التحكم</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <section class="about-hero">
            <div class="container">
                <h1>HOLF</h1>
                <p>نظام إلكتروني متطور لإدارة جميع طلبات الصيانة في الكلية التقنية بجازان</p>
            </div>
        </section>

        <section class="container">
            <div class="card vision-card">
                <h2>رؤيتنا</h2>
                <p>
                    نسعى لتوفير بيئة تعليمية مثالية من خلال نظام صيانة فعال وسريع الاستجابة، يضمن استمرارية العمل وراحة جميع منسوبي الكلية من طلاب وموظفين وأعضاء هيئة تدريس.
                </p>
            </div>

            <div class="grid grid-3 values-section">
                <div class="card">
                    <div class="card-icon">🎯</div>
                    <h3>مهمتنا</h3>
                    <p>تقديم خدمات صيانة عالية الجودة بكفاءة وسرعة، مع ضمان رضا جميع المستفيدين من خلال نظام إلكتروني متطور وسهل الاستخدام.</p>
                </div>

                <div class="card">
                    <div class="card-icon">⭐</div>
                    <h3>قيمنا</h3>
                    <p>الجودة، السرعة، الشفافية، والاحترافية في التعامل مع جميع طلبات الصيانة والدعم الفني لمنسوبي الكلية.</p>
                </div>

                <div class="card">
                    <div class="card-icon">🚀</div>
                    <h3>أهدافنا</h3>
                    <p>تحسين تجربة المستخدم، تقليل وقت الاستجابة، وزيادة كفاءة إدارة الصيانة من خلال التحول الرقمي الكامل.</p>
                </div>
            </div>
        </section>

        <section class="container team-section">
            <div class="card team-card">
                <h2>فريق العمل</h2>
                
                <div class="grid grid-3">
                    <div class="card team-member">
                        <div class="team-member-avatar">👨‍💼</div>
                        <h4>م. وليد حكمي</h4>
                        <p class="team-member-role">مدير الصيانة</p>
                        <p class="team-member-description">إشراف عام على جميع عمليات الصيانة</p>
                    </div>

                    <div class="card team-member">
                        <div class="team-member-avatar">👨‍🔧</div>
                        <h4>ريان الصميلي</h4>
                        <p class="team-member-role">رئيس فريق الصيانة</p>
                        <p class="team-member-description">تنسيق وتوزيع المهام على الفنيين</p>
                    </div>

                    <div class="card team-member">
                        <div class="team-member-avatar">👨‍💻</div>
                        <h4>ريان الصميلي</h4>
                        <p class="team-member-role">مسؤول الدعم الفني</p>
                        <p class="team-member-description">إدارة النظام والدعم التقني</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="container contact-section">
            <div class="card contact-card">
                <h2>تواصل معنا</h2>
                
                <div class="contact-grid">
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <h4>الهاتف</h4>
                        <p>017-3XXXXXX</p>
                        <p>داخلي: 1234</p>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <h4>البريد الإلكتروني</h4>
                        <p>maintenance@tvtc.gov.sa</p>
                        <p>support@tvtc.gov.sa</p>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <h4>الموقع</h4>
                        <p>الكلية التقنية بجازان</p>
                        <p>المبنى الإداري - الطابق الأول</p>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">⏰</div>
                        <h4>ساعات العمل</h4>
                        <p>الأحد - الخميس</p>
                        <p>7:30 ص - 3:30 م</p>
                    </div>
                </div>

                <div class="contact-cta">
                    <a href="submit-request.php" class="btn btn-primary">تقديم طلب صيانة الآن</a>
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