# 🏁 FLU RACE - File Upload Race Condition Challenge

## Overview
FLU RACE is a cybersecurity challenge that demonstrates a **race condition vulnerability** in file upload mechanisms. The challenge showcases how improper timing in file validation can lead to remote code execution.

## 🔧 Vulnerability Description

### The Race Condition Flow:
1. **Upload** → File is uploaded to the server ✅
2. **Random Delay** → Random processing delay (0.1-0.5s) to prevent predictable timing
3. **Scan** → YARA antivirus scans every 0.5 seconds
4. **Validate** → File type and content validation
5. **Delete** → Malicious files are removed ❌

### The Vulnerability Window:
Between steps 1 and 5, there's a **critical time window** where:
- The malicious file exists on the server
- The file can be accessed via HTTP requests
- PHP code can be executed before deletion
- This creates a **race condition** opportunity

**Challenge Hardening**: 
- ⚡ **Aggressive scanning**: YARA scans every 0.5 seconds (not 30s)
- 🎲 **Random delays**: 100-500ms random delays prevent manual timing
- 🚦 **Rate limiting**: Max 10 uploads per 5 seconds prevents spam
- 📜 **Scripting required**: Manual timing is nearly impossible

## 📁 Challenge Structure

```
FLU RACE Challenge/
├── index.php              # Main upload interface
├── upload.php             # Vulnerable upload handler
├── flu_race_solver.py     # Exploitation script
├── Dockerfile             # Container setup
├── docker-compose.yml     # Container orchestration
├── virus_rules.yar        # YARA detection rules
├── flag.txt               # Challenge flag
└── README.md              # This file
```

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Python 3.x (for the solver)
- Required Python packages: `requests`

### 1. Deploy the Challenge
```bash
# Clone or navigate to challenge directory
cd "FLU RACE Challenge"

# Build and start the container
docker-compose up -d --build

# Verify the service is running
docker-compose logs flu-race-web
```

### 2. Access the Challenge
- **Web Interface**: http://localhost:8080
- **Upload Endpoint**: http://localhost:8080/upload.php
- **Uploads Directory**: http://localhost:8080/uploads/

### 3. Test the Exploit
```bash
# Install required Python packages
pip3 install requests

# Run the exploitation script
python3 flu_race_solver.py http://localhost:8080

# With custom options
python3 flu_race_solver.py http://localhost:8080 --threads 50 --verbose
```

## 🎯 Exploitation Methodology

### Attack Strategy:
1. **Polyglot Creation**: Craft a file that appears to be a valid image but contains PHP code
2. **Rapid Upload**: Continuously upload the malicious file
3. **Concurrent Access**: Simultaneously attempt to access the uploaded file
4. **Time Window Exploitation**: Execute code during the validation delay
5. **Shell Interaction**: Gain remote command execution

### Technical Details:
- **JPEG Header Spoofing**: Uses valid JPEG magic bytes to bypass `getimagesize()`
- **PHP Code Injection**: Embeds webshell functionality within the fake image
- **Multi-threading**: Employs concurrent threads to maximize success probability
- **Timing Optimization**: Exploits the `usleep(100000)` delay in the upload handler

## 🔍 Security Analysis

### Vulnerable Code Pattern:
```php
// VULNERABLE: File uploaded first, validated later
if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $uploadPath)) {
    usleep(100000); // 0.1 second delay - RACE CONDITION WINDOW!
    
    // Validation happens AFTER upload
    exec($yaraCommand, $output, $returnCode);
    $isImage = getimagesize($uploadPath);
    
    if ($isImage === false || $containsSuspicious) {
        unlink($uploadPath); // Delete if invalid - TOO LATE!
    }
}
```

### Security Flaws:
1. **Improper Upload Flow**: Files are moved to accessible location before validation
2. **Timing Vulnerability**: Artificial delay increases exploitation window
3. **Insufficient Access Control**: No temporary upload directory isolation
4. **Missing Rate Limiting**: No protection against rapid upload attempts

## 🛡️ Mitigation Strategies

### Recommended Fixes:
1. **Upload to Temporary Directory**: Store files outside web root during validation
2. **Validate Before Moving**: Complete all security checks before making files accessible
3. **Atomic Operations**: Use atomic move operations after successful validation
4. **Access Controls**: Implement proper file permissions and access restrictions
5. **Rate Limiting**: Add upload frequency restrictions per IP/session

### Secure Implementation Example:
```php
// SECURE: Validate first, then move
$tempPath = sys_get_temp_dir() . '/' . uniqid('upload_', true);
if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $tempPath)) {
    // Validate in temporary location
    if (validateFile($tempPath)) {
        // Only move to web accessible location if valid
        move($tempPath, $uploadPath);
    } else {
        unlink($tempPath);
    }
}
```

## 🎮 Challenge Flags

The challenge contains a flag in the format: `CTF{...}`
- **Location**: `/flag.txt` on the container
- **Access Method**: Remote code execution via race condition exploit

## 🔧 Customization

### Adjusting Difficulty:
1. **Reduce Delay**: Modify `usleep()` value in `upload.php`
2. **Strengthen YARA Rules**: Add more detection patterns
3. **Add Rate Limiting**: Implement request frequency controls
4. **Enhanced Validation**: Add more security checks

### Environment Variables:
```yaml
# docker-compose.yml
environment:
  - UPLOAD_MAX_SIZE=1M
  - VALIDATION_DELAY=100000
  - YARA_RULES_PATH=/etc/yara/rules/virus_rules.yar
```

## 📚 Educational Objectives

Students will learn:
- **Race Condition Vulnerabilities**: Understanding timing-based security flaws
- **File Upload Security**: Proper validation and storage mechanisms
- **Web Application Testing**: Exploitation techniques and methodologies
- **Container Security**: Docker-based challenge deployment
- **Defensive Programming**: Secure coding practices and mitigation strategies

## 🐛 Troubleshooting

### Common Issues:

**Container Won't Start:**
```bash
# Check logs
docker-compose logs flu-race-web

# Rebuild container
docker-compose down
docker-compose up --build
```

**Exploit Not Working:**
```bash
# Increase thread count
python3 flu_race_solver.py http://localhost:8080 --threads 100

# Enable verbose mode
python3 flu_race_solver.py http://localhost:8080 --verbose
```

**Permission Errors:**
```bash
# Fix upload directory permissions
docker-compose exec flu-race-web chmod 755 /var/www/html/uploads
docker-compose exec flu-race-web chown www-data:www-data /var/www/html/uploads
```

## 📄 License

This challenge is created for educational purposes. Use responsibly and only in authorized testing environments.

## 👥 Credits

- **Challenge Design**: PAI Ganjil Assistant
- **Vulnerability Research**: Based on real-world file upload race conditions
- **Educational Framework**: Designed for cybersecurity learning

---

**⚠️ Disclaimer**: This challenge is for educational purposes only. Do not use these techniques against systems without proper authorization.