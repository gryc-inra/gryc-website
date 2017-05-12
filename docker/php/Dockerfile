FROM php:7-fpm

RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        git

RUN pecl install apcu xdebug
RUN docker-php-ext-install intl pdo pdo_mysql zip
RUN docker-php-ext-enable opcache apcu xdebug

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

COPY php.ini /usr/local/etc/php/

## THE BLAST PART
RUN apt-get update && apt-get install -y wget && rm -rf /var/lib/apt/lists/*

RUN mkdir /home/blast
WORKDIR /home/blast

RUN wget ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-2.6.0+-x64-linux.tar.gz \
&& tar zxvpf ncbi-blast-2.6.0+-x64-linux.tar.gz \
&& rm ncbi-blast-2.6.0+-x64-linux.tar.gz

ENV PATH=$PATH:/home/blast/ncbi-blast-2.6.0+/bin
ENV BLASTDB=/var/www/html/protected-files/db

WORKDIR /var/www/html
