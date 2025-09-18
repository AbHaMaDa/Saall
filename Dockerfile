# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# فعل Apache mod_rewrite
RUN a2enmod rewrite

# انسخ الملفات
COPY . /var/www/html

# حدد مجلد العمل
WORKDIR /var/www/html

# نزّل Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# نزّل المكتبات المطلوبة للـ Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# شغّل composer install
RUN composer install --no-dev --optimize-autoloader

# إعداد Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Laravel app runs on port 80
EXPOSE 80
