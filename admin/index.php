<?php
/**
 * Admin Dashboard
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Project.php';
require_once __DIR__ . '/../includes/models/ContactMessage.php';
require_once __DIR__ . '/../includes/models/Skill.php';

$pageTitle = 'Dashboard';

// Get statistics
$projectCount = Project::count();
$messageCount = ContactMessage::count();
$unreadCount = ContactMessage::countUnread();
$featuredCount = count(Project::getFeatured());
$skillCount = Skill::count();

// Get recent messages
$recentMessages = ContactMessage::getRecent(5);

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h1>Dashboard</h1>
</div>

<!-- Stats -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
            </svg>
        </div>
        <div class="admin-stat-content">
            <h3><?php echo $projectCount; ?></h3>
            <p>Total Projects</p>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
        <div class="admin-stat-content">
            <h3><?php echo $featuredCount; ?></h3>
            <p>Featured Projects</p>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>
        <div class="admin-stat-content">
            <h3><?php echo $skillCount; ?></h3>
            <p>Skills</p>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <div class="admin-stat-content">
            <h3><?php echo $messageCount; ?></h3>
            <p>Total Messages</p>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
            </svg>
        </div>
        <div class="admin-stat-content">
            <h3><?php echo $unreadCount; ?></h3>
            <p>Unread Messages</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-6);">
    <!-- Recent Messages -->
    <div class="admin-section">
        <div class="admin-section-header">
            <h2>Recent Messages</h2>
            <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="admin-btn admin-btn-secondary admin-btn-sm">
                View All
            </a>
        </div>
        <div class="admin-section-body">
            <?php if (!empty($recentMessages)): ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMessages as $msg): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo SITE_URL; ?>/admin/message.php?id=<?php echo $msg['id']; ?>" style="color: var(--color-text-primary); font-weight: 500;">
                                            <?php echo e($msg['name']); ?>
                                        </a>
                                        <br>
                                        <small style="color: var(--color-text-muted);"><?php echo e($msg['email']); ?></small>
                                    </td>
                                    <td><?php echo e(truncate($msg['subject'], 40)); ?></td>
                                    <td><?php echo formatDate($msg['created_at']); ?></td>
                                    <td>
                                        <?php if ($msg['status'] === 'new'): ?>
                                            <span class="admin-badge admin-badge-warning">New</span>
                                        <?php elseif ($msg['status'] === 'read'): ?>
                                            <span class="admin-badge admin-badge-info">Read</span>
                                        <?php elseif ($msg['status'] === 'replied'): ?>
                                            <span class="admin-badge admin-badge-success">Replied</span>
                                        <?php else: ?>
                                            <span class="admin-badge admin-badge-secondary">Archived</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="admin-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <h3>No messages yet</h3>
                    <p>Messages will appear here when someone contacts you.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="admin-section">
        <div class="admin-section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="admin-section-body">
            <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                <a href="<?php echo SITE_URL; ?>/admin/project-form.php" class="admin-btn admin-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add New Project
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/projects.php" class="admin-btn admin-btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Manage Projects
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/skills.php" class="admin-btn admin-btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    Manage Skills
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="admin-btn admin-btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    View Messages
                </a>
                <a href="<?php echo SITE_URL; ?>/" class="admin-btn admin-btn-secondary" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/>
                    </svg>
                    View Site
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
