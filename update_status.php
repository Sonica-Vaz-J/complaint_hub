<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = isset($_POST['feedback_id']) ? intval($_POST['feedback_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    // Validate inputs
    if ($feedback_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid feedback ID']);
        exit();
    }

    $valid_statuses = ['pending', 'in-progress', 'resolved'];
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit();
    }

    // Check if admin is logged in
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    try {
        // Update the status in database
        $stmt = $pdo->prepare("UPDATE feedback SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $result = $stmt->execute([$status, $feedback_id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>