#!/bin/bash

# Start the YARA scanner in background
/usr/local/bin/scan_and_cleanup.sh &

# Start Apache in foreground
apache2-foreground