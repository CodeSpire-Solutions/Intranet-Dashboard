<?php
$baseDir = 'uploads/';
$currentDir = $_POST['currentDir'];
$folderName = $_POST['folderName'];

$folderPath = $baseDir . ($currentDir ? "$currentDir/" : '') . $folderName;

// ...existing code...
if (mkdir($folderPath, 0755, true)) {
    // Save password hash if provided
    if (!empty($_POST['folderPassword'])) {
        $hash = password_hash($_POST['folderPassword'], PASSWORD_DEFAULT);
        file_put_contents($folderPath . '.meta', $hash);
    }
    // ...existing code...
}
// ...existing code...
if (!file_exists($folderPath)) {
    mkdir($folderPath, 0755, true);
    echo "Folder created successfully.";
} else {
    echo "Error 6: Folder already exists.";
}
header("Location: index.php?dir=" . urlencode($currentDir));
?>