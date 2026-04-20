<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $anonymous = isset($_POST['anonymous']) ? true : false;
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($category) || empty($message)) {
        $_SESSION['error'] = 'All fields are required!';
        header('Location: index.php');
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email address!';
        header('Location: index.php');
        exit();
    }
    
    try {
        $user_id = $_SESSION['user_id'] ?? null;
        $display_name = $anonymous ? 'Anonymous' : $name;
        $display_email = $anonymous ? '' : $email;
        
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, subject, category, message, anonymous, user_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$display_name, $display_email, $subject, $category, $message, $anonymous ? 1 : 0, $user_id]);
        
        $_SESSION['success'] = 'Feedback submitted successfully!';
        header('Location: index.php');
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>