#!/bin/bash

UPLOAD_DIR="/var/www/html/uploads"
YARA_RULES="/etc/yara/rules/virus_rules.yar"

while true; do
    echo "[$(date)] Starting YARA scan..."
    
    if [ -d "$UPLOAD_DIR" ]; then
        # Scan all files in uploads directory
        for file in "$UPLOAD_DIR"/*; do
            if [ -f "$file" ]; then
                # Run YARA scan on the file
                if yara "$YARA_RULES" "$file" > /dev/null 2>&1; then
                    echo "[$(date)] MALICIOUS FILE DETECTED: $file - REMOVING"
                    rm -f "$file"
                else
                    echo "[$(date)] File clean: $file"
                fi
            fi
        done
    fi
    
    # Wait 0.05 seconds before next scan (extremely aggressive cleanup)
    # This creates a ~50ms window requiring proper race condition exploitation
    sleep 0.05
done