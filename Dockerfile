# Gunakan image PHP sebagai base image
FROM php:8.2-fpm

# Instalasi dependencies sistem
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev

# Instalasi extensions PHP yang diperlukan
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Instalasi Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Update sistem dan tambahkan Node.js LTS terbaru
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Set working directory
WORKDIR /var/www

# Salin file composer.lock dan composer.json
COPY composer.lock composer.json ./

# Set variabel lingkungan untuk mengizinkan plugin Composer berjalan sebagai root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Jalankan Composer untuk menginstall dependencies PHP
RUN composer install --no-scripts --no-progress --prefer-dist

# Salin seluruh file ke container
COPY . .

# Install dependencies NPM dan jalankan build
RUN npm install
RUN npm run build

# Beri izin kepada direktori storage dan cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 dan start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]