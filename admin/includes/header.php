<?php
/**
 * Admin Header Component
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)));
}

require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/includes/models/Admin.php';
require_once BASE_PATH . '/includes/models/ContactMessage.php';

// Check authentication
requireAdmin();

// Get unread message count
$unreadCount = ContactMessage::countUnread();

// Set default page title
if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | Admin Panel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo asset('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
    
    <?php if (isset($extraStyles)): ?>
        <?php echo $extraStyles; ?>
    <?php endif; ?>
</head>
<body class="admin-page">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <a href="<?php echo SITE_URL; ?>/admin/" class="admin-logo">
                <span class="admin-logo-code">&lt;/&gt;</span>
                <span>Admin</span>
            </a>
            <button class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="Toggle sidebar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <nav class="admin-nav">
            <ul class="admin-nav-list">
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/admin/" class="admin-nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/admin/projects.php" class="admin-nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                        Projects
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/admin/skills.php" class="admin-nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['skills.php', 'skill-form.php']) ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        Skills
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="admin-nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Messages
                        <?php if ($unreadCount > 0): ?>
                            <span class="admin-nav-badge"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            
            <div class="admin-nav-divider"></div>
            
            <ul class="admin-nav-list">
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/" class="admin-nav-link" target="_blank">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/>
                        </svg>
                        View Site
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="admin-nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Admin Main Content -->
    <div class="admin-main">
        <!-- Admin Header -->
        <header class="admin-header">
            <button class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            
            <div class="admin-header-actions">
                <span class="admin-user">
                    <?php echo e($_SESSION['admin_full_name'] ?? 'Admin'); ?>
                </span>
            </div>
        </header>
        
        <!-- Flash Messages -->
        <?php $flash = getFlash(); if ($flash): ?>
            <div class="admin-flash admin-flash-<?php echo $flash['type']; ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Page Content -->
        <div class="admin-content">
