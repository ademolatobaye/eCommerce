<?php
include "qrcode/qrlib.php";

// Set up temp folder
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
if (!file_exists($PNG_TEMP_DIR)) {
    mkdir($PNG_TEMP_DIR);
}

$data = isset($_GET['data']) ? $_GET['data'] : 'No data';
$errorCorrectionLevel = 'L';
$matrixPointSize = 4;

// Generate filename based on data
$filename = $PNG_TEMP_DIR . 'qr_' . md5($data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';

// Generate the QR code only if it doesn't exist yet
if (!file_exists($filename)) {
    QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
}

// Output image directly
header('Content-Type: image/png');
readfile($filename);
exit;