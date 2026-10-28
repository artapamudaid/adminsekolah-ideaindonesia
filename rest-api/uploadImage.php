<?php
require_once __DIR__ . '/upload_security.php';

require_upload_token();

$uploadDir = realpath(__DIR__ . '/../uploads');
if ($uploadDir === false) {
    reject_upload('Upload directory is not available', 500);
}

$targetDir = $uploadDir . '/absensi';
ensure_upload_directory($targetDir);

$fileName = safe_uploaded_image_name('webcam');
$relativePath = 'absensi/' . $fileName;

if (!move_uploaded_file($_FILES['webcam']['tmp_name'], $targetDir . '/' . $fileName)) {
    reject_upload('Could not move the file', 500);
}

echo $relativePath;
?>
