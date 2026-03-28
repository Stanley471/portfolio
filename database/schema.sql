--
-- Portfolio Database Schema
-- Database Name: portfolio
-- 

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `full_description` text NOT NULL,
  `problem_statement` text,
  `features` text,
  `challenges` text,
  `tech_stack` varchar(500) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text,
  `demo_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') DEFAULT 'new',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Insert default admin user
-- Username: admin
-- Password: Admin@123 (change after first login)
--

INSERT INTO `admin_users` (`username`, `password`, `email`, `full_name`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.local', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Insert sample projects data
--

INSERT INTO `projects` (`title`, `slug`, `category`, `short_description`, `full_description`, `problem_statement`, `features`, `challenges`, `tech_stack`, `image`, `is_featured`, `status`) VALUES
(
  'Enterprise E-Commerce Platform',
  'enterprise-ecommerce-platform',
  'E-Commerce',
  'A full-featured B2B and B2C e-commerce platform handling 10,000+ daily transactions with real-time inventory management and multi-currency support.',
  'Built a comprehensive e-commerce solution for a wholesale distribution company that needed to serve both retail customers and business clients. The platform integrates with existing ERP systems and provides a seamless shopping experience across all devices.',
  'The client was struggling with manual order processing, inventory discrepancies between online and offline sales, and inability to handle bulk pricing for B2B customers. Their existing solution could not scale during peak seasons.',
  '- Real-time inventory synchronization with warehouse ERP\n- Dynamic pricing engine for tiered B2B customers\n- Multi-currency and multi-language support\n- Advanced search with Elasticsearch\n- Order tracking and automated notifications\n- Admin dashboard with sales analytics\n- Bulk import/export functionality',
  'Handling concurrent inventory updates during flash sales required implementing Redis-based locking mechanisms. Optimizing database queries for the product catalog with 50,000+ SKUs while maintaining sub-second response times.',
  'PHP 8.1, Laravel, MySQL, Redis, Elasticsearch, Vue.js, AWS',
  'project-ecommerce.jpg',
  1,
  'active'
),
(
  'Digital Banking & Wallet System',
  'digital-banking-wallet-system',
  'FinTech',
  'Secure digital banking platform with virtual wallets, peer-to-peer transfers, bill payments, and comprehensive transaction history for banking institutions.',
  'Developed a core banking system module enabling digital wallet functionality for a regional bank. The system supports KYC verification, multi-level approval workflows, and integrates with national payment gateways for seamless fund transfers.',
  'Traditional banking customers were moving to fintech alternatives due to lack of digital services. The bank needed a secure, compliant solution that could handle KYC requirements while providing modern user experience.',
  '- Virtual wallet creation and management\n- Peer-to-peer instant transfers\n- Scheduled and recurring bill payments\n- KYC document upload and verification workflow\n- Transaction limits and fraud detection\n- Multi-factor authentication\n- Comprehensive audit trails\n- REST API for mobile app integration',
  'Ensuring PCI DSS compliance while maintaining performance. Implementing idempotency for payment transactions to prevent duplicate charges. Creating a fraud detection system that analyzes transaction patterns without adding friction for legitimate users.',
  'PHP 8.0, Symfony, PostgreSQL, RabbitMQ, Docker, JWT Authentication',
  'project-banking.jpg',
  1,
  'active'
),
(
  'Fleet & Asset Tracking System',
  'fleet-asset-tracking-system',
  'Tracking & Logistics',
  'Real-time GPS tracking platform for vehicle fleets and high-value assets with geofencing, route optimization, and maintenance scheduling.',
  'A logistics company needed visibility into their 200+ vehicle fleet across multiple countries. The solution provides real-time tracking, driver behavior monitoring, fuel consumption analysis, and automated maintenance alerts based on mileage and engine diagnostics.',
  'The company was losing revenue due to inefficient routing, unauthorized vehicle usage, and delayed maintenance causing breakdowns. They had no centralized view of fleet operations across different regions.',
  '- Real-time GPS tracking with 30-second updates\n- Geofencing with instant alerts for boundary violations\n- Route optimization based on traffic and delivery windows\n- Driver behavior scoring (speeding, harsh braking, idling)\n- Fuel consumption monitoring and anomaly detection\n- Maintenance scheduling based on mileage and time\n- Custom reports and analytics dashboard\n- Mobile app for drivers',
  'Processing high-frequency GPS data from hundreds of devices without database bottlenecks. Implementing efficient geospatial queries for geofencing checks. Managing device connectivity issues and data synchronization when vehicles enter/exit network coverage.',
  'PHP 8.1, CodeIgniter, MySQL, MQTT, Node.js, Google Maps API, WebSockets',
  'project-tracking.jpg',
  1,
  'active'
),
(
  'University Result Portal',
  'university-result-portal',
  'Education',
  'Secure academic result management system for universities with student verification, transcript generation, and grade computation workflows.',
  'A state university needed to modernize their result processing system to handle 50,000+ students across multiple faculties. The system automates grade computation, provides secure student access, and generates official transcripts with verification capabilities.',
  'Manual result processing was error-prone and time-consuming. Students waited weeks for results. Verification of academic credentials by employers was difficult and required manual staff intervention.',
  '- Automated grade computation and GPA calculation\n- Secure student login with registration number\n- Individual and bulk result uploads\n- Transcript generation with QR code verification\n- Appeals and remarking workflow\n- Department-level result approval chains\n- Integration with student information system\n- Audit trail for all grade modifications',
  'Ensuring data accuracy during bulk uploads with proper validation and rollback capabilities. Implementing a secure verification system that prevents credential fraud while remaining easy for employers to use. Managing concurrent access during result release periods.',
  'PHP 7.4, MySQL, Bootstrap, PDF Generation, QR Code Library',
  'project-education.jpg',
  1,
  'active'
),
(
  'Healthcare Appointment & Records System',
  'healthcare-appointment-records',
  'Healthcare',
  'End-to-end healthcare management platform with appointment booking, electronic health records, prescription management, and billing integration.',
  'A multi-location clinic chain needed to unify their patient management across 5 locations. The system enables online appointment booking, maintains centralized patient records, and streamlines the prescription and billing workflow.',
  'Patients experienced long wait times due to inefficient scheduling. Medical records were scattered across locations, requiring patients to carry paper files. Prescription management was manual and prone to errors.',
  '- Online appointment booking with doctor availability\n- Patient self-check-in via QR code\n- Electronic health records with version history\n- Digital prescription generation and pharmacy integration\n- Medical history and allergy alerts\n- Billing and insurance claim processing\n- Lab result integration\n- HIPAA-compliant audit logging',
  'Implementing role-based access control that allows doctors to see only their patients while administrators have broader access. Ensuring HIPAA compliance for data encryption and access logging. Synchronizing appointment slots across multiple locations in real-time.',
  'PHP 8.0, Laravel, MySQL, React, FHIR API Standards, AES Encryption',
  'project-healthcare.jpg',
  1,
  'active'
);

-- --------------------------------------------------------

--
-- Insert default site settings
--

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'Backend Developer Portfolio'),
('site_description', 'Professional backend developer specializing in business systems, e-commerce platforms, and financial applications.'),
('site_keywords', 'backend developer, PHP developer, e-commerce, fintech, web development, freelance developer'),
('author_name', 'Your Name'),
('author_title', 'Backend & Full-Stack Developer'),
('contact_email', 'contact@yourdomain.com'),
('contact_phone', '+1234567890'),
('whatsapp_number', '+1234567890'),
('linkedin_url', 'https://linkedin.com/in/yourprofile'),
('github_url', 'https://github.com/yourusername'),
('twitter_url', 'https://twitter.com/yourusername'),
('hero_headline', 'I build backend systems for businesses'),
('hero_subtext', 'Specialized in developing robust e-commerce platforms, financial systems, and tracking solutions that power real business operations.'),
('about_summary', 'I am a backend-focused full-stack developer with expertise in building scalable business systems. I help companies transform their operations through custom software solutions that handle real-world complexity.'),
('footer_text', 'Built with precision. All systems operational.');


-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT 'Other',
  `icon` varchar(255) DEFAULT NULL,
  `proficiency` int(11) DEFAULT 80,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Insert default skills
--

INSERT INTO `skills` (`name`, `category`, `proficiency`, `display_order`) VALUES
('PHP', 'Backend', 95, 1),
('Laravel', 'Framework', 90, 2),
('Blade', 'Frontend', 85, 3),
('MySQL', 'Database', 90, 4),
('HTML', 'Frontend', 95, 5),
('JavaScript', 'Frontend', 85, 6),
('Python', 'Backend', 75, 7),
('Java', 'Backend', 70, 8),
('Tailwind CSS', 'Frontend', 85, 9),
('Bootstrap', 'Frontend', 90, 10),
('Git', 'Tools', 88, 11);
