<?php
/**
 * Project Detail Page
 * 
 * Displays detailed information about a specific project.
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Project.php';

// Get project slug
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    setFlash('error', 'Project not found.');
    redirect(SITE_URL . '/pages/projects.php');
}

$project = Project::getBySlug($slug);

if (!$project) {
    setFlash('error', 'Project not found.');
    redirect(SITE_URL . '/pages/projects.php');
}

$pageTitle = $project['title'];
$metaDescription = $project['short_description'];

// Get related projects
$relatedProjects = array_slice(
    array_filter(
        Project::getByCategory($project['category']),
        fn($p) => $p['id'] != $project['id']
    ),
    0,
    2
);

include __DIR__ . '/../includes/header.php';
?>

<article class="project-detail">
    <div class="container">
        <!-- Header -->
        <header class="project-detail-header">
            <div class="project-detail-meta">
                <span class="project-detail-category"><?php echo e($project['category']); ?></span>
                <span class="project-detail-separator">•</span>
                <span class="project-detail-date"><?php echo formatDate($project['created_at'], 'F Y'); ?></span>
            </div>
            <h1 class="project-detail-title"><?php echo e($project['title']); ?></h1>
            
            <div class="project-detail-actions">
                <?php if ($project['demo_url']): ?>
                    <a href="<?php echo e($project['demo_url']); ?>" 
                       class="btn btn-primary" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        Live Demo
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <?php if ($project['github_url']): ?>
                    <a href="<?php echo e($project['github_url']); ?>" 
                       class="btn btn-secondary" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        View Code
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo SITE_URL; ?>/pages/contact.php?project=<?php echo e($project['slug']); ?>" class="btn btn-secondary">
                    Discuss This Project
                </a>
            </div>
        </header>
        
        <!-- Featured Image -->
        <?php if ($project['image']): ?>
            <div class="project-detail-image">
                <img src="<?php echo uploadUrl($project['image']); ?>" 
                     alt="<?php echo e($project['title']); ?>">
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="project-detail-content">
            <div class="project-detail-main">
                <!-- Overview -->
                <section>
                    <h2>Overview</h2>
                    <p><?php echo nl2brHtml($project['full_description']); ?></p>
                </section>
                
                <!-- Problem Statement -->
                <?php if ($project['problem_statement']): ?>
                    <section>
                        <h2>The Problem</h2>
                        <p><?php echo nl2brHtml($project['problem_statement']); ?></p>
                    </section>
                <?php endif; ?>
                
                <!-- Features -->
                <?php if ($project['features']): ?>
                    <section>
                        <h2>Key Features</h2>
                        <ul>
                            <?php foreach (explode("\n", $project['features']) as $feature): ?>
                                <?php if (trim($feature)): ?>
                                    <li><?php echo e(trim(str_replace('- ', '', $feature))); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
                
                <!-- Challenges -->
                <?php if ($project['challenges']): ?>
                    <section>
                        <h2>Technical Challenges</h2>
                        <p><?php echo nl2brHtml($project['challenges']); ?></p>
                    </section>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="project-detail-sidebar">
                <div class="project-detail-info">
                    <h3>Technologies Used</h3>
                    <div class="project-detail-tech-list">
                        <?php foreach (parseTechStack($project['tech_stack']) as $tech): ?>
                            <span class="project-detail-tech-item"><?php echo e(trim($tech)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="project-detail-info" style="margin-top: var(--space-6);">
                    <h3>Have a Similar Project?</h3>
                    <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin-bottom: var(--space-4);">
                        I can help you build a similar system tailored to your business needs.
                    </p>
                    <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="btn btn-primary" style="width: 100%;">
                        Get in Touch
                    </a>
                </div>
            </aside>
        </div>
    </div>
</article>

<!-- Related Projects -->
<?php if (!empty($relatedProjects)): ?>
    <section class="section" style="background: var(--color-bg-secondary);">
        <div class="container">
            <div class="section-header">
                <p class="section-label">More Work</p>
                <h2 class="section-title">Related Projects</h2>
            </div>
            
            <div class="projects-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <?php foreach ($relatedProjects as $related): ?>
                    <article class="project-card">
                        <a href="<?php echo SITE_URL; ?>/pages/project.php?slug=<?php echo e($related['slug']); ?>" class="project-link">
                            <div class="project-image">
                                <?php if ($related['image']): ?>
                                    <img src="<?php echo uploadUrl($related['image']); ?>" 
                                         alt="<?php echo e($related['title']); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="project-image-placeholder">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h4v4H7V7zm0 6h4v4H7v-4zm6-6h4v4h-4V7zm0 6h4v4h-4v-4z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="project-content">
                                <p class="project-category"><?php echo e($related['category']); ?></p>
                                <h3 class="project-title"><?php echo e($related['title']); ?></h3>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
