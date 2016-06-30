<?php

$sql = "CREATE DATABASE IF NOT EXISTS cookbook
			CHARACTER SET = 'utf8'
			COLLATE = 'utf8_bin'
		";

$sql = "CREATE TABLE IF NOT EXISTS users (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			username VARCHAR(50) NOT NULL,
			role VARCHAR(50) NOT NULL,
			password VARCHAR(255) NULL
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
		)";

$sql = "CREATE TABLE IF NOT EXISTS recipes_ingredients (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			recipe_id INT NOT NULL,
			ingredient_id INT NOT NULL,
			quantity_id INT NOT NULL,
			quantity DOUBLE(10,2) NOT NULL DEFAULT 0.00,
		)";
