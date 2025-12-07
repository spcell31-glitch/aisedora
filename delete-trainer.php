<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
check_auth();

header('Content-Type: application/json');

if (!isset($_GET['id']) || !isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("DELETE FROM trainers WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>