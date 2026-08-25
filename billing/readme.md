## Biller | ZeroFy MVP Plan

Build the empty billing scaffold into a PHP/MySQL application for small businesses. The home screen will be the invoice editor. Anonymous users can generate up to 10 watermarked invoices; signup plus a mock checkout unlocks saved history, additional invoices, and watermark-free output.

## Implementation Plan

1. **Foundation and local setup**
	- Add PDO/MySQL configuration, sessions, shared helpers, and database migrations.
	- Keep secrets in an untracked local configuration file.
	- Document MAMP setup, database creation, local URLs, and required PHP extensions.
	- Create tables for users, business settings, customers, invoices, invoice line items, and email metadata.

2. **Authentication and account entitlements**
	- Implement signup, login, logout, password hashing, session regeneration, and CSRF protection.
	- Add free and paid account states.
	- Implement a clearly labeled mock checkout that unlocks paid access without processing real payments.
	- Enforce the anonymous 10-invoice quota and paid-account permissions on the server.

3. **Home invoice editor**
	- Turn `index.php` into the primary invoice workspace.
	- Add fields for biller name/address, customer name/address, invoice number, dates, and due date.
	- Add repeatable item rows with item name, quantity, and unit price.
	- Add currency selection, custom tax percentage or amount, optional discount, notes, and payment instructions.
	- Display subtotal, tax, total, and balance with live browser-side updates.
	- Include accessible labels, validation messages, keyboard-friendly controls, and responsive layouts.

4. **Invoice calculations and persistence**
	- Keep calculation and validation logic separate from templates.
	- Recalculate every total on the server; never trust browser-submitted totals.
	- Define precision, rounding, currency formatting, and negative-value rules.
	- Save invoices and line items transactionally.
	- Add draft, generated, issued, paid, and overdue statuses.
	- Generate unique invoice numbers per account and store watermark metadata for free invoices.

5. **Invoice history and output**
	- Add authenticated invoice list, detail, edit, search, and status-filter views.
	- Restrict every invoice query to the owning account.
	- Add a professional print view and print stylesheet.
	- Support browser printing and “Save as PDF” in the first release.
	- Display a visible watermark on free invoices.
	- Add configurable email delivery with success and failure feedback, without committing credentials.

6. **Shared layout and visual design**
	- Use `header.php` for the document head, Biller | ZeroFy branding, navigation, account state, and flash messages.
	- Use `footer.php` for shared closing markup and scripts.
	- Use `style.css` for CSS variables, responsive form grids, line-item editing, tables, status indicators, focus states, and print rules.
	- Adapt the existing ZeroFy site’s layout conventions while keeping the billing interface compact and operational.

7. **Documentation and hardening**
	- Document routes, account rules, mock checkout limitations, email configuration, and deployment requirements.
	- Use prepared statements, output escaping, CSRF validation, secure sessions, password hashing, and authorization checks.
	- Add clear empty, loading, error, and success states.
	- Do not commit production credentials or sensitive configuration.

## Proposed Structure

```text
billing/
├── index.php
├── header.php
├── footer.php
├── style.css
├── config.php
├── includes/
│   ├── auth.php
│   ├── database.php
│   ├── csrf.php
│   ├── flash.php
│   └── validation.php
├── lib/
│   ├── InvoiceCalculator.php
│   ├── InvoiceRepository.php
│   └── Mailer.php
├── database/
│   └── schema.sql
└── views/
	 ├── invoice-editor.php
	 ├── invoice-history.php
	 ├── invoice-detail.php
	 ├── invoice-print.php
	 ├── login.php
	 ├── signup.php
	 └── checkout.php
```

## Product Decisions

- **Name:** Biller | ZeroFy
- **Anonymous access:** Up to 10 generated invoices with a watermark.
- **Paid access:** Mock checkout initially; no real payment gateway or recurring billing in the MVP.
- **Paid benefits:** Saved invoice history, more than 10 invoices, and no watermark.
- **Currency:** Selectable per business or invoice.
- **Tax:** Custom user-entered tax percentage or amount.
- **PDF:** Browser print and “Save as PDF” initially.
- **Email:** Included behind a configurable mailer interface.
- **Recipient access:** No customer portal or public invoice link in the first release.
- **Tenancy:** One business per account; staff roles and multi-business accounts are excluded initially.

## Verification Checklist

1. Run `php -l` against every PHP file and load the application through MAMP/Apache with a clean MySQL database.
2. Confirm anonymous invoices 1 through 10 work, and invoice 11 is blocked with a signup or upgrade path.
3. Verify watermark rendering for free invoices and watermark-free rendering for paid accounts.
4. Exercise signup, login, logout, mock checkout, saved history, and account isolation.
5. Test quantities, tax, discounts, currencies, rounding, malformed values, and negative-value rejection.
6. Verify CSRF protection, password hashing, prepared queries, output escaping, session behavior, and authorization.
7. Test create, save, list, detail, edit, print, PDF, and email success/failure flows.
8. Check responsive behavior, keyboard focus, validation states, and invoice editing on desktop and mobile widths.

## Further Considerations

- Anonymous quotas should use a server-validated browser token or session. Local storage alone is not sufficient because users can reset it.
- Anonymous limits are an abuse deterrent, not strong identity enforcement.
- Before production email delivery, choose SMTP or a transactional provider and add rate limiting, delivery logging, and secret management.
- Define supported ISO currency codes and zero-decimal currency behavior before finalizing the database schema.

## Current Website Pages

- `index.php` - Invoice editor, preview, print/save PDF, free quota, and account modal.
- `history.php` - Generated invoice history with preview and share-link actions.
- `pricing.php` - Free plan and the single ₹99/month paid plan with mock checkout.
- `account.php` - Local account details and paid custom-header preferences.
- `signup.php` - Local free-account signup.
- `login.php` - Local account login.

The current implementation uses Firebase Authentication and Firebase Storage for account access and invoice history. Plan upgrades are local MVP metadata; production use requires verified billing and subscription management.

## MAMP Database Setup

1. Start Apache in MAMP.
2. Complete the Firebase setup below.
3. Open `http://localhost/zerofy/billing/`.

Firebase handles authentication and invoice storage. Guests may preview invoices, while signed-in users can save them to Firestore. The ₹99/₹999 checkout is a local mock upgrade and does not charge a card.

## Firebase Setup

The active authentication and invoice storage flow uses Firebase; MySQL is not required for the current app flow.

1. Create a Firebase project and add a Web app.
2. Enable Authentication > Sign-in method > Email/Password.
3. Enable Firestore and Realtime Database, then deploy `firestore.rules` and `database.rules.json`.
4. Copy the Web app settings into `firebase-config.js`.
5. Serve the app through MAMP/Apache. Firebase Auth will not work reliably from a `file://` URL.

Invoices are stored as documents under `users/{uid}/invoices` in Firestore. The current invoice draft is stored at `users/{uid}/draft` in Realtime Database. Profile branding is stored as `custom_header` and `hide_header` in `users/{uid}`. The `plan` field in `users/{uid}` controls paid features; payment processing and subscription verification are not implemented.
