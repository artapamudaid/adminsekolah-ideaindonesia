<?php

function reject_upload($message, $statusCode = 400)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(array('error' => true, 'message' => $message));
    exit;
}

function safe_uploaded_image_name($fieldName, $prefix = '')
{
    if (!isset($_FILES[$fieldName]) || !isset($_FILES[$fieldName]['tmp_name'])) {
        reject_upload('No file uploaded');
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        reject_upload('Upload failed');
    }

    if ($_FILES[$fieldName]['size'] > 2 * 1024 * 1024) {
        reject_upload('File is too large');
    }

    $tmpName = $_FILES[$fieldName]['tmp_name'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpName);
    $allowed = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    );

    if (!isset($allowed[$mime]) || @getimagesize($tmpName) === false) {
        reject_upload('Only valid image files are allowed');
    }

    $safePrefix = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $prefix);
    if ($safePrefix !== '') {
        $safePrefix .= '_';
    }

    return $safePrefix . date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
}

function ensure_upload_directory($directory)
{
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        reject_upload('Upload directory is not available', 500);
    }
}

function require_upload_token()
{
    $expectedToken = getenv('REST_UPLOAD_TOKEN');
    if (!$expectedToken) {
        return;
    }

    $providedToken = isset($_SERVER['HTTP_X_UPLOAD_TOKEN']) ? $_SERVER['HTTP_X_UPLOAD_TOKEN'] : '';
    if (!$providedToken && isset($_REQUEST['upload_token'])) {
        $providedToken = $_REQUEST['upload_token'];
    }

    if (!hash_equals($expectedToken, $providedToken)) {
        reject_upload('Unauthorized upload', 401);
    }
}
