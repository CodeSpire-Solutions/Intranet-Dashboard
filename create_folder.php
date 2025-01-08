<?php
$baseDir = 'uploads/';
$currentDir = $_POST['currentDir'];
$folderName = $_POST['folderName'];

$folderPath = $baseDir . ($currentDir ? "$currentDir/" : '') . $folderName;

if (!file_exists($folderPath)) {
    mkdir($folderPath, 0755, true);
    echo "Folder created successfully.";
} else {
    echo "Error 6: Folder already exists.";
}
header("Location: index.php?dir=" . urlencode($currentDir));
?>