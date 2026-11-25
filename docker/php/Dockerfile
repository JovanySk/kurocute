# Imagen de desarrollo para KuroCute (Laravel 13, PHP 8.5)
FROM php:8.5-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libicu-dev libpng-dev libjpeg62-turbo-dev \
        libfreetype6-dev libwebp-dev default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip intl bcmath exif \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN { \
        echo 'memory_limit=512M'; \
        echo 'upload_max_filesize=12M'; \
        echo 'post_max_size=16M'; \
    } > /usr/local/etc/php/conf.d/kurocute.ini

COPY docker/php/entrypoint.sh /usr/local/bin/kurocute-entrypoint
RUN chmod +x /usr/local/bin/kurocute-entrypoint

WORKDIR /var/www/html
EXPOSE 8000
ENTRYPOINT ["kurocute-entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
