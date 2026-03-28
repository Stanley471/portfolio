<?php
/**
 * Admin Skills List
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Skill.php';

$pageTitle = 'Skills';

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (Skill::delete($id)) {
        setFlash('success', 'Skill deleted successfully.');
    } else {
        setFlash('error', 'Failed to delete skill.');
    }
    redirect(SITE_URL . '/admin/skills.php');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    if (Skill::toggleActive($id)) {
        setFlash('success', 'Skill status updated.');
    } else {
        setFlash('error', 'Failed to update skill.');
    }
    redirect(SITE_URL . '/admin/skills.php');
}

// Get all skills
$skills = Skill::getAll(false);

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Skills</h1>
    <a href="<?php echo SITE_URL; ?>/admin/skill-form.php" class="admin-btn admin-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Skill
    </a>
</div>

<div class="admin-section">
    <div class="admin-section-body">
        <?php if (!empty($skills)): ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Proficiency</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?php echo $skill['display_order']; ?></td>
                                <td>
                                    <strong style="color: var(--color-text-primary);"><?php echo e($skill['name']); ?></strong>
                                </td>
                                <td><?php echo e($skill['category']); ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: var(--space-2);">
                                        <div style="flex: 1; max-width: 100px; height: 6px; background: var(--color-bg-tertiary); border-radius: 3px; overflow: hidden;">
                                            <div style="width: <?php echo $skill['proficiency']; ?>%; height: 100%; background: var(--color-accent-primary); border-radius: 3px;"></div>
                                        </div>
                                        <span style="font-size: var(--font-size-xs); color: var(--color-text-muted);"><?php echo $skill['proficiency']; ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="?toggle=<?php echo $skill['id']; ?>" 
                                       class="admin-badge <?php echo $skill['is_active'] ? 'admin-badge-success' : 'admin-badge-error'; ?>">
                                        <?php echo $skill['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="<?php echo SITE_URL; ?>/admin/skill-form.php?id=<?php echo $skill['id']; ?>" 
                                           class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon"
                                           title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="?delete=<?php echo $skill['id']; ?>" 
                                           class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon"
                                           title="Delete"
                                           data-confirm="Are you sure you want to delete this skill?">
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
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <h3>No skills yet</h3>
                <p>Add your technical skills to display them on your portfolio.</p>
                <a href="<?php echo SITE_URL; ?>/admin/skill-form.php" class="admin-btn admin-btn-primary" style="margin-top: var(--space-4);">
                    Add Skill
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
