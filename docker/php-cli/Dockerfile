FROM php:7.1.2-cli

MAINTAINER Alex Agile <alexagile@alexagile.com>

# Install dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libpng12-dev \
    zlib1g-dev

# install extensions
RUN docker-php-ext-install \
    gd \
    json \
    mcrypt \
    pdo_mysql \
    zip

# install pecl extensions
RUN pecl install \
    apcu-beta \
    memcached \
    redis \
    xdebug

# configure xdebug extension
COPY ./xdebug.ini /tmp/
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -f /tmp/xdebug.ini

# enable pecl extensions
RUN docker-php-ext-enable \
    apcu \
    memcached \
    redis \
    xdebug
