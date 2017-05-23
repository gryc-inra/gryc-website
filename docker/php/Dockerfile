FROM php:7.1.5-fpm

############################
# Install PHP requirements #
############################

# Install wget, git and libraries needed by php extensions
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        wget \
        git

# Remove lists
RUN rm -rf /var/lib/apt/lists/*

# Compile ICU (required by intl php extension)
RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/58.2/icu4c-58_2-src.tgz && \
    tar -zxf /tmp/icu.tar.gz -C /tmp && \
    cd /tmp/icu/source && \
    ./configure --prefix=/usr/local && \
    make && \
    make install

# Configure, install and enable php extensions
RUN docker-php-ext-configure intl --with-icu-dir=/usr/local
RUN docker-php-ext-install intl pdo pdo_mysql zip
RUN docker-php-ext-enable opcache

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Copy the php.ini file
COPY php.ini /usr/local/etc/php/

#################
# Install BLAST #
#################

## Go in the Opt folder
WORKDIR /opt

# Download, extract and remove BLAST archive
RUN wget ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-2.6.0+-x64-linux.tar.gz && \
    tar zxvpf ncbi-blast-2.6.0+-x64-linux.tar.gz && \
    rm ncbi-blast-2.6.0+-x64-linux.tar.gz

# Set environment variables for BLAST
ENV PATH=$PATH:/opt/ncbi-blast-2.6.0+/bin

##################
# Define workdir #
##################
WORKDIR /var/www/html
