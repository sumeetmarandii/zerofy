<?php
declare(strict_types=1);
$pageTitle = 'Log in';
$currentPage = 'account.php';
require __DIR__ . '/header.php';
?>
	<section class="auth-page"><div class="auth-card"><p class="eyebrow">Welcome back</p><h1>Log in to Biller.</h1><p class="intro-copy">Access your invoice history and profile settings.</p><p class="form-message" role="alert"></p><form id="firebase-login-form" class="account-form"><label>Email address<input name="email" type="email" required placeholder="you@example.com"></label><label>Password<input name="password" type="password" required placeholder="Your password"></label><button class="button button-primary" type="submit">Log in</button></form><p class="auth-switch">New here? <a class="text-button" href="signup.php">Create an account</a></p></div></section>
<?php require __DIR__ . '/footer.php'; ?>
