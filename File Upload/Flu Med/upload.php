<?php
$uploadDir = "uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $destination = $uploadDir . basename($filename);

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmp);
    finfo_close($finfo);

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mimeType, $allowedMimeTypes)) {
        die("Invalid file type.");
    }

    if (move_uploaded_file($fileTmp, $destination)) {
        echo "File uploaded successfully: <a href='$destination'>$filename</a>";
    } else {
        echo "Upload failed.";
    }
} else {
    echo "No file uploaded.";
}
?>
