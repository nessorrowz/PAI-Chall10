# Challenge Hardening Changes

## Problem
Players were solving the challenge **manually** by:
- Uploading files with GIF89a magic bytes + PHP code
- Accessing the file before the 30-second cleanup cycle
- Not using scripting/race condition exploitation as intended

## Solution: Make Scripting Mandatory

### 1. ⚡ Aggressive Cleanup (scan_and_cleanup.sh)
**Changed:** Scan interval from 30 seconds → **0.5 seconds**
```bash
sleep 0.5  # Was: sleep 30
```
**Impact:** Files are scanned and deleted within 0.5s, making manual timing nearly impossible

### 2. 🎲 Random Upload Delays (upload.php)
**Added:** Random delay between 0.1-0.5 seconds on each upload
```php
usleep(rand(100000, 500000)); // 0.1 to 0.5 seconds
```
**Impact:** Players cannot predict exact timing, must use concurrent threads

### 3. 🚦 Rate Limiting (upload.php)  
**Added:** Maximum 10 uploads per 5 seconds per session
```php
// Rate limiting: max 10 uploads per 5 seconds
if (count($_SESSION['upload_times']) >= $max_uploads) {
    exit("Too many upload attempts");
}
```
**Impact:** Prevents manual spam clicking, requires programmatic approach

## Why These Changes Work

### Manual Attack (Now Failed)
1. Player uploads malicious file manually
2. Tries to quickly access it in browser
3. **FAILS**: Random delay + 0.5s cleanup = unpredictable timing
4. **FAILS**: Rate limit prevents rapid retries

### Scripted Attack (Intended Solution)
1. Script uploads file in loop
2. **Concurrent threads** continuously attempt to access
3. Eventually hits the narrow time window
4. **SUCCESS**: Multi-threaded race condition exploitation

## Testing the Changes

### Should Fail (Manual)
```bash
# Manual curl attempts
curl -F "imageFile=@shell.php" http://localhost:8080/upload.php
curl http://localhost:8080/uploads/shell.php  # Will likely 404
```

### Should Succeed (Scripted)
```bash
# Race condition exploit
python3 flu_race_solver.py http://localhost:8080
```
```python
# httpx solution also works
python3 httpx_solver.py -t http://localhost:8080
```

## Configuration Tuning

If the challenge is **too hard**:
- Increase scan interval: `sleep 1` or `sleep 2`
- Reduce random delay: `usleep(rand(50000, 200000))`
- Increase rate limit: `$max_uploads = 20`

If the challenge is **too easy**:
- Decrease scan interval: `sleep 0.2`
- Increase random delay: `usleep(rand(200000, 1000000))`
- Decrease rate limit: `$max_uploads = 5`

## Expected Behavior

✅ **Players must:**
- Write a Python/bash script
- Use threading/concurrent requests
- Understand race condition timing
- Upload + access simultaneously

❌ **Players cannot:**
- Upload once and access manually
- Use browser alone to solve
- Rely on GIF89a bypass without timing exploitation

## Files Modified
1. `scan_and_cleanup.sh` - Reduced scan interval to 0.5s
2. `upload.php` - Added rate limiting + random delays
3. `README.md` - Updated documentation

## Deployment
```bash
# Rebuild container with changes
docker-compose down
docker-compose up -d --build

# Verify changes
docker-compose logs flu-race-web
```
