# Use official PHP image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate app key if not set (optional safety)
RUN php artisan key:generate || true

# Expose Render port
EXPOSE 10000

# Migrate Database Eynd :> Start Laravel
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000