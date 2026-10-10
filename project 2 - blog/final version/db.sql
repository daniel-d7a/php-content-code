CREATE DATABASE IF NOT EXISTS blog_test;

USE blog_test;

CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name varchar(255) not null,
  email varchar(255) not null unique,
  password_hash varchar(255) not null
);

CREATE TABLE IF NOT EXISTS blogs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title varchar(255) not null,
  content text not null,
  image varchar(255),
  published_at timestamp DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  FOREIGN KEY (user_id) references users(id) ON DELETE SET NULL
);
