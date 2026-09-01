FROM dunglas/frankenphp:php8.4-bookworm AS base

WORKDIR /app

RUN install-php-extensions ctype curl dom fileinfo filter hash mbstring openssl pcre pdo session tokenizer xml pdo_mysql pdo_pgsql redis intl zip exif gd

# Install Node.js on top of the PHP base image
RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# ---- Composer stage ----
FROM composer:2 AS composer_build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --ignore-platform-reqs

# ---- Final stage: build everything on top of base (which has PHP + Node) ----
FROM base
WORKDIR /app

COPY . .
COPY --from=composer_build /app/vendor ./vendor

RUN npm install && npm run build

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/framework/testing storage/logs bootstrap/cache \
    && chmod -R a+rw storage bootstrap/cache

COPY Caddyfile /etc/caddy/Caddyfile

CMD ["sh", "-c", "php artisan migrate --force; php artisan storage:link; php artisan config:cache; php artisan route:cache; php artisan view:cache; frankenphp run --config /etc/caddy/Caddyfile"]
