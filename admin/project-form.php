<?php
/**
 * Admin Project Form (Add/Edit)
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Project.php';

$pageTitle = 'Add Project';
$project = null;
$errors = [];

// Check if editing
$editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($editId > 0) {
    $project = Project::getById($editId);
    if ($project) {
        $pageTitle = 'Edit Project';
    } else {
        setFlash('error', 'Project not found.');
        redirect(SITE_URL . '/admin/projects.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid form submission.';
    } else {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'full_description' => trim($_POST['full_description'] ?? ''),
            'problem_statement' => trim($_POST['problem_statement'] ?? ''),
            'features' => trim($_POST['features'] ?? ''),
            'challenges' => trim($_POST['challenges'] ?? ''),
            'tech_stack' => trim($_POST['tech_stack'] ?? ''),
            'demo_url' => trim($_POST['demo_url'] ?? ''),
            'github_url' => trim($_POST['github_url'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        // Validation
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['category'])) {
            $errors[] = 'Category is required.';
        }
        if (empty($data['short_description'])) {
            $errors[] = 'Short description is required.';
        }
        if (empty($data['full_description'])) {
            $errors[] = 'Full description is required.';
        }
        if (empty($data['tech_stack'])) {
            $errors[] = 'Tech stack is required.';
        }
        
        // Handle image upload
        $imageName = $project['image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $uploaded = handleUpload($_FILES['image']);
            if ($uploaded) {
                // Delete old image if exists
                if ($imageName) {
                    deleteUpload($imageName);
                }
                $imageName = $uploaded;
            } else {
                $errors[] = 'Failed to upload image. Please check file type and size.';
            }
        }
        
        // Delete image if requested
        if (isset($_POST['delete_image']) && $imageName) {
            deleteUpload($imageName);
            $imageName = null;
        }
        
        $data['image'] = $imageName;
        
        // Save to database
        if (empty($errors)) {
            try {
                if ($editId > 0) {
                    Project::update($editId, $data);
                    setFlash('success', 'Project updated successfully.');
                } else {
                    $newId = Project::create($data);
                    setFlash('success', 'Project created successfully.');
                }
                redirect(SITE_URL . '/admin/projects.php');
            } catch (Exception $e) {
                error_log('Project save error: ' . $e->getMessage());
                $errors[] = 'An error occurred while saving the project.';
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
        <form method="POST" action="" enctype="multipart/form-data" data-validate>
            <?php echo csrfField(); ?>
            
            <div class="admin-form-grid">
                <div class="form-group">
                    <label for="title" class="form-label">Project Title <span>*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-input" 
                           value="<?php echo e($_POST['title'] ?? $project['title'] ?? ''); ?>"
                           required
                           maxlength="255">
                </div>
                
                <div class="form-group">
                    <label for="category" class="form-label">Category <span>*</span></label>
                    <input type="text" 
                           id="category" 
                           name="category" 
                           class="form-input" 
                           value="<?php echo e($_POST['category'] ?? $project['category'] ?? ''); ?>"
                           required
                           maxlength="100"
                           placeholder="E-Commerce, FinTech, etc.">
                </div>
                
                <div class="form-group full-width">
                    <label for="short_description" class="form-label">Short Description <span>*</span></label>
                    <textarea id="short_description" 
                              name="short_description" 
                              class="form-textarea"
                              rows="3"
                              required
                              maxlength="500"><?php echo e($_POST['short_description'] ?? $project['short_description'] ?? ''); ?></textarea>
                    <small style="color: var(--color-text-muted);">Brief description shown on project cards (max 500 chars)</small>
                </div>
                
                <div class="form-group full-width">
                    <label for="full_description" class="form-label">Full Description <span>*</span></label>
                    <textarea id="full_description" 
                              name="full_description" 
                              class="form-textarea large"
                              required><?php echo e($_POST['full_description'] ?? $project['full_description'] ?? ''); ?></textarea>
                    <small style="color: var(--color-text-muted);">Detailed overview of the project</small>
                </div>
                
                <div class="form-group full-width">
                    <label for="problem_statement" class="form-label">Problem Statement</label>
                    <textarea id="problem_statement" 
                              name="problem_statement" 
                              class="form-textarea"
                              rows="4"><?php echo e($_POST['problem_statement'] ?? $project['problem_statement'] ?? ''); ?></textarea>
                    <small style="color: var(--color-text-muted);">What problem did this project solve?</small>
                </div>
                
                <div class="form-group full-width">
                    <label for="features" class="form-label">Key Features</label>
                    <textarea id="features" 
                              name="features" 
                              class="form-textarea"
                              rows="6"><?php echo e($_POST['features'] ?? $project['features'] ?? ''); ?></textarea>
                    <small style="color: var(--color-text-muted);">List features (one per line, optionally starting with - )</small>
                </div>
                
                <div class="form-group full-width">
                    <label for="challenges" class="form-label">Technical Challenges</label>
                    <textarea id="challenges" 
                              name="challenges" 
                              class="form-textarea"
                              rows="4"><?php echo e($_POST['challenges'] ?? $project['challenges'] ?? ''); ?></textarea>
                    <small style="color: var(--color-text-muted);">What challenges did you face and how did you solve them?</small>
                </div>
                
                <div class="form-group full-width">
                    <label for="tech_stack" class="form-label">Tech Stack <span>*</span></label>
                    <input type="text" 
                           id="tech_stack" 
                           name="tech_stack" 
                           class="form-input" 
                           value="<?php echo e($_POST['tech_stack'] ?? $project['tech_stack'] ?? ''); ?>"
                           required
                           placeholder="PHP, MySQL, Redis, Vue.js, AWS">
                    <small style="color: var(--color-text-muted);">Comma-separated list of technologies</small>
                </div>
                
                <div class="form-group">
                    <label for="demo_url" class="form-label">Demo URL</label>
                    <input type="url" 
                           id="demo_url" 
                           name="demo_url" 
                           class="form-input" 
                           value="<?php echo e($_POST['demo_url'] ?? $project['demo_url'] ?? ''); ?>"
                           placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label for="github_url" class="form-label">GitHub URL</label>
                    <input type="url" 
                           id="github_url" 
                           name="github_url" 
                           class="form-input" 
                           value="<?php echo e($_POST['github_url'] ?? $project['github_url'] ?? ''); ?>"
                           placeholder="https://github.com/...">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Project Image</label>
                    
                    <?php if ($project['image'] ?? false): ?>
                        <div class="admin-image-preview" style="margin-bottom: var(--space-3);">
                            <img src="<?php echo uploadUrl($project['image']); ?>" alt="Current image">
                        </div>
                        <label style="display: flex; align-items: center; gap: var(--space-2); margin-bottom: var(--space-3); font-size: var(--font-size-sm);">
                            <input type="checkbox" name="delete_image" value="1">
                            <span style="color: var(--color-error);">Delete current image</span>
                        </label>
                    <?php endif; ?>
                    
                    <div class="admin-file-upload" onclick="document.getElementById('image').click()">
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               onchange="updateFileName(this)">
                        <svg class="admin-file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p>Click to upload or drag and drop</p>
                        <p style="font-size: var(--font-size-xs); margin-top: var(--space-1);">
                            <span>PNG, JPG, GIF, WebP</span> up to 5MB
                        </p>
                        <p id="fileName" style="color: var(--color-accent-primary); margin-top: var(--space-2);"></p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Options</label>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        <label style="display: flex; align-items: center; gap: var(--space-2);">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1"
                                   <?php echo ($_POST['is_featured'] ?? $project['is_featured'] ?? false) ? 'checked' : ''; ?>>
                            <span>Featured project</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-input">
                        <option value="active" <?php echo ($_POST['status'] ?? $project['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($_POST['status'] ?? $project['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <?php echo $editId > 0 ? 'Update Project' : 'Create Project'; ?>
                </button>
                <a href="<?php echo SITE_URL; ?>/admin/projects.php" class="admin-btn admin-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        fileName.textContent = 'Selected: ' + input.files[0].name;
    } else {
        fileName.textContent = '';
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
