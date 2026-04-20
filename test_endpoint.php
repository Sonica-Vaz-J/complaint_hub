<?php
require_once 'config.php';

// Start session for testing
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simulate user login for testing
$_SESSION['user_id'] = 1; // Assuming user ID 1 exists
$_SESSION['username'] = 'testuser';

echo "Testing get_user_feedback.php endpoint...\n";

// Test the endpoint
$url = 'http://localhost/complaint/get_user_feedback.php';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Cookie: ' . session_name() . '=' . session_id() . "\r\n"
    ]
]);

$response = file_get_contents($url, false, $context);
echo "Response: " . $response . "\n";

$data = json_decode($response, true);
if ($data) {
    echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "Feedback count: " . count($data['feedback']) . "\n";
    echo "Last update: " . $data['last_update'] . "\n";
} else {
    echo "Failed to decode JSON response\n";
}
?>