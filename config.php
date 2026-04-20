<?php
$host = 'localhost';
$dbname = 'complainthub';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tailwind color constants
define('PRIMARY_COLOR', '#3b82f6');
define('SECONDARY_COLOR', '#1e40af');
define('DANGER_COLOR', '#ef4444');
?>