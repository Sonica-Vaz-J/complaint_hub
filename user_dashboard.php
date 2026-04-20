<?php 
require_once 'config.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM feedback WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$userFeedback = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Feedback - ComplaintHub</title>
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-headset text-primary text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">ComplaintHub</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</span>
                    <a href="logout.php" class="bg-danger hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Feedback History</h1>
                    <p class="text-sm text-gray-600 mt-1">Track the status of your submitted feedback • <span class="text-primary font-medium">Status updates by admins</span></p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refreshFeedback()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                    <a href="index.php#submit-section" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-md text-sm">
                        <i class="fas fa-plus mr-1"></i> New Feedback
                    </a>
                </div>
            </div>
            
            <?php if (empty($userFeedback)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No feedback submitted yet</h3>
                    <a href="index.php#submit-section" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-md inline-block">
                        Submit First Feedback
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($userFeedback as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= intval($item['id']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate"><?= htmlspecialchars($item['subject']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['category']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        $status = isset($item['status']) ? strtolower($item['status']) : 'pending';
                                        switch($status) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'in-progress': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'in progress': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'resolved': echo 'bg-green-100 text-green-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= htmlspecialchars(ucwords(str_replace('-', ' ', $status))) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= isset($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : 'N/A' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        function refreshFeedback() {
            const refreshBtn = document.querySelector('button[onclick="refreshFeedback()"]');
            const originalText = refreshBtn.innerHTML;
            
            // Show loading state
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...';
            refreshBtn.disabled = true;
            
            // Fetch updated feedback data
            fetch('get_user_feedback.php', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const previousLastUpdate = localStorage.getItem('lastFeedbackUpdate');
                    const currentLastUpdate = data.last_update;
                    
                    updateFeedbackTable(data.feedback);
                    
                    // Store the new timestamp
                    localStorage.setItem('lastFeedbackUpdate', currentLastUpdate);
                    
                    // Check if there were actual updates
                    if (previousLastUpdate && previousLastUpdate !== currentLastUpdate) {
                        showMessage('Status updated by admin!', 'success');
                    } else {
                        showMessage('Feedback refreshed!', 'success');
                    }
                } else {
                    showMessage('Error refreshing feedback: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error refreshing feedback. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                refreshBtn.innerHTML = originalText;
                refreshBtn.disabled = false;
            });
        }
        
        function updateFeedbackTable(feedbackData) {
            const tbody = document.querySelector('tbody');
            
            if (feedbackData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 block text-gray-400"></i>
                            No feedback submitted yet
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            feedbackData.forEach(item => {
                const status = item.status ? item.status.toLowerCase() : 'pending';
                let statusClass = '';
                switch(status) {
                    case 'pending': statusClass = 'bg-yellow-100 text-yellow-800'; break;
                    case 'in-progress': statusClass = 'bg-blue-100 text-blue-800'; break;
                    case 'in progress': statusClass = 'bg-blue-100 text-blue-800'; break;
                    case 'resolved': statusClass = 'bg-green-100 text-green-800'; break;
                    default: statusClass = 'bg-gray-100 text-gray-800';
                }
                
                const displayStatus = item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1).replace('-', ' ') : 'Pending';
                const createdDate = item.created_at ? new Date(item.created_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                }) : 'N/A';
                const updatedDate = item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                }) : createdDate;
                
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.id}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">${escapeHtml(item.subject)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(item.category)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${displayStatus}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${updatedDate}</td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
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
            
            // Remove message after 3 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 3000);
        }
        
        // Initialize last update timestamp on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Store initial timestamp for comparison
            fetch('get_user_feedback.php', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.last_update) {
                    localStorage.setItem('lastFeedbackUpdate', data.last_update);
                }
            })
            .catch(error => console.error('Error initializing timestamp:', error));
        });
        
        // Auto-refresh every 30 seconds
        setInterval(refreshFeedback, 30000);
    </script>
</body>
</html>