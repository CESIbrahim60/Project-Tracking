
-- Create Database
CREATE DATABASE IF NOT EXISTS `security`;
USE `security`;

-- ============================================================
-- Table: users
-- Description: User accounts with role-based access control
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(100) UNIQUE NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20),
    `role` ENUM('admin', 'client', 'technician', 'sales') NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_role` (`role`),
    INDEX `idx_status` (`status`),
    INDEX `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: clients
-- Description: Client company information
-- ============================================================
CREATE TABLE IF NOT EXISTS `clients` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `company_name` VARCHAR(200) NOT NULL,
    `contact_person` VARCHAR(150),
    `phone` VARCHAR(20),
    `email` VARCHAR(100),
    `address` TEXT,
    `city` VARCHAR(100),
    `country` VARCHAR(100),
    `notes` TEXT,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_status` (`status`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_company_name` (`company_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: projects
-- Description: CCTV and security projects
-- ============================================================
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `client_id` INT NOT NULL,
    `project_name` VARCHAR(200) NOT NULL,
    `description` TEXT,
    `project_type` VARCHAR(100),
    `location` VARCHAR(200),
    `start_date` DATE,
    `end_date` DATE,
    `budget` DECIMAL(12, 2),
    `progress_percentage` INT DEFAULT 0,
    `status` ENUM('planning', 'in_progress', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning',
    `assigned_technician_id` INT,
    `assigned_sales_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_technician_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_sales_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client_id` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_progress` (`progress_percentage`),
    INDEX `idx_technician_id` (`assigned_technician_id`),
    INDEX `idx_sales_id` (`assigned_sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: project_media
-- Description: Project images and videos
-- ============================================================
CREATE TABLE IF NOT EXISTS `project_media` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `project_id` INT NOT NULL,
    `media_type` ENUM('image', 'video') NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `uploaded_by` INT NULL,
    `description` TEXT,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_project_id` (`project_id`),
    INDEX `idx_media_type` (`media_type`),
    INDEX `idx_uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: progress_updates
-- Description: Technician progress reports
-- ============================================================
CREATE TABLE IF NOT EXISTS `progress_updates` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `project_id` INT NOT NULL,
    `technician_id` INT NULL,
    `progress_percentage` INT,
    `report_text` TEXT,
    `update_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`technician_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,

    INDEX `idx_project_id` (`project_id`),
    INDEX `idx_technician_id` (`technician_id`),
    INDEX `idx_update_date` (`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: feedback
-- Description: Client feedback and notes on projects
-- ============================================================
CREATE TABLE IF NOT EXISTS `feedback` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `project_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `feedback_text` TEXT NOT NULL,
    `feedback_type` ENUM('note', 'issue', 'suggestion', 'approval') DEFAULT 'note',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_project_id` (`project_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_feedback_type` (`feedback_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: leads
-- Description: Sales leads pipeline
-- ============================================================
CREATE TABLE IF NOT EXISTS `leads` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `lead_name` VARCHAR(150) NOT NULL,
    `company_name` VARCHAR(200),
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `project_type` VARCHAR(100),
    `budget_range` VARCHAR(100),
    `assigned_sales_id` INT,
    `status` ENUM('new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost') DEFAULT 'new',
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`assigned_sales_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_status` (`status`),
    INDEX `idx_assigned_sales` (`assigned_sales_id`),
    INDEX `idx_lead_name` (`lead_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: sessions
-- Description: User session management
-- ============================================================
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `session_token` VARCHAR(255) UNIQUE NOT NULL,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,

    INDEX `idx_session_token` (`session_token`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INSERT DEMO DATA
-- ============================================================

-- Admin User (password: admin123)
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role`, `status`) 
VALUES ('admin', 'admin@maysan.com', '$2y$10$YourHashedPassword1', 'مدير النظام', '+966501234567', 'admin', 'active');

-- Client User (password: client123)
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role`, `status`) 
VALUES ('client1', 'client1@example.com', '$2y$10$YourHashedPassword2', 'عميل تجريبي', '+966502345678', 'client', 'active');

-- Technician User (password: tech123)
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role`, `status`) 
VALUES ('technician1', 'tech1@maysan.com', '$2y$10$YourHashedPassword3', 'فني متخصص', '+966503456789', 'technician', 'active');

-- Sales User (password: sales123)
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role`, `status`) 
VALUES ('sales1', 'sales1@maysan.com', '$2y$10$YourHashedPassword4', 'موظف المبيعات', '+966504567890', 'sales', 'active');

-- Sample Client Company
INSERT IGNORE INTO `clients` (`user_id`, `company_name`, `contact_person`, `phone`, `email`, `city`, `country`, `status`) 
VALUES (2, 'شركة الأمان للتجارة', 'محمد علي', '+966501111111', 'info@alaman.com', 'الرياض', 'السعودية', 'active');

-- Sample Project
INSERT IGNORE INTO `projects` (`client_id`, `project_name`, `description`, `project_type`, `location`, `start_date`, `budget`, `progress_percentage`, `status`, `assigned_technician_id`, `assigned_sales_id`) 
VALUES (1, 'نظام مراقبة متقدم', 'تركيب نظام CCTV متكامل', 'CCTV System', 'الرياض - حي النخيل', '2025-01-15', 50000.00, 0, 'planning', 3, 4);

-- ============================================================
-- NOTES
-- ============================================================
-- 1. Replace the hashed passwords in the INSERT statements with actual bcrypt hashes
--    Use PHP: password_hash('password', PASSWORD_BCRYPT)
-- 2. All tables use utf8mb4 charset for full Arabic support
-- 3. Proper indexes are included for performance optimization
-- 4. Foreign keys ensure data integrity
-- 5. Timestamps are automatically managed
-- ============================================================
