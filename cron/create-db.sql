CREATE DATABASE IF NOT EXISTS cookbook
			CHARACTER SET = 'utf8'
			COLLATE = 'utf8_bin'
		;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS auth_tokens;
DROP TABLE IF EXISTS logins;
DROP TABLE IF EXISTS `ingredients`;
DROP TABLE IF EXISTS `quantities`;
DROP TABLE IF EXISTS `recipes_ingredients`;
DROP TABLE IF EXISTS `ingredientrows`;
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `images`;

CREATE TABLE `users` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `firstname` varchar(50),
    `name` varchar(50),
    `email` varchar(100),
    `forgotpass_hash` varchar(255),
    `forgotpass_expiration` datetime,
    `created_by` INT(10) NOT NULL DEFAULT 0,
    `updated_by` INT(10) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
CREATE UNIQUE INDEX `username` ON `users` (username);

CREATE TABLE `auth_tokens` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `selector` char(12),
    `validator` char(255),
    `user_id` integer(11) not null,
    `expires` datetime,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
CREATE UNIQUE INDEX `selector` ON `auth_tokens` (selector);

create table logins (
	`id`  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`username` varchar(32) not null,
	`ip_address` int(11) unsigned not null,
	`attempted` datetime not null,
	`success` int null,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
create index `attempted_idx` ON logins (`attempted`);

CREATE TABLE IF NOT EXISTS `ingredients` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `plural` VARCHAR(50),
    `department` INT,
    `created_by` INT(10) NOT NULL DEFAULT 0,
    `updated_by` INT(10) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
ALTER TABLE `ingredients` ADD UNIQUE INDEX `name` (`name`);

CREATE TABLE IF NOT EXISTS `quantities` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `plural` VARCHAR(50),
    `created_by` INT(10) NOT NULL DEFAULT 0,
    `updated_by` INT(10) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
ALTER TABLE `quantities` ADD UNIQUE INDEX `name` (`name`);

CREATE TABLE IF NOT EXISTS `recipes` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `image_id` INT,
    `intro` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `created_by` INT(10) NOT NULL DEFAULT 0,
    `updated_by` INT(10) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
ALTER TABLE `recipes` ADD UNIQUE INDEX `path` (`path`);

CREATE TABLE IF NOT EXISTS `ingredientrows` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `recipe_id` INT NOT NULL,
    `ingredient_id` INT NOT NULL,
    `quantity_id` INT,
    `amount` DOUBLE(10,2),
    `position` INT,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`)
       ON DELETE CASCADE
       ON UPDATE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
ALTER TABLE `ingredientrows` ADD INDEX `recipe_id` (`recipe_id`);
ALTER TABLE `ingredientrows` ADD INDEX `ingredient_id` (`ingredient_id`);
ALTER TABLE `ingredientrows` ADD INDEX `quantity_id` (`quantity_id`);

CREATE TABLE IF NOT EXISTS images (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `path_thumb` VARCHAR(255),
    `path_recipe_page` VARCHAR(255),
    `extension` VARCHAR(4),
    `title` VARCHAR(255),
    `created_by` INT(10) NOT NULL DEFAULT 0,
    `updated_by` INT(10) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
     `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     `name` VARCHAR(255) NOT NULL,
     `path` VARCHAR(255) NOT NULL,
     `created_by` INT(10) NOT NULL DEFAULT 0,
     `updated_by` INT(10) NOT NULL DEFAULT 0,
     `created_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
     `updated_at` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);
ALTER TABLE `tags` ADD UNIQUE INDEX `name` (`name`);

DROP TABLE IF EXISTS `recipe_tag`;
CREATE TABLE `recipe_tag` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `recipe_id` INT NOT NULL,
    `tag_id` INT NOT NULL
);
ALTER TABLE `recipe_tag` ADD UNIQUE INDEX `recipe_tag` (`recipe_id`, `tag_id`);