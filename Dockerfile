ARG PHP_VERSION
ARG BASE_IMAGE="php:${PHP_VERSION}-fpm-alpine"
FROM ${BASE_IMAGE} as php_base

ARG APCU_VERSION=5.1.21
ARG COMPOSER_VERSION=2.1.14
ARG PICKLE_VERSION=0.7.7
ENV TZ=Europe/Paris

RUN apk upgrade -U -a \
    && apk add --no-cache \
    bash \
    tzdata \
    && rm -rf /var/cache/* \
    && mkdir /var/cache/apk

RUN wget https://github.com/FriendsOfPHP/pickle/releases/download/v${PICKLE_VERSION}/pickle.phar \
 && mv pickle.phar /usr/local/bin/pickle \
 && chmod +x /usr/local/bin/pickle

RUN apk add --no-cache \
        $PHPIZE_DEPS \
        libzip-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libmcrypt-dev \
        zlib-dev \
        libpng-dev \
        libwebp-dev \
        icu-dev \
        g++ \
        exiftool \
        git \
        su-exec \
        libxslt-dev \
        libgcrypt-dev \
        bash

#Configure, install and enable all php packages
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/ \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure exif

RUN docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && docker-php-ext-install iconv \
    && docker-php-ext-install exif \
    && docker-php-ext-install opcache \
    && docker-php-ext-install xml \
    && docker-php-ext-install dom \
    && docker-php-ext-install xsl

RUN pickle install apcu-${APCU_VERSION} \
    && docker-php-ext-enable apcu

#Install composer programmatically
COPY docker/install-composer.sh /install-composer.sh
RUN chmod +x /install-composer.sh \
    && /install-composer.sh

#Remove cached files
RUN docker-php-source delete \
    && rm -rf \
        /usr/include/php \
        /usr/lib/php/build \
        /tmp/* \
        /var/lib/apt/lists/* \
        /root/.composer

RUN mkdir /.composer \
    && chmod 777 /.composer

WORKDIR /srv/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin

COPY docker/entrypoint.sh /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]

#Development image
FROM php_base AS php_dev

ENV XDEBUG_START_WITH_REQUEST=0 \
    XDEBUG_MAX_NESTING_LEVEL=9999 \
    XDEBUG_CLIENT_PORT=9003 \
    XDEBUG_IDEKEY=PHPSTORM \
    XDEBUG_MODE=debug \
    XDEBUG_DISCOVER_CLIENT_HOST=1 \
    XDEBUG_CLIENT_HOST=host.docker.internal \
    XDEBUG_VERSION=3.1.2 \
    XDEBUG_LOG_LEVEL=0

ENV PHP_IDE_CONFIG='serverName=Xdebug'

#Install Xdebug
RUN pickle install xdebug-${XDEBUG_VERSION}
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=$XDEBUG_MODE" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=$XDEBUG_CLIENT_PORT" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=$XDEBUG_DISCOVER_CLIENT_HOST" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=$XDEBUG_CLIENT_HOST" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.max_nesting_level=$XDEBUG_MAX_NESTING_LEVEL" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=$XDEBUG_START_WITH_REQUEST" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=$XDEBUG_IDEKEY" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.log_level=$XDEBUG_LOG_LEVEL" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && docker-php-ext-enable xdebug

#Set opcache config for dev
RUN grep -q '^opcache.validate_timestamps=0' /usr/local/etc/php/conf.d/opcache.ini \
    && sed -i 's/^opcache.validate_timestamps=0/opcache.validate_timestamps=1/' /usr/local/etc/php/conf.d/opcache.ini \
    || echo 'opcache.validate_timestamps=1' >> /usr/local/etc/php/conf.d/opcache.ini

#Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN chown -R 1000 /srv/app \
    && chown -R 1000 /opt
#Set global git
RUN git config --global user.email "you@example.com" && git config --global user.name "Your Name"



