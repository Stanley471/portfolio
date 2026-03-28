<?php
/**
 * Contact Page
 * 
 * Contact form with database storage.
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/ContactMessage.php';

$pageTitle = 'Contact';
$metaDescription = 'Get in touch to discuss your project. I\'m available for freelance backend development work.';

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validation
        if (empty($name)) {
            $errors[] = 'Name is required.';
        } elseif (strlen($name) > 100) {
            $errors[] = 'Name must be less than 100 characters.';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!isValidEmail($email)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (empty($message)) {
            $errors[] = 'Message is required.';
        } elseif (strlen($message) > 5000) {
            $errors[] = 'Message must be less than 5000 characters.';
        }
        
        // Rate limiting - check if email has sent message in last 5 minutes
        if (empty($errors) && !ContactMessage::canSend($email, 5)) {
            $errors[] = 'Please wait a few minutes before sending another message.';
        }
        
        // Save to database
        if (empty($errors)) {
            try {
                ContactMessage::create([
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject ?: 'Contact Form Submission',
                    'message' => $message
                ]);
                
                $success = true;
                
                // Clear form
                $name = $email = $subject = $message = '';
            } catch (Exception $e) {
                error_log('Contact form error: ' . $e->getMessage());
                $errors[] = 'An error occurred while sending your message. Please try again later.';
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info reveal">
                <h1>Let's Work Together</h1>
                <p>
                    Have a project in mind? I'd love to hear about it. Whether you need a complete system built 
                    from scratch or help with an existing application, let's discuss how I can help.
                </p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h3>Email</h3>
                            <p>Send me a detailed message</p>
                            <a href="mailto:<?php echo e(getSetting('contact_email', CONTACT_EMAIL)); ?>">
                                <?php echo e(getSetting('contact_email', CONTACT_EMAIL)); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h3>WhatsApp</h3>
                            <p>Quick chat for urgent inquiries</p>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', getSetting('whatsapp_number', WHATSAPP_NUMBER)); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                +<?php echo e(getSetting('whatsapp_number', WHATSAPP_NUMBER)); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h3>Availability</h3>
                            <p>Currently accepting new projects</p>
                            <span style="color: var(--color-success);">● Available for freelance</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-wrapper reveal" style="transition-delay: 100ms;">
                <?php if ($success): ?>
                    <div class="form-success">
                        <p><strong>Message sent successfully!</strong></p>
                        <p>Thank you for reaching out. I'll get back to you within 24 hours.</p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" data-validate>
                    <?php echo csrfField(); ?>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Name <span>*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input" 
                               placeholder="Your name"
                               value="<?php echo e($_POST['name'] ?? ''); ?>"
                               required
                               maxlength="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email <span>*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input" 
                               placeholder="your@email.com"
                               value="<?php echo e($_POST['email'] ?? ''); ?>"
                               required
                               maxlength="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">
                            Subject
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               class="form-input" 
                               placeholder="What is this about?"
                               value="<?php echo e($_POST['subject'] ?? ''); ?>"
                               maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">
                            Message <span>*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  class="form-textarea" 
                                  placeholder="Tell me about your project... What problem are you trying to solve? What's your timeline?"
                                  required
                                  maxlength="5000"><?php echo e($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Send Message
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
