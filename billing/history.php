<?php
declare(strict_types=1);
$pageTitle = 'Invoice history';
$currentPage = 'history.php';
require __DIR__ . '/header.php';
?>
<section class="simple-page-heading">
	<p class="eyebrow">Your workspace</p>
	<h1>Invoice history</h1>
	<p class="intro-copy">Find, preview, and share invoices saved in Firebase Storage.</p>
</section>
<section class="history-section standalone-history">
	<div id="history-list" class="history-list"><p class="empty-state">Sign in to load your invoices.</p></div>
</section>
<script>
(() => {
	const historyList = document.querySelector('#history-list');
	const safe = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[character]));
	const invoiceData = (invoice) => invoice.data || invoice;
	const invoiceLink = (invoice) => {
		const encoded = btoa(unescape(encodeURIComponent(JSON.stringify(invoiceData(invoice)))));
		const url = new URL('index.php', window.location.href);
		url.hash = `invoice=${encodeURIComponent(encoded)}`;
		return url.href;
	};
	const render = (history) => {
	if (!history.length) { historyList.innerHTML = '<p class="empty-state">No invoices yet. <a class="text-button" href="index.php">Create your first invoice</a></p>'; return; }
	historyList.innerHTML = history.map((invoice) => `<article class="history-item"><div><strong>${safe(invoice.invoice_number)}</strong><span>${safe(invoice.customer_name || 'No customer')} · ${safe(invoice.invoice_date)}</span></div><strong>${safe(invoice.total)}</strong><div class="history-actions"><button class="text-button" type="button" data-preview="${safe(invoice.id)}">Preview</button><button class="text-button" type="button" data-delete="${safe(invoice.id)}">Delete</button></div></article>`).join('');
	historyList.querySelectorAll('[data-preview]').forEach((button) => button.addEventListener('click', () => {
		const invoice = history.find((entry) => entry.id === button.dataset.preview);
		if (invoice) window.open(invoiceLink(invoice), 'invoice-preview', 'width=850,height=900');
	}));
	historyList.querySelectorAll('[data-delete]').forEach((button) => button.addEventListener('click', async () => {
		const invoice = history.find((entry) => entry.id === button.dataset.delete);
		if (!invoice || !window.confirm(`Delete invoice ${invoice.invoice_number}?`)) return;
		button.disabled = true;
		try {
			await window.firebaseInvoiceStore.remove(invoice.id);
			const remaining = history.filter((entry) => entry.id !== invoice.id);
			localStorage.setItem('zerofy-invoice-history', JSON.stringify(remaining));
			const count = Number(localStorage.getItem('zerofy-generated-count') || 0);
			localStorage.setItem('zerofy-generated-count', String(Math.max(0, count - 1)));
			render(remaining);
		} catch (error) {
			button.disabled = false;
			historyList.insertAdjacentHTML('afterbegin', `<p class="form-message">Could not delete invoice: ${safe(error.message)}</p>`);
		}
	}));
	};
	if (window.firebaseAuth) window.firebaseAuth.onAuthStateChanged(async (user) => {
		if (!user) { window.location.href = 'login.php'; return; }
		try { render(await window.firebaseInvoiceStore.load()); } catch (error) { historyList.innerHTML = `<p class="empty-state">Could not load invoices: ${safe(error.message)}</p>`; }
	});
})();
</script>
<?php require __DIR__ . '/footer.php'; ?>
