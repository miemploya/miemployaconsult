-- ============================================================
-- MIEMPLOYA CONSULT PLATFORM — DATABASE SCHEMA
-- Empleo System Limited
-- ============================================================

CREATE DATABASE IF NOT EXISTS `miemployaconsult` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `miemployaconsult`;

-- ============================================================
-- 1. USERS & AUTHENTICATION
-- ============================================================
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `role` ENUM('super_admin','staff_admin','user') NOT NULL DEFAULT 'user',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default Super Admin (password: Admin@123)
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`) VALUES
('Super Admin', 'admin@miemployaconsult.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08000000000', 'super_admin');

-- ============================================================
-- 2. CONSULTING REQUESTS
-- ============================================================
CREATE TABLE `consulting_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(255) NOT NULL,
    `contact_person` VARCHAR(150) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `industry_sector` VARCHAR(150) DEFAULT NULL,
    `description` TEXT NOT NULL,
    `status` ENUM('new','in_progress','completed','closed') NOT NULL DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 3. DIGITAL PRODUCTS
-- ============================================================
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `tagline` VARCHAR(500) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `features` TEXT DEFAULT NULL,
    `screenshots` TEXT DEFAULT NULL,
    `demo_video_url` VARCHAR(500) DEFAULT NULL,
    `external_link` VARCHAR(500) DEFAULT NULL,
    `icon_class` VARCHAR(100) DEFAULT NULL,
    `color_from` VARCHAR(30) DEFAULT '#3b82f6',
    `color_to` VARCHAR(30) DEFAULT '#a855f7',
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed default products
INSERT INTO `products` (`name`, `slug`, `tagline`, `description`, `features`, `external_link`, `icon_class`, `color_from`, `color_to`, `sort_order`) VALUES
('MiPayMaster', 'mipaymaster', 'Payroll & Employee Management System', 'Payroll and employee management system designed to help organizations manage salary processing, employee records, and payroll reporting.', '["Automated salary processing","Employee record management","Statutory compliance (PAYE, Pension, NHF)","Payslip generation","Multi-branch payroll","Leave & attendance tracking","Performance evaluation"]', 'https://mipaymaster.com', 'banknote', '#3b82f6', '#6366f1', 1),
('MFillPilot', 'mfillpilot', 'Gas Station Management System', 'A gas station management system designed for sales reconciliation, haulage, reporting and operational control.', '["Sales reconciliation","Haulage management","Daily operations reporting","Stock tracking","Financial reporting","Multi-station support"]', '#', 'fuel', '#f59e0b', '#ef4444', 2),
('MIAUDITOPS', 'miauditops', 'Audit & Operational Monitoring System', 'An audit and operational monitoring system designed to help businesses generate operational reports such as profit and loss analysis, stock reports, expense monitoring, and requisition tracking.', '["Profit & loss analysis","Stock reports","Expense monitoring","Requisition tracking","Station audits","Lubricant inventory management"]', '#', 'shield-check', '#10b981', '#059669', 3),
('OpenClax', 'openclax', 'Digital Learning & Academic Management', 'A digital learning and academic management platform designed to provide curriculum-aligned textbooks and digital learning resources for schools.', '["Curriculum-aligned textbooks","Digital learning resources","School management tools","Student performance tracking","Certificate generation","Training & conference management"]', '#', 'graduation-cap', '#8b5cf6', '#ec4899', 4);

-- ============================================================
-- 4. JOB VACANCIES
-- ============================================================
CREATE TABLE `job_vacancies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `company_name` VARCHAR(255) DEFAULT 'Miemploya Consult',
    `location` VARCHAR(255) DEFAULT NULL,
    `employment_type` ENUM('full_time','part_time','contract','internship','remote') NOT NULL DEFAULT 'full_time',
    `description` TEXT NOT NULL,
    `requirements` TEXT DEFAULT NULL,
    `deadline` DATE DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 5. JOB APPLICATIONS
-- ============================================================
CREATE TABLE `job_applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vacancy_id` INT NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `address` TEXT DEFAULT NULL,
    `education` TEXT DEFAULT NULL,
    `experience` TEXT DEFAULT NULL,
    `cv_path` VARCHAR(500) DEFAULT NULL,
    `cover_letter` TEXT DEFAULT NULL,
    `status` ENUM('new','reviewed','shortlisted','rejected') NOT NULL DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`vacancy_id`) REFERENCES `job_vacancies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 6. TRAINING PROGRAMS
-- ============================================================
CREATE TABLE `training_programs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `program_date` DATE DEFAULT NULL,
    `venue` VARCHAR(255) DEFAULT NULL,
    `fee` DECIMAL(12,2) DEFAULT 0.00,
    `registration_limit` INT DEFAULT NULL,
    `registrations_count` INT DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 7. TRAINING REGISTRATIONS
-- ============================================================
CREATE TABLE `training_registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `program_id` INT NOT NULL,
    `user_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `organization` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`program_id`) REFERENCES `training_programs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 8. QUARTERLY TRAINING REQUESTS
-- ============================================================
CREATE TABLE `quarterly_training_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(255) NOT NULL,
    `contact_person` VARCHAR(150) NOT NULL,
    `department` VARCHAR(150) DEFAULT NULL,
    `staff_count` INT DEFAULT NULL,
    `training_category` VARCHAR(255) DEFAULT NULL,
    `preferred_period` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 9. BUSINESS TEMPLATES
-- ============================================================
CREATE TABLE `business_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `category` ENUM('hr','business_development','finance','audit','legal','proposal') NOT NULL DEFAULT 'hr',
    `file_path` VARCHAR(500) NOT NULL,
    `file_type` VARCHAR(20) DEFAULT 'pdf',
    `download_count` INT DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 10. VIDEOS
-- ============================================================
CREATE TABLE `videos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT 'general',
    `thumbnail_path` VARCHAR(500) DEFAULT NULL,
    `video_url` VARCHAR(500) NOT NULL,
    `video_source` ENUM('youtube','vimeo','upload') NOT NULL DEFAULT 'youtube',
    `is_featured` TINYINT(1) DEFAULT 0,
    `publish_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 11. NEWS POSTS
-- ============================================================
CREATE TABLE `news_posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image_path` VARCHAR(500) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT 'general',
    `is_featured` TINYINT(1) DEFAULT 0,
    `publish_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 12. POSTERS & FLYERS
-- ============================================================
CREATE TABLE `posters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image_path` VARCHAR(500) NOT NULL,
    `event_date` DATE DEFAULT NULL,
    `registration_link` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 13. PRODUCT PROMOTIONS
-- ============================================================
CREATE TABLE `product_promotions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(500) DEFAULT NULL,
    `short_description` TEXT DEFAULT NULL,
    `external_link` VARCHAR(500) DEFAULT NULL,
    `is_featured` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 14. HOMEPAGE PINS (Content Pinning)
-- ============================================================
CREATE TABLE `homepage_pins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `content_type` ENUM('video','news','product','training') NOT NULL,
    `content_id` INT NOT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
