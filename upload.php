<?php
// Ensure the user is authenticated by the server
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized access';
    exit;
}

$baseDir = 'uploads/';
$currentDir = isset($_POST['currentDir']) ? $_POST['currentDir'] : '';
$uploadDir = $baseDir . ($currentDir ? "$currentDir/" : '');

// Create the directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $fileName = basename($_FILES['fileToUpload']['name']);
    $tempPath = $_FILES['fileToUpload']['tmp_name'];
    $zipFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.zip';
    $zipFilePath = $uploadDir . $zipFileName;

    // Compress the file into a ZIP file
    $zip = new ZipArchive();
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $zip->addFile($tempPath, $fileName);
        $zip->close();
        echo "File successfully compressed and uploaded: $zipFileName";

        // Log the upload
        $logFile = 'upload_log.txt';
        $username = $_SERVER['PHP_AUTH_USER']; // Get the username from .htpasswd
        $logEntry = date('Y-m-d H:i:s') . " | User: $username | File: $zipFileName\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

    } else {
        echo "Error 3: Failed to compress file.";
    }
} else {
    echo "Error 4: No file selected for upload.";
}

echo "<br><a href='index.php?dir=" . urlencode($currentDir) . "'>Back to Dashboard</a>";
?>