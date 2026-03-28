<?php
/**
 * Admin Logout
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Admin.php';

if (isAdmin()) {
    Admin::logout();
}

setFlash('success', 'You have been logged out successfully.');
redirect(SITE_URL . '/admin/login.php');
