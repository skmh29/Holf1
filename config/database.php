<?php
// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_NAME', 'holf_maintenance');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("حدث خطأ في الاتصال بقاعدة البيانات");
}

/**
 * تنفيذ استعلام وإرجاع جميع النتائج
 */
function fetchAll($query, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * تنفيذ استعلام وإرجاع صف واحد
 */
function fetchOne($query, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * إدراج بيانات في جدول
 */
function insert($table, $data) {
    global $pdo;
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    
    $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($data);
    
    return $pdo->lastInsertId();
}

/**
 * تحديث بيانات في جدول
 */
function update($table, $data, $where, $whereParams = []) {
    global $pdo;
    $set = [];
    foreach (array_keys($data) as $key) {
        $set[] = "$key = :$key";
    }
    $setClause = implode(', ', $set);
    
    $query = "UPDATE $table SET $setClause WHERE $where";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge($data, $whereParams));
    
    return $stmt->rowCount();
}

/**
 * حذف بيانات من جدول
 */
function delete($table, $where, $params = []) {
    global $pdo;
    $query = "DELETE FROM $table WHERE $where";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    return $stmt->rowCount();
}