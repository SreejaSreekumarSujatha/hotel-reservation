<?php
// Global configuration file

// ⚠️ Adjust these values for your project
define('BASE_URL', 'http://localhost/php_projects_github/hotel-reservation/');

// Email settings (optional for PHPMailer)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your_email@gmail.com');
define('MAIL_PASSWORD', 'your_app_password');
define('MAIL_FROM', 'your_email@gmail.com');
define('MAIL_FROM_NAME', 'Hotel Reservation System');

// Date & timezone
date_default_timezone_set('Europe/Berlin');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
