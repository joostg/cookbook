<?php

$sql = "CREATE DATABASE IF NOT EXISTS cookbook
			CHARACTER SET = 'utf8'
			COLLATE = 'utf8_bin'
		";

$sql = "CREATE TABLE users (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			user VARCHAR(32) NOT NULL,
			hash VARCHAR(255) NULL
		)";

$sql = "CREATE TABLE IF NOT EXISTS ingredients (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL
		)";

$sql = "CREATE TABLE IF NOT EXISTS quantities (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL
		)";

$sql = "CREATE TABLE IF NOT EXISTS recipes (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL,
			path VARCHAR(50) NOT NULL,
			image VARCHAR(50) NOT NULL,
			intro TEXT NOT NULL,
			description TEXT NOT NULL,
			creator INT(10) NOT NULL DEFAULT 0,
			modifier INT(10) NOT NULL DEFAULT 0,
			created DATETIME NOT NULL DEFAULT 0,
			modified DATETIME NOT NULL DEFAULT 0
		)";

$sql = "CREATE TABLE IF NOT EXISTS recipes_ingredients (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			recipe_id INT NOT NULL,
			ingredient_id INT NOT NULL,
			quantity_id INT NOT NULL,
			quantity DOUBLE(10,2) NOT NULL DEFAULT 0.00
		)";

$sql = "ALTER TABLE recipes_ingredients ADD INDEX recipe_id (recipe_id)";
$sql = "ALTER TABLE recipes_ingredients ADD INDEX ingredient_id (ingredient_id)";
$sql = "ALTER TABLE recipes_ingredients ADD INDEX quantity_id (quantity_id)";

$sql = "GRANT ALL PRIVILEGES ON cookbook.* TO 'masterchef'@'localhost' IDENTIFIED BY 'letmecook' WITH GRANT OPTION;
		FLUSH PRIVILEGES;";
