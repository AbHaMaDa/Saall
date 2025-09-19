# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# فعل Apache mod_rewrite
RUN a2enmod rewrite

# حدد مجلد العمل
WORKDIR /var/www/html

# انسخ الملفات
COPY . /var/www/html

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



# خلي Apache يوجّه للـ public folder
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Laravel app runs on port 80
EXPOSE 80

