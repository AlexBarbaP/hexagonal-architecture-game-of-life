FROM php:7.1.3-fpm
MAINTAINER Alex Agile <alexagile@alexagile.com>

# session.save_path = /tmp

RUN mkdir -p /var/lib/php/sessions
RUN chmod -R 777 /var/lib/php/sessions

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

## Compile and install latest version of intl extension (Symfony projects required)
#RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/58.2/icu4c-58_2-src.tgz \
#    && tar -zxf /tmp/icu.tar.gz -C /tmp && cd /tmp/icu/source \
#    && ./configure --prefix=/usr/local && make && make install
#
#RUN docker-php-ext-configure intl --with-icu-dir=/usr/local \
#    && docker-php-ext-install intl