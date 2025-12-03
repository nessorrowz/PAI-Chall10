<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FLU RACE - Secure Image Upload</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .upload-form {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border: 2px dashed #dee2e6;
            text-align: center;
            transition: all 0.3s ease;
        }
        .upload-form:hover {
            border-color: #667eea;
            background: #f0f2ff;
        }
        .file-input {
            margin: 20px 0;
        }
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .upload-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .upload-btn:hover {
            transform: translateY(-2px);
        }
        .message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .info {
            background: #e2f3ff;
            border: 1px solid #b8e6ff;
            color: #0c5460;
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .security-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .uploaded-files {
            margin-top: 30px;
        }
        .file-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-link {
            color: #667eea;
            text-decoration: none;
        }
        .file-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏁 FLU RACE</h1>
            <p>Secure Image Upload Challenge</p>
        </div>
        <div class="content">
            <?php if (isset($_GET['message'])): ?>
                <div class="message <?= strpos($_GET['message'], 'Success') === 0 ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <?php endif; ?>
            
            <div class="security-info">
                <strong>🛡️ Security Features:</strong>
                <ul>
                    <li>Advanced YARA virus scanning</li>
                    <li>File type validation (JPG, JPEG, PNG, GIF only)</li>
                    <li>Content analysis for malicious code</li>
                    <li>SHA256 integrity checking</li>
                </ul>
            </div>
            
            <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
                <h3>📁 Upload Your Image</h3>
                <div class="file-input">
                    <input type="file" name="imageFile" accept="image/*" required>
                </div>
                <button type="submit" name="submit" class="upload-btn">
                    🚀 Upload Image
                </button>
            </form>
            
            <div class="info">
                <strong>ℹ️ Upload Guidelines:</strong><br>
                • Maximum file size: 1MB<br>
                • Allowed formats: JPG, JPEG, PNG, GIF<br>
                • All files are scanned for malicious content<br>
                • Invalid files are automatically removed
            </div>
            
            <?php
            $uploadDir = 'uploads/';
            if (is_dir($uploadDir)) {
                $files = array_diff(scandir($uploadDir), array('..', '.'));
                if (!empty($files)):
            ?>
            <div class="uploaded-files">
                <h3>📂 Uploaded Files</h3>
                <?php foreach ($files as $file): 
                    if (pathinfo($file, PATHINFO_EXTENSION) !== 'txt'): ?>
                    <div class="file-item">
                        <span><?= htmlspecialchars($file) ?></span>
                        <a href="uploads/<?= htmlspecialchars($file) ?>" target="_blank" class="file-link">View</a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php 
                endif;
            }
            ?>
        </div>
    </div>
</body>
</html>