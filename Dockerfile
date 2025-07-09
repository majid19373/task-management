# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port
EXPOSE 9000
CMD ["php-fpm"]

RUN echo "alias art='php artisan'" >> /root/.profile
RUN echo "alias artm='php artisan migrate'" >> /root/.profile
RUN echo "alias artm:s='php artisan migrate:status'" >> /root/.profile
RUN echo "alias artm:f='php artisan migrate:fresh'" >> /root/.profile
RUN echo "alias artm:fs='php artisan migrate:fresh --seed'" >> /root/.profile
RUN echo "alias artt='php artisan test'" >> /root/.profile
