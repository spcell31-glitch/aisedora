<?php
// Включить вывод ошибок для отладки (отключить после настройки)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Базовые настройки
session_start();

// Функция для очистки входных данных
function clean_input($data) {
    if (empty($data)) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Корневые пути
define('SITE_ROOT', dirname(dirname(__FILE__)) . '/..');
define('ADMIN_PATH', dirname(__FILE__) . '/..');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');

// Подключение БД
require_once 'database.php';

// CSRF защита
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Ошибка безопасности. Пожалуйста, перезагрузите страницу.');
    }
}

// Автозагрузка классов (если будут)
spl_autoload_register(function($class) {
    $file = ADMIN_PATH . '/includes/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
?>