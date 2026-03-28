# Developer Portfolio

A professional, dark-themed portfolio website for backend/full-stack developers. Built with PHP, MySQL, and vanilla JavaScript. Features a custom MVC-style architecture, admin panel, contact form with database storage, and smooth animations.

![Portfolio Preview](assets/images/preview.png)

## Features

### Frontend
- **Dark, minimal design** with subtle hacker/developer aesthetic
- **Mobile-first responsive** layout
- **Smooth animations** (scroll reveals, hover effects, transitions)
- **SEO-friendly** structure with proper meta tags
- **Fast loading** with optimized assets

### Backend
- **Custom PHP architecture** (MVC-style, no heavy frameworks)
- **MySQL database** for projects and contact messages
- **Secure admin panel** with session-based authentication
- **Contact form** with validation and rate limiting
- **File upload** system for project images

### Admin Panel
- **Dashboard** with statistics
- **Project management** (CRUD operations)
- **Message inbox** with read/reply/archive workflow
- **Responsive admin interface**

## Tech Stack

- **Backend:** PHP 7.4+ (structured, MVC-style)
- **Frontend:** HTML5, CSS3, JavaScript (vanilla)
- **Database:** MySQL 5.7+
- **Web Server:** Apache/Nginx

## Project Structure

```
portfolio/
├── admin/                  # Admin panel
│   ├── includes/          # Admin components
│   │   ├── header.php     # Admin header
│   │   └── footer.php     # Admin footer
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── logout.php         # Admin logout
│   ├── projects.php       # Project list
│   ├── project-form.php   # Add/Edit project
│   ├── messages.php       # Message list
│   └── message.php        # View message
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   │   ├── main.css      # Main styles
│   │   └── admin.css     # Admin styles
│   ├── js/               # JavaScript
│   │   └── main.js       # Main scripts
│   ├── images/           # Static images
│   └── uploads/          # Uploaded files
├── database/             # Database files
│   └── schema.sql        # Database schema
├── includes/             # Core PHP files
│   ├── models/          # Data models
│   │   ├── Project.php  # Project model
│   │   ├── ContactMessage.php  # Message model
│   │   └── Admin.php    # Admin model
│   ├── config.php       # Configuration
│   ├── database.php     # Database class
│   ├── helpers.php      # Utility functions
│   ├── header.php       # Site header
│   └── footer.php       # Site footer
├── pages/               # Public pages
│   ├── projects.php     # Projects listing
│   ├── project.php      # Project detail
│   └── contact.php      # Contact page
├── index.php            # Home page
└── README.md           # This file
```

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP, WAMP, MAMP, or similar local development environment

### Step 1: Clone or Download
```bash
# Clone the repository (if using git)
git clone <repository-url>

# Or download and extract the ZIP file
```

### Step 2: Move to Web Root
Move the project folder to your web server root:

**XAMPP:**
```
Windows: C:\xampp\htdocs\portfolio
Linux/Mac: /opt/lampp/htdocs/portfolio
```

### Step 3: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `portfolio`
3. Import the schema file:
   - Go to the **Import** tab
   - Select `database/schema.sql`
   - Click **Go**

Or via command line:
```bash
mysql -u root -p portfolio < database/schema.sql
```

### Step 4: Configure Database
Edit `includes/config.php` and update database credentials if needed:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
```

### Step 5: Configure Site URL
In `includes/config.php`, update the site URL:

```php
define('SITE_URL', 'http://localhost/portfolio');
```

### Step 6: Set Permissions
Ensure the uploads folder is writable:

```bash
chmod 755 assets/uploads
```

On Windows, right-click the folder → Properties → Security tab, and ensure write permissions.

### Step 7: Access the Site

**Public Site:** http://localhost/portfolio/

**Admin Panel:** http://localhost/portfolio/admin/

**Default Admin Credentials:**
- Username: `admin`
- Password: `Admin@123`

> **IMPORTANT:** Change the default password after first login!

## Customization

### Update Personal Information
Edit the `site_settings` table in the database or modify the default values in `database/schema.sql` before importing.

Key settings to update:
- `site_title`
- `site_description`
- `author_name`
- `author_title`
- `contact_email`
- `whatsapp_number`
- `linkedin_url`
- `github_url`
- `twitter_url`
- `hero_headline`
- `hero_subtext`

### Add/Edit Projects
1. Log in to the admin panel
2. Navigate to **Projects**
3. Click **Add Project** or edit existing projects

### Update Colors/Styles
Edit `assets/css/main.css` to customize:
- Color scheme (CSS variables at the top)
- Typography
- Spacing
- Animations

## Security Considerations

1. **Change default admin password** immediately after setup
2. **Use HTTPS** in production
3. **Update database credentials** to strong passwords
4. **Restrict file uploads** to allowed types only (configured in config.php)
5. **Keep PHP and MySQL updated**
6. **Set proper file permissions**:
   - Folders: 755
   - Files: 644
   - Uploads folder: 755 (writable)

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check credentials in `includes/config.php`
- Ensure database `portfolio` exists

### File Upload Errors
- Check `assets/uploads` folder permissions
- Verify file size is under 5MB
- Ensure file type is allowed (jpg, png, gif, webp)

### 404 Errors
- Check that `SITE_URL` in config.php matches your actual URL
- Ensure `.htaccess` or URL rewriting is configured (if using clean URLs)
- Verify mod_rewrite is enabled (Apache)

### Contact Form Not Working
- Check that the `contact_messages` table exists
- Verify write permissions are not needed (data goes to database)
- Check PHP error logs

## Development

### Adding New Pages
1. Create a new file in `pages/` directory
2. Include required files:
   ```php
   $pageTitle = 'Page Title';
   include __DIR__ . '/../includes/header.php';
   // Your content
   include __DIR__ . '/../includes/footer.php';
   ```

### Database Changes
1. Update `database/schema.sql` with new schema
2. Create migration script for existing databases
3. Update relevant models in `includes/models/`

## License

This project is open source and available under the [MIT License](LICENSE).

## Credits

- Fonts: [Inter](https://rsms.me/inter/) and [JetBrains Mono](https://www.jetbrains.com/lp/mono/)
- Icons: Custom SVG icons

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review PHP and MySQL error logs
3. Ensure all prerequisites are met
