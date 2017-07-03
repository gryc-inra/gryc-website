FROM php:7.1.6-fpm

######################################
# Install PHP requirements and MAFFT #
######################################

# Install wget, git and libraries needed by php extensions
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        wget \
        git \
        mafft \
        supervisor

# Remove lists
RUN rm -rf /var/lib/apt/lists/*

# Compile ICU (required by intl php extension)
RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/59.1/icu4c-59_1-src.tgz && \
    tar -zxf /tmp/icu.tar.gz -C /tmp && \
    cd /tmp/icu/source && \
    ./configure --prefix=/usr/local && \
    make && \
    make install

# To avoid a bug with the intl extension compilation
# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

# Configure, install and enable php extensions
RUN docker-php-ext-configure intl --with-icu-dir=/usr/local
RUN docker-php-ext-install intl pdo pdo_mysql zip bcmath
RUN docker-php-ext-enable opcache

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Copy the php.ini file
COPY ./docker/app/php.ini /usr/local/etc/php/
COPY ./docker/app/php-cli.ini /usr/local/etc/php/

##################
## Install BLAST #
##################
# Download, extract and remove BLAST archive
RUN cd /opt && wget ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-2.6.0+-x64-linux.tar.gz && \
    tar zxvpf ncbi-blast-2.6.0+-x64-linux.tar.gz && \
    rm ncbi-blast-2.6.0+-x64-linux.tar.gz

# Set environment variables for BLAST
ENV PATH=$PATH:/opt/ncbi-blast-2.6.0+/bin

###################
# Add source code #
###################
COPY . /var/www/html
RUN rm -R /var/www/html/docker

# Set the WORKDIR, for composer install, and for the user directly is in the sourcecode folder when connect to the container
WORKDIR /var/www/html

# Install Vendors
RUN composer install --no-suggest --optimize-autoloader
RUN chmod -R 777 var/cache var/logs

VOLUME /var/www/html

##############
# Supervisor #
##############
COPY ./docker/app/supervisor-programs.conf /etc/supervisor/conf.d/supervisor-programs.conf

CMD /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
