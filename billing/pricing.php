<?php
declare(strict_types=1);
$pageTitle = 'Pricing';
$currentPage = 'pricing.php';
require __DIR__ . '/header.php';
?>
<section class="simple-page-heading">
	<p class="eyebrow">Simple pricing</p>
	<h1>One plan. More room to bill.</h1>
	<p class="intro-copy">Start free, then upgrade when your invoice history becomes part of your daily work.</p>
</section>
<section class="pricing-layout">
	<div class="paid-plans">
		<article class="price-card">
			<div class="price-card-top"><span class="status-pill">Monthly</span><span class="price">₹99 <small>/ month</small></span></div>
			<h2>Everything you need to keep moving.</h2>
			<ul>
				<li>Unlimited invoices</li>
				<li>No Biller | ZeroFy watermark</li>
				<li>Custom header name or no header</li>
				<li>Invoice history and invoice downloads</li>
				<li>Early access to new features and products</li>
				<li>Everything included in the free plan</li>
			</ul>
			<button class="button button-primary" type="button" data-plan="paid">Choose monthly plan</button>
		</article>
		<article class="price-card yearly-card">
			<div class="price-card-top"><span class="status-pill">Yearly</span><span class="price"><del>₹1,188</del> ₹999 <small>/ year</small></span></div>
			<h2>Save with a full year.</h2>
			<ul>
				<li>Everything in the monthly plan</li>
				<li>Pay once for 12 months</li>
				<li>Save ₹189 compared with monthly billing</li>
			</ul>
			<button class="button button-primary" type="button" data-plan="paid">Choose yearly plan</button>
		</article>
	</div>
	<div class="plan-note"><p class="eyebrow">Free plan</p><h2>Try the full invoice editor.</h2><p>Create up to 10 invoices with a Biller | ZeroFy watermark. No account is needed to start.</p><a class="text-button" href="index.php">Create an invoice -></a></div>
</section>
<script>
(() => document.querySelectorAll('[data-plan]').forEach((button) => button.addEventListener('click', async () => {
		try {
			await window.firebaseReady;
			if (!window.firebaseAuth.currentUser) { window.location.href = 'signup.php?plan=paid'; return; }
			window.alert('Payment activation is not connected yet. Your account will stay on the free plan until payment verification is added.');
		} catch (error) { window.alert(error.message); }
})))();
</script>
<?php require __DIR__ . '/footer.php'; ?>
