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

# Set folder permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Aktifkan mod_rewrite untuk Apache (jika diperlukan)
RUN a2enmod rewrite

# Expose port 80 untuk HTTP dan port 8080 untuk PHP built-in server
EXPOSE 80
EXPOSE 8080

# Jalankan PHP built-in server dan Apache secara bersamaan
CMD ["sh", "-c", "php -S 0.0.0.0:80 -t /var/www/html & apache2-foreground"]
