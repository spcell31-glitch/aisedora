<?php
require_once 'includes/config.php';

$password = 'admin123';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "Пароль: " . $password . "<br>";
echo "Хеш в БД: " . $hash . "<br>";
echo "Проверка: " . (password_verify($password, $hash) ? '✅ СОВПАДАЕТ' : '❌ НЕ СОВПАДАЕТ');

// Проверим что в БД
try {
    $stmt = $pdo->query("SELECT username, password_hash FROM admin_users");
    $users = $stmt->fetchAll();
    echo "<h3>Пользователи в БД:</h3>";
    foreach ($users as $user) {
        echo $user['username'] . " - " . $user['password_hash'] . "<br>";
    }
} catch(Exception $e) {
    echo "Ошибка БД: " . $e->getMessage();
}
?>