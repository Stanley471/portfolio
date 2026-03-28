<?php
/**
 * Admin Messages List
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/ContactMessage.php';

$pageTitle = 'Messages';

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (ContactMessage::delete($id)) {
        setFlash('success', 'Message deleted successfully.');
    } else {
        setFlash('error', 'Failed to delete message.');
    }
    redirect(SITE_URL . '/admin/messages.php');
}

// Handle archive action
if (isset($_GET['archive']) && is_numeric($_GET['archive'])) {
    $id = (int) $_GET['archive'];
    if (ContactMessage::archive($id)) {
        setFlash('success', 'Message archived.');
    } else {
        setFlash('error', 'Failed to archive message.');
    }
    redirect(SITE_URL . '/admin/messages.php');
}

// Filter by status
$statusFilter = $_GET['status'] ?? null;
$messages = ContactMessage::getAll($statusFilter);

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-4);">
    <h1>Messages</h1>
    
    <div style="display: flex; gap: var(--space-2);">
        <a href="?" class="admin-btn admin-btn-sm <?php echo !$statusFilter ? 'admin-btn-primary' : 'admin-btn-secondary'; ?>">All</a>
        <a href="?status=new" class="admin-btn admin-btn-sm <?php echo $statusFilter === 'new' ? 'admin-btn-primary' : 'admin-btn-secondary'; ?>">New</a>
        <a href="?status=read" class="admin-btn admin-btn-sm <?php echo $statusFilter === 'read' ? 'admin-btn-primary' : 'admin-btn-secondary'; ?>">Read</a>
        <a href="?status=replied" class="admin-btn admin-btn-sm <?php echo $statusFilter === 'replied' ? 'admin-btn-primary' : 'admin-btn-secondary'; ?>">Replied</a>
    </div>
</div>

<div class="admin-section">
    <div class="admin-section-body">
        <?php if (!empty($messages)): ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr style="<?php echo $msg['status'] === 'new' ? 'background: rgba(251, 191, 36, 0.03);' : ''; ?>">
                                <td>
                                    <strong style="color: var(--color-text-primary);"><?php echo e($msg['name']); ?></strong>
                                    <br>
                                    <small style="color: var(--color-text-muted);"><?php echo e($msg['email']); ?></small>
                                </td>
                                <td><?php echo e(truncate($msg['subject'], 50)); ?></td>
                                <td><?php echo e(truncate($msg['message'], 80)); ?></td>
                                <td><?php echo formatDate($msg['created_at'], 'M j, Y H:i'); ?></td>
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
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="<?php echo SITE_URL; ?>/admin/message.php?id=<?php echo $msg['id']; ?>" 
                                           class="admin-btn admin-btn-primary admin-btn-sm admin-btn-icon"
                                           title="View">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <?php if ($msg['status'] !== 'archived'): ?>
                                            <a href="?archive=<?php echo $msg['id']; ?>" 
                                               class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon"
                                               title="Archive">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="21 8 21 21 3 21 3 8"/>
                                                    <rect x="1" y="3" width="22" height="5"/>
                                                    <line x1="10" y1="12" x2="14" y2="12"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $msg['id']; ?>" 
                                           class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon"
                                           title="Delete"
                                           data-confirm="Are you sure you want to delete this message?">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                            </svg>
                                        </a>
                                    </div>
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
                <h3>No messages</h3>
                <p>Messages will appear here when someone contacts you.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
