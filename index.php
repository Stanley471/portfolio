<?php
/**
 * Home Page
 * 
 * Main landing page with hero section and featured projects.
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/models/Project.php';
require_once __DIR__ . '/includes/models/Skill.php';

$pageTitle = 'Home';
$metaDescription = getSetting('hero_subtext', 'Professional backend developer specializing in business systems, e-commerce platforms, and financial applications.');

// Get featured projects
$featuredProjects = Project::getFeatured(4);

// Get skills grouped by category
$skillsByCategory = Skill::getCategories();

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <p class="hero-greeting">
                    <span id="type-greeting">Hello, I'm Stanley Obimma</span><span class="typing-cursor">|</span>
                </p>
                <h1 class="hero-title">
                    <span id="type-headline">I build <span class="hero-title-accent">web applications</span> for businesses</span>
                </h1>
                <p class="hero-description">
                    <?php echo e(getSetting('hero_subtext', 'Specialized in developing robust e-commerce platforms, financial systems, and tracking solutions that power real business operations.')); ?>
                </p>
                <div class="hero-cta">
                    <a href="<?php echo SITE_URL; ?>/pages/projects.php" class="btn btn-primary btn-lg">
                        View Projects
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="btn btn-secondary btn-lg">
                        Get in Touch
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">5+</div>
                        <div class="hero-stat-label">Years Experience</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">20+</div>
                        <div class="hero-stat-label">Projects Delivered</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">15+</div>
                        <div class="hero-stat-label">Happy Clients</div>
                    </div>
                </div>
            </div>
            
            <!-- Code Block Visualization -->
            <div class="hero-code">
                <div class="hero-code-header">
                    <span class="hero-code-dot hero-code-dot-red"></span>
                    <span class="hero-code-dot hero-code-dot-yellow"></span>
                    <span class="hero-code-dot hero-code-dot-green"></span>
                    <span style="margin-left: auto; font-size: 0.75rem; color: var(--color-text-muted);">system architecture</span>
                </div>
                <div class="hero-code-body">
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">1</span>
                        <span class="hero-code-line-content">
                            <span class="hero-code-keyword">class</span> 
                            <span class="hero-code-function">BusinessSystem</span> 
                            <span class="hero-code-keyword">extends</span> 
                            <span class="hero-code-function">ScalableArchitecture</span>
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">2</span>
                        <span class="hero-code-line-content">{</span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">3</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="hero-code-keyword">protected</span> 
                            <span class="hero-code-variable">$focus</span> = 
                            <span class="hero-code-string">'reliability'</span>;
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">4</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="hero-code-keyword">protected</span> 
                            <span class="hero-code-variable">$stack</span> = [
                            <span class="hero-code-string">'PHP'</span>, 
                            <span class="hero-code-string">'MySQL'</span>, 
                            <span class="hero-code-string">'Redis'</span>];
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">5</span>
                        <span class="hero-code-line-content">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">6</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="hero-code-keyword">public function</span> 
                            <span class="hero-code-function">deliver</span>()
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">7</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;{
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">8</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="hero-code-keyword">return</span> 
                            <span class="hero-code-variable">$this</span>->
                            <span class="hero-code-function">build</span>(
                            <span class="hero-code-string">'solutions that scale'</span>);
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">9</span>
                        <span class="hero-code-line-content">
                            &nbsp;&nbsp;&nbsp;&nbsp;}
                        </span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">10</span>
                        <span class="hero-code-line-content">}</span>
                    </div>
                    <div class="hero-code-line">
                        <span class="hero-code-line-number">11</span>
                        <span class="hero-code-line-content">
                            <span class="hero-code-comment">// Ready to build your next system?</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Section -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <p class="section-label">Featured Work</p>
            <h2 class="section-title">Systems I've Built</h2>
            <p class="section-description">
                A selection of backend systems and applications that solve real business problems. 
                Each project represents a unique challenge and a production-ready solution.
            </p>
        </div>
        
        <?php if (!empty($featuredProjects)): ?>
            <div class="projects-grid">
                <?php foreach ($featuredProjects as $index => $project): ?>
                    <article class="project-card reveal" style="transition-delay: <?php echo $index * 100; ?>ms">
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
            
            <div style="text-align: center; margin-top: var(--space-12);">
                <a href="<?php echo SITE_URL; ?>/pages/projects.php" class="btn btn-secondary btn-lg">
                    View All Projects
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <h3>No projects yet</h3>
                <p>Projects will appear here once added.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- What I Do Section -->
<section class="section" style="background: var(--color-bg-secondary);">
    <div class="container">
        <div class="section-header reveal">
            <p class="section-label">Expertise</p>
            <h2 class="section-title">Systems I Specialize In</h2>
            <p class="section-description">
                I focus on building the backbone of business operations — systems that need to be reliable, secure, and scalable.
            </p>
        </div>
        
        <div class="projects-grid">
            <div class="project-card reveal" style="padding: var(--space-8);">
                <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(100, 255, 218, 0.1); border-radius: var(--radius-lg); margin-bottom: var(--space-4);">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color: var(--color-accent-primary);">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                </div>
                <h3 style="font-size: var(--font-size-xl); font-weight: var(--font-weight-semibold); margin-bottom: var(--space-3);">E-Commerce Platforms</h3>
                <p style="color: var(--color-text-secondary); line-height: var(--line-height-relaxed);">
                    Full-featured online stores with inventory management, payment processing, 
                    order tracking, and multi-vendor support. Built for scale.
                </p>
            </div>
            
            <div class="project-card reveal" style="padding: var(--space-8); transition-delay: 100ms;">
                <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(100, 255, 218, 0.1); border-radius: var(--radius-lg); margin-bottom: var(--space-4);">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color: var(--color-accent-primary);">
                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                </div>
                <h3 style="font-size: var(--font-size-xl); font-weight: var(--font-weight-semibold); margin-bottom: var(--space-3);">Financial Systems</h3>
                <p style="color: var(--color-text-secondary); line-height: var(--line-height-relaxed);">
                    Digital wallets, payment gateways, transaction processing, and banking integrations. 
                    Secure, compliant, and auditable.
                </p>
            </div>
            
            <div class="project-card reveal" style="padding: var(--space-8); transition-delay: 200ms;">
                <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(100, 255, 218, 0.1); border-radius: var(--radius-lg); margin-bottom: var(--space-4);">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color: var(--color-accent-primary);">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <h3 style="font-size: var(--font-size-xl); font-weight: var(--font-weight-semibold); margin-bottom: var(--space-3);">Tracking Systems</h3>
                <p style="color: var(--color-text-secondary); line-height: var(--line-height-relaxed);">
                    GPS-based fleet tracking, asset management, route optimization, 
                    and real-time monitoring dashboards.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Skills Section -->
<?php if (!empty($skillsByCategory)): ?>
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <p class="section-label">Tech Stack</p>
            <h2 class="section-title">Skills & Technologies</h2>
            <p class="section-description">
                Technologies I work with to build robust, scalable applications.
            </p>
        </div>
        
        <div class="skills-cards-grid">
            <?php 
            $displayCount = 0;
            $maxDisplay = 6;
            foreach ($skillsByCategory as $category => $skills): 
                foreach ($skills as $skill): 
                    if ($displayCount >= $maxDisplay) break 2;
                    $displayCount++;
            ?>
                <div class="skill-card reveal" style="transition-delay: <?php echo ($displayCount - 1) * 50; ?>ms">
                    <div class="skill-card-header">
                        <span class="skill-card-category"><?php echo e($category); ?></span>
                        <span class="skill-card-percent"><?php echo $skill['proficiency']; ?>%</span>
                    </div>
                    <h3 class="skill-card-name"><?php echo e($skill['name']); ?></h3>
                    <div class="skill-card-bar">
                        <div class="skill-card-progress" style="width: 0%" data-width="<?php echo $skill['proficiency']; ?>"></div>
                    </div>
                </div>
            <?php 
                endforeach;
            endforeach; 
            ?>
        </div>
        
        <div style="text-align: center; margin-top: var(--space-10);">
            <a href="<?php echo SITE_URL; ?>/pages/skills.php" class="btn btn-secondary btn-lg">
                View All Skills
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto;" class="reveal">
            <h2 class="section-title">Ready to Build Something?</h2>
            <p style="color: var(--color-text-secondary); margin-bottom: var(--space-8);">
                I'm currently available for freelance projects. If you have a system that needs building, 
                let's discuss how I can help bring it to life.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: var(--space-4); justify-content: center;">
                <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="btn btn-primary btn-lg">
                    Start a Project
                </a>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', getSetting('whatsapp_number', WHATSAPP_NUMBER)); ?>" 
                   class="btn btn-secondary btn-lg"
                   target="_blank" 
                   rel="noopener noreferrer">
                    Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
