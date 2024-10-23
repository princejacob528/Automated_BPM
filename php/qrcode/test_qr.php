<?php
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a QR code instance
$qrCode = new QrCode('Hello, World!');
$qrCode->setSize(300);

// Set the content type header
header('Content-Type: image/png');

// Output the QR code image directly
echo $qrCode->writeString();
?>
