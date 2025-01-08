<?php
// Ensure the user is authenticated by the server
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized access';
    exit;
}

// Read the upload log file
$logFile = 'upload_log.txt';
$logEntries = file_exists($logFile) ? file($logFile) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Log</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Upload Log</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Date and Time</th>
                <th>User</th>
                <th>File Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logEntries)): ?>
                <tr>
                    <td colspan="3">No uploads logged yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($logEntries as $entry): ?>
                    <?php 
                    $parts = explode('|', $entry);
                    $datetime = trim($parts[0]);
                    $user = trim(str_replace('User:', '', $parts[1]));
                    $file = trim(str_replace('File:', '', $parts[2]));
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($datetime) ?></td>
                        <td><?= htmlspecialchars($user) ?></td>
                        <td><?= htmlspecialchars($file) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>