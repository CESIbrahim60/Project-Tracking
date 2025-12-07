<?php
/**
 * Database Schema Initialization
 * Maysan Al-Riyidh CCTV Security Systems
 */

// Database connection settings (without selecting database)
$conn = new mysqli('localhost', 'root', '', '', 3306);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS maysan_security";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select database
$conn->select_db('maysan_security');

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'client', 'technician', 'sales') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully.\n";
} else {
    echo "Error creating users table: " . $conn->error . "\n";
}

// Create clients table
$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    company_name VARCHAR(200) NOT NULL,
    contact_person VARCHAR(150),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    city VARCHAR(100),
    country VARCHAR(100),
    notes TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Clients table created successfully.\n";
} else {
    echo "Error creating clients table: " . $conn->error . "\n";
}

// Create projects table
$sql = "CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    project_name VARCHAR(200) NOT NULL,
    description TEXT,
    project_type VARCHAR(100),
    location VARCHAR(200),
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12, 2),
    progress_percentage INT DEFAULT 0,
    status ENUM('planning', 'in_progress', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning',
    assigned_technician_id INT,
    assigned_sales_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_technician_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_sales_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_client_id (client_id),
    INDEX idx_status (status),
    INDEX idx_progress (progress_percentage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Projects table created successfully.\n";
} else {
    echo "Error creating projects table: " . $conn->error . "\n";
}

// Create project media table
$sql = "CREATE TABLE IF NOT EXISTS project_media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    media_type ENUM('image', 'video') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    description TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_project_id (project_id),
    INDEX idx_media_type (media_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Project media table created successfully.\n";
} else {
    echo "Error creating project media table: " . $conn->error . "\n";
}

// Create progress updates table
$sql = "CREATE TABLE IF NOT EXISTS progress_updates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    technician_id INT NOT NULL,
    progress_percentage INT,
    report_text TEXT,
    update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_project_id (project_id),
    INDEX idx_technician_id (technician_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Progress updates table created successfully.\n";
} else {
    echo "Error creating progress updates table: " . $conn->error . "\n";
}

// Create feedback/notes table
$sql = "CREATE TABLE IF NOT EXISTS feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    feedback_text TEXT NOT NULL,
    feedback_type ENUM('note', 'issue', 'suggestion', 'approval') DEFAULT 'note',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_project_id (project_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Feedback table created successfully.\n";
} else {
    echo "Error creating feedback table: " . $conn->error . "\n";
}

// Create leads table
$sql = "CREATE TABLE IF NOT EXISTS leads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lead_name VARCHAR(150) NOT NULL,
    company_name VARCHAR(200),
    email VARCHAR(100),
    phone VARCHAR(20),
    project_type VARCHAR(100),
    budget_range VARCHAR(100),
    assigned_sales_id INT,
    status ENUM('new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost') DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_sales_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_assigned_sales (assigned_sales_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Leads table created successfully.\n";
} else {
    echo "Error creating leads table: " . $conn->error . "\n";
}

// Create sessions table
$sql = "CREATE TABLE IF NOT EXISTS sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_session_token (session_token),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Sessions table created successfully.\n";
} else {
    echo "Error creating sessions table: " . $conn->error . "\n";
}

// Insert sample admin user (password: admin123)
$admin_password = password_hash('admin123', PASSWORD_BCRYPT);
$sql = "INSERT IGNORE INTO users (username, email, password, full_name, phone, role, status) 
        VALUES ('admin', 'admin@maysan.com', '$admin_password', 'مدير النظام', '+966501234567', 'admin', 'active')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully.\n";
} else {
    echo "Admin user already exists or error: " . $conn->error . "\n";
}

// Insert sample client user (password: client123)
$client_password = password_hash('client123', PASSWORD_BCRYPT);
$sql = "INSERT IGNORE INTO users (username, email, password, full_name, phone, role, status) 
        VALUES ('client1', 'client1@example.com', '$client_password', 'عميل تجريبي', '+966502345678', 'client', 'active')";

if ($conn->query($sql) === TRUE) {
    echo "Sample client user created successfully.\n";
} else {
    echo "Sample client user already exists or error: " . $conn->error . "\n";
}

// Insert sample technician user (password: tech123)
$tech_password = password_hash('tech123', PASSWORD_BCRYPT);
$sql = "INSERT IGNORE INTO users (username, email, password, full_name, phone, role, status) 
        VALUES ('technician1', 'tech1@maysan.com', '$tech_password', 'فني متخصص', '+966503456789', 'technician', 'active')";

if ($conn->query($sql) === TRUE) {
    echo "Sample technician user created successfully.\n";
} else {
    echo "Sample technician user already exists or error: " . $conn->error . "\n";
}

// Insert sample sales user (password: sales123)
$sales_password = password_hash('sales123', PASSWORD_BCRYPT);
$sql = "INSERT IGNORE INTO users (username, email, password, full_name, phone, role, status) 
        VALUES ('sales1', 'sales1@maysan.com', '$sales_password', 'موظف المبيعات', '+966504567890', 'sales', 'active')";

if ($conn->query($sql) === TRUE) {
    echo "Sample sales user created successfully.\n";
} else {
    echo "Sample sales user already exists or error: " . $conn->error . "\n";
}

echo "\n=== Database initialization completed successfully! ===\n";
echo "Sample Users:\n";
echo "Admin: admin / admin123\n";
echo "Client: client1 / client123\n";
echo "Technician: technician1 / tech123\n";
echo "Sales: sales1 / sales123\n";

$conn->close();
?>
