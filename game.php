<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/HF.png" type="image/x-icon">
    <title>لعبة الصيانة والسلامة - HOLF</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/game.css">
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
                <li><a href="game.php" class="active">لعبة الصيانة</a></li>
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
        <section class="container game-container">
            <div class="card game-card">
                <div class="game-header">
                    <h1>اختبر معلوماتك في الصيانة والسلامة</h1>
                    <p>لعبة تعليمية لتطوير معرفتك بأساسيات الصيانة والسلامة المهنية</p>
                </div>

                <div id="game-screen" class="game-screen">
                    <div class="game-info">
                        <div class="score-board">
                            <div class="score-item">
                                <span class="score-label">النقاط</span>
                                <span class="score-value" id="score">0</span>
                            </div>
                            <div class="score-item">
                                <span class="score-label">السؤال</span>
                                <span class="score-value" id="question-number">1/10</span>
                            </div>
                        </div>
                    </div>

                    <div id="start-screen" class="start-screen">
                        <div class="start-content">
                            <div class="game-icon">🎮</div>
                            <h2>مرحباً بك في لعبة الصيانة والسلامة</h2>
                            <div class="game-rules">
                                <h3>قواعد اللعبة</h3>
                                <ul>
                                    <li>10 أسئلة عن الصيانة والسلامة المهنية</li>
                                    <li>كل إجابة صحيحة = 10 نقاط</li>
                                    <li>حاول الحصول على أعلى نقاط ممكنة</li>
                                    <li>تعلم معلومات مفيدة بطريقة ممتعة</li>
                                </ul>
                            </div>
                            <button class="btn btn-primary" onclick="startGame()">ابدأ اللعبة</button>
                        </div>
                    </div>

                    <div id="question-screen" class="question-screen" style="display: none;">
                        <div class="question-card">
                            <h2 id="question-text"></h2>
                            <div id="answers-container" class="answers-container"></div>
                        </div>
                    </div>

                    <div id="result-screen" class="result-screen" style="display: none;">
                        <div class="result-content">
                            <div id="result-icon" class="result-icon"></div>
                            <h2 id="result-title"></h2>
                            <div class="final-score">
                                <span>نقاطك النهائية</span>
                                <span id="final-score" class="final-score-value">0</span>
                            </div>
                            <div id="result-message" class="result-message"></div>
                            <div class="result-buttons">
                                <button class="btn btn-primary" onclick="restartGame()">العب مرة أخرى</button>
                                <a href="index.php" class="btn btn-secondary">العودة للرئيسية</a>
                            </div>
                        </div>
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

    <script src="js/game.js"></script>
</body>
</html>