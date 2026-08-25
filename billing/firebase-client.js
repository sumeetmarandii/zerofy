(() => {
	const config = window.firebaseConfig || {};
	const configured = config.apiKey && !config.apiKey.startsWith('YOUR_') && config.projectId && !config.projectId.startsWith('YOUR_');
	if (!configured) {
		window.firebaseReady = Promise.reject(new Error('Add your Firebase web app settings to firebase-config.js.'));
		return;
	}

	firebase.initializeApp(config);
	window.firebaseAuth = firebase.auth();
	window.firebaseDb = firebase.firestore();
	window.firebaseRealtime = firebase.database();
	window.firebaseReady = Promise.resolve();
	window.firebasePlanReady = Promise.resolve();
	window.firebasePlan = 'free';
	window.firebaseInvoiceStore = {
		async save(invoice) {
			const user = firebaseAuth.currentUser;
			if (!user) return null;
			const id = invoice.id || `${Date.now()}`;
			const duplicate = await firebaseDb.collection('users').doc(user.uid).collection('invoices').where('invoice_number', '==', invoice.invoice_number).limit(1).get();
			if (!duplicate.empty) throw new Error(`Invoice number ${invoice.invoice_number} already exists.`);
			await firebaseDb.collection('users').doc(user.uid).collection('invoices').doc(id).set({ ...invoice, createdAt: firebase.firestore.FieldValue.serverTimestamp() });
			return id;
		},
		async load() {
			const user = firebaseAuth.currentUser;
			if (!user) return [];
			const result = await firebaseDb.collection('users').doc(user.uid).collection('invoices').get();
			return result.docs.map((document) => ({ id: document.id, ...document.data() })).sort((a, b) => (b.createdAt?.toMillis?.() || 0) - (a.createdAt?.toMillis?.() || 0));
		},
		async remove(id) {
			const user = firebaseAuth.currentUser;
			if (!user) throw new Error('Please sign in to delete an invoice.');
			await firebaseDb.collection('users').doc(user.uid).collection('invoices').doc(id).delete();
		},
		async saveDraft(draft) {
			const user = firebaseAuth.currentUser;
			if (user) await firebaseRealtime.ref(`users/${user.uid}/draft`).set(draft);
		},
		async loadDraft() {
			const user = firebaseAuth.currentUser;
			if (!user) return null;
			const snapshot = await firebaseRealtime.ref(`users/${user.uid}/draft`).once('value');
			return snapshot.val();
		}
	};

	document.addEventListener('DOMContentLoaded', () => {
	const accountKey = 'zerofy-account';
	const getAccount = () => JSON.parse(localStorage.getItem(accountKey) || 'null');
	const saveAccount = (user) => {
		const account = getAccount() || {};
		localStorage.setItem(accountKey, JSON.stringify({ ...account, uid: user.uid, name: user.displayName || account.name || '', email: user.email || '' }));
	};

	let resolvePlan;
	window.firebasePlanReady = new Promise((resolve) => { resolvePlan = resolve; });
	firebaseAuth.onAuthStateChanged(async (user) => {
		window.firebasePlan = 'free';
		let profileData = {};
		try {
			if (user) {
				saveAccount(user);
				const profileRef = firebaseDb.collection('users').doc(user.uid);
				const profile = await profileRef.get();
				profileData = profile.data() || {};
				window.firebasePlan = profileData.plan === 'paid' ? 'paid' : 'free';
				const account = getAccount() || {};
				localStorage.setItem(accountKey, JSON.stringify({ ...account, uid: user.uid }));
				profileRef.set({ name: user.displayName || '', email: user.email || '' }, { merge: true }).catch(() => {});
			} else {
				window.firebasePlan = 'free';
			}
		} catch (error) {
			window.firebasePlan = 'free';
			console.error('Unable to load Firebase profile:', error);
		}
		resolvePlan();
		document.dispatchEvent(new CustomEvent('firebase-plan-ready', { detail: { user, plan: window.firebasePlan, profile: profileData } }));
		const planLabel = document.querySelector('[data-profile-plan]');
		const planCopy = document.querySelector('[data-profile-plan-copy]');
		if (planLabel) planLabel.textContent = window.firebasePlan === 'paid' ? 'Paid plan' : 'Free plan';
		if (planCopy) planCopy.textContent = window.firebasePlan === 'paid' ? 'Unlimited invoices, custom branding, and no watermark.' : 'Upgrade to unlock unlimited invoices and custom branding.';
		document.querySelectorAll('[data-auth-only]').forEach((element) => { element.hidden = window.firebasePlan !== 'paid'; });
		document.querySelectorAll('[data-guest-only]').forEach((element) => { element.hidden = Boolean(user); });
		document.querySelectorAll('[data-auth-link]').forEach((element) => { element.textContent = user ? 'Profile' : 'Log in'; });
		document.querySelectorAll('[data-firebase-logout]').forEach((element) => element.hidden = !user);
	});

	document.querySelectorAll('[data-firebase-logout]').forEach((element) => element.addEventListener('click', async (event) => {
		event.preventDefault();
		await firebaseAuth.signOut();
		localStorage.removeItem(accountKey);
		window.location.href = 'index.php';
	}));

	const showError = (form, error) => {
		const output = form.querySelector('.form-message');
		if (output) output.textContent = error.message || 'Authentication failed.';
	};
	const loginForm = document.querySelector('#firebase-login-form');
	if (loginForm) loginForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		try {
			await firebaseReady;
			await firebaseAuth.signInWithEmailAndPassword(loginForm.email.value.trim(), loginForm.password.value);
			window.location.href = 'index.php';
		} catch (error) { showError(loginForm, error); }
	});

	const signupForm = document.querySelector('#firebase-signup-form');
	if (signupForm) signupForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		try {
			await firebaseReady;
			const credential = await firebaseAuth.createUserWithEmailAndPassword(signupForm.email.value.trim(), signupForm.password.value);
			await credential.user.updateProfile({ displayName: signupForm.name.value.trim() });
			saveAccount(credential.user);
			window.location.href = new URLSearchParams(window.location.search).get('plan') === 'paid' ? 'pricing.php' : 'index.php';
		} catch (error) { showError(signupForm, error); }
	});

	const profileForm = document.querySelector('#firebase-profile-form');
	if (profileForm) firebaseAuth.onAuthStateChanged((user) => {
		if (!user) { window.location.href = 'login.php'; return; }
		firebaseDb.collection('users').doc(user.uid).get().then((snapshot) => {
			const profile = snapshot.data() || {};
			profileForm.custom_header.value = profile.custom_header || '';
			profileForm.hide_header.checked = profile.hide_header === true;
		}).catch(() => {});
		profileForm.name.value = user.displayName || '';
		profileForm.email.value = user.email || '';
		profileForm.querySelector('[data-profile-name]').textContent = user.displayName || 'Your account';
		profileForm.querySelector('[data-profile-email]').textContent = user.email || '';
	});
	if (profileForm) profileForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		try {
			await firebaseAuth.currentUser.updateProfile({ displayName: profileForm.name.value.trim() });
			const account = getAccount() || {};
			localStorage.setItem(accountKey, JSON.stringify({ ...account, name: profileForm.name.value.trim() }));
			await firebaseDb.collection('users').doc(firebaseAuth.currentUser.uid).set({ name: profileForm.name.value.trim(), custom_header: profileForm.custom_header.value.trim(), hide_header: profileForm.hide_header.checked }, { merge: true });
			profileForm.querySelector('.form-message').textContent = 'Profile saved.';
		} catch (error) { showError(profileForm, error); }
	});
	});
})();
