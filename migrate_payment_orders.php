<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Ensure transaction_id column exists
$check_tx = $db->query("SHOW COLUMNS FROM orders LIKE 'transaction_id'");
if ($check_tx->num_rows == 0) {
    $db->query("ALTER TABLE orders ADD COLUMN transaction_id VARCHAR(100) DEFAULT NULL AFTER payment_method");
    echo "Added 'transaction_id' column to orders table.\n";
}

// Ensure payment_proof column exists
$check_proof = $db->query("SHOW COLUMNS FROM orders LIKE 'payment_proof'");
if ($check_proof->num_rows == 0) {
    $db->query("ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) DEFAULT NULL AFTER transaction_id");
    echo "Added 'payment_proof' column to orders table.\n";
}

// Update payment_status column definition to support 'pending', 'completed', 'rejected'
$db->query("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'pending'");
echo "Updated 'payment_status' column default to 'pending'.\n";

// Update existing test orders to completed
$db->query("UPDATE orders SET payment_status = 'completed' WHERE payment_status = '' OR payment_status IS NULL");

$db->close();

// Create directories if not existing
$proofs_dir = __DIR__ . '/assets/uploads/payment_proofs';
if (!file_exists($proofs_dir)) {
    mkdir($proofs_dir, 0777, true);
    echo "Created directory: assets/uploads/payment_proofs/\n";
}

$images_dir = __DIR__ . '/assets/images';
if (!file_exists($images_dir)) {
    mkdir($images_dir, 0777, true);
    echo "Created directory: assets/images/\n";
}

// Copy QR image from uploaded path
$uploaded_qr = 'C:/Users/kunja/.gemini/antigravity/brain/2b49c349-0671-422a-92b7-9532a03532a7/.user_uploaded/media_1789470907589.png';
$target_qr = __DIR__ . '/assets/images/payment_qr.png';

if (file_exists($uploaded_qr)) {
    copy($uploaded_qr, $target_qr);
    echo "Copied payment QR code to: assets/images/payment_qr.png\n";
} else {
    echo "Warning: Source QR code file not found at " . $uploaded_qr . "\n";
}

echo "Database migration & folder setup finished successfully.\n";
