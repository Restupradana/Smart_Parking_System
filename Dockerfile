FROM php:8.2-fpm

# ─── System Dependencies ──────────────────────────────
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ─── Install Composer ─────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ─── Install Node.js 18.x ────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ─── Working Directory ────────────────────────────────
WORKDIR /var/www/html

# ─── Step 1: Install PHP Dependencies ────────────────
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ─── Step 2: Install Frontend Dependencies & Build ────
COPY package.json package-lock.json ./
RUN npm install && npm run build

# ─── Step 3: Copy Source Code ────────────────────────
COPY . .

# ─── Step 4: Run Composer Post-Install Scripts ────────
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi

# ─── Permissions ──────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ─── Entrypoint ──────────────────────────────────────
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
