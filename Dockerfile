FROM php:7.1.10-fpm

# Declare ENV
    # Set the tools versions
    # Set the path to the blast binaries
    # To avoid a bug with the intl extension compilation
    # PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV MAFFT_VERSION=7.310 \
    BLAST_VERSION=2.6.0+ \
    PATH=$PATH:/opt/blast/bin \
    PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

# Install git, supervisor, yarn and libraries needed by php extensions
RUN apt-get update && \
    apt-get install -y \
            zlib1g-dev \
            git \
            supervisor && \
    rm -rf /var/lib/apt/lists/*

# Compile ICU (required by intl php extension)
RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/59.1/icu4c-59_1-src.tgz && \
    tar -zxf /tmp/icu.tar.gz -C /tmp && \
    cd /tmp/icu/source && \
    ./configure --prefix=/usr/local && \
    make clean && \
    make && \
    make install && \
    rm /tmp/icu.tar.gz && \
    rm -R /tmp/icu

# Configure, install and enable php extensions
RUN docker-php-source extract && \
    docker-php-ext-configure intl --with-icu-dir=/usr/local && \
    docker-php-ext-install intl pdo pdo_mysql zip bcmath && \
    pecl install apcu-5.1.8 && \
    docker-php-ext-enable opcache apcu && \
    docker-php-source delete

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Copy the php.ini file
COPY ["./docker/php.ini", "./docker/php-cli.ini", "/usr/local/etc/php/"]

# Install BLAST
RUN curl -sS -o /opt/ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz -L ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz && \
    tar -zxf /opt/ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz -C /opt && \
    mv /opt/ncbi-blast-${BLAST_VERSION} /opt/blast && \
    rm /opt/ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz


# Install MAFFT
RUN curl -sS -o /tmp/mafft-${MAFFT_VERSION}-without-extensions-src.tgz -L https://mafft.cbrc.jp/alignment/software/mafft-${MAFFT_VERSION}-without-extensions-src.tgz && \
    tar -zxf /tmp/mafft-${MAFFT_VERSION}-without-extensions-src.tgz -C /tmp && \
    cd /tmp/mafft-${MAFFT_VERSION}-without-extensions/core && \
    make clean && \
    make && \
    make install && \
    rm /tmp/mafft-${MAFFT_VERSION}-without-extensions-src.tgz && \
    rm -R /tmp/mafft-${MAFFT_VERSION}-without-extensions

# Define the working directory
WORKDIR /var/www/html

# Copy the source code
COPY . /var/www/html/

# Install app dependencies
RUN composer install --no-suggest --optimize-autoloader && \
    chown -R www-data:www-data /var/www/html

# Copy script and supervisor conf
COPY ./docker/init.sh /opt/app/init.sh
COPY ./docker/supervisor-programs.conf /etc/supervisor/conf.d/supervisor-programs.conf

# Remove the docker folder
RUN rm -R ./docker

# Define the /var/www/html folder as volume
VOLUME /var/www/html

# Execute the sript
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
