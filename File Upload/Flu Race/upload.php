<?php
session_start();

// Rate limiting: max 10 uploads per 5 seconds
$rate_limit_window = 5; // seconds
$max_uploads = 10;

if (!isset($_SESSION['upload_times'])) {
    $_SESSION['upload_times'] = [];
}

// Clean old timestamps
$current_time = time();
$_SESSION['upload_times'] = array_filter($_SESSION['upload_times'], function($timestamp) use ($current_time, $rate_limit_window) {
    return ($current_time - $timestamp) < $rate_limit_window;
});

// Check rate limit
if (count($_SESSION['upload_times']) >= $max_uploads) {
    header("Location: index.php?message=" . urlencode("Error: Too many upload attempts. Please slow down."));
    exit();
}

$_SESSION['upload_times'][] = $current_time;

// Add random delay to make manual timing impossible
usleep(rand(100000, 500000)); // 0.1 to 0.5 seconds random delay

if (isset($_POST["submit"])) {

    if (!isset($_FILES['imageFile']) || $_FILES['imageFile']['error'] !== UPLOAD_ERR_OK) {
        
        switch ($_FILES['imageFile']['error'] ?? UPLOAD_ERR_NO_FILE) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = "Error: The uploaded file exceeds the 1MB size limit.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "Error: No file was selected for upload.";
                break;
            default:
                $message = "Error: A server-side error occurred during upload.";
        }
        header("Location: index.php?message=" . urlencode($message));
        exit();
    }
    
    $uploadDir = "uploads/";
    $fileName = basename($_FILES["imageFile"]["name"]);
    $uploadPath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
    $checksumPath = $uploadPath . '.txt';

    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $uploadPath)) {

        $content = file_get_contents($uploadPath);
        $hash = hash('sha256', $content);

        // Store hash for tracking
        file_put_contents($checksumPath, $hash);
        
        $message = "Success: File uploaded.";

    } else {
        $message = "Error: There was a problem with the upload.";
    }
    header("Location: index.php?message=" . urlencode($message));
    exit();
} else {
    header("Location: index.php");
    exit();
}

?>