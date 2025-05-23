<?php
$baseDir = 'uploads/';
$currentDir = isset($_GET['dir']) ? $_GET['dir'] : '';
$fullPath = $baseDir . $currentDir;

// Create the upload folder if it does not yet exist
if (!file_exists($baseDir)) {
    mkdir($baseDir, 0755, true);
}

$folders = array_filter(glob($fullPath . '/*'), 'is_dir');
$files = array_filter(glob($fullPath . '/*'), 'is_file');

// Process renaming of the file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newFileName']) && isset($_POST['oldFileName'])) {
    $oldFileName = basename($_POST['oldFileName']);
    $newFileName = basename($_POST['newFileName']);
    
    // Extract file extension
    $fileExtension = pathinfo($oldFileName, PATHINFO_EXTENSION);
    
    // Prevent the file extension from being changed
    $newFileName = pathinfo($newFileName, PATHINFO_FILENAME) . '.' . $fileExtension;
    
    // The complete path of the old and new file
    $oldFilePath = $baseDir . ($currentDir ? "$currentDir/" : '') . $oldFileName;
    $newFilePath = $baseDir . ($currentDir ? "$currentDir/" : '') . $newFileName;
    
    // Check whether the file exists and the new name has not yet been assigned
    if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
        if (rename($oldFilePath, $newFilePath)) {
            echo "The File successfully saved in: $newFileName";
        } else {
            echo "Error 2: Failed to rename the file.";
        }
    } else {
        echo "Error 1: The file already exists or the new name is invalid.";
    }
}

// Check if current folder is password protected
$metaFile = $fullPath . '.meta';
if (file_exists($metaFile)) {
    session_start();
    $unlockedKey = 'unlocked_' . md5($fullPath);
    if (
        !isset($_SESSION[$unlockedKey]) ||
        $_SESSION[$unlockedKey] !== true
    ) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folderUnlockPassword'])) {
            $hash = file_get_contents($metaFile);
            if (password_verify($_POST['folderUnlockPassword'], $hash)) {
                $_SESSION[$unlockedKey] = true;
                header("Location: index.php?dir=" . urlencode($currentDir));
                exit;
            } else {
                $unlockError = "Incorrect password!";
            }
        }
        ?>
        <form method="post">
            <h2>This folder is password protected.</h2>
            <?php if (!empty($unlockError)) echo "<p style='color:red;'>$unlockError</p>"; ?>
            <input type="password" name="folderUnlockPassword" placeholder="Enter password" required>
            <button type="submit">Unlock</button>
        </form>
        </body></html>
        <?php exit; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Intranet Dashboard</h1>
    
    <!-- Navigation -->
    <h2>Current Folder: <?= $currentDir ? $currentDir : 'Home' ?></h2>
    <?php if ($currentDir): ?>
        <a href="index.php?dir=<?= dirname($currentDir) ?>">⬅️ Zurück</a>
    <?php endif; ?>
    
    <!-- ...existing code... -->
<!-- Create Folder Section -->
<h2>Create Folder</h2>
<form action="create_folder.php" method="post">
    <input type="hidden" name="currentDir" value="<?= $currentDir ?>">
    <input type="text" name="folderName" placeholder="Foldername" required>
    <input type="password" name="folderPassword" placeholder="Password (optional)">
    <button type="submit">Create Folder</button>
</form>

<!-- File Upload Section -->
<h2>Upload File</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="currentDir" value="<?= $currentDir ?>">
    <input type="file" name="fileToUpload" required>
    <input type="password" name="filePassword" placeholder="Password (optional)">
    <button type="submit">Upload</button>
</form>
<!-- ...existing code... -->
    
    <!-- Folder List -->
    <h2>Folder</h2>
    <?php foreach ($folders as $folder): ?>
        <?php $folderName = basename($folder); ?>
        <p>📁 <a href="index.php?dir=<?= $currentDir ? "$currentDir/$folderName" : $folderName ?>">
            <?= $folderName ?>
        </a></p>
    <?php endforeach; ?>
    
    <!-- File List -->
    <h2>File</h2>
    <?php foreach ($files as $file): ?>
        <?php $fileName = basename($file); ?>
        <p>📄 
            <a href="download.php?file=<?= urlencode($currentDir ? "$currentDir/$fileName" : $fileName) ?>">
                <?= $fileName ?>
            </a>
            
            <!-- Rename Form -->
            <form action="index.php?dir=<?= urlencode($currentDir) ?>" method="post" style="display:inline;">
                <input type="hidden" name="oldFileName" value="<?= $fileName ?>">
                <input type="text" name="newFileName" placeholder="New filename" required>
                <button type="submit">Rename</button>
            </form>
        </p>
    <?php endforeach; ?>
</div>
</body>
</html>