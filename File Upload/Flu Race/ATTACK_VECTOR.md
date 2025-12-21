# Attack Vector Analysis - Flu Race Challenge

## ✅ Challenge Design (Updated)

### How It Works:
1. **Upload accepts ANY file** - No immediate validation
2. **File briefly exists** in `/var/www/html/uploads/`
3. **YARA scanner runs every 0.5 seconds** - Detects and deletes malicious files
4. **Rate limiting**: Max 10 uploads per 5 seconds
5. **Random delays**: 0.1-0.5s per upload
6. **Players must race** to execute before deletion

### The Race Window:
```
Upload → File Exists (0-0.5s) → YARA Scan → File Deleted
              ↑
         Must access HERE!
```

## 🎯 Both Solver Scripts Work

### Solver 1: solverrace.py
```python
# Uploads: shell_<timestamp>.php
# Content: <?php system("cat /flag*") ?>
# Strategy: Continuous upload + concurrent access threads
```

**Why it works:**
- ✅ Uploads pure PHP (no image headers needed)
- ✅ Multi-threaded: 20 concurrent access attempts
- ✅ Continuous upload in background thread
- ✅ Eventually hits the 0-0.5s window before YARA deletes

### Solver 2: httpx_solver.py
```python
# Uploads: rootkids.php
# Content: <?php system('cat /f*')?>
# Strategy: Single upload + delayed access
```

**Why it works:**
- ✅ Uploads pure PHP
- ✅ Uses ThreadPoolExecutor for concurrent upload+access
- ✅ 0.1s delay gives time for upload to complete
- ✅ Small window but possible with timing

## 🔒 Why Manual Solving Fails

### Manual Attempt:
1. User uploads file via browser
2. Random delay: 0.1-0.5s
3. User tries to access via browser
4. **FAIL**: YARA already scanned and deleted (0.5s cycle)
5. Human reaction time > 0.5s window

### Script Advantage:
- **No human delay**: Sub-millisecond access attempts
- **Concurrent threads**: Multiple simultaneous requests
- **Automated retry**: Continuous attempts until success
- **Precise timing**: Can calculate optimal delays

## 📊 Difficulty Tuning

### Current Settings:
- Scan interval: `0.5s` ⚡
- Random delay: `0.1-0.5s` 🎲
- Rate limit: `10/5s` 🚦
- Threads needed: `20+`

### If Too Hard (reduce difficulty):
```bash
# scan_and_cleanup.sh
sleep 1  # Was: 0.5
```
```php
// upload.php
usleep(rand(50000, 200000)); // Was: 100000-500000
$max_uploads = 20; // Was: 10
```

### If Too Easy (increase difficulty):
```bash
# scan_and_cleanup.sh
sleep 0.2  # Was: 0.5
```
```php
// upload.php
usleep(rand(200000, 1000000)); // Was: 100000-500000
$max_uploads = 5; // Was: 10
```

## 🧪 Testing Checklist

### ✅ Test 1: Manual Upload Should Fail
```bash
# In browser:
1. Go to http://localhost:6005
2. Upload any PHP file
3. Quickly access http://localhost:6005/uploads/yourfile.php
4. Expected: 404 Not Found (file deleted)
```

### ✅ Test 2: Solver 1 Should Succeed
```bash
cd "PAI Ganjil/File Upload/FLU RACE"
python solverrace.py http://localhost:6005
# Expected: Flag output within 30 seconds
```

### ✅ Test 3: Solver 2 Should Succeed
```bash
pip install httpx
python solver.py -t http://localhost:6005
# Expected: Flag output
```

### ✅ Test 4: Rate Limiting Works
```bash
# Run solver with verbose mode and observe:
# - Initial uploads work
# - After 10 uploads in 5s: "Too many upload attempts"
# - After 5s cooldown: Uploads resume
```

## 🎓 Learning Objectives

Players learn:
1. **Race condition exploitation** - Timing attacks
2. **Multi-threading** - Concurrent programming
3. **HTTP automation** - requests/httpx libraries
4. **Attack timing** - Understanding defensive windows
5. **Persistence** - Retry strategies

## 🔧 Challenge Files

### Modified Files:
- ✅ `upload.php` - Removed immediate validation, accepts all files
- ✅ `scan_and_cleanup.sh` - Fast 0.5s scan interval
- ✅ `virus_rules.yar` - Simplified to catch obvious shells
- ✅ `Dockerfile` - Proper Apache + YARA setup

### Working As Intended:
- ✅ Rate limiting prevents brute force
- ✅ Random delays prevent predictable timing
- ✅ YARA scanner removes persistent shells
- ✅ Both example solvers work
- ✅ Manual solving is impractical

## 🏁 Success Criteria

Challenge is successful if:
- ✅ Manual browser upload → access fails (human too slow)
- ✅ Script with race condition → succeeds (automated timing)
- ✅ Flag retrieved: `PAI{r4c3_c0nd1t10n_upl04d_pwn3d_2025}`
- ✅ Players must write/use automation scripts
- ✅ Players understand race condition concept

---

**Challenge Status:** ✅ Ready for Deployment

The attack vector is now **perfectly tuned** for both example solvers!
