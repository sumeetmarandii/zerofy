<?php
declare(strict_types=1);
$pageTitle = 'Account';
$currentPage = 'account.php';
require __DIR__ . '/header.php';
?>
<section class="simple-page-heading">
	<p class="eyebrow">Account</p>
	<h1>Your billing space.</h1>
	<p class="intro-copy">Manage your Firebase account and invoice branding preferences.</p>
</section>
	<section class="account-layout"><article class="settings-card"><p class="eyebrow">Profile</p><h2 data-profile-name>Your account</h2><p class="intro-copy" data-profile-email></p><div class="account-plan"><p class="eyebrow">Current plan</p><strong data-profile-plan>Free plan</strong><p data-profile-plan-copy>Your account has free-plan access.</p></div><form id="firebase-profile-form" class="account-form"><label>Your name<input name="name" required placeholder="Your name"></label><label>Email address<input name="email" type="email" readonly></label><label>Custom invoice header<input name="custom_header" placeholder="Your business name"></label><label class="signature-option"><input type="checkbox" name="hide_header"> Remove header from invoice</label><button class="button button-primary" type="submit">Save profile</button><p class="form-message" role="status"></p></form></article><div class="plan-note"><p class="eyebrow">Plan</p><h2>Firebase account</h2><p>Invoices are saved in Firestore for your account.</p><a class="text-button" href="pricing.php">View plan details -></a></div></section>
<?php require __DIR__ . '/footer.php'; ?>
