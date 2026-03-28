<?php
/**
 * Helper Functions
 * 
 * Common utility functions used throughout the application.
 */

require_once __DIR__ . '/config.php';

/**
 * Escape HTML entities to prevent XSS
 * 
 * @param string $text Input text
 * @return string Escaped text
 */
function e(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Get site setting from database
 * 
 * @param string $key Setting key
 * @param string $default Default value if not found
 * @return string Setting value
 */
function getSetting(string $key, string $default = ''): string {
    static $settings = null;
    
    if ($settings === null) {
        try {
            require_once __DIR__ . '/database.php';
            $results = Database::query("SELECT setting_key, setting_value FROM site_settings");
            $settings = array_column($results, 'setting_value', 'setting_key');
        } catch (Exception $e) {
            $settings = [];
        }
    }
    
    return $settings[$key] ?? $default;
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generateCsrfToken(): string {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validate CSRF token
 * 
 * @param string $token Token to validate
 * @return bool True if valid
 */
function validateCsrfToken(string $token): bool {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Get CSRF token input field
 * 
 * @return string HTML input field
 */
function csrfField(): string {
    $token = generateCsrfToken();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
}

/**
 * Create URL-friendly slug
 * 
 * @param string $text Input text
 * @return string Slug
 */
function createSlug(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'unnamed';
}

/**
 * Format date for display
 * 
 * @param string $date Date string
 * @param string $format Output format
 * @return string Formatted date
 */
function formatDate(string $date, string $format = 'M j, Y'): string {
    return date($format, strtotime($date));
}

/**
 * Truncate text to specified length
 * 
 * @param string $text Input text
 * @param int $length Maximum length
 * @param string $suffix Suffix for truncated text
 * @return string Truncated text
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Convert newlines to HTML line breaks
 * 
 * @param string $text Input text
 * @return string HTML formatted text
 */
function nl2brHtml(string $text): string {
    return nl2br(e($text), false);
}

/**
 * Get asset URL
 * 
 * @param string $path Asset path relative to assets folder
 * @return string Full asset URL
 */
function asset(string $path): string {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Get upload URL
 * 
 * @param string $filename Filename
 * @return string Full upload URL
 */
function uploadUrl(string $filename): string {
    return UPLOAD_URL . $filename;
}

/**
 * Redirect to URL
 * 
 * @param string $url URL to redirect to
 * @param int $code HTTP status code
 */
function redirect(string $url, int $code = 302): void {
    header("Location: {$url}", true, $code);
    exit;
}

/**
 * Set flash message
 * 
 * @param string $type Message type (success, error, info, warning)
 * @param string $message Message text
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null Flash message or null
 */
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Check if user is logged in as admin
 * 
 * @return bool True if logged in
 */
function isAdmin(): bool {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

/**
 * Require admin authentication
 * Redirects to login if not authenticated
 */
function requireAdmin(): void {
    if (!isAdmin()) {
        setFlash('error', 'Please log in to access the admin panel.');
        redirect(SITE_URL . '/admin/login.php');
    }
}

/**
 * Validate email address
 * 
 * @param string $email Email to validate
 * @return bool True if valid
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize filename for upload
 * 
 * @param string $filename Original filename
 * @return string Sanitized filename
 */
function sanitizeFilename(string $filename): string {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    $filename = preg_replace('/_{2,}/', '_', $filename);
    return strtolower($filename);
}

/**
 * Generate unique filename
 * 
 * @param string $originalName Original filename
 * @return string Unique filename
 */
function generateUniqueFilename(string $originalName): string {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $basename = sanitizeFilename($basename);
    $unique = bin2hex(random_bytes(8));
    return "{$basename}_{$unique}.{$extension}";
}

/**
 * Handle file upload
 * 
 * @param array $file $_FILES array element
 * @param string $destination Destination directory
 * @return string|false Uploaded filename or false on error
 */
function handleUpload(array $file, string $destination = UPLOAD_PATH): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if (!in_array($file['type'], UPLOAD_ALLOWED_TYPES)) {
        return false;
    }
    
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        return false;
    }
    
    $filename = generateUniqueFilename($file['name']);
    $filepath = $destination . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return false;
}

/**
 * Delete uploaded file
 * 
 * @param string $filename Filename to delete
 * @param string $directory Directory containing file
 * @return bool True if deleted
 */
function deleteUpload(string $filename, string $directory = UPLOAD_PATH): bool {
    if (empty($filename)) {
        return false;
    }
    
    $filepath = $directory . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    
    return false;
}

/**
 * Get client IP address
 * 
 * @return string IP address
 */
function getClientIp(): string {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            return trim($ips[0]);
        }
    }
    return '0.0.0.0';
}

/**
 * Parse tech stack string to array
 * 
 * @param string $techStack Comma-separated technologies
 * @return array Array of technologies
 */
function parseTechStack(string $techStack): array {
    return array_map('trim', explode(',', $techStack));
}

/**
 * Get current page URL
 * 
 * @return string Current URL
 */
function currentUrl(): string {
    return SITE_URL . $_SERVER['REQUEST_URI'];
}

/**
 * Get page class based on current page
 * 
 * @return string CSS class name
 */
function pageClass(): string {
    $uri = trim($_SERVER['REQUEST_URI'], '/');
    $parts = explode('/', $uri);
    $page = $parts[0] ?? 'home';
    return 'page-' . preg_replace('/[^a-z-]/', '', $page);
}
