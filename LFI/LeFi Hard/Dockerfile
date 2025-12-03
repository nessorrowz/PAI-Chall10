FROM php:8.1-apache

RUN apt-get update && \
    apt-get install -y xxd

COPY html/ /var/www/html/

COPY flag.txt /var/www/html/flag.txt
RUN mv /var/www/html/flag.txt /`head -c 16 /dev/urandom | xxd -p`.txt

EXPOSE 80
