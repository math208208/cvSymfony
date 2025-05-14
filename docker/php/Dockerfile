FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
    libzip-dev \
    unzip \
    libicu-dev \
    libpng-dev \
    libpq-dev \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP
RUN docker-php-ext-install \
    pdo_pgsql \
    zip \
    intl \
    gd \
    opcache

# Configurer Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.disabled
RUN if [ "$PHP_XDEBUG_ENABLED" = "1" ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    mv /usr/local/etc/php/conf.d/xdebug.ini.disabled /usr/local/etc/php/conf.d/xdebug.ini; \
    fi

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN composer self-update --2

# Configurer les permissions
RUN mkdir -p /var/www/.composer /tmp/phpstan/ && \
    chown -R 1000:1000 /var/www/.composer /tmp/phpstan/ /usr/local/bin/composer

# Paramètres PHP
RUN { \
    echo 'memory_limit = 8G'; \
    echo 'post_max_size = 100M'; \
    echo 'upload_max_filesize = 100M'; \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/app

USER 1000:1000