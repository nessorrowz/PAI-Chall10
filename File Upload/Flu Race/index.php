<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FLU RACE - File Upload Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 650px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .header h1 {
            font-size: 2.8em;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.95;
            font-weight: 300;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .message {
            margin-bottom: 25px;
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .success {
            background: #d1f4e0;
            border: 2px solid #48c774;
            color: #257942;
        }
        
        .error {
            background: #ffe5e5;
            border: 2px solid #ff6b6b;
            color: #c92a2a;
        }
        
        .upload-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            border-radius: 16px;
            padding: 35px;
            border: 2px dashed #cbd5e0;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-section:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        
        .upload-icon {
            font-size: 3.5em;
            margin-bottom: 15px;
            display: block;
        }
        
        .upload-section h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .upload-section p {
            color: #718096;
            font-size: 0.95em;
            margin-bottom: 20px;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .file-label {
            display: inline-block;
            padding: 12px 28px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #4a5568;
        }
        
        .file-label:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .upload-btn {
            width: 100%;
            margin-top: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .upload-btn:active {
            transform: translateY(0);
        }
        
        .info-card {
            background: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
        }
        
        .info-card h4 {
            font-size: 1.1em;
            margin-bottom: 12px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
        }
        
        .info-list li {
            padding: 8px 0;
            color: #4a5568;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-list li::before {
            content: '✓';
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #48c774;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
            font-size: 0.8em;
        }
        
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            padding: 12px 20px;
            border-radius: 10px;
            margin-top: 25px;
            font-weight: 600;
            color: #2d3748;
            box-shadow: 0 2px 8px rgba(253, 203, 110, 0.3);
        }
        
        .filename-display {
            margin-top: 15px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            color: #4a5568;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            display: none;
        }
        
        .filename-display.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>🏁 FLU RACE</h1>
                <p>File Upload Racing Challenge</p>
            </div>
        </div>
        
        <div class="content">
            <?php if (isset($_GET['message'])): ?>
                <div class="message <?= strpos($_GET['message'], 'Success') === 0 ? 'success' : 'error' ?>">
                    <span><?= strpos($_GET['message'], 'Success') === 0 ? '✓' : '✗' ?></span>
                    <span><?= htmlspecialchars($_GET['message']) ?></span>
                </div>
            <?php endif; ?>
            
            <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <div class="upload-section" onclick="document.getElementById('fileInput').click()">
                    <span class="upload-icon">📁</span>
                    <h3>Upload Your File</h3>
                    <p>Click or drag to select a file</p>
                    <input type="file" name="imageFile" id="fileInput" required>
                    <label for="fileInput" class="file-label">Choose File</label>
                    <div id="filenameDisplay" class="filename-display"></div>
                    <button type="submit" name="submit" class="upload-btn">
                        Upload File
                    </button>
                </div>
            </form>
            
            <div class="security-badge">
                <span>🛡️</span>
                <span>Protected by Advanced YARA Scanning</span>
            </div>
            
            <div class="info-card">
                <h4>📋 Upload Guidelines</h4>
                <ul class="info-list">
                    <li>Maximum file size: 1MB</li>
                    <li>Real-time malware detection</li>
                    <li>Automatic content validation</li>
                    <li>SHA256 integrity checking</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        const fileInput = document.getElementById('fileInput');
        const filenameDisplay = document.getElementById('filenameDisplay');
        
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                filenameDisplay.textContent = '📄 ' + this.files[0].name;
                filenameDisplay.classList.add('show');
            }
        });
        
        // Prevent default drag behavior
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
    </script>
</body>
</html>