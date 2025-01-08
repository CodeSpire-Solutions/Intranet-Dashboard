<?php
$baseDir = 'uploads/';
$file = isset($_GET['file']) ? $_GET['file'] : '';
$filePath = $baseDir . $file;

if (file_exists($filePath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    readfile($filePath);
    exit;
} else {
    echo "Error 5: File not found";
}
?>