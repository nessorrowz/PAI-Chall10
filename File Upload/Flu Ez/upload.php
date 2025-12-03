<?php
$uploadDir = "uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $destination = $uploadDir . basename($filename);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        echo "File uploaded successfully: <a href='$destination'>$filename</a>";
    } else {
        echo "Upload failed.";
    }
} else {
    echo "No file uploaded.";
}
?>
