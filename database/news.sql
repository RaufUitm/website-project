-- SQL: create database and news table for Tajdid
-- NOTE: This file now targets `tajdid_db`. If you already created `tajdid_db` in HeidiSQL,
-- import the `news_for_existing_db.sql` file instead which only creates the table.
CREATE DATABASE IF NOT EXISTS `tajdid_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tajdid_db`;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT NULL,
  `content` LONGTEXT NOT NULL,
  `image` TEXT NULL,
  `category` VARCHAR(100) DEFAULT 'general',
  `is_featured` TINYINT(1) DEFAULT 0,
  `views` INT UNSIGNED DEFAULT 0,
  `published_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample insert
INSERT INTO `news` (`title`, `slug`, `excerpt`, `content`, `image`, `category`, `is_featured`, `published_at`)
VALUES
('Welcome to Tajdid', 'welcome-to-tajdid', 'Introduction to Tajdid and our mission', '<p>This is an example news post. Replace with real content.</p>', NULL, 'general', 1, NOW());
