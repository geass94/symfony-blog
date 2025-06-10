FROM php:8.2-fpm

# Update and install necessary packages
RUN apt-get update && apt-get install -y --fix-missing \
    build-essential \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libmagickwand-dev --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql zip intl mbstring exif pcntl bcmath gd sockets

# Install Imagick extension
RUN pecl install imagick
RUN docker-php-ext-enable imagick

# Install PostgreSQL driver
RUN apt-get update && apt-get install -y libpq-dev

# Install Node.js 18.x
RUN curl -sLS https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

# Install npm
RUN npm install -g npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for Laravel application
RUN useradd -u 1000 -ms /bin/bash -g www-data www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www . /var/www

# Change current user to www
USER www

EXPOSE 9000
