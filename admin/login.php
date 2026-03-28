<?php
/**
 * Admin Login Page
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/models/Admin.php';

// Redirect if already logged in
if (isAdmin()) {
    redirect(SITE_URL . '/admin/');
}

$errors = [];

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $errors[] = 'Please enter both username and password.';
    } else {
        $admin = Admin::verifyLogin($username, $password);
        
        if ($admin) {
            Admin::login($admin);
            redirect(SITE_URL . '/admin/');
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}

// Get flash message
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin Panel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo asset('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
</head>
<body class="admin-login-page">
    <div class="admin-login-box">
        <div class="admin-login-logo">
            <span>&lt;/&gt; Admin</span>
            <p>Portfolio Management System</p>
        </div>
        
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>" style="margin-bottom: var(--space-6);">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" style="margin-bottom: var(--space-6);">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-input" 
                       placeholder="Enter username"
                       required
                       autofocus>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input" 
                       placeholder="Enter password"
                       required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                Sign In
            </button>
        </form>
        
        <p style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-xs); color: var(--color-text-muted);">
            Default: admin / Admin@123
        </p>
    </div>
</body>
</html>
