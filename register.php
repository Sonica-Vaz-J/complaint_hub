<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['register_error'] = 'All fields are required!';
        header('Location: index.php');
        exit();
    }
    
    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'Password must be at least 6 characters!';
        header('Location: index.php');
        exit();
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = 'Passwords do not match!';
        header('Location: index.php');
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Invalid email address!';
        header('Location: index.php');
        exit();
    }
    
    try {
        // Check if email already exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            $_SESSION['register_error'] = 'Email already registered!';
            header('Location: index.php');
            exit();
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$email, $email, $hashedPassword]);
        
        $_SESSION['success'] = 'Registration successful! Please login with your credentials.';
        header('Location: index.php');
        exit();
    } catch(PDOException $e) {
        $_SESSION['register_error'] = 'Database error: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
