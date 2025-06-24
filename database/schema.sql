-- database/schema.sql

-- Table for Users
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('student', 'college', 'admin') NOT NULL DEFAULT 'student',
    `email_verified_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Colleges
CREATE TABLE `colleges` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL, -- Link to user if college has a login
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `logo_url` VARCHAR(255),
    `website` VARCHAR(255),
    `address` VARCHAR(255),
    `city` VARCHAR(255),
    `state_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- Table for Scholarship Categories
CREATE TABLE `scholarship_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Scholarship Boards (e.g., CBSE, ICSE, State Boards)
CREATE TABLE `scholarship_boards` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for States (for scholarships and colleges)
CREATE TABLE `states` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `abbreviation` VARCHAR(10) UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Scholarships
CREATE TABLE `scholarships` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `type` ENUM('Cash', 'Fee Waiver', 'Gift', 'Certificate') NOT NULL,
    `amount` DECIMAL(10, 2) NULL,
    `min_percentage` DECIMAL(5, 2) NULL,
    `deadline` DATE NOT NULL,
    `provider_id` INT NULL, -- Can be a college_id or admin_id
    `provider_type` ENUM('college', 'admin') NOT NULL,
    `logo_url` VARCHAR(255) NULL,
    `featured` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`provider_id`) REFERENCES `colleges`(`id`) ON DELETE CASCADE -- If provider is a college
    -- Note: If provider_type is 'admin', provider_id would refer to an admin user ID, which is not directly linked here for simplicity.
);

-- Junction table for Scholarships and Categories (Many-to-Many)
CREATE TABLE `scholarship_has_categories` (
    `scholarship_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    PRIMARY KEY (`scholarship_id`, `category_id`),
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `scholarship_categories`(`id`) ON DELETE CASCADE
);

-- Junction table for Scholarships and Boards (Many-to-Many)
CREATE TABLE `scholarship_has_boards` (
    `scholarship_id` INT NOT NULL,
    `board_id` INT NOT NULL,
    PRIMARY KEY (`scholarship_id`, `board_id`),
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`board_id`) REFERENCES `scholarship_boards`(`id`) ON DELETE CASCADE
);

-- Junction table for Scholarships and States (Many-to-Many, indicating applicable states)
CREATE TABLE `scholarship_has_states` (
    `scholarship_id` INT NOT NULL,
    `state_id` INT NOT NULL,
    PRIMARY KEY (`scholarship_id`, `state_id`),
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE CASCADE
);

-- Table for Student Applications
CREATE TABLE `applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `scholarship_id` INT NOT NULL,
    `application_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending', 'approved', 'rejected', 'shortlisted') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`) ON DELETE CASCADE,
    UNIQUE (`student_id`, `scholarship_id`) -- A student can apply to a scholarship only once
);

-- Table for Bookmarked Scholarships (Shortlist/Save for later)
CREATE TABLE `bookmarks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `scholarship_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`) ON DELETE CASCADE,
    UNIQUE (`student_id`, `scholarship_id`) -- A student can bookmark a scholarship only once
);
