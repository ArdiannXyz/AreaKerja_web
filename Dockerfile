# ===== Step 1: Gunakan PHP 8.2 dengan Apache =====
FROM php:8.2-apache

WORKDIR /var/www/html

# ===== Step 2: Install system dependencies =====
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev zip curl \
    libpng-dev libjpeg-dev libfreetype6-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
    
# ===== Step 3: Enable mod_rewrite =====
RUN a2enmod rewrite

# ===== Step 3.1: Set Apache DocumentRoot ke /public (WAJIB) =====
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

RUN echo "<Directory /var/www/html/public>
    AllowOverride All
    Require all granted
</Directory>" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# ===== Step 4: Install Composer =====
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ===== Step 5: Copy project =====
COPY . .

# ===== Step 6: Bersihkan cache yang mungkin bikin error =====
RUN rm -rf bootstrap/cache/*.php

# ===== Step 7: Install dependencies =====
RUN composer install --no-dev --optimize-autoloader

# ===== Step 8: Set permissions Laravel =====
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ===== Step 9: Buat ulang config cache =====
RUN php artisan config:clear && php artisan config:cache

EXPOSE 80
CMD ["apache2-foreground"]
