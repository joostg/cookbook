<?php


spl_autoload_register(function ($classname) {
	$file = __DIR__ . '/' . str_replace('\\','/',$classname) . '.php';
	printr($file);die();

	if (!file_exists($file)) {
		return false;
	}

	require $file;

	return true;

	//require ("../classes/" . $classname . ".php");
});