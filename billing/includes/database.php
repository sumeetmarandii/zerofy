<?php
declare(strict_types=1);

function db(): PDO
{
	static $pdo;
	if ($pdo instanceof PDO) return $pdo;
	$configPath = dirname(__DIR__) . '/config.php';
	if (!is_file($configPath)) throw new RuntimeException('Create config.php and set your MySQL credentials.');
	$config = require $configPath;
	$db = $config['db'];
	$dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
	$pdo = new PDO($dsn, $db['user'], $db['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
	return $pdo;
}
