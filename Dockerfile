# Gunakan PHP image resmi dengan Apache
FROM php:8.2-apache

# Set lingkungan kerja
WORKDIR /var/www/html

# Install ekstensi PHP yang diperlukan
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin aplikasi ke dalam container
COPY . /var/www/html

# Set folder permissions to ensure Apache can read files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Aktifkan mod_rewrite untuk Apache (jika diperlukan)
RUN a2enmod rewrite

# Expose port 80 untuk HTTP
EXPOSE 80

# Jalankan Apache saat container dijalankan
CMD ["apache2-foreground"]
