<?php
/**
 * Configuration File
 * 
 * Central configuration for the portfolio application.
 * Contains database credentials, site settings, and constants.
 */

// Prevent direct access
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Database Configuration
// Modify these settings according to your local environment
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');           // Default XAMPP password is empty
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('SITE_URL', 'http://localhost/portfolio');
define('SITE_NAME', 'Portfolio');
define('SITE_VERSION', '1.0.0');

// Admin Settings
define('ADMIN_SESSION_NAME', 'portfolio_admin_session');
define('SESSION_LIFETIME', 7200); // 2 hours in seconds

// File Upload Settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('UPLOAD_PATH', BASE_PATH . '/assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

// Security Settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('HASH_COST', 12);

// Contact Settings
define('CONTACT_EMAIL', 'contact@yourdomain.com');
define('WHATSAPP_NUMBER', '+1234567890');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(ADMIN_SESSION_NAME);
    session_start();
}
