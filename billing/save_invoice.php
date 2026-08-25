<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
$user = require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
$data = json_decode(file_get_contents('php://input'), true) ?: [];
$_POST['csrf'] = $data['csrf'] ?? '';
verify_csrf();
$invoice = $data['invoice'] ?? [];
$number = trim((string) ($invoice['invoice_number'] ?? ''));
$customer = trim((string) ($invoice['customer_name'] ?? ''));
if ($number === '' || $customer === '') { http_response_code(422); exit('Invoice number and customer are required.'); }
$stmt = db()->prepare('INSERT INTO invoices (user_id, invoice_number, customer_name, invoice_date, currency, total, invoice_data) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$user['id'], $number, $customer, $invoice['invoice_date'] ?: null, $invoice['currency'] ?? 'INR', (float) ($data['total'] ?? 0), json_encode($invoice, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)]);
header('Content-Type: application/json');
echo json_encode(['ok' => true, 'id' => db()->lastInsertId()]);
