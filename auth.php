<?php
function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function login($username, $password) {
    global $pdo;
    
    // Простая проверка без брутфорса для начала
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ? AND active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    // Для отладки: выведем что нашли
    error_log("Login attempt: user=" . $username . ", found=" . ($user ? 'yes' : 'no'));
    
    if ($user) {
        // Проверяем пароль
        $is_valid = password_verify($password, $user['password_hash']);
        error_log("Password verify: " . ($is_valid ? 'ok' : 'fail'));
        
        if ($is_valid) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    
    return false;
}

function logout() {
    session_destroy();
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
?>