<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Invoice workspace';
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Create simple, professional invoices with Biller | ZeroFy.">
	<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Biller | ZeroFy</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
	<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>
	<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore-compat.js"></script>
	<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database-compat.js"></script>
	<script src="firebase-config.js"></script>
	<script src="firebase-client.js"></script>
</head>
<body>
<header class="site-header">
	<a class="brand" href="index.php" aria-label="Biller ZeroFy home">
		<span class="brand-mark">B</span>
		<span>Biller <em>|</em> ZeroFy</span>
	</a>
	<nav class="top-nav" aria-label="Primary navigation">
		<a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Create invoice</a>
		<a class="<?= $currentPage === 'history.php' ? 'active' : '' ?>" href="history.php">History</a>
		<a class="<?= $currentPage === 'pricing.php' ? 'active' : '' ?>" href="pricing.php">Pricing</a>
		<a class="<?= $currentPage === 'account.php' ? 'active' : '' ?>" href="account.php" data-auth-link>Log in</a>
		<a href="logout.php" data-firebase-logout hidden>Log out</a>
	</nav>
</header>
<main class="page-shell">
