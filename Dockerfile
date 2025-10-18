# Dockerfile for QSEND Application
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    git \
    curl \
    nano \
    composer \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mysqli \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Create necessary directories and set permissions
# Note: Code will be mounted as volume, not copied
RUN mkdir -p /var/www/html/logs \
    /var/www/html/temp \
    /var/www/html/deacompress \
    /var/www/html/DEASemester \
    /var/www/html/vendor3 \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/temp \
    && chmod -R 777 /var/www/html/deacompress \
    && chown -R www-data:www-data /var/www/html

# Configure Apache
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/qsend.conf \
    && a2enconf qsend

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

