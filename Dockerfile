# ============================================================
# Multi-stage Dockerfile for SIM UKM Jurnalistik
# Works with: Fly.io, Oracle Cloud, Railway, any Docker host
# ============================================================

# ── Stage 1: Build frontend assets ─────────────────────────
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.js ./
RUN npm run build


# ── Stage 2: Build PHP dependencies ────────────────────────
FROM composer:2 AS vendor-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist --ignore-platform-reqs


# ── Stage 3: Production image ─────────────────────────────
FROM php:8.4-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    sqlite-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        gd \
        zip \
        bcmath \
        intl \
        mbstring \
        opcache \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

# Copy application files
COPY . .

# Create required directories BEFORE composer commands
RUN mkdir -p storage/app/public storage/framework/{cache,sessions,views} \
    storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy vendor from builder
COPY --from=vendor-builder /app/vendor/ vendor/

# Copy built frontend assets
COPY --from=frontend-builder /app/public/build/ public/build/

# Install composer (for artisan commands)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Run composer dump-autoload for production
RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize --no-dev --ignore-platform-reqs

# Create storage link
RUN php artisan storage:link || true

# ── Nginx Configuration ────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# ── PHP-FPM Configuration ──────────────────────────────────
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ── PHP Production Settings ────────────────────────────────
COPY docker/php.ini /usr/local/etc/php/conf.d/production.ini

# ── Supervisor Configuration ───────────────────────────────
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ── Entrypoint Script ──────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
