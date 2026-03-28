<?php
/**
 * Projects Listing Page
 * 
 * Displays all projects with filtering options.
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Project.php';

$pageTitle = 'Projects';
$metaDescription = 'View my portfolio of backend systems, e-commerce platforms, financial applications, and tracking solutions.';

// Get all projects
$projects = Project::getAll(true);
$categories = Project::getCategories();

include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top: var(--space-16);">
    <div class="container">
        <div class="section-header reveal">
            <p class="section-label">Portfolio</p>
            <h2 class="section-title">All Projects</h2>
            <p class="section-description">
                A complete collection of systems I've built. Each project represents a unique business challenge 
                and a production-ready solution.
            </p>
        </div>
        
        <?php if (!empty($projects)): ?>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <article class="project-card reveal" style="transition-delay: <?php echo ($index % 3) * 100; ?>ms">
                        <a href="<?php echo SITE_URL; ?>/pages/project.php?slug=<?php echo e($project['slug']); ?>" class="project-link">
                            <div class="project-image">
                                <?php if ($project['image']): ?>
                                    <img src="<?php echo uploadUrl($project['image']); ?>" 
                                         alt="<?php echo e($project['title']); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="project-image-placeholder">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h4v4H7V7zm0 6h4v4H7v-4zm6-6h4v4h-4V7zm0 6h4v4h-4v-4z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="project-overlay">
                                    <span class="btn btn-secondary btn-sm">View Details</span>
                                </div>
                            </div>
                            <div class="project-content">
                                <p class="project-category"><?php echo e($project['category']); ?></p>
                                <h3 class="project-title"><?php echo e($project['title']); ?></h3>
                                <p class="project-description"><?php echo e($project['short_description']); ?></p>
                                <div class="project-tech">
                                    <?php foreach (array_slice(parseTechStack($project['tech_stack']), 0, 4) as $tech): ?>
                                        <span class="project-tech-tag"><?php echo e(trim($tech)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <h3>No projects yet</h3>
                <p>Check back soon for new projects.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
