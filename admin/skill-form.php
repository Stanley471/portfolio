<?php
/**
 * Admin Skill Form (Add/Edit)
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Skill.php';

$pageTitle = 'Add Skill';
$skill = null;
$errors = [];

// Check if editing
$editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($editId > 0) {
    $skill = Skill::getById($editId);
    if ($skill) {
        $pageTitle = 'Edit Skill';
    } else {
        setFlash('error', 'Skill not found.');
        redirect(SITE_URL . '/admin/skills.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid form submission.';
    } else {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'proficiency' => (int) ($_POST['proficiency'] ?? 80),
            'display_order' => (int) ($_POST['display_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Validation
        if (empty($data['name'])) {
            $errors[] = 'Skill name is required.';
        }
        if (empty($data['category'])) {
            $errors[] = 'Category is required.';
        }
        if ($data['proficiency'] < 0 || $data['proficiency'] > 100) {
            $errors[] = 'Proficiency must be between 0 and 100.';
        }
        
        // Check for duplicate name (excluding current record when editing)
        if (empty($errors)) {
            $existing = Skill::getByName($data['name']);
            if ($existing && (!$editId || $existing['id'] != $editId)) {
                $errors[] = 'A skill with this name already exists.';
            }
        }
        
        // Save to database
        if (empty($errors)) {
            try {
                if ($editId > 0) {
                    Skill::update($editId, $data);
                    setFlash('success', 'Skill updated successfully.');
                } else {
                    $newId = Skill::create($data);
                    setFlash('success', 'Skill created successfully.');
                }
                redirect(SITE_URL . '/admin/skills.php');
            } catch (Exception $e) {
                error_log('Skill save error: ' . $e->getMessage());
                $errors[] = 'An error occurred while saving the skill.';
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error" style="margin-bottom: var(--space-6);">
        <?php foreach ($errors as $error): ?>
            <p><?php echo e($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="admin-section">
    <div class="admin-section-body">
        <form method="POST" action="" data-validate>
            <?php echo csrfField(); ?>
            
            <div class="admin-form-grid">
                <div class="form-group">
                    <label for="name" class="form-label">Skill Name <span>*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-input" 
                           value="<?php echo e($_POST['name'] ?? $skill['name'] ?? ''); ?>"
                           required
                           maxlength="100"
                           placeholder="e.g., PHP, Laravel, JavaScript">
                </div>
                
                <div class="form-group">
                    <label for="category" class="form-label">Category <span>*</span></label>
                    <select id="category" name="category" class="form-input" required>
                        <option value="">Select Category</option>
                        <option value="Backend" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Backend' ? 'selected' : ''; ?>>Backend</option>
                        <option value="Frontend" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Frontend' ? 'selected' : ''; ?>>Frontend</option>
                        <option value="Framework" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Framework' ? 'selected' : ''; ?>>Framework</option>
                        <option value="Database" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Database' ? 'selected' : ''; ?>>Database</option>
                        <option value="Tools" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Tools' ? 'selected' : ''; ?>>Tools</option>
                        <option value="Other" <?php echo ($_POST['category'] ?? $skill['category'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="proficiency" class="form-label">Proficiency (%)</label>
                    <div style="display: flex; align-items: center; gap: var(--space-3);">
                        <input type="range" 
                               id="proficiency" 
                               name="proficiency" 
                               min="0" 
                               max="100" 
                               value="<?php echo $_POST['proficiency'] ?? $skill['proficiency'] ?? 80; ?>"
                               style="flex: 1;"
                               oninput="document.getElementById('proficiencyValue').textContent = this.value + '%'">
                        <span id="proficiencyValue" style="min-width: 45px; font-family: var(--font-mono); font-size: var(--font-size-sm);">
                            <?php echo $_POST['proficiency'] ?? $skill['proficiency'] ?? 80; ?>%
                        </span>
                    </div>
                    <small style="color: var(--color-text-muted);">Your skill level from 0% to 100%</small>
                </div>
                
                <div class="form-group">
                    <label for="display_order" class="form-label">Display Order</label>
                    <input type="number" 
                           id="display_order" 
                           name="display_order" 
                           class="form-input" 
                           value="<?php echo $_POST['display_order'] ?? $skill['display_order'] ?? 0; ?>"
                           min="0">
                    <small style="color: var(--color-text-muted);">Lower numbers display first</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Options</label>
                    <label style="display: flex; align-items: center; gap: var(--space-2);">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               <?php echo ($_POST['is_active'] ?? $skill['is_active'] ?? 1) ? 'checked' : ''; ?>>
                        <span>Active (visible on portfolio)</span>
                    </label>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <?php echo $editId > 0 ? 'Update Skill' : 'Create Skill'; ?>
                </button>
                <a href="<?php echo SITE_URL; ?>/admin/skills.php" class="admin-btn admin-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
