<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint/Feedback Management System</title>
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
        .text-danger { color: var(--danger); }
        .hover\:bg-secondary:hover { background-color: var(--secondary); }
        .border-primary { border-color: var(--primary); }
        .focus\:ring-primary:focus { --tw-ring-color: var(--primary); }
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
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                        <a href="user_dashboard.php" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-list mr-1"></i> My Feedback
                        </a>
                        <a href="logout.php" class="bg-danger hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    <?php else: ?>
                        <button id="login-btn" onclick="showLoginModal()" class="bg-primary hover:bg-secondary text-black px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </button>
                        <button id="register-btn" onclick="showRegisterModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Modal -->
    <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Tab Navigation -->
                <div class="flex border-b mb-6">
                    <button id="user-login-tab" onclick="switchLoginTab('user')" class="flex-1 py-2 px-4 font-medium text-sm text-primary border-b-2 border-primary">User Login</button>
                    <button id="admin-login-tab" onclick="switchLoginTab('admin')" class="flex-1 py-2 px-4 font-medium text-sm text-gray-500 hover:text-gray-700 border-b-2 border-transparent">Admin Login</button>
                </div>
                
                <!-- User Login Form -->
                <form id="user-login-form" action="login.php" method="POST" class="space-y-4">
                    <input type="hidden" name="role" value="user">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition">
                        Login as User
                    </button>
                </form>

                <!-- Admin Login Form -->
                <form id="admin-login-form" action="login.php" method="POST" class="hidden space-y-4">
                    <input type="hidden" name="role" value="admin">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Username *</label>
                        <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="admin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition">
                        Login as Admin
                    </button>
                </form>

                <button type="button" onclick="closeLoginModal()" class="w-full mt-4 text-gray-600 hover:text-gray-800 text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="register-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Create New Account</h2>
                
                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= htmlspecialchars($_SESSION['register_error']) ?>
                        <?php unset($_SESSION['register_error']); ?>
                    </div>
                <?php endif; ?>
                
                <form id="register-form" action="register.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="fullname" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••" minlength="6">
                        <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition">
                        Create Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600 mt-4">
                    Already have an account? 
                    <button type="button" onclick="switchModals('login')" class="text-primary font-medium hover:underline">
                        Login here
                    </button>
                </p>

                <button type="button" onclick="closeRegisterModal()" class="w-full mt-4 text-gray-600 hover:text-gray-800 text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4 text-black">
                <i class="fas fa-comments mr-3"></i>Complaint & Feedback Hub
            </h1>
            <p class="text-xl text-black mb-8">Your voice matters. Share your feedback with us.</p>
            <div class="flex gap-4 justify-center flex-wrap">
                <button onclick="scrollToSubmit()" class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition">
                    <i class="fas fa-pen mr-2"></i>Submit Feedback
                </button>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button onclick="showLoginModal()" class="border-2 border-white text-primary hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login to View
                    </button>
                <?php else: ?>
                    <a href="user_dashboard.php" class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg transition inline-block">
                        <i class="fas fa-list mr-2"></i>View My Feedback
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Success Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-6xl mx-auto px-4 py-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span><?= htmlspecialchars($_SESSION['success']) ?></span>
                <?php unset($_SESSION['success']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Features Section -->
    <section class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Why Choose Us?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-lock text-primary text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Anonymous Feedback</h3>
                <p class="text-gray-600">Share your concerns anonymously if you prefer complete privacy.</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-chart-bar text-primary text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Track Progress</h3>
                <p class="text-gray-600">Monitor the status of your feedback in real-time.</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-headset text-primary text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Expert Support</h3>
                <p class="text-gray-600">Our team reviews every complaint with utmost care.</p>
            </div>
        </div>
    </section>

    <!-- Feedback Form Section -->
    <section id="submit-section" class="bg-gray-100 py-16">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 mb-2 text-center">Share Your Feedback</h2>
            <p class="text-gray-600 text-center mb-8">Help us improve by sharing your valuable feedback.</p>
            
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form id="feedback-form" action="submit_feedback.php" method="POST" class="space-y-6" novalidate>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <input type="text" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Brief subject">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select a category</option>
                                <option value="complaint">Complaint</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="appreciation">Appreciation</option>
                                <option value="bug_report">Bug Report</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent resize-none" placeholder="Please describe your feedback in detail..."></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="anonymous" name="anonymous" class="h-4 w-4 text-primary rounded">
                        <label for="anonymous" class="ml-2 text-sm text-gray-700">Submit as Anonymous</label>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-md transition flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Feedback
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; 2024 ComplaintHub. All rights reserved. | Your feedback helps us improve!</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Helper functions for form validation
        function showError(element, message) {
            // Remove existing error message
            const existingError = element.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }

            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            element.parentNode.appendChild(errorDiv);

            // Add error styling to input
            element.classList.add('border-red-500');
            element.classList.remove('border-gray-300');
        }

        function clearErrors(form) {
            // Remove all error messages
            const errorMessages = form.querySelectorAll('.error-message');
            errorMessages.forEach(error => error.remove());

            // Remove error styling from inputs
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');
            });
        }
        function showLoginModal() {
            document.getElementById('login-modal').classList.remove('hidden');
        }

        function closeLoginModal() {
            document.getElementById('login-modal').classList.add('hidden');
        }

        function showRegisterModal() {
            document.getElementById('register-modal').classList.remove('hidden');
        }

        function closeRegisterModal() {
            document.getElementById('register-modal').classList.add('hidden');
        }

        function switchModals(modal) {
            if (modal === 'login') {
                closeRegisterModal();
                showLoginModal();
            } else if (modal === 'register') {
                closeLoginModal();
                showRegisterModal();
            }
        }

        function switchLoginTab(tab) {
            const userForm = document.getElementById('user-login-form');
            const adminForm = document.getElementById('admin-login-form');
            const userTab = document.getElementById('user-login-tab');
            const adminTab = document.getElementById('admin-login-tab');

            if (tab === 'user') {
                userForm.classList.remove('hidden');
                adminForm.classList.add('hidden');
                userTab.classList.add('text-primary', 'border-primary');
                userTab.classList.remove('text-gray-500', 'border-transparent');
                adminTab.classList.add('text-gray-500', 'border-transparent');
                adminTab.classList.remove('text-primary', 'border-primary');
            } else {
                adminForm.classList.remove('hidden');
                userForm.classList.add('hidden');
                adminTab.classList.add('text-primary', 'border-primary');
                adminTab.classList.remove('text-gray-500', 'border-transparent');
                userTab.classList.add('text-gray-500', 'border-transparent');
                userTab.classList.remove('text-primary', 'border-primary');
            }
        }

        function scrollToSubmit() {
            document.getElementById('submit-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Close modal when clicking outside
        document.getElementById('login-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        document.getElementById('register-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegisterModal();
            }
        });

        // Register form validation
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const fullname = this.querySelector('input[name="fullname"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const password = this.querySelector('input[name="password"]').value;
            const confirmPassword = this.querySelector('input[name="confirm_password"]').value;

            // Clear previous error messages
            clearErrors(this);

            let hasErrors = false;

            if (!fullname) {
                showError(this.querySelector('input[name="fullname"]'), 'Full name is required');
                hasErrors = true;
            }

            if (!email) {
                showError(this.querySelector('input[name="email"]'), 'Email is required');
                hasErrors = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError(this.querySelector('input[name="email"]'), 'Please enter a valid email address');
                hasErrors = true;
            }

            if (!password) {
                showError(this.querySelector('input[name="password"]'), 'Password is required');
                hasErrors = true;
            } else if (password.length < 6) {
                showError(this.querySelector('input[name="password"]'), 'Password must be at least 6 characters');
                hasErrors = true;
            }

            if (!confirmPassword) {
                showError(this.querySelector('input[name="confirm_password"]'), 'Please confirm your password');
                hasErrors = true;
            } else if (password !== confirmPassword) {
                showError(this.querySelector('input[name="confirm_password"]'), 'Passwords do not match');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                return false;
            }
        });

        // Feedback form validation
        document.getElementById('feedback-form').addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const subject = this.querySelector('input[name="subject"]').value.trim();
            const category = this.querySelector('select[name="category"]').value;
            const message = this.querySelector('textarea[name="message"]').value.trim();

            // Clear previous error messages
            clearErrors(this);

            let hasErrors = false;

            if (!name) {
                showError(this.querySelector('input[name="name"]'), 'Full name is required');
                hasErrors = true;
            }

            if (!email) {
                showError(this.querySelector('input[name="email"]'), 'Email is required');
                hasErrors = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError(this.querySelector('input[name="email"]'), 'Please enter a valid email address');
                hasErrors = true;
            }

            if (!subject) {
                showError(this.querySelector('input[name="subject"]'), 'Subject is required');
                hasErrors = true;
            }

            if (!category) {
                showError(this.querySelector('select[name="category"]'), 'Please select a category');
                hasErrors = true;
            }

            if (!message) {
                showError(this.querySelector('textarea[name="message"]'), 'Message is required');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>