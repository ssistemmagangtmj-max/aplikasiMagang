FROM php:8.2-apache

# Update dan install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl

# Install Node.js & npm (dibutuhkan untuk build Vue/Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install PHP extensions (MySQL & PostgreSQL)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite (Penting untuk Laravel routing)
RUN a2enmod rewrite

# Mengubah Document Root Apache agar membaca folder /public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set direktori kerja
WORKDIR /var/www/html

# Copy seluruh file project ke dalam container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependency PHP (tanpa package development)
RUN composer install --no-dev --optimize-autoloader

# Install dependency Frontend & Build (Vue)
RUN npm install
RUN npm run build

# Berikan hak akses (permission) ke Apache untuk folder storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port web
EXPOSE 80

# Jalankan script startup saat container berjalan
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
