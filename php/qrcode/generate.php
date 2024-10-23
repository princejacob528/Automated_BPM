<?php
require 'vendor/autoload.php'; // Include the Composer autoloader

use Endroid\QrCode\QrCode;

// Get the text from the URL parameter, or set a default text
$text = $_GET['text'] ?? 'default text';

// Create a QR code instance
$qrCode = new QrCode($text);
$qrCode->setSize(300);

// Set the error correction level
$qrCode->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel\Medium());

// Set the logo if needed (optional)
$qrCode->setLogoPath(__DIR__ . '/path/to/logo.png'); // Provide the correct path to your logo

// Output the QR code image
header('Content-Type: image/png');
echo $qrCode->writeString();
?>
