#!/bin/bash

UPLOAD_DIR="/var/www/html/uploads"

echo "[*] Resetting storage id folders..."

if [ ! -d "$UPLOAD_DIR" ]; then
    echo "[!] Upload directory does not exist: $UPLOAD_DIR"
    exit 1
fi

find "$UPLOAD_DIR" -mindepth 1 -maxdepth 1 -exec rm -rf {} \;

echo "[+] Storage id folders cleaned."