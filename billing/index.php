<?php
declare(strict_types=1);

$user = null;

$pageTitle = 'Create invoice';
require __DIR__ . '/header.php';
?>
<section class="intro-row">
	<div>
		<p class="eyebrow">Invoice workspace</p>
		<h1>Make getting paid feel lighter.</h1>
		<p class="intro-copy">Create a clean invoice in minutes. No account needed for your first 10 invoices.</p>
	</div>
	<div class="free-counter"><span class="counter-dot"></span> <strong>Free mode</strong><br><span><span id="free-count">0</span> of 10 invoices used</span></div>
</section>

<div id="success-message" class="notice" role="status" hidden>
	Invoice saved. You can find it in your history or print it as a PDF.
</div>

<form id="invoice-form" class="workspace" data-plan="guest" data-custom-header="" data-hide-header="0" novalidate>
	<section class="invoice-panel">
		<div class="panel-heading">
			<div>
				<p class="eyebrow">01 / Details</p>
				<h2>Your invoice</h2>
			</div>
			<label class="compact-field">Currency
				<select id="currency" name="currency">
					<option value="USD">USD</option>
					<option value="EUR">EUR</option>
					<option value="GBP">GBP</option>
					<option value="INR" selected>INR</option>
					<option value="AUD">AUD</option>
				</select>
			</label>
		</div>
		<div class="form-grid two-col">
			<label>Biller name<input name="biller_name" placeholder="Your business name" required></label>
			<label>Invoice number<input id="invoice-number" name="invoice_number" value="ZF-0001"></label>
			<label class="wide">Biller address<textarea name="biller_address" rows="2" placeholder="Street, city, country"></textarea></label>
			<label>Invoice date<input name="invoice_date" type="date" value="<?= date('Y-m-d') ?>"></label>
			<label>Due date<input name="due_date" type="date"></label>
		</div>
		<div class="paid-options" data-auth-only hidden>
			<div><p class="eyebrow">Paid workspace</p><h3>Invoice branding</h3></div>
			<label>Custom header name<input name="custom_header" placeholder="Your business name"></label>
			<label class="signature-option"><input type="checkbox" name="use_custom_header"> Use custom header</label>
			<label class="signature-option"><input type="checkbox" name="hide_header"> Remove header from invoice</label>
			<p class="paid-only-note">Available with the ₹99/month plan. Includes unlimited invoices, no watermark, and early access to new features.</p>
		</div><div class="paid-options locked-options" data-auth-guest><p class="eyebrow">Paid-only branding</p><p class="paid-only-note">Custom invoice branding and PDF printing unlock with a paid plan.</p><a class="text-button" href="pricing.php">View paid plans -></a></div>

		<div class="section-divider"></div>
		<div class="panel-heading compact-heading"><div><p class="eyebrow">02 / Recipient</p><h2>Bill to</h2></div></div>
		<div class="form-grid two-col">
			<label>Customer name<input name="customer_name" placeholder="Client or company name" required></label>
			<label>Customer email<input name="customer_email" type="email" placeholder="hello@example.com"></label>
			<label class="wide">Customer address<textarea name="customer_address" rows="2" placeholder="Street, city, country"></textarea></label>
		</div>

		<div class="section-divider"></div>
		<div class="panel-heading compact-heading">
			<div><p class="eyebrow">03 / Line items</p><h2>What are you billing for?</h2></div>
			<button class="text-button" type="button" data-add-item>+ Add item</button>
		</div>
		<div class="items-header"><span>S.No.</span><span>Description</span><span>Qty</span><span>Price</span><span>Amount</span><span></span></div>
		<div id="item-list"></div>
		<template id="item-template"><div class="item-row">
			<output data-serial aria-label="Item serial number">1</output>
			<input data-name name="item_name[]" placeholder="Service or product" aria-label="Item description">
			<input data-quantity name="quantity[]" type="number" min="0" step="1" value="1" aria-label="Quantity">
			<input data-price name="price[]" type="number" min="0" step="0.01" placeholder="0.00" aria-label="Unit price">
			<output data-line-total>$ 0.00</output>
			<button class="remove-button" type="button" data-remove aria-label="Remove item">Remove</button>
		</div></template>

		<div class="section-divider"></div>
		<div class="form-grid two-col adjustments">
			<label>Tax rate (%)<input id="tax-rate" name="tax_rate" type="number" min="0" step="0.01" value="0"></label>
			<label>Discount<input id="discount" name="discount" type="number" min="0" step="0.01" value="0"></label>
			<label class="wide">Notes<textarea name="notes" rows="3" placeholder="Thank you for your business."></textarea></label>
		</div>
	</section>

	<aside class="summary-panel">
		<div class="summary-top"><span class="eyebrow">Invoice preview</span><span class="status-pill">Draft</span></div>
		<div class="preview-mark">B</div>
		<p class="preview-label">Billing | ZeroFy</p>
		<p class="preview-recipient">Your invoice will be ready to print here.</p>
		<div class="totals">
			<div><span>Subtotal</span><strong id="subtotal-output">$ 0.00</strong></div>
			<div><span>Tax</span><strong id="tax-output">$ 0.00</strong></div>
			<div class="total-line"><span>Total due</span><strong id="total-output">$ 0.00</strong></div>
		</div>
		<div class="summary-actions">
			<button class="button button-primary" type="submit">Save invoice</button>
			<button class="button button-preview" type="button" data-preview>Preview invoice</button>
			<button class="button button-quiet" type="button" data-reset>Clear draft</button>
		</div>
		<label class="signature-option"><input type="checkbox" name="include_signature" checked> Add signature and stamp area</label>
		<div class="upgrade-note" data-guest-only hidden><strong>Need invoice history?</strong><p>Sign up for unlimited invoices without the watermark.</p><button class="text-button" type="button" data-account>Explore paid access -></button></div>
	</aside>
</form>

<section id="history" class="history-section">
	<div class="section-heading"><div><p class="eyebrow">Your workspace</p><h2>Invoice history</h2></div><span id="account-status" class="status-pill">Guest mode</span></div>
	<div id="history-list" class="history-list"><p class="empty-state">Saved invoices will appear here after you save an invoice.</p></div>
</section>

<div id="account-modal" class="modal" hidden>
	<div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="account-title">
		<button class="modal-close" type="button" data-close-modal aria-label="Close">&times;</button>
		<p class="eyebrow">Biller | ZeroFy</p><h2 id="account-title">Keep every invoice.</h2>
		<p class="modal-copy">Create your free account, then keep invoices securely in Firebase Storage.</p>
		<form id="account-form" class="account-form">
			<label>Your name<input name="account_name" required placeholder="Your name"></label>
			<label>Email address<input name="account_email" type="email" required placeholder="you@example.com"></label>
			<button class="button button-primary" type="submit">Create free account</button>
		</form>
		<div class="plan-card"><div><strong>Paid workspace</strong><span>₹99 / month or ₹999 / year</span></div><p>Unlimited invoices, no watermark, custom headers, early access to new features and products.</p><a class="button button-download" href="pricing.php">View paid plans</a></div>
		<p id="account-message" class="form-message" role="status"></p>
	</div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
