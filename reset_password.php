<?php
require_once 'includes/config.php';

// Создаем новый пароль
$new_password = 'admin123';
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

echo "Новый пароль: " . $new_password . "<br>";
echo "Новый хеш: " . $new_hash . "<br><br>";

// Обновляем в БД
try {
    $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
    $stmt->execute([$new_hash]);
    
    echo "✅ Пароль обновлен в БД!<br><br>";
    
    // Проверяем
    $stmt = $pdo->query("SELECT username, password_hash FROM admin_users");
    $users = $stmt->fetchAll();
    
    echo "<h3>Текущие пользователи:</h3>";
    foreach ($users as $user) {
        $check = password_verify($new_password, $user['password_hash']);
        echo $user['username'] . " - проверка: " . ($check ? '✅ OK' : '❌ FAIL') . "<br>";
        echo "Хеш: " . $user['password_hash'] . "<br><br>";
    }
    
    echo '<a href="../admin/">Попробовать войти</a>';
    
} catch(Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage();
}
?>