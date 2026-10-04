-- =====================================================================
-- Blog database initialization script
-- Import via phpMyAdmin, CLI (mysql -u root < database.sql),
-- or simply open init_db.php in the browser.
-- Safe to re-run: drops and recreates all tables.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS migrations;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------
-- posts
-- ---------------------------------------------------------------------
CREATE TABLE posts (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_posts_user_id (user_id),
    CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------
-- sample users
--   admin@admin.com / admin123   (admin)
--   sara@example.com / user123
--   omar@example.com / user123
-- ---------------------------------------------------------------------
INSERT INTO users (name, email, password, role, created_at) VALUES
('Admin',      'admin@admin.com', '$2y$12$LNbYCXzk6si4HoouYsx0CuUUfqzm/jh14bQZEZ5qVWbIptRcDRGiO', 'admin', '2026-01-01 10:00:00'),
('Sara Ahmed', 'sara@example.com', '$2y$12$A0mXseXiA7uPtjL3rRjKAOklTgTbLdeuiYFbLTihZE2QAsrzjNzpO', 'user',  '2026-01-02 09:00:00'),
('Omar Ali',   'omar@example.com', '$2y$12$A0mXseXiA7uPtjL3rRjKAOklTgTbLdeuiYFbLTihZE2QAsrzjNzpO', 'user',  '2026-01-03 09:00:00');

-- ---------------------------------------------------------------------
-- sample posts (image = NULL shows a placeholder in the views)
-- ---------------------------------------------------------------------
INSERT INTO posts (user_id, title, content, image, created_at) VALUES
(1, 'Welcome to the Blog', 'This is the very first post on our new blog.\n\nWe will be sharing news, tutorials and updates here on a regular basis. Thanks for reading, and stay tuned for more!', NULL, '2026-01-05 11:00:00'),
(2, 'Getting Started with PHP', 'PHP is still one of the easiest ways to build dynamic websites.\n\nIn this post we cover the basics: variables, loops, and how to connect to a MySQL database using mysqli with plain function calls - no objects required.', NULL, '2026-01-07 14:30:00'),
(3, 'Why Procedural Code Still Matters', 'Not every project needs frameworks and classes.\n\nProcedural PHP is simple, readable and perfect for small sites like this one. Keep your functions small, escape your output, and use prepared statements.', NULL, '2026-01-09 16:45:00'),
(1, 'Keeping Your Site Secure', 'A few simple habits go a long way: hash your passwords with password_hash(), escape every piece of output with htmlspecialchars(), never trust user input, and only allow logged-in owners to edit their own content.', NULL, '2026-01-11 10:15:00'),
(2, 'Image Uploads Made Simple', 'Uploading files with PHP takes three steps: check $_FILES, validate the extension and MIME type, then move_uploaded_file() to your uploads folder.\n\nAlways rename the file to something random so users can not guess or overwrite paths.', NULL, '2026-01-13 12:00:00'),
(3, 'Database Migrations for Demo Projects', 'A tiny migration system is a great learning exercise.\n\nKeep your SQL in numbered files, record which ones ran in a migrations table, and apply only the pending ones. Our migrate.php does exactly that - and is completely optional.', NULL, '2026-01-15 09:30:00'),
(1, 'Centralized Routing with a Query Param', 'Instead of one PHP file per page, this site uses a single entry point: index.php?page=login.\n\nThe router validates the page name against a whitelist, lets the controller handle any POST data, and then includes the matching view. Simple and safe.', NULL, '2026-01-17 18:20:00'),
(2, 'What Is Coming Next', 'Future posts will cover form validation, sessions, flash messages, and how to structure a small project so it stays easy to maintain.\n\nDo you have a topic request? Leave a comment and we will consider it.', NULL, '2026-01-19 20:00:00');
