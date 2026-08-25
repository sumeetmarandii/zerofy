<?php
declare(strict_types=1);
$pageTitle = 'Sign up';
$currentPage = 'account.php';
require __DIR__ . '/header.php';
?>
	<section class="auth-page"><div class="auth-card"><p class="eyebrow">Start free</p><h1>Make your account.</h1><p class="intro-copy">Keep your invoice history securely in Firebase Storage.</p><p class="form-message" role="alert"></p><form id="firebase-signup-form" class="account-form"><label>Your name<input name="name" required placeholder="Your name"></label><label>Email address<input name="email" type="email" required placeholder="you@example.com"></label><label>Password<input name="password" type="password" minlength="8" required placeholder="At least 8 characters"></label><button class="button button-primary" type="submit">Create free account</button></form><p class="auth-switch">Already have an account? <a class="text-button" href="login.php">Log in</a></p></div></section>
<?php require __DIR__ . '/footer.php'; ?>
