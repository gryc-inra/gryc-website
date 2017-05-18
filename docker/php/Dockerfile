FROM php:7.1.5-fpm

# Install git and libraries for php extensions
RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        wget \
        git

RUN rm -rf /var/lib/apt/lists/*

# Install and enable php extensions
RUN docker-php-ext-install intl pdo pdo_mysql zip
RUN docker-php-ext-enable opcache

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Copy the php.ini file
COPY php.ini /usr/local/etc/php/

## Go in the Opt folder
WORKDIR /opt

# Download, extract and remove BLAST archive
RUN wget ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-2.6.0+-x64-linux.tar.gz \
&& tar zxvpf ncbi-blast-2.6.0+-x64-linux.tar.gz \
&& rm ncbi-blast-2.6.0+-x64-linux.tar.gz

# Set environment variables for BLAST
ENV PATH=$PATH:/opt/ncbi-blast-2.6.0+/bin
#ENV BLASTDB=/var/www/html/protected-files/db

# Fix the Workdir on the html folder
WORKDIR /var/www/html
