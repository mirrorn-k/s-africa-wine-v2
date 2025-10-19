# ========== BASE IMAGE ==========
ARG TAG=8.1-apache-bullseye
FROM php:${TAG}

# ========== ENVIRONMENT ==========
ENV APACHE_DOCUMENT_ROOT /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR ${APACHE_DOCUMENT_ROOT}

# ========== SYSTEM SETUP ==========
RUN apt update && apt upgrade -y && \
    apt install --no-install-recommends -y \
      apt-transport-https apt-utils build-essential curl git vim gnupg2 \
      libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev \
      libzip-dev locales ssl-cert unzip zlib1g-dev libwebp-dev \
      libpq-dev && \
    apt clean && rm -rf /var/lib/apt/lists/* && \
    echo "en_US.UTF-8 UTF-8" >/etc/locale.gen && locale-gen

# ========== PHP EXTENSIONS ==========
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) zip gd mysqli pdo_mysql opcache intl pgsql pdo_pgsql && \
    pecl install apcu && echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apc.ini

# ========== APACHE SETUP ==========
RUN mkdir -p ${APACHE_DOCUMENT_ROOT} && \
    sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite headers ssl && \
    ln -s /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf && \
    echo "Mutex posixsem" >> /etc/apache2/apache2.conf

EXPOSE 80 443

# ========== PHP CONFIGURATION ==========
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY dockerbuild/php.ini $PHP_INI_DIR/conf.d/

# ========== COMPOSER ==========
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer && \
    composer config -g repos.packagist composer https://packagist.jp

# ========== APP COPY (ホスト側のファイルをそのまま使用) ==========
# ホストにEC-CUBEのソースがある前提で、build時はコピーしない
# COPY . ${APACHE_DOCUMENT_ROOT}

# ========== PERMISSION ADJUSTMENTS ==========
# vendorはホストでcomposer install済みなら volumeで上書きされる
RUN chown -R www-data:www-data ${APACHE_DOCUMENT_ROOT} && \
    find ${APACHE_DOCUMENT_ROOT} -type d -exec chmod 775 {} \; && \
    find ${APACHE_DOCUMENT_ROOT} -type f -exec chmod 664 {} \;

# ========== HEALTHCHECK ==========
HEALTHCHECK --interval=10s --timeout=5s --retries=30 CMD pgrep apache2 || exit 1

CMD ["apache2-foreground"]
