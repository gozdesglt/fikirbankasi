FROM php:8.2-apache

# Diğer MPM'leri KAPAT (ÖNEMLİ)
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# PHP MySQL driver'ları
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Apache rewrite
RUN a2enmod rewrite

# Projeyi kopyala
COPY . /var/www/html/

# Yetkiler
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
