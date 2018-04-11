<?php

CREATE DATABASE IF NOT EXISTS cookbook
			CHARACTER SET = 'utf8'
			COLLATE = 'utf8_bin'
		;

CREATE TABLE users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user VARCHAR(32) NOT NULL, hash VARCHAR(255) NOT NULL);

CREATE TABLE `auth_tokens` (
        `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `selector` char(12),
        `validator` char(255),
        `user_id` integer(11) not null,
        `expires` datetime
    );
CREATE UNIQUE INDEX `selector` ON auth_tokens (selector);

create table logins (
	id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username varchar(16) not null,
	ip_address int(11) unsigned not null,
	attempted datetime not null,
	success int null
);
create index attempted_idx	on logins (attempted);

DROP TABLE ingredients;
DROP TABLE quantities;
TRUNCATE TABLE recipes_ingredients;

CREATE TABLE IF NOT EXISTS ingredients (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL,
			plural VARCHAR(50),
            department INT,
            creator INT(10) NOT NULL DEFAULT 0,
			modifier INT(10) NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
		);
ALTER TABLE ingredients ADD UNIQUE INDEX name (name);

CREATE TABLE IF NOT EXISTS quantities (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL,
			plural VARCHAR(50),
			creator INT(10) NOT NULL DEFAULT 0,
			modifier INT(10) NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
		);
ALTER TABLE quantities ADD UNIQUE INDEX name (name);

CREATE TABLE IF NOT EXISTS recipes (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			path VARCHAR(255) NOT NULL,
			image INT,
			intro TEXT NOT NULL,
			description TEXT NOT NULL,
			creator INT(10) NOT NULL DEFAULT 0,
			modifier INT(10) NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
		);
ALTER TABLE recipes ADD UNIQUE INDEX path (path);

CREATE TABLE IF NOT EXISTS recipe_ingredientrow (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    ingredientrow_id INT NOT NULL,
    `position` INT,
    created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);

ALTER TABLE recipe_ingredientrow ADD INDEX recipe_id (recipe_id);
ALTER TABLE recipe_ingredientrow ADD INDEX ingredientrow_id (ingredientrow_id);

CREATE TABLE IF NOT EXISTS ingredientrows (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ingredient_id INT NOT NULL,
    quantity_id INT,
    amount DOUBLE(10,2),
    created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);

ALTER TABLE ingredientrows ADD INDEX ingredient_id (ingredient_id);
ALTER TABLE ingredientrows ADD INDEX quantity_id (quantity_id);

CREATE TABLE IF NOT EXISTS images (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    path_thumb VARCHAR(255),
    path_recipe_page VARCHAR(255),
    extension VARCHAR(4),
    title VARCHAR(255),
    creator INT(10) NOT NULL DEFAULT 0,
    modifier INT(10) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
    modified_at DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'
);