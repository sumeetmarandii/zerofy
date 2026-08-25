<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function current_user(): ?array
{
	if (empty($_SESSION['user_id'])) return null;
	$stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
	$stmt->execute([$_SESSION['user_id']]);
	return $stmt->fetch() ?: null;
}

function require_login(): array
{
	$user = current_user();
	if (!$user) { header('Location: signup.php'); exit; }
	return $user;
}

function csrf_token(): string
{
	if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
	return $_SESSION['csrf'];
}

function verify_csrf(): void
{
	if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(403); exit('Invalid request.'); }
}
