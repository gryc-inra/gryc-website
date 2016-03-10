FROM php:7-fpm
MAINTAINER Mathieu Piot <mathieu.piot@agroparistech.fr>

RUN apt-get update && apt-get install -y libicu-dev zlib1g-dev git
RUN pecl install xdebug apcu
RUN docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        zip
RUN docker-php-ext-enable \
        opcache \
        xdebug \
        apcu
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer
