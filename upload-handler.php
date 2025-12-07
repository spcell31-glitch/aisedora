<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
check_auth();

header('Content-Type: application/json');

// Включить отладку
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Проверка CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF token mismatch']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Ошибка загрузки файла', 'error_code' => $_FILES['image']['error'] ?? 'no file']);
    exit;
}

$file = $_FILES['image'];

// Проверка размера (5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Файл слишком большой. Максимум 5MB']);
    exit;
}

// Проверка типа файла
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);

if (!in_array($mime, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Разрешены только JPG, PNG и GIF. Ваш тип: ' . $mime]);
    exit;
}

// Генерация уникального имени
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$new_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

// ========== ИСПРАВЛЕННЫЕ ПУТИ ==========
// Абсолютный путь к папке uploads
$upload_dir = 'C:/OSPanel/domains/cms/uploads/';
$thumb_dir = $upload_dir . 'thumbs/';

// Создаем папки если нет
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!is_dir($thumb_dir)) {
    mkdir($thumb_dir, 0777, true);
}

// Защита от выполнения PHP
$htaccess_content = "Order Deny,Allow\nDeny from all\n<FilesMatch '\.(jpg|jpeg|png|gif|webp|css|js)$'>\nAllow from all\n</FilesMatch>";
file_put_contents($upload_dir . '.htaccess', $htaccess_content);
// =======================================

// Отладка
error_log("Uploading to: $upload_dir$new_filename");

// Перемещение файла
if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
    error_log("File moved successfully");
    
    // Создание миниатюры
    if (function_exists('create_thumbnail')) {
        create_thumbnail($upload_dir . $new_filename, $thumb_dir . $new_filename, 300, 300);
        error_log("Thumbnail created");
    } else {
        error_log("create_thumbnail function not found");
        // Просто копируем оригинал как миниатюру
        copy($upload_dir . $new_filename, $thumb_dir . $new_filename);
    }
    
    // Сохранение в базу данных
    try {
        $stmt = $pdo->prepare("INSERT INTO gallery_images (filename, title) VALUES (?, ?)");
        $stmt->execute([$new_filename, pathinfo($file['name'], PATHINFO_FILENAME)]);
        
        $image_id = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'image' => [
                'id' => $image_id,
                'filename' => $new_filename,
                'title' => pathinfo($file['name'], PATHINFO_FILENAME),
                'path' => '/uploads/' . $new_filename  // Добавляем путь для отладки
            ]
        ]);
        
        error_log("Database record created: ID $image_id");
        
    } catch (Exception $e) {
        // Удаляем файл если ошибка БД
        if (file_exists($upload_dir . $new_filename)) {
            unlink($upload_dir . $new_filename);
        }
        if (file_exists($thumb_dir . $new_filename)) {
            unlink($thumb_dir . $new_filename);
        }
        
        error_log("Database error: " . $e->getMessage());
        
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: ' . $e->getMessage(),
            'debug' => $e->getTraceAsString()
        ]);
    }
} else {
    error_log("Move uploaded file failed");
    echo json_encode([
        'success' => false, 
        'message' => 'Ошибка перемещения файла',
        'debug' => [
            'tmp_name' => $file['tmp_name'],
            'destination' => $upload_dir . $new_filename,
            'upload_dir_exists' => is_dir($upload_dir) ? 'yes' : 'no',
            'upload_dir_writable' => is_writable($upload_dir) ? 'yes' : 'no'
        ]
    ]);
}
?>