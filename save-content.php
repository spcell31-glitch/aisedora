<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
check_auth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Проверка CSRF
if (!isset($_POST['csrf_token']) && !isset($_SERVER['HTTP_CONTENT_TYPE'])) {
    // Если это JSON запрос
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'CSRF token mismatch']);
        exit;
    }
    $_POST = $input;
} else if (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF token mismatch']);
    exit;
}

$page = $_POST['page'] ?? '';
$field = $_POST['field'] ?? '';
$content = $_POST['content'] ?? '';

if (empty($page) || empty($field)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Проверяем, существует ли запись
    $stmt = $pdo->prepare("SELECT id FROM site_content WHERE page = ? AND field = ?");
    $stmt->execute([$page, $field]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Обновляем существующую запись
        $stmt = $pdo->prepare("UPDATE site_content SET content = ? WHERE page = ? AND field = ?");
        $stmt->execute([$content, $page, $field]);
    } else {
        // Создаем новую запись
        $stmt = $pdo->prepare("INSERT INTO site_content (page, field, content) VALUES (?, ?, ?)");
        $stmt->execute([$page, $field, $content]);
    }
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>