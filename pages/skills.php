<?php
/**
 * Skills Page
 * 
 * Displays all skills grouped by category.
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Skill.php';

$pageTitle = 'Skills';
$metaDescription = 'View my technical skills and technologies I work with. PHP, Laravel, MySQL, JavaScript, and more.';

// Get skills grouped by category
$skillsByCategory = Skill::getCategories();

include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top: var(--space-16);">
    <div class="container">
        <div class="section-header reveal">
            <p class="section-label">My Expertise</p>
            <h2 class="section-title">Skills & Technologies</h2>
            <p class="section-description">
                A comprehensive list of technologies and tools I use to build robust, scalable applications.
            </p>
        </div>
        
        <?php if (!empty($skillsByCategory)): ?>
            <?php foreach ($skillsByCategory as $category => $skills): ?>
                <div class="skills-category-section reveal">
                    <h3 class="skills-category-heading"><?php echo e($category); ?></h3>
                    <div class="skills-page-grid">
                        <?php foreach ($skills as $index => $skill): ?>
                            <div class="skill-card" style="transition-delay: <?php echo ($index % 4) * 50; ?>ms">
                                <div class="skill-card-header">
                                    <span class="skill-card-category"><?php echo e($category); ?></span>
                                    <span class="skill-card-percent"><?php echo $skill['proficiency']; ?>%</span>
                                </div>
                                <h3 class="skill-card-name"><?php echo e($skill['name']); ?></h3>
                                <div class="skill-card-bar">
                                    <div class="skill-card-progress" style="width: 0%" data-width="<?php echo $skill['proficiency']; ?>"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <h3>No skills listed yet</h3>
                <p>Check back soon for updates.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
