FROM php:8.1-apache

# Install system dependencies including YARA
RUN apt-get update && apt-get install -y \
    wget \
    build-essential \
    automake \
    libtool \
    make \
    gcc \
    pkg-config \
    libssl-dev \
    libjansson-dev \
    libmagic-dev \
    curl \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*

# Install YARA from source
RUN wget https://github.com/VirusTotal/yara/archive/v4.3.2.tar.gz \
    && tar -xzf v4.3.2.tar.gz \
    && cd yara-4.3.2 \
    && ./bootstrap.sh \
    && ./configure --enable-magic --enable-dotnet \
    && make \
    && make install \
    && ldconfig \
    && cd .. \
    && rm -rf yara-4.3.2 v4.3.2.tar.gz

# Create necessary directories
RUN mkdir -p /etc/yara/rules /var/www/html/uploads

# Enable Apache modules
RUN a2enmod rewrite

# Set PHP upload limits
RUN echo "upload_max_filesize = 1M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 2M" >> /usr/local/etc/php/php.ini \
    && echo "max_execution_time = 30" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 128M" >> /usr/local/etc/php/php.ini

# Configure Apache to execute .jpg files as PHP (VULNERABILITY for CTF)
RUN echo "AddType application/x-httpd-php .jpg" >> /etc/apache2/apache2.conf

# Copy application files
COPY index.php /var/www/html/
COPY upload.php /var/www/html/
COPY flag.txt /flag.txt

# Copy YARA rules
COPY virus_rules.yar /etc/yara/rules/

# Copy scripts and fix line endings
COPY scan_and_cleanup.sh /usr/local/bin/scan_and_cleanup.sh
COPY start.sh /usr/local/bin/start.sh
RUN dos2unix /usr/local/bin/scan_and_cleanup.sh /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/scan_and_cleanup.sh /usr/local/bin/start.sh

# Set proper permissions
RUN chmod 644 /flag.txt \
    && chmod 644 /etc/yara/rules/virus_rules.yar \
    && chown -R www-data:www-data /var/www/html \
    && chown www-data:www-data /var/www/html/uploads \
    && chmod 777 /var/www/html/uploads

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

EXPOSE 80

# Start services using our start script
CMD ["/usr/local/bin/start.sh"]