<?php

spl_autoload_register(function ($classname) {
	$file = __DIR__ . '/../' . str_replace('\\','/',str_replace('cookbook\\','',$classname)) . '.php';

	if (!file_exists($file)) {
		return false;
	}

	require $file;

	return true;
});