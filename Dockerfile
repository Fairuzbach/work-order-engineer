# Menggunakan PHP versi 8.2 dengan Apache
FROM php:8.2-apache

# 1. Install program pendukung (System Dependencies)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    nodejs \
    npm

# 2. Install Ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Install Composer (Manajer PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Atur folder kerja
WORKDIR /var/www/html

# 5. Copy semua kode Anda ke dalam Docker
COPY . .

# 6. Install Library Laravel
RUN composer install --no-dev --optimize-autoloader

# 7. Build React JS
RUN npm install && npm run build

# 8. Atur hak akses folder storage (supaya tidak error permission denied)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Konfigurasi Apache agar membaca folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 10. Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# 11. Buka Port 80
EXPOSE 80