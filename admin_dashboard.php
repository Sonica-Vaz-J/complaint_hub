<?php 
require_once 'config.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Fetch all feedback
$stmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC");
$allFeedback = $stmt->fetchAll();

// Count by status
$statusStmt = $pdo->query("SELECT status, COUNT(*) as count FROM feedback GROUP BY status");
$statusCounts = $statusStmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ComplaintHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #1e40af;
            --danger: #ef4444;
        }
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .bg-danger { background-color: var(--danger); }
        .text-primary { color: var(--primary); }
        .hover\:bg-secondary:hover { background-color: var(--secondary); }
        
        /* Status dropdown styling */
        .status-select {
            transition: all 0.2s ease;
        }
        .status-select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        .status-select.bg-yellow-100 { background-color: #fef3c7; color: #92400e; }
        .status-select.bg-blue-100 { background-color: #dbeafe; color: #1e40af; }
        .status-select.bg-green-100 { background-color: #dcfce7; color: #166534; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-headset text-primary text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-gray-800">ComplaintHub</span>
                    <span class="ml-4 text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Admin</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                    <a href="logout.php" class="bg-danger hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-primary">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Total Feedback</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($allFeedback) ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Pending</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $statusCounts['pending'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-primary">
                        <i class="fas fa-spinner text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $statusCounts['in-progress'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Resolved</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $statusCounts['resolved'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-900">All Feedback</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($allFeedback)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block text-gray-400"></i>
                                    No feedback yet
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allFeedback as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= intval($item['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <?php if ($item['anonymous']): ?>
                                        <span class="ml-2 text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded">Anonymous</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate"><?= htmlspecialchars($item['subject']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['category']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select class="status-select px-3 py-1 text-xs leading-5 font-semibold rounded-full border-0 cursor-pointer" 
                                            data-feedback-id="<?= intval($item['id']) ?>"
                                            data-original-value="<?= isset($item['status']) ? strtolower($item['status']) : 'pending' ?>"
                                            onchange="updateStatus(this)">
                                        <option value="pending" <?= (isset($item['status']) && strtolower($item['status']) === 'pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="in-progress" <?= (isset($item['status']) && strtolower($item['status']) === 'in-progress') ? 'selected' : '' ?>>In Progress</option>
                                        <option value="resolved" <?= (isset($item['status']) && strtolower($item['status']) === 'resolved') ? 'selected' : '' ?>>Resolved</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="<?= htmlspecialchars($item['message']) ?>">
                                    <?= htmlspecialchars(substr($item['message'], 0, 50)) ?>...
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8 mt-12">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; 2024 ComplaintHub. All rights reserved. | Admin Dashboard</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function updateStatus(selectElement) {
            const feedbackId = selectElement.getAttribute('data-feedback-id');
            const newStatus = selectElement.value;
            const originalValue = selectElement.getAttribute('data-original-value') || selectElement.value;
            
            // Store original value for potential rollback
            selectElement.setAttribute('data-original-value', originalValue);
            
            // Disable select during update
            selectElement.disabled = true;
            selectElement.style.opacity = '0.6';
            
            // Update visual styling based on status
            selectElement.className = 'status-select px-3 py-1 text-xs leading-5 font-semibold rounded-full border-0 cursor-pointer';
            
            switch(newStatus) {
                case 'pending':
                    selectElement.classList.add('bg-yellow-100', 'text-yellow-800');
                    break;
                case 'in-progress':
                    selectElement.classList.add('bg-blue-100', 'text-blue-800');
                    break;
                case 'resolved':
                    selectElement.classList.add('bg-green-100', 'text-green-800');
                    break;
            }
            
            // Send AJAX request to update status
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `feedback_id=${feedbackId}&status=${newStatus}`,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable select
                selectElement.disabled = false;
                selectElement.style.opacity = '1';
                
                if (data.success) {
                    // Show success message
                    showMessage('Status updated successfully!', 'success');
                    // Update original value
                    selectElement.setAttribute('data-original-value', newStatus);
                    // Refresh stats after a short delay
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage('Error updating status: ' + data.message, 'error');
                    // Revert to original value
                    selectElement.value = originalValue;
                    updateStatusVisual(selectElement);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Re-enable select
                selectElement.disabled = false;
                selectElement.style.opacity = '1';
                showMessage('Error updating status. Please try again.', 'error');
                // Revert to original value
                selectElement.value = originalValue;
                updateStatusVisual(selectElement);
            });
        }
        
        function updateStatusVisual(selectElement) {
            const status = selectElement.value;
            selectElement.className = 'status-select px-3 py-1 text-xs leading-5 font-semibold rounded-full border-0 cursor-pointer';
            
            switch(status) {
                case 'pending':
                    selectElement.classList.add('bg-yellow-100', 'text-yellow-800');
                    break;
                case 'in-progress':
                    selectElement.classList.add('bg-blue-100', 'text-blue-800');
                    break;
                case 'resolved':
                    selectElement.classList.add('bg-green-100', 'text-green-800');
                    break;
            }
        }
        
        function showMessage(message, type) {
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.status-message');
            existingMessages.forEach(msg => msg.remove());
            
            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = `status-message fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
        // Initialize status dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('.status-select');
            statusSelects.forEach(select => {
                updateStatusVisual(select);
            });
        });
    </script>
</body>
</html>