<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, subject, category, status, created_at, updated_at FROM feedback WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the latest update timestamp (use updated_at if exists, otherwise created_at)
    $updateStmt = $pdo->prepare("SELECT GREATEST(COALESCE(MAX(updated_at), '1970-01-01'), COALESCE(MAX(created_at), '1970-01-01')) as last_update FROM feedback WHERE user_id = ?");
    $updateStmt->execute([$_SESSION['user_id']]);
    $lastUpdate = $updateStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'feedback' => $feedback,
        'last_update' => $lastUpdate['last_update']
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>