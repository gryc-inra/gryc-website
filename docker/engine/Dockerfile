FROM php:7.0-fpm
MAINTAINER Mathieu Piot <mathieu.piot@agroparistech.fr>

RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        git \
    && curl -L -o /tmp/xdebug-2.4.0rc4.tgz https://xdebug.org/files/xdebug-2.4.0rc4.tgz \
    && tar xfz /tmp/xdebug-2.4.0rc4.tgz \
    && rm -r /tmp/xdebug-2.4.0rc4.tgz \
    && mv xdebug-2.4.0RC4 /usr/src/php/ext/xdebug \
    && docker-php-ext-install \
        intl \
        pdo \
	pdo_mysql \
        xdebug \
        zip \
    && docker-php-ext-enable \
        opcache \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

