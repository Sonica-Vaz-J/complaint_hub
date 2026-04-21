📌 Complaint Hub System

A simple Feedback & Complaint Management System built using PHP, MySQL, HTML, CSS.

It allows users to submit complaints and admins to manage them.


🚀 Features

👤 User

->Register & Login

->Submit complaints/feedback

->View submitted complaints

->Track complaint status


🛠️ Admin

->Admin login

->View all complaints

->Update complaint status (Pending / Resolved)

->Manage users


🏗️ Project Structure

complaint_hub/

├── admin_dashboard.php      # Admin panel

├── user_dashboard.php       # User panel

├── index.php                # Login page

├── register.php             # User registration

├── login.php                # Login logic

├── logout.php               # Logout

├── submit_feedback.php      # Submit complaint

├── get_user_feedback.php    # Fetch user complaints

├── update_status.php        # Admin updates complaint status

├── config.php               # Database connection

├── complainthub.sql         # Database file

└── test_endpoint.php        # Testing API

⚙️ Installation & Setup

1️⃣ Install XAMPP

Download and install XAMPP

Start:

Apache ✅

MySQL ✅

2️⃣ Move Project Folder

Place your project inside:
C:\xampp\htdocs\
Final path:
C:\xampp\htdocs\complaint_hub

3️⃣ Setup Database

Open: http://localhost/phpmyadmin

Create database: complainthub

Click Import

Upload:complainthub.sql

4️⃣ Configure Database Connection

Edit config.php:

<?php

$conn = new mysqli("localhost", "root", "", "complainthub");

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
    
}

?>


5️⃣ Run the Project

Open browser: http://localhost/complaint_hub/


🔄 How It Works

User registers and logs in

Submits complaint via form

Data stored in MySQL database

Admin views complaints

Admin updates complaint status


🧑‍💻 Technologies Used

PHP (Backend),
MySQL (Database),
HTML, CSS (Frontend),
XAMPP (Local Server)
