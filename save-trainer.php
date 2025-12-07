<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
check_auth();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF token mismatch']);
    exit;
}

try {
    if (isset($input['id'])) {
        // Обновление существующего тренера
        $stmt = $pdo->prepare("UPDATE trainers SET full_name = ?, position = ?, facts = ? WHERE id = ?");
        $stmt->execute([$input['full_name'], $input['position'], $input['facts'], $input['id']]);
    } else {
        // Добавление нового тренера
        $stmt = $pdo->prepare("INSERT INTO trainers (full_name, position, facts) VALUES (?, ?, ?)");
        $stmt->execute([$input['full_name'], $input['position'], $input['facts']]);
    }
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>