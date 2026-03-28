<?php
/**
 * Admin Message View
 */

require_once __DIR__ . '/../includes/models/ContactMessage.php';

$pageTitle = 'View Message';

// Get message ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    setFlash('error', 'Message not found.');
    redirect(SITE_URL . '/admin/messages.php');
}

$message = ContactMessage::getById($id);

if (!$message) {
    setFlash('error', 'Message not found.');
    redirect(SITE_URL . '/admin/messages.php');
}

// Mark as read if new
if ($message['status'] === 'new') {
    ContactMessage::markAsRead($id);
    $message['status'] = 'read';
}

// Handle reply
if (isset($_GET['reply']) && $_GET['reply'] === 'done') {
    ContactMessage::markAsReplied($id);
    $message['status'] = 'replied';
    setFlash('success', 'Message marked as replied.');
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-4);">
    <h1>Message from <?php echo e($message['name']); ?></h1>
    
    <div style="display: flex; gap: var(--space-2);">
        <a href="mailto:<?php echo e($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
           class="admin-btn admin-btn-primary"
           onclick="markReplied()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            Reply via Email
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="admin-btn admin-btn-secondary">
            Back to Messages
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-6);">
    <div class="admin-section">
        <div class="admin-message-view">
            <div class="admin-message-header">
                <div class="admin-message-meta">
                    <h3><?php echo e($message['subject'] ?: 'No Subject'); ?></h3>
                    <p>From: <?php echo e($message['name']); ?> &lt;<?php echo e($message['email']); ?>&gt;</p>
                    <p>Received: <?php echo formatDate($message['created_at'], 'F j, Y \a\t g:i A'); ?></p>
                    <?php if ($message['ip_address']): ?>
                        <p>IP: <?php echo e($message['ip_address']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($message['status'] === 'new'): ?>
                        <span class="admin-badge admin-badge-warning">New</span>
                    <?php elseif ($message['status'] === 'read'): ?>
                        <span class="admin-badge admin-badge-info">Read</span>
                    <?php elseif ($message['status'] === 'replied'): ?>
                        <span class="admin-badge admin-badge-success">Replied</span>
                    <?php else: ?>
                        <span class="admin-badge admin-badge-secondary">Archived</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="admin-message-body">
                <?php echo nl2brHtml($message['message']); ?>
            </div>
        </div>
    </div>
    
    <div>
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Actions</h2>
            </div>
            <div class="admin-section-body">
                <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                    <?php if ($message['status'] !== 'replied'): ?>
                        <a href="mailto:<?php echo e($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
                           class="admin-btn admin-btn-primary"
                           onclick="markReplied()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Reply via Email
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($message['status'] !== 'replied'): ?>
                        <a href="?id=<?php echo $id; ?>&reply=done" class="admin-btn admin-btn-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Mark as Replied
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($message['status'] !== 'archived'): ?>
                        <a href="<?php echo SITE_URL; ?>/admin/messages.php?archive=<?php echo $id; ?>" class="admin-btn admin-btn-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="21 8 21 21 3 21 3 8"/>
                                <rect x="1" y="3" width="22" height="5"/>
                                <line x1="10" y1="12" x2="14" y2="12"/>
                            </svg>
                            Archive
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo SITE_URL; ?>/admin/messages.php?delete=<?php echo $id; ?>" 
                       class="admin-btn admin-btn-danger"
                       data-confirm="Are you sure you want to delete this message?">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        </svg>
                        Delete
                    </a>
                </div>
            </div>
        </div>
        
        <div class="admin-section" style="margin-top: var(--space-6);">
            <div class="admin-section-header">
                <h2>Quick Info</h2>
            </div>
            <div class="admin-section-body">
                <div style="display: flex; flex-direction: column; gap: var(--space-3); font-size: var(--font-size-sm);">
                    <div>
                        <span style="color: var(--color-text-muted);">Name:</span>
                        <p style="color: var(--color-text-primary); margin-top: var(--space-1);"><?php echo e($message['name']); ?></p>
                    </div>
                    <div>
                        <span style="color: var(--color-text-muted);">Email:</span>
                        <p style="margin-top: var(--space-1);">
                            <a href="mailto:<?php echo e($message['email']); ?>"><?php echo e($message['email']); ?></a>
                        </p>
                    </div>
                    <div>
                        <span style="color: var(--color-text-muted);">Received:</span>
                        <p style="color: var(--color-text-primary); margin-top: var(--space-1);">
                            <?php echo formatDate($message['created_at'], 'M j, Y'); ?>
                        </p>
                    </div>
                    <?php if ($message['ip_address']): ?>
                        <div>
                            <span style="color: var(--color-text-muted);">IP Address:</span>
                            <p style="color: var(--color-text-primary); margin-top: var(--space-1); font-family: var(--font-mono);">
                                <?php echo e($message['ip_address']); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markReplied() {
    // Mark as replied when email client opens
    fetch('?id=<?php echo $id; ?>&reply=done', { method: 'GET' });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
