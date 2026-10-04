-- =====================================================================
-- Migration 003: sample data
-- Idempotent: users rely on the unique email key, posts check their
-- own title, so re-running never creates duplicates.
--
--   admin@admin.com / admin123   (admin)
--   sara@example.com / user123
--   omar@example.com / user123
-- =====================================================================

INSERT IGNORE INTO users (name, email, password, role, created_at) VALUES
('Admin',      'admin@admin.com', '$2y$12$LNbYCXzk6si4HoouYsx0CuUUfqzm/jh14bQZEZ5qVWbIptRcDRGiO', 'admin', '2026-01-01 10:00:00'),
('Sara Ahmed', 'sara@example.com', '$2y$12$A0mXseXiA7uPtjL3rRjKAOklTgTbLdeuiYFbLTihZE2QAsrzjNzpO', 'user',  '2026-01-02 09:00:00'),
('Omar Ali',   'omar@example.com', '$2y$12$A0mXseXiA7uPtjL3rRjKAOklTgTbLdeuiYFbLTihZE2QAsrzjNzpO', 'user',  '2026-01-03 09:00:00');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 1, 'Welcome to the Blog', 'This is the very first post on our new blog.\n\nWe will be sharing news, tutorials and updates here on a regular basis. Thanks for reading, and stay tuned for more!', NULL, '2026-01-05 11:00:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Welcome to the Blog');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 2, 'Getting Started with PHP', 'PHP is still one of the easiest ways to build dynamic websites.\n\nIn this post we cover the basics: variables, loops, and how to connect to a MySQL database using mysqli with plain function calls - no objects required.', NULL, '2026-01-07 14:30:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Getting Started with PHP');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 3, 'Why Procedural Code Still Matters', 'Not every project needs frameworks and classes.\n\nProcedural PHP is simple, readable and perfect for small sites like this one. Keep your functions small, escape your output, and use prepared statements.', NULL, '2026-01-09 16:45:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Why Procedural Code Still Matters');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 1, 'Keeping Your Site Secure', 'A few simple habits go a long way: hash your passwords with password_hash(), escape every piece of output with htmlspecialchars(), never trust user input, and only allow logged-in owners to edit their own content.', NULL, '2026-01-11 10:15:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Keeping Your Site Secure');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 2, 'Image Uploads Made Simple', 'Uploading files with PHP takes three steps: check $_FILES, validate the extension and MIME type, then move_uploaded_file() to your uploads folder.\n\nAlways rename the file to something random so users can not guess or overwrite paths.', NULL, '2026-01-13 12:00:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Image Uploads Made Simple');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 3, 'Database Migrations for Demo Projects', 'A tiny migration system is a great learning exercise.\n\nKeep your SQL in numbered files, record which ones ran in a migrations table, and apply only the pending ones. Our migrate.php does exactly that - and is completely optional.', NULL, '2026-01-15 09:30:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Database Migrations for Demo Projects');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 1, 'Centralized Routing with a Query Param', 'Instead of one PHP file per page, this site uses a single entry point: index.php?page=login.\n\nThe router validates the page name against a whitelist, lets the controller handle any POST data, and then includes the matching view. Simple and safe.', NULL, '2026-01-17 18:20:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'Centralized Routing with a Query Param');

INSERT INTO posts (user_id, title, content, image, created_at)
SELECT 2, 'What Is Coming Next', 'Future posts will cover form validation, sessions, flash messages, and how to structure a small project so it stays easy to maintain.\n\nDo you have a topic request? Leave a comment and we will consider it.', NULL, '2026-01-19 20:00:00'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM posts WHERE title = 'What Is Coming Next');
