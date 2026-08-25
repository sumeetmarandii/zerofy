</main>
<footer class="site-footer">
	<p>Simple billing for thoughtful businesses.</p>
	<p>Free invoices include a subtle watermark.</p>
</footer>
<script>
(() => {
	const form = document.querySelector('#invoice-form');
	if (!form) return;

	const currency = document.querySelector('#currency');
	const taxRate = document.querySelector('#tax-rate');
	const discount = document.querySelector('#discount');
	const itemList = document.querySelector('#item-list');
	const subtotalOutput = document.querySelector('#subtotal-output');
	const taxOutput = document.querySelector('#tax-output');
	const totalOutput = document.querySelector('#total-output');
	const itemTemplate = document.querySelector('#item-template');
	const currencySymbols = { USD: '$', EUR: '€', GBP: '£', INR: '₹', AUD: 'A$' };
	const storageKey = 'zerofy-invoice-draft';
	const countOutput = document.querySelector('#free-count');
	const previewButton = document.querySelector('[data-preview]');
	const signatureOption = form.elements.include_signature;
	let firebaseUser = null;
	let serverPlan = 'guest';
	const accountModal = document.querySelector('#account-modal');
	const accountForm = document.querySelector('#account-form');
	const accountStatus = document.querySelector('#account-status');
	const historyList = document.querySelector('#history-list');
	const accountMessage = document.querySelector('#account-message');
	let isResetting = false;

	const money = (value) => `${currencySymbols[currency.value] || currency.value} ${value.toFixed(2)}`;
	const accountKey = 'zerofy-account';
	const historyKey = 'zerofy-invoice-history';
	const getAccount = () => JSON.parse(localStorage.getItem(accountKey) || 'null');
	const getHistory = () => JSON.parse(localStorage.getItem(historyKey) || '[]');
	const isAuthenticated = () => Boolean(firebaseUser || window.firebaseAuth?.currentUser);
	const isPaid = () => window.firebasePlan === 'paid';
	const openAccount = () => { accountModal.hidden = false; };
	const invoiceData = () => {
		const data = Object.fromEntries(new FormData(form).entries());
		data.items = [...itemList.querySelectorAll('.item-row')].map((row) => ({
			name: row.querySelector('[data-name]').value,
			quantity: row.querySelector('[data-quantity]').value,
			price: row.querySelector('[data-price]').value
		}));
		return data;
	};
	const restoreInvoice = (data) => {
		Object.entries(data).forEach(([key, value]) => {
			const field = form.elements[key];
			if (field && typeof value === 'string') field.value = value;
		});
		itemList.innerHTML = '';
		(data.items || [{}]).forEach(addItem);
		calculate();
	};
	const renderHistory = () => {
		const history = getHistory();
		if (!history.length) {
			historyList.innerHTML = '<p class="empty-state">Saved invoices will appear here after you save an invoice.</p>';
			return;
		}
		const safeText = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({
			'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
		}[character]));
		historyList.innerHTML = history.map((invoice) => `<article class="history-item"><div><strong>${safeText(invoice.invoice_number || invoice.number)}</strong><span>${safeText(invoice.customer_name || invoice.customer || 'No customer')} · ${safeText(invoice.invoice_date || invoice.date)}</span></div><strong>${safeText(invoice.total)}</strong><div class="history-actions"><button class="text-button" type="button" data-history-preview="${safeText(invoice.id)}">Preview</button></div></article>`).join('');
		historyList.querySelectorAll('[data-history-preview]').forEach((button) => button.addEventListener('click', () => {
			const invoice = getHistory().find((entry) => entry.id === button.dataset.historyPreview);
			if (invoice) { restoreInvoice(invoice.data || invoice); openPreview(); }
		}));
	};
	const updateAccountUi = () => {
		const account = getAccount();
		accountStatus.textContent = firebaseUser ? (isPaid() ? 'Paid workspace' : 'Free account') : 'Guest mode';
		document.querySelectorAll('[data-account]').forEach((button) => { button.textContent = account ? 'Account' : 'Sign up / Log in'; });
	};
	const calculate = () => {
		let subtotal = 0;
		itemList.querySelectorAll('.item-row').forEach((row, index) => {
			row.querySelector('[data-serial]').textContent = index + 1;
			const quantity = Math.max(0, Number(row.querySelector('[data-quantity]').value) || 0);
			const price = Math.max(0, Number(row.querySelector('[data-price]').value) || 0);
			subtotal += quantity * price;
			row.querySelector('[data-line-total]').textContent = money(quantity * price);
		});
		const tax = subtotal * (Math.max(0, Number(taxRate.value) || 0) / 100);
		const total = Math.max(0, subtotal + tax - (Number(discount.value) || 0));
		subtotalOutput.textContent = money(subtotal);
		taxOutput.textContent = money(tax);
		totalOutput.textContent = money(total);
	};
	const createUniqueInvoiceNumber = () => `ZF-${Date.now()}-${crypto.randomUUID().slice(0, 6).toUpperCase()}`;

	const saveDraft = () => {
		const data = Object.fromEntries(new FormData(form).entries());
		data.items = [...itemList.querySelectorAll('.item-row')].map((row) => ({
			name: row.querySelector('[data-name]').value,
			quantity: row.querySelector('[data-quantity]').value,
			price: row.querySelector('[data-price]').value
		}));
		localStorage.setItem(storageKey, JSON.stringify(data));
		if (firebaseUser && window.firebaseInvoiceStore) window.firebaseInvoiceStore.saveDraft(data).catch(() => {});
	};

	const addItem = (item = {}) => {
		const row = itemTemplate.content.cloneNode(true).querySelector('.item-row');
		row.querySelector('[data-name]').value = item.name || '';
		row.querySelector('[data-quantity]').value = item.quantity || 1;
		row.querySelector('[data-price]').value = item.price || '';
		row.querySelector('[data-remove]').addEventListener('click', () => {
			if (itemList.children.length > 1) row.remove();
			calculate();
			saveDraft();
		});
		row.querySelectorAll('input').forEach((input) => input.addEventListener('input', () => {
			calculate();
			saveDraft();
		}));
		itemList.appendChild(row);
		calculate();
		saveDraft();
	};

	const draft = JSON.parse(localStorage.getItem(storageKey) || 'null');
	if (draft) {
		Object.entries(draft).forEach(([key, value]) => {
			const field = form.elements[key];
			if (field && typeof value === 'string') field.value = value;
		});
		itemList.innerHTML = '';
		(draft.items || [{}]).forEach(addItem);
	} else {
		addItem();
	}
	const sharedInvoice = window.location.hash.startsWith('#invoice=') ? window.location.hash.slice(9) : '';
	const printSharedInvoice = new URLSearchParams(window.location.search).get('print') === '1';
	let sharedInvoiceData = null;
	if (sharedInvoice) {
		try { sharedInvoiceData = JSON.parse(decodeURIComponent(escape(atob(decodeURIComponent(sharedInvoice))))); restoreInvoice(sharedInvoiceData.data || sharedInvoiceData); }
		catch { window.alert('This shared invoice link is invalid or expired.'); }
	}
	const savedSettings = JSON.parse(localStorage.getItem('zerofy-settings') || '{}');
	if (form.dataset.customHeader && !form.elements.custom_header.value) form.elements.custom_header.value = form.dataset.customHeader;
	if (form.dataset.hideHeader === '1') form.elements.hide_header.checked = true;
	if (savedSettings.custom_header && !form.elements.custom_header.value && serverPlan === 'guest') form.elements.custom_header.value = savedSettings.custom_header;
	if (savedSettings.hide_header === true && serverPlan === 'guest') form.elements.hide_header.checked = true;
	form.addEventListener('input', saveDraft);
	form.addEventListener('change', saveDraft);
	window.addEventListener('beforeunload', () => {
		if (!isResetting) saveDraft();
	});

	document.querySelector('[data-add-item]').addEventListener('click', () => addItem());
	document.querySelector('[data-reset]').addEventListener('click', () => {
		isResetting = true;
		localStorage.removeItem(storageKey);
		window.location.reload();
	});
	document.querySelectorAll('[data-upgrade]').forEach((button) => button.addEventListener('click', () => {
		window.alert('Paid accounts are coming next. Your free invoice is ready to generate.');
	}));
	const generatedCount = Number(localStorage.getItem('zerofy-generated-count') || 0);
	countOutput.textContent = isPaid() ? 'Unlimited' : Math.min(generatedCount, 10);
	if (!draft && generatedCount > 0) {
		document.querySelector('#invoice-number').value = `ZF-${String(generatedCount + 1).padStart(4, '0')}`;
	}
	currency.addEventListener('change', calculate);
	taxRate.addEventListener('input', calculate);
	discount.addEventListener('input', calculate);
	form.addEventListener('submit', async (event) => {
		event.preventDefault();
		if (window.firebasePlanReady) await window.firebasePlanReady;
		if (!isAuthenticated()) {
			window.location.href = 'signup.php';
			return;
		}
		const currentCount = Number(localStorage.getItem('zerofy-generated-count') || 0);
		if (!isPaid() && currentCount >= 10) {
			window.alert('You have used all 10 free invoices. Upgrade to continue.');
			window.location.href = 'pricing.php';
			return;
		}
		const generated = currentCount + 1;
		const invoiceNumberField = document.querySelector('#invoice-number');
		if (!invoiceNumberField.value.trim() || /^ZF-\d{4}$/.test(invoiceNumberField.value.trim())) invoiceNumberField.value = createUniqueInvoiceNumber();
		const savedInvoice = invoiceData();
		const invoiceId = `${Date.now()}-${crypto.randomUUID().slice(0, 8)}`;
		try {
			if (firebaseUser && window.firebaseInvoiceStore) await window.firebaseInvoiceStore.save({ ...savedInvoice, id: invoiceId, total: totalOutput.textContent });
		} catch (error) {
			document.querySelector('#success-message').hidden = true;
			window.alert(`Invoice could not be saved: ${error.message}`);
			return;
		}
		localStorage.setItem('zerofy-generated-count', String(generated));
		countOutput.textContent = isPaid() ? 'Unlimited' : generated;
		document.querySelector('#success-message').hidden = false;
		const history = getHistory();
		history.unshift({
			id: invoiceId,
			invoice_number: savedInvoice.invoice_number,
			customer_name: savedInvoice.customer_name,
			invoice_date: savedInvoice.invoice_date,
			total: totalOutput.textContent,
			data: savedInvoice
		});
		localStorage.setItem(historyKey, JSON.stringify(history));
		saveDraft();
		renderHistory();
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});
	const openPreview = async () => {
		const previewWindow = window.open('', 'invoice-preview', 'width=850,height=900');
		if (!previewWindow) {
			window.alert('Please allow pop-ups to preview your invoice.');
			return;
		}
		if (window.firebasePlanReady) await window.firebasePlanReady;
		const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({
			'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
		}[character]));
		const invoiceNumber = escapeHtml(form.elements.invoice_number.value || 'ZF-0001');
		const billerName = escapeHtml(form.elements.biller_name.value.trim());
		const customHeader = isPaid() && form.elements.use_custom_header.checked && form.elements.custom_header.value.trim() ? escapeHtml(form.elements.custom_header.value.trim()) : 'Billing | ZeroFy';
		const billerAddress = escapeHtml(form.elements.biller_address.value || '');
		const customerName = escapeHtml(form.elements.customer_name.value || 'Customer name');
		const customerAddress = escapeHtml(form.elements.customer_address.value || '');
		const notes = escapeHtml(form.elements.notes.value || '');
		const invoiceDate = escapeHtml(form.elements.invoice_date.value || '');
		const dueDate = escapeHtml(form.elements.due_date.value || 'On receipt');
		const tax = Math.max(0, Number(taxRate.value) || 0);
		const discountValue = Math.max(0, Number(discount.value) || 0);
		let subtotal = 0;
		const rows = [...itemList.querySelectorAll('.item-row')].map((row, index) => {
			const name = escapeHtml(row.querySelector('[data-name]').value || 'Item');
			const quantity = Math.max(0, Number(row.querySelector('[data-quantity]').value) || 0);
			const price = Math.max(0, Number(row.querySelector('[data-price]').value) || 0);
			const amount = quantity * price;
			subtotal += amount;
			return `<tr><td>${index + 1}</td><td>${name}</td><td>${quantity}</td><td>${money(price)}</td><td>${money(amount)}</td></tr>`;
		}).join('');
		const taxAmount = subtotal * (tax / 100);
		const total = Math.max(0, subtotal + taxAmount - discountValue);
		const signatureHtml = signatureOption.checked ? '<section class="signatures"><div><span></span><p>Signature / Company stamp</p></div></section>' : '';
		const notesHtml = notes.trim() ? `<section class="notes"><h2>Notes</h2><p>${notes.replace(/\n/g, '<br>')}</p></section>` : '';
		const watermarkHtml = isPaid() ? '' : '<span class="watermark">Billing | ZeroFy</span>';
		const headerHtml = form.elements.hide_header.checked && isPaid() ? '' : `<h1>${customHeader}</h1>`;
		previewWindow.document.write(`<!doctype html><html><head><title>${invoiceNumber} | Biller | ZeroFy</title><style>
			@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap');*{box-sizing:border-box}html,body{background:#e8ece7;margin:0;padding:0}body{color:#17221c;font:15px 'DM Sans',sans-serif}main{background:#fff;margin:0 auto;min-height:1123px;padding:48px;position:relative;width:794px}header{border-bottom:2px solid #17221c;display:flex;justify-content:space-between;padding-bottom:28px}h1,h2{font-family:'Space Grotesk',sans-serif}h1{font-size:28px;margin:0}h2{font-size:13px;letter-spacing:.12em;margin:0 0 10px;text-transform:uppercase}p{line-height:1.5;margin:5px 0}.meta{text-align:right}.muted{color:#6d756f;font-size:13px}.parties{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin:35px 0}table{border-collapse:collapse;width:100%}th{background:#17221c;color:white;font-size:12px;padding:12px;text-align:left}td{border-bottom:1px solid #dce3dc;padding:13px 12px}th:not(:first-child),td:not(:first-child){text-align:right}.totals{margin-left:auto;margin-top:28px;width:290px}.totals div{display:flex;justify-content:space-between;padding:7px 0}.total{border-top:2px solid #17221c;font-size:18px;font-weight:bold;margin-top:8px;padding-top:15px!important}.notes{border-left:3px solid #df5d3f;margin-top:45px;padding-left:14px}.notes p{color:#6d756f;font-size:13px}.signatures{bottom:42px;display:block;left:48px;margin:0;position:absolute;right:48px}.signatures div{margin:auto;max-width:420px}.signatures span{border-bottom:1px solid #17221c;display:block}.signatures p{color:#6d756f;font-size:12px;margin-top:8px;text-align:center}.watermark{color:#df5d3f;font-size:40px;font-weight:bold;left:42%;opacity:.12;position:absolute;top:45%;transform:rotate(-30deg)}@page{size:A4 portrait;margin:0}@media print{html,body{background:#fff;height:296mm;margin:0;max-height:296mm;overflow:hidden;padding:0;width:210mm}main{break-after:avoid;break-inside:avoid;height:296mm;margin:0;max-height:296mm;min-height:0;overflow:hidden;padding:17mm;width:210mm}.signatures{bottom:17mm;left:17mm;right:17mm}.watermark{position:absolute}}
		</style></head><body><main>${watermarkHtml}<header><div>${headerHtml}<p class="muted">${billerName}</p></div><div class="meta"><h2>Invoice</h2><p>${invoiceNumber}</p><p class="muted">Date: ${invoiceDate}<br>Due: ${dueDate}</p></div></header><section class="parties"><div><h2>From</h2><p>${billerName}</p><p class="muted">${billerAddress.replace(/\n/g, '<br>')}</p></div><div><h2>Bill to</h2><p>${customerName}</p><p class="muted">${customerAddress.replace(/\n/g, '<br>')}</p></div></section><table><thead><tr><th>S.No.</th><th>Description</th><th>Qty</th><th>Price</th><th>Amount</th></tr></thead><tbody>${rows}</tbody></table><section class="totals"><div><span>Subtotal</span><strong>${money(subtotal)}</strong></div><div><span>Tax (${tax.toFixed(2)}%)</span><strong>${money(taxAmount)}</strong></div><div><span>Discount</span><strong>${money(discountValue)}</strong></div><div class="total"><span>Total due</span><strong>${money(total)}</strong></div></section>${notesHtml}${signatureHtml}</main></body></html>`);
		previewWindow.document.close();
	};
	previewButton.addEventListener('click', () => openPreview());
	if (sharedInvoiceData) setTimeout(() => openPreview(printSharedInvoice), 0);
	document.querySelectorAll('[data-account]').forEach((button) => button.addEventListener('click', openAccount));
	document.querySelector('[data-close-modal]').addEventListener('click', () => { accountModal.hidden = true; });
	accountForm.addEventListener('submit', (event) => {
		event.preventDefault();
		window.location.href = 'signup.php';
	});
	const updatePlanUi = (event) => {
		const user = event.detail.user;
		firebaseUser = user;
		serverPlan = user ? event.detail.plan : 'guest';
		if (user && event.detail.profile) {
			form.elements.custom_header.value = event.detail.profile.custom_header || '';
			form.elements.hide_header.checked = event.detail.profile.hide_header === true;
		}
		document.querySelectorAll('[data-auth-only]').forEach((element) => { element.hidden = serverPlan !== 'paid'; });
		document.querySelectorAll('[data-auth-guest]').forEach((element) => { element.hidden = serverPlan === 'paid'; });
		updateAccountUi();
		if (user && !draft && window.firebaseInvoiceStore) window.firebaseInvoiceStore.loadDraft().then((remoteDraft) => { if (remoteDraft) restoreInvoice(remoteDraft); }).catch(() => {});
	};
	document.addEventListener('firebase-plan-ready', updatePlanUi);
	updateAccountUi();
	renderHistory();
	calculate();
})();
</script>
</body>
</html>
