FROM php:8.2-apache

RUN apt-get update && apt-get install -y xxd
RUN a2enmod rewrite

RUN mkdir /var/www/html/uploads && chmod 777 /var/www/html/uploads

COPY index.php /var/www/html/index.php
COPY upload.php /var/www/html/upload.php
COPY flag.txt /var/www/html/flag.txt
RUN mv /var/www/html/flag.txt /`head -c 16 /dev/urandom | xxd -p`.txt