<?php
$baseDir = 'uploads/';
$currentDir = isset($_POST['currentDir']) ? $_POST['currentDir'] : '';
$oldFileName = isset($_POST['oldFileName']) ? $_POST['oldFileName'] : '';
$newFileName = isset($_POST['newFileName']) ? $_POST['newFileName'] : '';

$oldFilePath = $baseDir . ($currentDir ? "$currentDir/" : '') . $oldFileName;
$fileExtension = pathinfo($oldFileName, PATHINFO_EXTENSION);

// Remove the file extension from the new name if the user has entered it
$newFileName = pathinfo($newFileName, PATHINFO_FILENAME) . '.' . $fileExtension;
$newFilePath = $baseDir . ($currentDir ? "$currentDir/" : '') . $newFileName;

// Check whether the file exists and the new name has not yet been assigned
if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
    if (rename($oldFilePath, $newFilePath)) {
        echo "File succesfully renamed: $newFileName";
    } else {
        echo "Error 2: Failed to rename the file.";
    }
} else {
    echo "Error 1: The file already exists or the new name is invalid.";
}

echo "<br><a href='index.php?dir=" . urlencode($currentDir) . "'>Back to dashboard</a>";
?>