<?php
/**
 * Admin Projects List
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Project.php';

$pageTitle = 'Projects';

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (Project::delete($id)) {
        setFlash('success', 'Project deleted successfully.');
    } else {
        setFlash('error', 'Failed to delete project.');
    }
    redirect(SITE_URL . '/admin/projects.php');
}

// Handle toggle featured
if (isset($_GET['toggle_featured']) && is_numeric($_GET['toggle_featured'])) {
    $id = (int) $_GET['toggle_featured'];
    if (Project::toggleFeatured($id)) {
        setFlash('success', 'Project featured status updated.');
    } else {
        setFlash('error', 'Failed to update project.');
    }
    redirect(SITE_URL . '/admin/projects.php');
}

// Handle toggle status
if (isset($_GET['toggle_status']) && is_numeric($_GET['toggle_status'])) {
    $id = (int) $_GET['toggle_status'];
    if (Project::toggleStatus($id)) {
        setFlash('success', 'Project status updated.');
    } else {
        setFlash('error', 'Failed to update project.');
    }
    redirect(SITE_URL . '/admin/projects.php');
}

// Get all projects
$projects = Project::getAll(false);

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Projects</h1>
    <a href="<?php echo SITE_URL; ?>/admin/project-form.php" class="admin-btn admin-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Project
    </a>
</div>

<div class="admin-section">
    <div class="admin-section-body">
        <?php if (!empty($projects)): ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td>
                                    <?php if ($project['image']): ?>
                                        <img src="<?php echo uploadUrl($project['image']); ?>" 
                                             alt="" 
                                             style="width: 60px; height: 40px; object-fit: cover; border-radius: var(--radius-sm);">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 40px; background: var(--color-bg-tertiary); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: var(--color-text-muted);">
                                                <path d="M3 3h18v18H3V3zm16 16V5H5v14h14z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/project.php?slug=<?php echo e($project['slug']); ?>" 
                                       target="_blank"
                                       style="color: var(--color-text-primary); font-weight: 500;">
                                        <?php echo e($project['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo e($project['category']); ?></td>
                                <td>
                                    <a href="?toggle_status=<?php echo $project['id']; ?>" 
                                       class="admin-badge <?php echo $project['status'] === 'active' ? 'admin-badge-success' : 'admin-badge-error'; ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="?toggle_featured=<?php echo $project['id']; ?>">
                                        <?php if ($project['is_featured']): ?>
                                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20" style="color: var(--color-accent-primary);">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="color: var(--color-text-muted);">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                            </svg>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo formatDate($project['created_at']); ?></td>
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="<?php echo SITE_URL; ?>/admin/project-form.php?id=<?php echo $project['id']; ?>" 
                                           class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon"
                                           title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="?delete=<?php echo $project['id']; ?>" 
                                           class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon"
                                           title="Delete"
                                           data-confirm="Are you sure you want to delete this project? This action cannot be undone.">
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
                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                </svg>
                <h3>No projects yet</h3>
                <p>Get started by adding your first project.</p>
                <a href="<?php echo SITE_URL; ?>/admin/project-form.php" class="admin-btn admin-btn-primary" style="margin-top: var(--space-4);">
                    Add Project
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
